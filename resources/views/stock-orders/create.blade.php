@extends('layouts.app')

@section('title', 'Create Stock Order')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-semibold text-gray-800">Create Stock Order</h2>
        <a href="{{ route('stock-orders.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Orders
        </a>
    </div>

    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
            <p>{{ session('error') }}</p>
        </div>
    @endif

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="p-6">
            <form action="{{ route('stock-orders.store') }}" method="POST" id="orderForm">
                @csrf
                
                @if ($errors->any())
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">There were errors with your submission:</h3>
                                <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Order Number -->
                    <div>
                        <label for="order_number" class="block text-sm font-medium text-gray-700">Order Number <span class="text-red-500">*</span></label>
                        <input type="text" name="order_number" id="order_number" value="{{ old('order_number', 'PO-' . date('Ymd') . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT)) }}" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                        @error('order_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Auto-generated, you can modify if needed</p>
                    </div>

                    <!-- Reference Number -->
                    <div>
                        <label for="reference_number" class="block text-sm font-medium text-gray-700">Reference Number</label>
                        <input type="text" name="reference_number" id="reference_number" value="{{ old('reference_number') }}" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        @error('reference_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <small class="form-text text-muted">Supplier's reference number, if available</small>
                    </div>

                    <!-- Supplier -->
                    <div>
                        <label for="supplier_id" class="block text-sm font-medium text-gray-700">Supplier <span class="text-red-500">*</span></label>
                        <select id="supplier_id" name="supplier_id" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                            <option value="">Select Supplier</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('supplier_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Order Date -->
                    <div>
                        <label for="order_date" class="block text-sm font-medium text-gray-700">Order Date <span class="text-red-500">*</span></label>
                        <input type="date" name="order_date" id="order_date" value="{{ old('order_date', date('Y-m-d')) }}" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                        @error('order_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Expected Delivery Date -->
                    <div>
                        <label for="expected_delivery_date" class="block text-sm font-medium text-gray-700">Expected Delivery Date</label>
                        <input type="date" name="expected_delivery_date" id="expected_delivery_date" value="{{ old('expected_delivery_date') }}" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        @error('expected_delivery_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Order Notes -->
                    <div class="md:col-span-2">
                        <label for="notes" class="block text-sm font-medium text-gray-700">Order Notes</label>
                        <textarea id="notes" name="notes" rows="2" placeholder="Enter any additional notes about this order" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div id="supplierDetails" class="mt-4">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-1"></i> Select a supplier to view their details
                    </div>
                    </div>
                </div>
                
                <div class="bg-white shadow-md rounded-lg overflow-hidden mt-8">
                    <div class="bg-gray-50 px-4 py-3 border-b border-gray-200 flex justify-between items-center">
                        <h3 class="text-lg font-medium text-gray-700 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            Order Items
                        </h3>
                        <button type="button" id="addItemBtn" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Add Item
                        </button>
                    </div>
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200" id="itemsTable">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/3">Item</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6">Quantity</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6">Unit Type</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6">Unit Price</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6">Subtotal</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/12">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="itemsTableBody" class="bg-white divide-y divide-gray-200">
                                    <!-- Items will be added here dynamically -->
                                    @if(old('items'))
                                        @foreach(old('items') as $index => $item)
                                            <tr class="item-row">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <select class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm item-select" name="items[{{ $index }}][stock_id]" required>
                                                        <option value="">Select Item</option>
                                                        @foreach($stocks as $stock)
                                                            <option value="{{ $stock->id }}" {{ $item['stock_id'] == $stock->id ? 'selected' : '' }}>
                                                                {{ $stock->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <input type="number" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md item-quantity" name="items[{{ $index }}][quantity]" value="{{ $item['quantity'] }}" min="1" required>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <input type="text" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md item-unit" name="items[{{ $index }}][unit_type]" value="{{ $item['unit_type'] }}" required>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <input type="number" step="0.01" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md item-price" name="items[{{ $index }}][unit_price]" value="{{ $item['unit_price'] }}" min="0.01" required>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="text-sm text-gray-900 item-subtotal">{{ number_format($item['quantity'] * $item['unit_price'], 2) }}</span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <button type="button" class="text-red-600 hover:text-red-900 remove-item">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr id="noItemsRow">
                                            <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">No items added yet. Click "Add Item" to add items to this order.</td>
                                        </tr>
                                    @endif
                                </tbody>
                                <tfoot class="bg-gray-50">
                                    <tr>
                                        <th colspan="4" class="px-6 py-3 text-right text-sm font-medium text-gray-700">Subtotal:</th>
                                        <th colspan="2" class="px-6 py-3 text-left text-sm text-gray-700">
                                            <span id="subtotalAmount" class="font-medium">0.00</span>
                                            <input type="hidden" name="subtotal" id="subtotalInput" value="{{ old('subtotal', 0) }}">
                                        </th>
                                    </tr>
                                    <tr>
                                        <th colspan="4" class="px-6 py-3 text-right text-sm font-medium text-gray-700">Tax (%):</th>
                                        <th colspan="2" class="px-6 py-3 text-left text-sm text-gray-700">
                                            <input type="number" step="0.01" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-1/2 shadow-sm sm:text-sm border-gray-300 rounded-md" id="taxRate" name="tax_rate" value="{{ old('tax_rate', 0) }}" min="0" max="100">
                                        </th>
                                    </tr>
                                    <tr>
                                        <th colspan="4" class="px-6 py-3 text-right text-sm font-medium text-gray-700">Tax Amount:</th>
                                        <th colspan="2" class="px-6 py-3 text-left text-sm text-gray-700">
                                            <span id="taxAmount" class="font-medium">0.00</span>
                                            <input type="hidden" name="tax_amount" id="taxAmountInput" value="{{ old('tax_amount', 0) }}">
                                        </th>
                                    </tr>
                                    <tr>
                                        <th colspan="4" class="px-6 py-3 text-right text-sm font-medium text-gray-700">Shipping:</th>
                                        <th colspan="2" class="px-6 py-3 text-left text-sm text-gray-700">
                                            <input type="number" step="0.01" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-1/2 shadow-sm sm:text-sm border-gray-300 rounded-md" id="shippingCost" name="shipping_cost" value="{{ old('shipping_cost', 0) }}" min="0">
                                        </th>
                                    </tr>
                                    <tr>
                                        <th colspan="4" class="px-6 py-3 text-right text-sm font-medium text-gray-700">Total:</th>
                                        <th colspan="2" class="px-6 py-3 text-left text-sm text-gray-900">
                                            <span id="totalAmount" class="text-lg font-bold">0.00</span>
                                            <input type="hidden" name="total" id="totalInput" value="{{ old('total', 0) }}">
                                    <tr class="table-primary">
                                        <th colspan="4" class="text-end">Total:</th>
                                        <th colspan="2">
                                            <span id="totalAmount" class="fw-bold">0.00</span>
                                            <input type="hidden" name="total_amount" id="totalAmountInput" value="{{ old('total_amount', 0) }}">
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end mt-6 mb-6">
                    <a href="{{ route('stock-orders.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-300 focus:outline-none focus:border-gray-300 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 mr-3">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Cancel
                    </a>
                    <button type="submit" id="submitOrder" class="inline-flex items-center px-4 py-2 mr-3 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Create Order
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize variables
        let itemIndex = {{ old('items') ? count(old('items')) : 0 }};
        const stocksData = {
            @foreach($stocks as $stock)
                {{ $stock->id }}: {
                    name: "{{ $stock->name }}",
                    unit_type: "{{ $stock->unit_type }}",
                    unit_price: {{ $stock->unit_price }},
                    category_id: {{ $stock->category_id ?? 'null' }},
                    category_name: "{{ $stock->category->name ?? 'N/A' }}"
                },
            @endforeach
        };
        
        // Supplier details
        const supplierId = document.getElementById('supplier_id');
        const supplierDetails = document.getElementById('supplierDetails');
        
        supplierId.addEventListener('change', function() {
            const selectedSupplierId = this.value;
            if (selectedSupplierId) {
                // Fetch supplier details via AJAX
                fetch(`/api/suppliers/${selectedSupplierId}`)
                    .then(response => response.json())
                    .then(data => {
                        supplierDetails.innerHTML = `
                            <div class="card">
                                <div class="card-body p-3">
                                    <h6 class="card-title">${data.name}</h6>
                                    <p class="card-text mb-1">
                                        <i class="fas fa-user me-1"></i> ${data.contact_person || 'N/A'}
                                    </p>
                                    <p class="card-text mb-1">
                                        <i class="fas fa-envelope me-1"></i> ${data.email || 'N/A'}
                                    </p>
                                    <p class="card-text mb-1">
                                        <i class="fas fa-phone me-1"></i> ${data.phone || 'N/A'}
                                    </p>
                                    <p class="card-text mb-0">
                                        <i class="fas fa-map-marker-alt me-1"></i> ${data.address || 'N/A'}
                                    </p>
                                </div>
                            </div>
                        `;
                    })
                    .catch(error => {
                        console.error('Error fetching supplier details:', error);
                        supplierDetails.innerHTML = `
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-circle me-1"></i> Error loading supplier details
                            </div>
                        `;
                    });
            } else {
                supplierDetails.innerHTML = `
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-1"></i> Select a supplier to view their details
                    </div>
                `;
            }
        });
        
        // Trigger change event if supplier is already selected
        if (supplierId.value) {
            const event = new Event('change');
            supplierId.dispatchEvent(event);
        }
        
        // Add item button
        document.getElementById('addItemBtn').addEventListener('click', function() {
            addItemRow();
        });
        
        // Function to add a new item row
        function addItemRow(stockId = '', quantity = 1, unitType = '', unitPrice = 0) {
            // Remove the "no items" row if it exists
            const noItemsRow = document.getElementById('noItemsRow');
            if (noItemsRow) {
                noItemsRow.remove();
            }
            
            const tbody = document.getElementById('itemsTableBody');
            const tr = document.createElement('tr');
            tr.className = 'item-row';
            
            tr.innerHTML = `
                <td>
                    <select class="form-select item-select" name="items[${itemIndex}][stock_id]" required>
                        <option value="">Select Item</option>
                        ${Object.entries(stocksData).map(([id, stock]) => `
                            <option value="${id}" ${stockId == id ? 'selected' : ''}>
                                ${stock.name}
                            </option>
                        `).join('')}
                    </select>
                </td>
                <td>
                    <input type="number" class="form-control item-quantity" name="items[${itemIndex}][quantity]" value="${quantity}" min="1" required>
                </td>
                <td>
                    <input type="text" class="form-control item-unit" name="items[${itemIndex}][unit_type]" value="${unitType}" required>
                </td>
                <td>
                    <input type="number" step="0.01" class="form-control item-price" name="items[${itemIndex}][unit_price]" value="${unitPrice}" min="0.01" required>
                </td>
                <td>
                    <span class="item-subtotal">${(quantity * unitPrice).toFixed(2)}</span>
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm remove-item">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            `;
            
            tbody.appendChild(tr);
            
            // Add event listeners to the new row
            const select = tr.querySelector('.item-select');
            const quantityInput = tr.querySelector('.item-quantity');
            const unitInput = tr.querySelector('.item-unit');
            const priceInput = tr.querySelector('.item-price');
            const removeButton = tr.querySelector('.remove-item');
            
            // Stock selection change
            select.addEventListener('change', function() {
                const selectedStockId = this.value;
                if (selectedStockId && stocksData[selectedStockId]) {
                    const stock = stocksData[selectedStockId];
                    unitInput.value = stock.unit_type;
                    priceInput.value = stock.unit_price;
                    updateSubtotal(tr);
                }
            });
            
            // Quantity or price change
            quantityInput.addEventListener('input', function() {
                updateSubtotal(tr);
            });
            
            priceInput.addEventListener('input', function() {
                updateSubtotal(tr);
            });
            
            // Remove item
            removeButton.addEventListener('click', function() {
                tr.remove();
                updateOrderTotals();
                
                // If no items left, add the "no items" row
                if (document.querySelectorAll('.item-row').length === 0) {
                    const tbody = document.getElementById('itemsTableBody');
                    const noItemsRow = document.createElement('tr');
                    noItemsRow.id = 'noItemsRow';
                    noItemsRow.innerHTML = `
                        <td colspan="6" class="text-center">No items added yet. Click "Add Item" to add items to this order.</td>
                    `;
                    tbody.appendChild(noItemsRow);
                }
            });
            
            // Trigger change event if stock is already selected
            if (stockId) {
                const event = new Event('change');
                select.dispatchEvent(event);
            }
            
            itemIndex++;
            updateOrderTotals();
        }
        
        // Update subtotal for a row
        function updateSubtotal(row) {
            const quantity = parseFloat(row.querySelector('.item-quantity').value) || 0;
            const price = parseFloat(row.querySelector('.item-price').value) || 0;
            const subtotal = quantity * price;
            row.querySelector('.item-subtotal').textContent = subtotal.toFixed(2);
            updateOrderTotals();
        }
        
        // Update order totals
        function updateOrderTotals() {
            // Calculate subtotal
            let subtotal = 0;
            document.querySelectorAll('.item-subtotal').forEach(function(el) {
                subtotal += parseFloat(el.textContent) || 0;
            });
            
            // Get other values
            const taxRate = parseFloat(document.getElementById('taxRate').value) || 0;
            const discountAmount = parseFloat(document.getElementById('discountAmount').value) || 0;
            const shippingAmount = parseFloat(document.getElementById('shippingAmount').value) || 0;
            
            // Calculate tax
            const taxAmount = subtotal * (taxRate / 100);
            
            // Calculate total
            const total = subtotal + taxAmount - discountAmount + shippingAmount;
            
            // Update displayed values
            document.getElementById('subtotalAmount').textContent = subtotal.toFixed(2);
            document.getElementById('subtotalInput').value = subtotal.toFixed(2);
            
            document.getElementById('taxAmount').textContent = taxAmount.toFixed(2);
            document.getElementById('taxAmountInput').value = taxAmount.toFixed(2);
            
            document.getElementById('discountDisplay').textContent = discountAmount.toFixed(2);
            document.getElementById('shippingDisplay').textContent = shippingAmount.toFixed(2);
            
            document.getElementById('totalAmount').textContent = total.toFixed(2);
            document.getElementById('totalAmountInput').value = total.toFixed(2);
        }
        
        // Add event listeners to tax rate, discount, and shipping inputs
        document.getElementById('taxRate').addEventListener('input', updateOrderTotals);
        document.getElementById('discountAmount').addEventListener('input', updateOrderTotals);
        document.getElementById('shippingAmount').addEventListener('input', updateOrderTotals);
        
        // Form submission validation
        document.getElementById('orderForm').addEventListener('submit', function(e) {
            const itemRows = document.querySelectorAll('.item-row');
            if (itemRows.length === 0) {
                e.preventDefault();
                alert('Please add at least one item to the order.');
                return false;
            }
            
            return true;
        });
        
        // Initialize order totals
        updateOrderTotals();
    });
</script>
@endsection
@endsection
