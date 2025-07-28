@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Edit Invoice #{{ $invoice->invoice_number }}</h1>
                    <p class="mt-2 text-gray-600">Edit invoice for {{ $portal->name }}</p>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('portals.invoices.show', [$portal, $invoice]) }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        View Invoice
                    </a>
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

        @if($invoice->status !== 'draft')
            <div class="mb-6 bg-yellow-50 border border-yellow-200 rounded-md p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">
                            Invoice Already Sent
                        </h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            <p>This invoice has been sent and cannot be edited. Only draft invoices can be modified.</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Invoice Form -->
        <form action="{{ route('portals.invoices.update', [$portal, $invoice]) }}" method="POST" id="invoice-form">
            @csrf
            @method('PUT')
            
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <!-- Invoice Header -->
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">Invoice Details</h2>
                </div>

                <div class="p-6 space-y-6">
                    <!-- Basic Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Client Selection -->
                        <div>
                            <label for="client_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Client *
                            </label>
                            <select name="client_id" id="client_id" required 
                                    {{ $invoice->status !== 'draft' ? 'disabled' : '' }}
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 {{ $invoice->status !== 'draft' ? 'bg-gray-100' : '' }}">
                                <option value="">Select a client</option>
                                @foreach($portal->clients as $client)
                                    <option value="{{ $client->id }}" {{ ($invoice->client_id == $client->id || old('client_id') == $client->id) ? 'selected' : '' }}>
                                        {{ $client->name }} ({{ $client->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('client_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Project Selection -->
                        <div>
                            <label for="project_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Project (Optional)
                            </label>
                            <select name="project_id" id="project_id" 
                                    {{ $invoice->status !== 'draft' ? 'disabled' : '' }}
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 {{ $invoice->status !== 'draft' ? 'bg-gray-100' : '' }}">
                                <option value="">No project</option>
                                @foreach($portal->projects as $project)
                                    <option value="{{ $project->id }}" {{ ($invoice->project_id == $project->id || old('project_id') == $project->id) ? 'selected' : '' }}>
                                        {{ $project->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('project_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Due Date -->
                        <div>
                            <label for="due_date" class="block text-sm font-medium text-gray-700 mb-2">
                                Due Date *
                            </label>
                            <input type="date" name="due_date" id="due_date" required
                                   {{ $invoice->status !== 'draft' ? 'disabled' : '' }}
                                   value="{{ old('due_date', $invoice->due_date->format('Y-m-d')) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 {{ $invoice->status !== 'draft' ? 'bg-gray-100' : '' }}">
                            @error('due_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tax Rate -->
                        <div>
                            <label for="tax_rate" class="block text-sm font-medium text-gray-700 mb-2">
                                Tax Rate (%)
                            </label>
                            <input type="number" name="tax_rate" id="tax_rate" step="0.01" min="0" max="100"
                                   {{ $invoice->status !== 'draft' ? 'disabled' : '' }}
                                   value="{{ old('tax_rate', $invoice->tax_rate) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 {{ $invoice->status !== 'draft' ? 'bg-gray-100' : '' }}">
                            @error('tax_rate')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Description
                        </label>
                        <input type="text" name="description" id="description" 
                               {{ $invoice->status !== 'draft' ? 'disabled' : '' }}
                               value="{{ old('description', $invoice->description) }}"
                               placeholder="Brief description of the invoice"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 {{ $invoice->status !== 'draft' ? 'bg-gray-100' : '' }}">
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Line Items -->
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Line Items</h3>
                            @if($invoice->status === 'draft')
                                <button type="button" id="add-item" 
                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                    </svg>
                                    Add Item
                                </button>
                            @endif
                        </div>

                        <div id="line-items" class="space-y-4">
                            @foreach($invoice->items as $index => $item)
                                <div class="line-item bg-gray-50 p-4 rounded-lg border border-gray-200">
                                    <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                                        <div class="md:col-span-5">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Description *</label>
                                            <input type="text" name="items[{{ $index }}][description]" required
                                                   {{ $invoice->status !== 'draft' ? 'disabled' : '' }}
                                                   value="{{ $item['description'] }}"
                                                   placeholder="Item description"
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 {{ $invoice->status !== 'draft' ? 'bg-gray-100' : '' }}">
                                        </div>
                                        <div class="md:col-span-2">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Quantity *</label>
                                            <input type="number" name="items[{{ $index }}][quantity]" min="1" step="0.01" required
                                                   {{ $invoice->status !== 'draft' ? 'disabled' : '' }}
                                                   value="{{ $item['quantity'] }}"
                                                   class="item-quantity w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 {{ $invoice->status !== 'draft' ? 'bg-gray-100' : '' }}">
                                        </div>
                                        <div class="md:col-span-2">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Rate ($) *</label>
                                            <input type="number" name="items[{{ $index }}][rate]" min="0" step="0.01" required
                                                   {{ $invoice->status !== 'draft' ? 'disabled' : '' }}
                                                   value="{{ $item['rate'] }}"
                                                   class="item-rate w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 {{ $invoice->status !== 'draft' ? 'bg-gray-100' : '' }}">
                                        </div>
                                        <div class="md:col-span-2">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Total</label>
                                            <div class="item-total px-3 py-2 bg-gray-100 border border-gray-300 rounded-md text-gray-700">
                                                ${{ number_format($item['quantity'] * $item['rate'], 2) }}
                                            </div>
                                        </div>
                                        <div class="md:col-span-1">
                                            @if($invoice->status === 'draft')
                                                <button type="button" class="remove-item w-full px-3 py-2 border border-red-300 rounded-md text-red-700 hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                    <svg class="w-4 h-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Invoice Totals -->
                        <div class="mt-6 bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <div class="flex justify-end">
                                <div class="w-64 space-y-2">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Subtotal:</span>
                                        <span id="subtotal" class="font-medium">${{ number_format($invoice->subtotal, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Tax:</span>
                                        <span id="tax-amount" class="font-medium">${{ number_format($invoice->tax_amount, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between text-lg font-bold border-t border-gray-300 pt-2">
                                        <span>Total:</span>
                                        <span id="total-amount">${{ number_format($invoice->total_amount, 2) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                            Notes
                        </label>
                        <textarea name="notes" id="notes" rows="3"
                                  {{ $invoice->status !== 'draft' ? 'disabled' : '' }}
                                  placeholder="Additional notes or payment terms"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 {{ $invoice->status !== 'draft' ? 'bg-gray-100' : '' }}">{{ old('notes', $invoice->notes) }}</textarea>
                        @error('notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-between">
                    <div>
                        @if($invoice->status === 'draft')
                            <form action="{{ route('portals.invoices.destroy', [$portal, $invoice]) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this invoice? This action cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="inline-flex items-center px-4 py-2 border border-red-300 rounded-md shadow-sm text-sm font-medium text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Delete Invoice
                                </button>
                            </form>
                        @endif
                    </div>
                    
                    <div class="flex space-x-3">
                        <a href="{{ route('portals.invoices.show', [$portal, $invoice]) }}" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Cancel
                        </a>
                        @if($invoice->status === 'draft')
                            <button type="submit" name="action" value="draft"
                                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Save Changes
                            </button>
                            <button type="submit" name="action" value="send"
                                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Save & Send
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@if($invoice->status === 'draft')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let itemIndex = {{ count($invoice->items) }};
    
    // Add new line item
    document.getElementById('add-item').addEventListener('click', function() {
        const lineItems = document.getElementById('line-items');
        const newItem = createLineItem(itemIndex);
        lineItems.appendChild(newItem);
        itemIndex++;
        updateRemoveButtons();
        calculateTotals();
    });
    
    // Remove line item
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-item')) {
            e.target.closest('.line-item').remove();
            updateRemoveButtons();
            calculateTotals();
        }
    });
    
    // Calculate totals when inputs change
    document.addEventListener('input', function(e) {
        if (e.target.matches('.item-quantity, .item-rate, #tax_rate')) {
            if (e.target.matches('.item-quantity, .item-rate')) {
                updateLineItemTotal(e.target.closest('.line-item'));
            }
            calculateTotals();
        }
    });
    
    function createLineItem(index) {
        const div = document.createElement('div');
        div.className = 'line-item bg-gray-50 p-4 rounded-lg border border-gray-200';
        div.innerHTML = `
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                <div class="md:col-span-5">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description *</label>
                    <input type="text" name="items[${index}][description]" required
                           placeholder="Item description"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Quantity *</label>
                    <input type="number" name="items[${index}][quantity]" min="1" step="0.01" value="1" required
                           class="item-quantity w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Rate ($) *</label>
                    <input type="number" name="items[${index}][rate]" min="0" step="0.01" required
                           class="item-rate w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Total</label>
                    <div class="item-total px-3 py-2 bg-gray-100 border border-gray-300 rounded-md text-gray-700">
                        $0.00
                    </div>
                </div>
                <div class="md:col-span-1">
                    <button type="button" class="remove-item w-full px-3 py-2 border border-red-300 rounded-md text-red-700 hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <svg class="w-4 h-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </div>
            </div>
        `;
        return div;
    }
    
    function updateLineItemTotal(lineItem) {
        const quantity = parseFloat(lineItem.querySelector('.item-quantity').value) || 0;
        const rate = parseFloat(lineItem.querySelector('.item-rate').value) || 0;
        const total = quantity * rate;
        lineItem.querySelector('.item-total').textContent = '$' + total.toFixed(2);
    }
    
    function updateRemoveButtons() {
        const removeButtons = document.querySelectorAll('.remove-item');
        removeButtons.forEach((button, index) => {
            button.disabled = removeButtons.length === 1;
        });
    }
    
    function calculateTotals() {
        let subtotal = 0;
        
        document.querySelectorAll('.line-item').forEach(item => {
            const quantity = parseFloat(item.querySelector('.item-quantity').value) || 0;
            const rate = parseFloat(item.querySelector('.item-rate').value) || 0;
            subtotal += quantity * rate;
        });
        
        const taxRate = parseFloat(document.getElementById('tax_rate').value) || 0;
        const taxAmount = subtotal * (taxRate / 100);
        const total = subtotal + taxAmount;
        
        document.getElementById('subtotal').textContent = '$' + subtotal.toFixed(2);
        document.getElementById('tax-amount').textContent = '$' + taxAmount.toFixed(2);
        document.getElementById('total-amount').textContent = '$' + total.toFixed(2);
    }
    
    // Initial calculation
    calculateTotals();
    updateRemoveButtons();
});
</script>
@endif
@endsection
