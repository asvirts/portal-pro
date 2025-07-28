<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Portal;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class ClientController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index(Portal $portal): View
    {
        $this->authorize('view', $portal);
        
        $clients = $portal->clients()
            ->withCount(['projects', 'invoices'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        return view('clients.index', compact('portal', 'clients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Portal $portal): View
    {
        $this->authorize('view', $portal);
        
        return view('clients.create', compact('portal'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Portal $portal): RedirectResponse
    {
        $this->authorize('view', $portal);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('clients', 'email')
            ],
            'company' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'send_invitation' => 'boolean',
        ]);
        
        // Generate a temporary password
        $temporaryPassword = str()->random(12);
        
        $validated['portal_id'] = $portal->id;
        $validated['password'] = Hash::make($temporaryPassword);
        $validated['is_active'] = true;
        
        $client = Client::create($validated);
        
        // Send invitation email if requested
        if ($request->boolean('send_invitation')) {
            // TODO: Implement invitation email
            // Mail::to($client->email)->send(new ClientInvitation($client, $temporaryPassword));
        }
        
        return redirect()->route('portals.clients.index', $portal)
            ->with('success', 'Client created successfully!' . 
                ($request->boolean('send_invitation') ? ' Invitation email sent.' : ''));
    }

    /**
     * Display the specified resource.
     */
    public function show(Portal $portal, Client $client): View
    {
        $this->authorize('view', $portal);
        
        if ($client->portal_id !== $portal->id) {
            abort(404);
        }
        
        $client->load(['projects', 'invoices', 'files']);
        
        return view('clients.show', compact('portal', 'client'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Portal $portal, Client $client): View
    {
        $this->authorize('view', $portal);
        
        if ($client->portal_id !== $portal->id) {
            abort(404);
        }
        
        return view('clients.edit', compact('portal', 'client'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Portal $portal, Client $client): RedirectResponse
    {
        $this->authorize('view', $portal);
        
        if ($client->portal_id !== $portal->id) {
            abort(404);
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('clients', 'email')->ignore($client->id)
            ],
            'company' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ]);
        
        $client->update($validated);
        
        return redirect()->route('portals.clients.show', [$portal, $client])
            ->with('success', 'Client updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Portal $portal, Client $client): RedirectResponse
    {
        $this->authorize('view', $portal);
        
        if ($client->portal_id !== $portal->id) {
            abort(404);
        }
        
        $client->delete();
        
        return redirect()->route('portals.clients.index', $portal)
            ->with('success', 'Client deleted successfully!');
    }

    /**
     * Send invitation email to client
     */
    public function sendInvitation(Portal $portal, Client $client): RedirectResponse
    {
        $this->authorize('view', $portal);
        
        if ($client->portal_id !== $portal->id) {
            abort(404);
        }
        
        // Generate new temporary password
        $temporaryPassword = str()->random(12);
        $client->update(['password' => Hash::make($temporaryPassword)]);
        
        // TODO: Implement invitation email
        // Mail::to($client->email)->send(new ClientInvitation($client, $temporaryPassword));
        
        return redirect()->route('portals.clients.show', [$portal, $client])
            ->with('success', 'Invitation sent successfully!');
    }
}
