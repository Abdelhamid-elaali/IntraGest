@extends('layouts.app')

@section('title', 'Order Details')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Order #{{ $order->order_number }}</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('stock-orders.index') }}">Stock Orders</a></li>
        <li class="breadcrumb-item active">Order #{{ $order->order_number }}</li>
    </ol>
    
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-shopping-cart me-1"></i>
                Order Details
            </div>
            <div>
                @if($order->status === 'pending')
                    <a href="{{ route('stock-orders.edit', $order->id) }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-edit me-1"></i> Edit Order
                    </a>
                    <button type="button" class="btn btn-success btn-sm ms-1" data-bs-toggle="modal" data-bs-target="#approveModal">
                        <i class="fas fa-check me-1"></i> Approve
                    </button>
                    <button type="button" class="btn btn-danger btn-sm ms-1" data-bs-toggle="modal" data-bs-target="#cancelModal">
                        <i class="fas fa-times me-1"></i> Cancel
                    </button>
                @elseif($order->status === 'approved')
                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#deliverModal">
                        <i class="fas fa-truck me-1"></i> Mark as Delivered
                    </button>
                @endif
                <a href="{{ route('stock-orders.index') }}" class="btn btn-secondary btn-sm ms-1">
                    <i class="fas fa-arrow-left me-1"></i> Back to Orders
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h5 class="mb-3">Order Information</h5>
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 40%">Order Number</th>
                            <td>{{ $order->order_number }}</td>
                        </tr>
                        <tr>
                            <th>Reference Number</th>
                            <td>{{ $order->reference_number ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Order Date</th>
                            <td>{{ $order->order_date->format('M d, Y') }}</td>
                        </tr>
                        <tr>
                            <th>Expected Delivery</th>
                            <td>
                                @if($order->expected_delivery_date)
                                    {{ $order->expected_delivery_date->format('M d, Y') }}
                                @else
                                    N/A
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                <span class="badge bg-{{ 
                                    $order->status === 'pending' ? 'warning' : 
                                    ($order->status === 'approved' ? 'info' : 
                                    ($order->status === 'delivered' ? 'success' : 'secondary')) 
                                }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Payment Status</th>
                            <td>
                                <span class="badge bg-{{ 
                                    $order->payment_status === 'paid' ? 'success' : 
                                    ($order->payment_status === 'partial' ? 'warning' : 'danger') 
                                }}">
                                    {{ ucfirst($order->payment_status) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Total Amount</th>
                            <td>{{ number_format($order->total_amount, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Amount Paid</th>
                            <td>{{ number_format($order->amount_paid, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Balance Due</th>
                            <td>{{ number_format($order->total_amount - $order->amount_paid, 2) }}</td>
                        </tr>
                    </table>
                </div>
                
                <div class="col-md-6">
                    <h5 class="mb-3">Supplier Information</h5>
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 40%">Supplier</th>
                            <td>
                                <a href="{{ route('suppliers.show', $order->supplier->id) }}">
                                    {{ $order->supplier->name }}
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <th>Contact Person</th>
                            <td>{{ $order->supplier->contact_person ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>
                                @if($order->supplier->email)
                                    <a href="mailto:{{ $order->supplier->email }}">{{ $order->supplier->email }}</a>
                                @else
                                    N/A
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Phone</th>
                            <td>
                                @if($order->supplier->phone)
                                    <a href="tel:{{ $order->supplier->phone }}">{{ $order->supplier->phone }}</a>
                                @else
                                    N/A
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Address</th>
                            <td>
                                {{ $order->supplier->address ?? 'N/A' }}
                                @if($order->supplier->city)
                                    <br>{{ $order->supplier->city }}
                                    @if($order->supplier->state)
                                        , {{ $order->supplier->state }}
                                    @endif
                                    @if($order->supplier->postal_code)
                                        {{ $order->supplier->postal_code }}
                                    @endif
                                @endif
                                @if($order->supplier->country)
                                    <br>{{ $order->supplier->country }}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Tax ID</th>
                            <td>{{ $order->supplier->tax_id ?? 'N/A' }}</td>
                        </tr>
                    </table>
                    
                    <div class="mt-3">
                        <a href="{{ route('suppliers.orders', $order->supplier->id) }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-list me-1"></i> View All Orders from this Supplier
                        </a>
                    </div>
                </div>
            </div>
            
            @if($order->notes)
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card bg-light">
                        <div class="card-header">
                            <i class="fas fa-sticky-note me-1"></i>
                            Order Notes
                        </div>
                        <div class="card-body">
                            {{ $order->notes }}
                        </div>
                    </div>
                </div>
            </div>
            @endif
            
            <div class="row mb-4">
                <div class="col-12">
                    <h5 class="mb-3">Order Items</h5>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Item</th>
                                    <th>Category</th>
                                    <th>Quantity</th>
                                    <th>Unit Price</th>
                                    <th>Subtotal</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($order->items as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        @if($item->stock)
                                            <a href="{{ route('stocks.show', $item->stock->id) }}">
                                                {{ $item->stock->name }}
                                            </a>
                                        @else
                                            {{ $item->name }}
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->stock && $item->stock->category)
                                            {{ $item->stock->category->name }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>{{ $item->quantity }} {{ $item->unit_type }}</td>
                                    <td>{{ number_format($item->unit_price, 2) }}</td>
                                    <td>{{ number_format($item->quantity * $item->unit_price, 2) }}</td>
                                    <td>
                                        <span class="badge bg-{{ 
                                            $order->status === 'pending' ? 'warning' : 
                                            ($order->status === 'approved' ? 'info' : 
                                            ($order->status === 'delivered' ? 'success' : 'secondary')) 
                                        }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">No items found for this order.</td>
                                </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="5" class="text-end">Subtotal:</th>
                                    <th>{{ number_format($order->subtotal, 2) }}</th>
                                    <td></td>
                                </tr>
                                @if($order->tax_amount > 0)
                                <tr>
                                    <th colspan="5" class="text-end">Tax ({{ $order->tax_rate }}%):</th>
                                    <th>{{ number_format($order->tax_amount, 2) }}</th>
                                    <td></td>
                                </tr>
                                @endif
                                @if($order->discount_amount > 0)
                                <tr>
                                    <th colspan="5" class="text-end">Discount:</th>
                                    <th>{{ number_format($order->discount_amount, 2) }}</th>
                                    <td></td>
                                </tr>
                                @endif
                                @if($order->shipping_amount > 0)
                                <tr>
                                    <th colspan="5" class="text-end">Shipping:</th>
                                    <th>{{ number_format($order->shipping_amount, 2) }}</th>
                                    <td></td>
                                </tr>
                                @endif
                                <tr>
                                    <th colspan="5" class="text-end">Total:</th>
                                    <th>{{ number_format($order->total_amount, 2) }}</th>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <h5 class="mb-3">Order Timeline</h5>
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-item-marker">
                                <div class="timeline-item-marker-text">{{ $order->created_at->format('M d') }}</div>
                                <div class="timeline-item-marker-indicator bg-primary"></div>
                            </div>
                            <div class="timeline-item-content">
                                <span class="fw-bold">Order Created</span>
                                <p class="mb-0">by {{ $order->user->name ?? 'System' }} at {{ $order->created_at->format('H:i') }}</p>
                            </div>
                        </div>
                        
                        @if($order->status !== 'pending')
                        <div class="timeline-item">
                            <div class="timeline-item-marker">
                                <div class="timeline-item-marker-text">{{ $order->approved_at ? $order->approved_at->format('M d') : 'N/A' }}</div>
                                <div class="timeline-item-marker-indicator bg-info"></div>
                            </div>
                            <div class="timeline-item-content">
                                <span class="fw-bold">Order Approved</span>
                                <p class="mb-0">by {{ $order->approved_by_user->name ?? 'System' }} at {{ $order->approved_at ? $order->approved_at->format('H:i') : 'N/A' }}</p>
                            </div>
                        </div>
                        @endif
                        
                        @if($order->status === 'delivered')
                        <div class="timeline-item">
                            <div class="timeline-item-marker">
                                <div class="timeline-item-marker-text">{{ $order->delivered_at ? $order->delivered_at->format('M d') : 'N/A' }}</div>
                                <div class="timeline-item-marker-indicator bg-success"></div>
                            </div>
                            <div class="timeline-item-content">
                                <span class="fw-bold">Order Delivered</span>
                                <p class="mb-0">by {{ $order->delivered_by_user->name ?? 'System' }} at {{ $order->delivered_at ? $order->delivered_at->format('H:i') : 'N/A' }}</p>
                            </div>
                        </div>
                        @endif
                        
                        @if($order->status === 'cancelled')
                        <div class="timeline-item">
                            <div class="timeline-item-marker">
                                <div class="timeline-item-marker-text">{{ $order->cancelled_at ? $order->cancelled_at->format('M d') : 'N/A' }}</div>
                                <div class="timeline-item-marker-indicator bg-secondary"></div>
                            </div>
                            <div class="timeline-item-content">
                                <span class="fw-bold">Order Cancelled</span>
                                <p class="mb-0">by {{ $order->cancelled_by_user->name ?? 'System' }} at {{ $order->cancelled_at ? $order->cancelled_at->format('H:i') : 'N/A' }}</p>
                                @if($order->cancellation_reason)
                                <p class="text-muted mt-2">Reason: {{ $order->cancellation_reason }}</p>
                                @endif
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                
                <div class="col-md-6">
                    <h5 class="mb-3">Payment Information</h5>
                    <div class="card mb-3">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-money-bill-wave me-1"></i>
                                Payment Status
                            </div>
                            @if($order->status !== 'cancelled' && $order->payment_status !== 'paid')
                            <div>
                                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#recordPaymentModal">
                                    <i class="fas fa-plus me-1"></i> Record Payment
                                </button>
                            </div>
                            @endif
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <div class="card bg-{{ $order->payment_status === 'paid' ? 'success' : ($order->payment_status === 'partial' ? 'warning' : 'danger') }} text-white h-100">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="me-3">
                                                    <div class="text-white-75 small">Status</div>
                                                    <div class="text-lg fw-bold">{{ ucfirst($order->payment_status) }}</div>
                                                </div>
                                                <i class="fas fa-{{ $order->payment_status === 'paid' ? 'check-circle' : ($order->payment_status === 'partial' ? 'percentage' : 'times-circle') }} fa-2x text-white-50"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="card bg-primary text-white h-100">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="me-3">
                                                    <div class="text-white-75 small">Total</div>
                                                    <div class="text-lg fw-bold">{{ number_format($order->total_amount, 2) }}</div>
                                                </div>
                                                <i class="fas fa-dollar-sign fa-2x text-white-50"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="card bg-info text-white h-100">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="me-3">
                                                    <div class="text-white-75 small">Balance</div>
                                                    <div class="text-lg fw-bold">{{ number_format($order->total_amount - $order->amount_paid, 2) }}</div>
                                                </div>
                                                <i class="fas fa-balance-scale fa-2x text-white-50"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            @if($order->payments->count() > 0)
                            <div class="table-responsive mt-3">
                                <table class="table table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Amount</th>
                                            <th>Method</th>
                                            <th>Reference</th>
                                            <th>Recorded By</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($order->payments as $payment)
                                        <tr>
                                            <td>{{ $payment->payment_date->format('M d, Y') }}</td>
                                            <td>{{ number_format($payment->amount, 2) }}</td>
                                            <td>{{ ucfirst($payment->payment_method) }}</td>
                                            <td>{{ $payment->reference_number ?? 'N/A' }}</td>
                                            <td>{{ $payment->user->name ?? 'System' }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <div class="alert alert-info mt-3">
                                <i class="fas fa-info-circle me-1"></i> No payments have been recorded for this order yet.
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Approve Modal -->
    @if($order->status === 'pending')
    <div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="approveModalLabel">Approve Order</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('stock-orders.approve', $order->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <p>Are you sure you want to approve order <strong>#{{ $order->order_number }}</strong>?</p>
                        <div class="mb-3">
                            <label for="notes" class="form-label">Approval Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Approve Order</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Cancel Modal -->
    <div class="modal fade" id="cancelModal" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cancelModalLabel">Cancel Order</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('stock-orders.cancel', $order->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <p>Are you sure you want to cancel order <strong>#{{ $order->order_number }}</strong>?</p>
                        <div class="mb-3">
                            <label for="cancel_reason" class="form-label">Cancellation Reason</label>
                            <textarea class="form-control" id="cancel_reason" name="notes" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger">Cancel Order</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @elseif($order->status === 'approved')
    <!-- Deliver Modal -->
    <div class="modal fade" id="deliverModal" tabindex="-1" aria-labelledby="deliverModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deliverModalLabel">Mark as Delivered</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('stock-orders.deliver', $order->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <p>Are you sure you want to mark order <strong>#{{ $order->order_number }}</strong> as delivered?</p>
                        <div class="mb-3">
                            <label for="delivery_date" class="form-label">Delivery Date</label>
                            <input type="date" class="form-control" id="delivery_date" name="delivery_date" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="delivery_notes" class="form-label">Delivery Notes</label>
                            <textarea class="form-control" id="delivery_notes" name="notes" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Mark as Delivered</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
    
    <!-- Record Payment Modal -->
    @if($order->status !== 'cancelled' && $order->payment_status !== 'paid')
    <div class="modal fade" id="recordPaymentModal" tabindex="-1" aria-labelledby="recordPaymentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="recordPaymentModalLabel">Record Payment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('stock-orders.payment', $order->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="payment_date" class="form-label">Payment Date</label>
                            <input type="date" class="form-control" id="payment_date" name="payment_date" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="amount" class="form-label">Amount</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" step="0.01" class="form-control" id="amount" name="amount" value="{{ number_format($order->total_amount - $order->amount_paid, 2, '.', '') }}" max="{{ number_format($order->total_amount - $order->amount_paid, 2, '.', '') }}" required>
                            </div>
                            <small class="form-text text-muted">Maximum amount: {{ number_format($order->total_amount - $order->amount_paid, 2) }}</small>
                        </div>
                        <div class="mb-3">
                            <label for="payment_method" class="form-label">Payment Method</label>
                            <select class="form-select" id="payment_method" name="payment_method" required>
                                <option value="cash">Cash</option>
                                <option value="check">Check</option>
                                <option value="credit_card">Credit Card</option>
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="reference_number" class="form-label">Reference Number</label>
                            <input type="text" class="form-control" id="reference_number" name="reference_number">
                            <small class="form-text text-muted">Transaction ID, check number, etc.</small>
                        </div>
                        <div class="mb-3">
                            <label for="payment_notes" class="form-label">Notes</label>
                            <textarea class="form-control" id="payment_notes" name="notes" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Record Payment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>

<style>
.timeline {
    position: relative;
    padding-left: 1.5rem;
    margin: 1rem auto;
}
.timeline::before {
    content: '';
    position: absolute;
    height: 100%;
    border-left: 1px dashed #e0e0e0;
    left: 0.5rem;
    top: 0;
}
.timeline-item {
    position: relative;
    margin-bottom: 1.5rem;
}
.timeline-item-marker {
    position: absolute;
    left: -1.5rem;
    width: 1rem;
    display: flex;
    flex-direction: column;
    align-items: center;
}
.timeline-item-marker-text {
    font-size: 0.75rem;
    color: #6c757d;
    margin-bottom: 0.25rem;
}
.timeline-item-marker-indicator {
    height: 0.75rem;
    width: 0.75rem;
    border-radius: 100%;
    background-color: #0d6efd;
}
.timeline-item-content {
    padding: 0 0 0 1rem;
}
</style>
@endsection
