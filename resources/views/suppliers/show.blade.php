@extends('layouts.app')

@section('title', 'Supplier Details')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">{{ $supplier->name }}</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('suppliers.index') }}">Suppliers</a></li>
        <li class="breadcrumb-item active">{{ $supplier->name }}</li>
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
    
    <div class="row">
        <div class="col-xl-4">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fas fa-building me-1"></i>
                        Supplier Information
                    </div>
                    <div>
                        <span class="badge bg-{{ $supplier->status === 'active' ? 'success' : 'secondary' }}">
                            {{ ucfirst($supplier->status) }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <div>
                            <a href="{{ route('suppliers.edit', $supplier->id) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-edit me-1"></i> Edit
                            </a>
                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                <i class="fas fa-trash me-1"></i> Delete
                            </button>
                        </div>
                        <div>
                            <a href="{{ route('suppliers.orders', $supplier->id) }}" class="btn btn-info btn-sm text-white">
                                <i class="fas fa-shopping-cart me-1"></i> Orders
                            </a>
                            <a href="{{ route('suppliers.stocks', $supplier->id) }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-boxes me-1"></i> Stock Items
                            </a>
                        </div>
                    </div>
                    
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th style="width: 35%">Contact Person</th>
                                <td>{{ $supplier->contact_person ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>
                                    @if($supplier->email)
                                        <a href="mailto:{{ $supplier->email }}">{{ $supplier->email }}</a>
                                    @else
                                        N/A
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Phone</th>
                                <td>
                                    @if($supplier->phone)
                                        <a href="tel:{{ $supplier->phone }}">{{ $supplier->phone }}</a>
                                    @else
                                        N/A
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Address</th>
                                <td>{{ $supplier->address ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>City</th>
                                <td>{{ $supplier->city ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>State/Province</th>
                                <td>{{ $supplier->state ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Postal Code</th>
                                <td>{{ $supplier->postal_code ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Country</th>
                                <td>{{ $supplier->country ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Tax Number</th>
                                <td>{{ $supplier->tax_number ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Website</th>
                                <td>
                                    @if($supplier->website)
                                        <a href="{{ $supplier->website }}" target="_blank">{{ $supplier->website }}</a>
                                    @else
                                        N/A
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Created At</th>
                                <td>{{ $supplier->created_at->format('M d, Y') }}</td>
                            </tr>
                            <tr>
                                <th>Last Updated</th>
                                <td>{{ $supplier->updated_at->format('M d, Y') }}</td>
                            </tr>
                        </tbody>
                    </table>
                    
                    @if($supplier->notes)
                        <div class="mt-3">
                            <h6>Notes:</h6>
                            <p class="border p-2 rounded bg-light">{{ $supplier->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-xl-8">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card border-left-primary h-100">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Total Stock Items</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalStocks }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-boxes fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4 mb-4">
                    <div class="card border-left-success h-100">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        Active Stock Items</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $activeStocks }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4 mb-4">
                    <div class="card border-left-warning h-100">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                        Low Stock Items</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $lowStockItems }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card border-left-info h-100">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                        Expiring Stock Items</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $expiringStocks }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-calendar-times fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 mb-4">
                    <div class="card border-left-danger h-100">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                        Total Purchases</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($totalPurchases, 2) }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-shopping-cart me-1"></i>
                    Recent Orders
                    <a href="{{ route('suppliers.orders', $supplier->id) }}" class="float-end text-decoration-none">View All</a>
                </div>
                <div class="card-body">
                    @if($recentOrders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Order #</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Amount</th>
                                        <th>Payment</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentOrders as $order)
                                        <tr>
                                            <td>{{ $order->order_number }}</td>
                                            <td>{{ $order->order_date->format('M d, Y') }}</td>
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
                                            <td>
                                                <a href="{{ route('stock-orders.show', $order->id) }}" class="btn btn-sm btn-info text-white">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-center">No recent orders found.</p>
                    @endif
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-boxes me-1"></i>
                    Stock Items
                    <a href="{{ route('suppliers.stocks', $supplier->id) }}" class="float-end text-decoration-none">View All</a>
                </div>
                <div class="card-body">
                    @if($stocks->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Category</th>
                                        <th>Quantity</th>
                                        <th>Unit Price</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($stocks as $stock)
                                        <tr>
                                            <td>{{ $stock->name }}</td>
                                            <td>{{ $stock->category->name ?? 'N/A' }}</td>
                                            <td>
                                                <span class="badge bg-{{ 
                                                    $stock->quantity <= $stock->minimum_quantity ? 'danger' : 
                                                    ($stock->quantity <= $stock->minimum_quantity * 1.5 ? 'warning' : 'success') 
                                                }}">
                                                    {{ $stock->quantity }} {{ $stock->unit_type }}
                                                </span>
                                            </td>
                                            <td>{{ number_format($stock->unit_price, 2) }}</td>
                                            <td>
                                                <span class="badge bg-{{ $stock->status === 'active' ? 'success' : 'secondary' }}">
                                                    {{ ucfirst($stock->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('stocks.show', $stock->id) }}" class="btn btn-sm btn-info text-white">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-flex justify-content-center mt-4">
                            {{ $stocks->links() }}
                        </div>
                    @else
                        <p class="text-center">No stock items found for this supplier.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete supplier <strong>{{ $supplier->name }}</strong>?
                    <p class="text-danger mt-2">
                        <i class="fas fa-exclamation-triangle"></i> This action cannot be undone.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
