@extends('layouts.app')

@section('title', 'Supplier Orders')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">{{ $supplier->name }} - Orders</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('suppliers.index') }}">Suppliers</a></li>
        <li class="breadcrumb-item"><a href="{{ route('suppliers.show', $supplier->id) }}">{{ $supplier->name }}</a></li>
        <li class="breadcrumb-item active">Orders</li>
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
                Orders from {{ $supplier->name }}
            </div>
            <div>
                <a href="{{ route('stock-orders.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus me-1"></i> Create New Order
                </a>
                <a href="{{ route('suppliers.show', $supplier->id) }}" class="btn btn-secondary btn-sm ms-2">
                    <i class="fas fa-arrow-left me-1"></i> Back to Supplier
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <form action="{{ route('suppliers.orders', $supplier->id) }}" method="GET" class="row g-3">
                    <div class="col-md-3">
                        <select name="status" class="form-select" onchange="this.form.submit()">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="payment_status" class="form-select" onchange="this.form.submit()">
                            <option value="">All Payment Statuses</option>
                            <option value="unpaid" {{ request('payment_status') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                            <option value="partial" {{ request('payment_status') == 'partial' ? 'selected' : '' }}>Partially Paid</option>
                            <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Search order #, reference..." value="{{ request('search') }}">
                            <button class="btn btn-outline-secondary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-2">
                        @if(request('search') || request('status') || request('payment_status'))
                            <a href="{{ route('suppliers.orders', $supplier->id) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-1"></i> Clear
                            </a>
                        @endif
                    </div>
                </form>
            </div>
            
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Order #</th>
                            <th>Reference</th>
                            <th>Order Date</th>
                            <th>Expected Delivery</th>
                            <th>Status</th>
                            <th>Total Amount</th>
                            <th>Payment Status</th>
                            <th>Created By</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr>
                                <td>{{ $order->order_number }}</td>
                                <td>{{ $order->reference_number ?? 'N/A' }}</td>
                                <td>{{ $order->order_date->format('M d, Y') }}</td>
                                <td>
                                    @if($order->expected_delivery_date)
                                        {{ $order->expected_delivery_date->format('M d, Y') }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-{{ 
                                        $order->status === 'pending' ? 'warning' : 
                                        ($order->status === 'approved' ? 'info' : 
                                        ($order->status === 'delivered' ? 'success' : 'secondary')) 
                                    }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td>{{ number_format($order->total_amount, 2) }}</td>
                                <td>
                                    <span class="badge bg-{{ 
                                        $order->payment_status === 'paid' ? 'success' : 
                                        ($order->payment_status === 'partial' ? 'warning' : 'danger') 
                                    }}">
                                        {{ ucfirst($order->payment_status) }}
                                    </span>
                                </td>
                                <td>{{ $order->user->name ?? 'N/A' }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('stock-orders.show', $order->id) }}" class="btn btn-sm btn-info text-white">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($order->status === 'pending')
                                            <a href="{{ route('stock-orders.edit', $order->id) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">No orders found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center mt-4">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-bar me-1"></i>
                    Order Status Summary
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <div class="card bg-warning text-white h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="me-3">
                                            <div class="text-white-75 small">Pending</div>
                                            <div class="text-lg fw-bold">{{ $supplier->pendingOrders()->count() }}</div>
                                        </div>
                                        <i class="fas fa-clock fa-2x text-white-50"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card bg-info text-white h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="me-3">
                                            <div class="text-white-75 small">Approved</div>
                                            <div class="text-lg fw-bold">{{ $supplier->approvedOrders()->count() }}</div>
                                        </div>
                                        <i class="fas fa-thumbs-up fa-2x text-white-50"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card bg-success text-white h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="me-3">
                                            <div class="text-white-75 small">Delivered</div>
                                            <div class="text-lg fw-bold">{{ $supplier->deliveredOrders()->count() }}</div>
                                        </div>
                                        <i class="fas fa-truck fa-2x text-white-50"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card bg-secondary text-white h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="me-3">
                                            <div class="text-white-75 small">Cancelled</div>
                                            <div class="text-lg fw-bold">{{ $supplier->orders()->where('status', 'cancelled')->count() }}</div>
                                        </div>
                                        <i class="fas fa-ban fa-2x text-white-50"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-pie me-1"></i>
                    Payment Status Summary
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="card bg-danger text-white h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="me-3">
                                            <div class="text-white-75 small">Unpaid</div>
                                            <div class="text-lg fw-bold">{{ $supplier->orders()->where('payment_status', 'unpaid')->count() }}</div>
                                        </div>
                                        <i class="fas fa-times-circle fa-2x text-white-50"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card bg-warning text-white h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="me-3">
                                            <div class="text-white-75 small">Partial</div>
                                            <div class="text-lg fw-bold">{{ $supplier->orders()->where('payment_status', 'partial')->count() }}</div>
                                        </div>
                                        <i class="fas fa-percentage fa-2x text-white-50"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card bg-success text-white h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="me-3">
                                            <div class="text-white-75 small">Paid</div>
                                            <div class="text-lg fw-bold">{{ $supplier->orders()->where('payment_status', 'paid')->count() }}</div>
                                        </div>
                                        <i class="fas fa-check-circle fa-2x text-white-50"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
