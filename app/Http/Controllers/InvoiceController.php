<?php

namespace App\Http\Controllers;

use App\Models\Portal;
use App\Models\Invoice;
use App\Models\Client;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Laravel\Cashier\Cashier;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class InvoiceController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of invoices for a portal.
     */
    public function index(Portal $portal)
    {
        $this->authorize('view', $portal);

        $invoices = $portal->invoices()
            ->with(['client', 'project'])
            ->when(request('status'), function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when(request('client'), function ($query, $clientId) {
                return $query->where('client_id', $clientId);
            })
            ->when(request('project'), function ($query, $projectId) {
                return $query->where('project_id', $projectId);
            })
            ->when(request('search'), function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('invoice_number', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%")
                      ->orWhereHas('client', function ($clientQuery) use ($search) {
                          $clientQuery->where('name', 'like', "%{$search}%");
                      });
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $clients = $portal->clients;
        $projects = $portal->projects;

        // Calculate invoice statistics
        $stats = [
            'total_invoices' => $portal->invoices()->count(),
            'total_amount' => $portal->invoices()->sum('total_amount'),
            'paid_amount' => $portal->invoices()->where('status', 'paid')->sum('total_amount'),
            'pending_amount' => $portal->invoices()->whereIn('status', ['draft', 'sent', 'viewed'])->sum('total_amount'),
            'overdue_count' => $portal->invoices()->where('status', 'overdue')->count(),
        ];

        return view('invoices.index', compact('portal', 'invoices', 'clients', 'projects', 'stats'));
    }

    /**
     * Show the form for creating a new invoice.
     */
    public function create(Portal $portal)
    {
        $this->authorize('view', $portal);

        $clients = $portal->clients;
        $projects = $portal->projects;

        return view('invoices.create', compact('portal', 'clients', 'projects'));
    }

    /**
     * Store a newly created invoice.
     */
    public function store(Request $request, Portal $portal)
    {
        $this->authorize('view', $portal);

        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'project_id' => 'nullable|exists:projects,id',
            'description' => 'required|string|max:500',
            'due_date' => 'required|date|after:today',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string|max:255',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.rate' => 'required|numeric|min:0.01',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Ensure client and project belong to this portal
        $client = Client::where('id', $validated['client_id'])
            ->where('portal_id', $portal->id)
            ->firstOrFail();

        if ($validated['project_id']) {
            $project = Project::where('id', $validated['project_id'])
                ->where('portal_id', $portal->id)
                ->firstOrFail();
        }

        DB::transaction(function () use ($validated, $portal, $request) {
            // Calculate totals
            $subtotal = 0;
            foreach ($validated['items'] as $item) {
                $subtotal += $item['quantity'] * $item['rate'];
            }

            $taxAmount = $subtotal * (($validated['tax_rate'] ?? 0) / 100);
            $totalAmount = $subtotal + $taxAmount;

            // Generate unique invoice number
            $invoiceNumber = $this->generateInvoiceNumber($portal);

            // Create invoice
            $invoice = $portal->invoices()->create([
                'client_id' => $validated['client_id'],
                'project_id' => $validated['project_id'],
                'invoice_number' => $invoiceNumber,
                'description' => $validated['description'],
                'due_date' => $validated['due_date'],
                'subtotal' => $subtotal,
                'tax_rate' => $validated['tax_rate'] ?? 0,
                'tax_amount' => $taxAmount,
                'total_amount' => $totalAmount,
                'status' => 'draft',
                'items' => $validated['items'],
                'notes' => $validated['notes'],
                'created_by' => auth()->id(),
            ]);

            // If request wants to send immediately
            if ($request->boolean('send_now')) {
                $invoice->update(['status' => 'sent', 'sent_at' => now()]);
                // TODO: Send email notification to client
            }
        });

        return redirect()->route('portals.invoices.index', $portal)
            ->with('success', 'Invoice created successfully!');
    }

    /**
     * Display the specified invoice.
     */
    public function show(Portal $portal, Invoice $invoice)
    {
        $this->authorize('view', $portal);
        
        // Ensure invoice belongs to this portal
        if ($invoice->portal_id !== $portal->id) {
            abort(404);
        }

        $invoice->load(['client', 'project']);

        return view('invoices.show', compact('portal', 'invoice'));
    }

    /**
     * Show the form for editing the specified invoice.
     */
    public function edit(Portal $portal, Invoice $invoice)
    {
        $this->authorize('view', $portal);
        
        // Ensure invoice belongs to this portal
        if ($invoice->portal_id !== $portal->id) {
            abort(404);
        }

        // Only allow editing of draft invoices
        if ($invoice->status !== 'draft') {
            return redirect()->route('portals.invoices.show', [$portal, $invoice])
                ->with('error', 'Only draft invoices can be edited.');
        }

        $clients = $portal->clients;
        $projects = $portal->projects;

        return view('invoices.edit', compact('portal', 'invoice', 'clients', 'projects'));
    }

    /**
     * Update the specified invoice.
     */
    public function update(Request $request, Portal $portal, Invoice $invoice)
    {
        $this->authorize('view', $portal);
        
        // Ensure invoice belongs to this portal
        if ($invoice->portal_id !== $portal->id) {
            abort(404);
        }

        // Only allow editing of draft invoices
        if ($invoice->status !== 'draft') {
            return redirect()->route('portals.invoices.show', [$portal, $invoice])
                ->with('error', 'Only draft invoices can be edited.');
        }

        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'project_id' => 'nullable|exists:projects,id',
            'description' => 'required|string|max:500',
            'due_date' => 'required|date|after:today',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string|max:255',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.rate' => 'required|numeric|min:0.01',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'notes' => 'nullable|string|max:1000',
        ]);

        DB::transaction(function () use ($validated, $invoice, $request) {
            // Calculate totals
            $subtotal = 0;
            foreach ($validated['items'] as $item) {
                $subtotal += $item['quantity'] * $item['rate'];
            }

            $taxAmount = $subtotal * (($validated['tax_rate'] ?? 0) / 100);
            $totalAmount = $subtotal + $taxAmount;

            // Update invoice
            $invoice->update([
                'client_id' => $validated['client_id'],
                'project_id' => $validated['project_id'],
                'description' => $validated['description'],
                'due_date' => $validated['due_date'],
                'subtotal' => $subtotal,
                'tax_rate' => $validated['tax_rate'] ?? 0,
                'tax_amount' => $taxAmount,
                'total_amount' => $totalAmount,
                'items' => $validated['items'],
                'notes' => $validated['notes'],
            ]);

            // If request wants to send immediately
            if ($request->boolean('send_now')) {
                $invoice->update(['status' => 'sent', 'sent_at' => now()]);
                // TODO: Send email notification to client
            }
        });

        return redirect()->route('portals.invoices.show', [$portal, $invoice])
            ->with('success', 'Invoice updated successfully!');
    }

    /**
     * Remove the specified invoice.
     */
    public function destroy(Portal $portal, Invoice $invoice)
    {
        $this->authorize('view', $portal);
        
        // Ensure invoice belongs to this portal
        if ($invoice->portal_id !== $portal->id) {
            abort(404);
        }

        // Only allow deletion of draft invoices
        if ($invoice->status !== 'draft') {
            return redirect()->route('portals.invoices.index', $portal)
                ->with('error', 'Only draft invoices can be deleted.');
        }

        $invoice->delete();

        return redirect()->route('portals.invoices.index', $portal)
            ->with('success', 'Invoice deleted successfully!');
    }

    /**
     * Send invoice to client.
     */
    public function send(Portal $portal, Invoice $invoice)
    {
        $this->authorize('view', $portal);
        
        // Ensure invoice belongs to this portal
        if ($invoice->portal_id !== $portal->id) {
            abort(404);
        }

        if ($invoice->status !== 'draft') {
            return redirect()->route('portals.invoices.show', [$portal, $invoice])
                ->with('error', 'Only draft invoices can be sent.');
        }

        $invoice->update([
            'status' => 'sent',
            'sent_at' => now()
        ]);

        // TODO: Send email notification to client

        return redirect()->route('portals.invoices.show', [$portal, $invoice])
            ->with('success', 'Invoice sent to client successfully!');
    }

    /**
     * Create Stripe payment intent for invoice.
     */
    public function createPaymentIntent(Portal $portal, Invoice $invoice)
    {
        // Ensure invoice belongs to this portal
        if ($invoice->portal_id !== $portal->id) {
            abort(404);
        }

        if ($invoice->status === 'paid') {
            return redirect()->route('portals.invoices.show', [$portal, $invoice])
                ->with('error', 'This invoice has already been paid.');
        }

        try {
            Stripe::setApiKey(config('cashier.secret'));

            $paymentIntent = PaymentIntent::create([
                'amount' => $invoice->total_amount * 100, // Convert to cents
                'currency' => 'usd',
                'metadata' => [
                    'invoice_id' => $invoice->id,
                    'portal_id' => $portal->id,
                    'client_id' => $invoice->client_id,
                ],
                'description' => "Payment for Invoice #{$invoice->invoice_number}",
            ]);

            $invoice->update([
                'stripe_payment_intent_id' => $paymentIntent->id
            ]);

            return response()->json([
                'client_secret' => $paymentIntent->client_secret
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Unable to create payment intent: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Handle successful payment webhook from Stripe.
     */
    public function handlePaymentSuccess(Request $request)
    {
        $paymentIntentId = $request->input('payment_intent_id');
        
        $invoice = Invoice::where('stripe_payment_intent_id', $paymentIntentId)->first();
        
        if ($invoice && $invoice->status !== 'paid') {
            $invoice->update([
                'status' => 'paid',
                'paid_at' => now()
            ]);
            
            // TODO: Send payment confirmation email
        }

        return response()->json(['success' => true]);
    }

    /**
     * Generate unique invoice number for portal.
     */
    private function generateInvoiceNumber(Portal $portal): string
    {
        $year = date('Y');
        $prefix = strtoupper(substr($portal->name, 0, 3));
        
        $lastInvoice = $portal->invoices()
            ->where('invoice_number', 'like', "{$prefix}-{$year}-%")
            ->orderBy('invoice_number', 'desc')
            ->first();

        if ($lastInvoice) {
            $lastNumber = (int) substr($lastInvoice->invoice_number, -4);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        return sprintf('%s-%s-%04d', $prefix, $year, $nextNumber);
    }
}
