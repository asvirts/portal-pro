@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Invoice #{{ $invoice->invoice_number }}</h1>
                    <div class="mt-2 flex items-center space-x-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $invoice->status_color }}-100 text-{{ $invoice->status_color }}-800">
                            {{ ucfirst($invoice->status) }}
                        </span>
                        @if($invoice->isOverdue())
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                Overdue
                            </span>
                        @endif
                        <span class="text-gray-600">Created {{ $invoice->created_at->format('M j, Y') }}</span>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    @if($invoice->status === 'draft')
                        <a href="{{ route('portals.invoices.edit', [$portal, $invoice]) }}" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Edit
                        </a>
                        <form action="{{ route('portals.invoices.send', [$portal, $invoice]) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                </svg>
                                Send Invoice
                            </button>
                        </form>
                    @endif
                    
                    @if($invoice->status !== 'paid' && $invoice->status !== 'cancelled')
                        <button onclick="openPaymentModal()" 
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                            Pay Now
                        </button>
                    @endif
                    
                    <a href="{{ route('portals.invoices.index', $portal) }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Back to Invoices
                    </a>
                </div>
            </div>
        </div>

        <!-- Invoice Content -->
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <!-- Invoice Header -->
            <div class="px-8 py-6 border-b border-gray-200">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- From -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-3">From</h3>
                        <div class="text-gray-600">
                            <div class="font-medium text-gray-900">{{ $portal->name }}</div>
                            @if($portal->address)
                                <div>{{ $portal->address }}</div>
                            @endif
                            @if($portal->phone)
                                <div>{{ $portal->phone }}</div>
                            @endif
                            @if($portal->email)
                                <div>{{ $portal->email }}</div>
                            @endif
                        </div>
                    </div>

                    <!-- To -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-3">Bill To</h3>
                        <div class="text-gray-600">
                            <div class="font-medium text-gray-900">{{ $invoice->client->name }}</div>
                            <div>{{ $invoice->client->email }}</div>
                            @if($invoice->client->company)
                                <div>{{ $invoice->client->company }}</div>
                            @endif
                            @if($invoice->client->address)
                                <div>{{ $invoice->client->address }}</div>
                            @endif
                            @if($invoice->client->phone)
                                <div>{{ $invoice->client->phone }}</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Invoice Details -->
            <div class="px-8 py-6 border-b border-gray-200">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Invoice Number</h4>
                        <div class="mt-1 text-lg font-medium text-gray-900">#{{ $invoice->invoice_number }}</div>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Issue Date</h4>
                        <div class="mt-1 text-lg font-medium text-gray-900">{{ $invoice->created_at->format('M j, Y') }}</div>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Due Date</h4>
                        <div class="mt-1 text-lg font-medium text-gray-900">{{ $invoice->due_date->format('M j, Y') }}</div>
                    </div>
                </div>

                @if($invoice->description)
                    <div class="mt-6">
                        <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Description</h4>
                        <div class="mt-1 text-gray-900">{{ $invoice->description }}</div>
                    </div>
                @endif

                @if($invoice->project)
                    <div class="mt-6">
                        <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Project</h4>
                        <div class="mt-1">
                            <a href="{{ route('portals.projects.show', [$portal, $invoice->project]) }}" 
                               class="text-blue-600 hover:text-blue-800 font-medium">
                                {{ $invoice->project->name }}
                            </a>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Line Items -->
            <div class="px-8 py-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Items</h3>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Description
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Quantity
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Rate
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Amount
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($invoice->items as $item)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $item['description'] }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                                        {{ number_format($item['quantity'], 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                                        ${{ number_format($item['rate'], 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right font-medium">
                                        ${{ number_format($item['quantity'] * $item['rate'], 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Totals -->
                <div class="mt-6 flex justify-end">
                    <div class="w-64 space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Subtotal:</span>
                            <span class="font-medium">${{ number_format($invoice->subtotal, 2) }}</span>
                        </div>
                        @if($invoice->tax_rate > 0)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Tax ({{ number_format($invoice->tax_rate, 2) }}%):</span>
                                <span class="font-medium">${{ number_format($invoice->tax_amount, 2) }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between text-lg font-bold border-t border-gray-300 pt-2">
                            <span>Total:</span>
                            <span>${{ number_format($invoice->total_amount, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notes -->
            @if($invoice->notes)
                <div class="px-8 py-6 border-t border-gray-200 bg-gray-50">
                    <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-2">Notes</h4>
                    <div class="text-gray-700 whitespace-pre-line">{{ $invoice->notes }}</div>
                </div>
            @endif

            <!-- Payment Information -->
            @if($invoice->status === 'paid')
                <div class="px-8 py-6 border-t border-gray-200 bg-green-50">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-green-800 font-medium">
                            Payment received on {{ $invoice->paid_at->format('M j, Y \a\t g:i A') }}
                        </span>
                    </div>
                </div>
            @endif
        </div>

        <!-- Activity Timeline -->
        <div class="mt-8 bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Activity Timeline</h3>
            </div>
            <div class="px-6 py-4">
                <div class="flow-root">
                    <ul class="-mb-8">
                        <li>
                            <div class="relative pb-8">
                                <div class="relative flex space-x-3">
                                    <div>
                                        <span class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center ring-8 ring-white">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                            </svg>
                                        </span>
                                    </div>
                                    <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                        <div>
                                            <p class="text-sm text-gray-500">Invoice created by <span class="font-medium text-gray-900">{{ $invoice->creator->name }}</span></p>
                                        </div>
                                        <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                            {{ $invoice->created_at->format('M j, Y g:i A') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>

                        @if($invoice->sent_at)
                            <li>
                                <div class="relative pb-8">
                                    <div class="relative flex space-x-3">
                                        <div>
                                            <span class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center ring-8 ring-white">
                                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                                </svg>
                                            </span>
                                        </div>
                                        <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                            <div>
                                                <p class="text-sm text-gray-500">Invoice sent to <span class="font-medium text-gray-900">{{ $invoice->client->name }}</span></p>
                                            </div>
                                            <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                {{ $invoice->sent_at->format('M j, Y g:i A') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endif

                        @if($invoice->paid_at)
                            <li>
                                <div class="relative">
                                    <div class="relative flex space-x-3">
                                        <div>
                                            <span class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center ring-8 ring-white">
                                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                            </span>
                                        </div>
                                        <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                            <div>
                                                <p class="text-sm text-gray-500">Payment received</p>
                                            </div>
                                            <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                {{ $invoice->paid_at->format('M j, Y g:i A') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Payment Modal -->
@if($invoice->status !== 'paid' && $invoice->status !== 'cancelled')
<div id="payment-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Pay Invoice</h3>
                <button onclick="closePaymentModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <div class="mb-4 p-4 bg-gray-50 rounded-lg">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Total Amount:</span>
                    <span class="text-2xl font-bold text-gray-900">${{ number_format($invoice->total_amount, 2) }}</span>
                </div>
            </div>
            
            <div id="card-element" class="p-3 border border-gray-300 rounded-md mb-4">
                <!-- Stripe Elements will create form elements here -->
            </div>
            
            <div id="card-errors" role="alert" class="text-red-600 text-sm mb-4 hidden"></div>
            
            <div class="flex justify-end space-x-3">
                <button onclick="closePaymentModal()" 
                        class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                    Cancel
                </button>
                <button id="submit-payment" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 disabled:opacity-50">
                    Pay Now
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://js.stripe.com/v3/"></script>
<script>
const stripe = Stripe('{{ config("cashier.key") }}');
const elements = stripe.elements();
const cardElement = elements.create('card');

function openPaymentModal() {
    document.getElementById('payment-modal').classList.remove('hidden');
    cardElement.mount('#card-element');
}

function closePaymentModal() {
    document.getElementById('payment-modal').classList.add('hidden');
    cardElement.unmount();
}

document.getElementById('submit-payment').addEventListener('click', async (event) => {
    event.preventDefault();
    
    const button = event.target;
    button.disabled = true;
    button.textContent = 'Processing...';
    
    try {
        // Create payment intent
        const response = await fetch('{{ route("portals.invoices.payment-intent", [$portal, $invoice]) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });
        
        const { client_secret } = await response.json();
        
        // Confirm payment
        const { error } = await stripe.confirmCardPayment(client_secret, {
            payment_method: {
                card: cardElement,
                billing_details: {
                    name: '{{ $invoice->client->name }}',
                    email: '{{ $invoice->client->email }}'
                }
            }
        });
        
        if (error) {
            document.getElementById('card-errors').textContent = error.message;
            document.getElementById('card-errors').classList.remove('hidden');
        } else {
            // Payment succeeded, reload page
            window.location.reload();
        }
    } catch (error) {
        document.getElementById('card-errors').textContent = 'An error occurred. Please try again.';
        document.getElementById('card-errors').classList.remove('hidden');
    }
    
    button.disabled = false;
    button.textContent = 'Pay Now';
});
</script>
@endif
@endsection
