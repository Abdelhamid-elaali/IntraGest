@extends('layouts.app')

@section('title', 'Edit Stock Order')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Edit Stock Order #{{ $order->order_number }}</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('stock-orders.index') }}">Stock Orders</a></li>
        <li class="breadcrumb-item"><a href="{{ route('stock-orders.show', $order->id) }}">Order #{{ $order->order_number }}</a></li>
        <li class="breadcrumb-item active">Edit</li>
    </ol>
    
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-edit me-1"></i>
            Edit Order Details
        </div>
        <div class="card-body">
            @if($order->status !== 'pending')
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-1"></i>
                    <strong>Warning:</strong> This order is already {{ $order->status }}. Only pending orders can be fully edited.
                    Some fields may be disabled.
                </div>
            @endif
            
            <form action="{{ route('stock-orders.update', $order->id) }}" method="POST" id="orderForm">
                @csrf
                @method('PUT')
                
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-header">
                                <i class="fas fa-info-circle me-1"></i>
                                Order Information
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="order_number" class="form-label">Order Number <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('order_number') is-invalid @enderror" id="order_number" name="order_number" value="{{ old('order_number', $order->order_number) }}" required {{ $order->status !== 'pending' ? 'readonly' : '' }}>
                                    @error('order_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="reference_number" class="form-label">Reference Number</label>
                                    <input type="text" class="form-control @error('reference_number') is-invalid @enderror" id="reference_number" name="reference_number" value="{{ old('reference_number', $order->reference_number) }}">
                                    @error('reference_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Supplier's reference number, if available</small>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="order_date" class="form-label">Order Date <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control @error('order_date') is-invalid @enderror" id="order_date" name="order_date" value="{{ old('order_date', $order->order_date->format('Y-m-d')) }}" required {{ $order->status !== 'pending' ? 'readonly' : '' }}>
                                            @error('order_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="expected_delivery_date" class="form-label">Expected Delivery</label>
                                            <input type="date" class="form-control @error('expected_delivery_date') is-invalid @enderror" id="expected_delivery_date" name="expected_delivery_date" value="{{ old('expected_delivery_date', $order->expected_delivery_date ? $order->expected_delivery_date->format('Y-m-d') : '') }}">
                                            @error('expected_delivery_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="notes" class="form-label">Order Notes</label>
                                    <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3">{{ old('notes', $order->notes) }}</textarea>
                                    @error('notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-header">
                                <i class="fas fa-building me-1"></i>
                                Supplier Information
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="supplier_id" class="form-label">Supplier <span class="text-danger">*</span></label>
                                    <select class="form-select @error('supplier_id') is-invalid @enderror" id="supplier_id" name="supplier_id" required {{ $order->status !== 'pending' ? 'disabled' : '' }}>
                                        <option value="">Select Supplier</option>
                                        @foreach($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}" {{ (old('supplier_id', $order->supplier_id) == $supplier->id) ? 'selected' : '' }}>
                                                {{ $supplier->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if($order->status !== 'pending')
                                        <input type="hidden" name="supplier_id" value="{{ $order->supplier_id }}">
                                    @endif
                                    @error('supplier_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div id="supplierDetails" class="mt-4">
                                    <div class="card">
                                        <div class="card-body p-3">
                                            <h6 class="card-title">{{ $order->supplier->name }}</h6>
                                            <p class="card-text mb-1">
                                                <i class="fas fa-user me-1"></i> {{ $order->supplier->contact_person ?? 'N/A' }}
                                            </p>
                                            <p class="card-text mb-1">
                                                <i class="fas fa-envelope me-1"></i> {{ $order->supplier->email ?? 'N/A' }}
                                            </p>
                                            <p class="card-text mb-1">
                                                <i class="fas fa-phone me-1"></i> {{ $order->supplier->phone ?? 'N/A' }}
                                            </p>
                                            <p class="card-text mb-0">
                                                <i class="fas fa-map-marker-alt me-1"></i> {{ $order->supplier->address ?? 'N/A' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-list me-1"></i>
                            Order Items
                        </div>
                        @if($order->status === 'pending')
                        <div>
                            <button type="button" class="btn btn-primary btn-sm" id="addItemBtn">
                                <i class="fas fa-plus me-1"></i> Add Item
                            </button>
                        </div>
                        @endif
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="itemsTable">
                                <thead>
                                    <tr>
                                        <th style="width: 35%">Item</th>
                                        <th style="width: 15%">Quantity</th>
                                        <th style="width: 15%">Unit Type</th>
                                        <th style="width: 15%">Unit Price</th>
                                        <th style="width: 15%">Subtotal</th>
                                        <th style="width: 5%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="itemsTableBody">
                                    @forelse($order->items as $index => $item)
                                        <tr class="item-row">
                                            <td>
                                                <select class="form-select item-select" name="items[{{ $index }}][stock_id]" {{ $order->status !== 'pending' ? 'disabled' : 'required' }}>
                                                    <option value="">Select Item</option>
                                                    @foreach($stocks as $stock)
                                                        <option value="{{ $stock->id }}" {{ $item->stock_id == $stock->id ? 'selected' : '' }}>
                                                            {{ $stock->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @if($order->status !== 'pending')
                                                    <input type="hidden" name="items[{{ $index }}][stock_id]" value="{{ $item->stock_id }}">
                                                @endif
                                                <input type="hidden" name="items[{{ $index }}][id]" value="{{ $item->id }}">
                                            </td>
                                            <td>
                                                <input type="number" class="form-control item-quantity" name="items[{{ $index }}][quantity]" value="{{ $item->quantity }}" min="1" {{ $order->status !== 'pending' ? 'readonly' : 'required' }}>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control item-unit" name="items[{{ $index }}][unit_type]" value="{{ $item->unit_type }}" {{ $order->status !== 'pending' ? 'readonly' : 'required' }}>
                                            </td>
                                            <td>
                                                <input type="number" step="0.01" class="form-control item-price" name="items[{{ $index }}][unit_price]" value="{{ $item->unit_price }}" min="0.01" {{ $order->status !== 'pending' ? 'readonly' : 'required' }}>
                                            </td>
                                            <td>
                                                <span class="item-subtotal">{{ number_format($item->quantity * $item->unit_price, 2) }}</span>
                                            </td>
                                            <td>
                                                @if($order->status === 'pending')
                                                <button type="button" class="btn btn-danger btn-sm remove-item">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                                @else
                                                <button type="button" class="btn btn-secondary btn-sm" disabled>
                                                    <i class="fas fa-lock"></i>
                                                </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr id="noItemsRow">
                                            <td colspan="6" class="text-center">No items added yet. Click "Add Item" to add items to this order.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="4" class="text-end">Subtotal:</th>
                                        <th colspan="2">
                                            <span id="subtotalAmount">{{ number_format($order->subtotal, 2) }}</span>
                                            <input type="hidden" name="subtotal" id="subtotalInput" value="{{ old('subtotal', $order->subtotal) }}">
                                        </th>
                                    </tr>
                                    <tr>
                                        <th colspan="4" class="text-end">
                                            Tax Rate (%):
                                            <input type="number" step="0.01" class="form-control form-control-sm d-inline-block" style="width: 80px;" id="taxRate" name="tax_rate" value="{{ old('tax_rate', $order->tax_rate) }}" min="0" max="100" {{ $order->status !== 'pending' ? 'readonly' : '' }}>
                                        </th>
                                        <th colspan="2">
                                            <span id="taxAmount">{{ number_format($order->tax_amount, 2) }}</span>
                                            <input type="hidden" name="tax_amount" id="taxAmountInput" value="{{ old('tax_amount', $order->tax_amount) }}">
                                        </th>
                                    </tr>
                                    <tr>
                                        <th colspan="4" class="text-end">
                                            Discount:
                                            <input type="number" step="0.01" class="form-control form-control-sm d-inline-block" style="width: 80px;" id="discountAmount" name="discount_amount" value="{{ old('discount_amount', $order->discount_amount) }}" min="0" {{ $order->status !== 'pending' ? 'readonly' : '' }}>
                                        </th>
                                        <th colspan="2">
                                            <span id="discountDisplay">{{ number_format($order->discount_amount, 2) }}</span>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th colspan="4" class="text-end">
                                            Shipping:
                                            <input type="number" step="0.01" class="form-control form-control-sm d-inline-block" style="width: 80px;" id="shippingAmount" name="shipping_amount" value="{{ old('shipping_amount', $order->shipping_amount) }}" min="0" {{ $order->status !== 'pending' ? 'readonly' : '' }}>
                                        </th>
                                        <th colspan="2">
                                            <span id="shippingDisplay">{{ number_format($order->shipping_amount, 2) }}</span>
                                        </th>
                                    </tr>
                                    <tr class="table-primary">
                                        <th colspan="4" class="text-end">Total:</th>
                                        <th colspan="2">
                                            <span id="totalAmount" class="fw-bold">{{ number_format($order->total_amount, 2) }}</span>
                                            <input type="hidden" name="total_amount" id="totalAmountInput" value="{{ old('total_amount', $order->total_amount) }}">
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                
                <div class="d-flex justify-content-end">
                    <a href="{{ route('stock-orders.show', $order->id) }}" class="btn btn-secondary me-2">Cancel</a>
                    @if($order->status === 'pending')
                        <button type="submit" class="btn btn-primary" id="submitOrder">Update Order</button>
                    @else
                        <button type="submit" class="btn btn-primary" id="submitOrder">Update Available Fields</button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize variables
        let itemIndex = {{ $order->items->count() }};
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
        
        // Add item button (only if order is pending)
        @if($order->status === 'pending')
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
        @endif
        
        // Add event listeners to existing rows
        document.querySelectorAll('.item-row').forEach(function(row) {
            const select = row.querySelector('.item-select');
            const quantityInput = row.querySelector('.item-quantity');
            const priceInput = row.querySelector('.item-price');
            const removeButton = row.querySelector('.remove-item');
            
            // Only add listeners if order is pending
            @if($order->status === 'pending')
            // Stock selection change
            if (select) {
                select.addEventListener('change', function() {
                    const selectedStockId = this.value;
                    if (selectedStockId && stocksData[selectedStockId]) {
                        const stock = stocksData[selectedStockId];
                        row.querySelector('.item-unit').value = stock.unit_type;
                        row.querySelector('.item-price').value = stock.unit_price;
                        updateSubtotal(row);
                    }
                });
            }
            
            // Quantity or price change
            if (quantityInput) {
                quantityInput.addEventListener('input', function() {
                    updateSubtotal(row);
                });
            }
            
            if (priceInput) {
                priceInput.addEventListener('input', function() {
                    updateSubtotal(row);
                });
            }
            
            // Remove item
            if (removeButton) {
                removeButton.addEventListener('click', function() {
                    row.remove();
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
            }
            @endif
        });
        
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
                subtotal += parseFloat(el.textContent.replace(/,/g, '')) || 0;
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
        
        // Add event listeners to tax rate, discount, and shipping inputs (only if order is pending)
        @if($order->status === 'pending')
        document.getElementById('taxRate').addEventListener('input', updateOrderTotals);
        document.getElementById('discountAmount').addEventListener('input', updateOrderTotals);
        document.getElementById('shippingAmount').addEventListener('input', updateOrderTotals);
        @endif
        
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
