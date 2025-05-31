@extends('layouts.app')

@section('title', 'Supplier Stock Items')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">{{ $supplier->name }} - Stock Items</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('suppliers.index') }}">Suppliers</a></li>
        <li class="breadcrumb-item"><a href="{{ route('suppliers.show', $supplier->id) }}">{{ $supplier->name }}</a></li>
        <li class="breadcrumb-item active">Stock Items</li>
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
                <i class="fas fa-boxes me-1"></i>
                Stock Items from {{ $supplier->name }}
            </div>
            <div>
                <a href="{{ route('stocks.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus me-1"></i> Add New Stock Item
                </a>
                <a href="{{ route('suppliers.show', $supplier->id) }}" class="btn btn-secondary btn-sm ms-2">
                    <i class="fas fa-arrow-left me-1"></i> Back to Supplier
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <form action="{{ route('suppliers.stocks', $supplier->id) }}" method="GET" class="row g-3">
                    <div class="col-md-3">
                        <select name="category_id" class="form-select" onchange="this.form.submit()">
                            <option value="">All Categories</option>
                            @foreach(\App\Models\StockCategory::where('parent_id', null)->get() as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select" onchange="this.form.submit()">
                            <option value="">All Statuses</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Search stock items..." value="{{ request('search') }}">
                            <button class="btn btn-outline-secondary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-2">
                        @if(request('search') || request('category_id') || request('status'))
                            <a href="{{ route('suppliers.stocks', $supplier->id) }}" class="btn btn-outline-secondary">
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
                            <th>Name</th>
                            <th>Category</th>
                            <th>Subcategory</th>
                            <th>Quantity</th>
                            <th>Unit Price</th>
                            <th>Location</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stocks as $stock)
                            <tr>
                                <td>{{ $stock->name }}</td>
                                <td>{{ $stock->category->name ?? 'N/A' }}</td>
                                <td>{{ $stock->subcategory->name ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-{{ 
                                        $stock->quantity <= $stock->minimum_quantity ? 'danger' : 
                                        ($stock->quantity <= $stock->minimum_quantity * 1.5 ? 'warning' : 'success') 
                                    }}">
                                        {{ $stock->quantity }} {{ $stock->unit_type }}
                                    </span>
                                </td>
                                <td>{{ number_format($stock->unit_price, 2) }}</td>
                                <td>{{ $stock->location ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-{{ $stock->status === 'active' ? 'success' : 'secondary' }}">
                                        {{ ucfirst($stock->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('stocks.show', $stock->id) }}" class="btn btn-sm btn-info text-white">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('stocks.edit', $stock->id) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-success" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#addStockModal{{ $stock->id }}">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-warning" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#removeStockModal{{ $stock->id }}">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                    </div>
                                    
                                    <!-- Add Stock Modal -->
                                    <div class="modal fade" id="addStockModal{{ $stock->id }}" tabindex="-1" aria-labelledby="addStockModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="addStockModalLabel">Add Stock</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('stocks.add', $stock->id) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label for="quantity{{ $stock->id }}" class="form-label">Quantity to Add</label>
                                                            <input type="number" class="form-control" id="quantity{{ $stock->id }}" name="quantity" min="1" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="notes{{ $stock->id }}" class="form-label">Notes</label>
                                                            <textarea class="form-control" id="notes{{ $stock->id }}" name="notes" rows="3"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-success">Add Stock</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Remove Stock Modal -->
                                    <div class="modal fade" id="removeStockModal{{ $stock->id }}" tabindex="-1" aria-labelledby="removeStockModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="removeStockModalLabel">Remove Stock</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('stocks.remove', $stock->id) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label for="remove_quantity{{ $stock->id }}" class="form-label">Quantity to Remove</label>
                                                            <input type="number" class="form-control" id="remove_quantity{{ $stock->id }}" name="quantity" min="1" max="{{ $stock->quantity }}" required>
                                                            <small class="text-muted">Current stock: {{ $stock->quantity }} {{ $stock->unit_type }}</small>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="remove_notes{{ $stock->id }}" class="form-label">Notes</label>
                                                            <textarea class="form-control" id="remove_notes{{ $stock->id }}" name="notes" rows="3"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-warning">Remove Stock</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">No stock items found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center mt-4">
                {{ $stocks->links() }}
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-pie me-1"></i>
                    Stock Status Summary
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="card bg-danger text-white h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="me-3">
                                            <div class="text-white-75 small">Low Stock</div>
                                            <div class="text-lg fw-bold">{{ $supplier->lowStockItems()->count() }}</div>
                                        </div>
                                        <i class="fas fa-exclamation-triangle fa-2x text-white-50"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card bg-warning text-white h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="me-3">
                                            <div class="text-white-75 small">Expiring Soon</div>
                                            <div class="text-lg fw-bold">{{ $supplier->expiringStocks()->count() }}</div>
                                        </div>
                                        <i class="fas fa-calendar-times fa-2x text-white-50"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card bg-success text-white h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="me-3">
                                            <div class="text-white-75 small">Active</div>
                                            <div class="text-lg fw-bold">{{ $supplier->activeStocks()->count() }}</div>
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
        
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-bar me-1"></i>
                    Stock Categories Distribution
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Category</th>
                                    <th>Items Count</th>
                                    <th>Total Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $categories = \App\Models\StockCategory::whereHas('stocks', function($query) use ($supplier) {
                                        $query->where('supplier_id', $supplier->id);
                                    })->get();
                                @endphp
                                
                                @foreach($categories as $category)
                                    @php
                                        $categoryStocks = $supplier->stocks()->where('category_id', $category->id)->get();
                                        $totalValue = $categoryStocks->sum(function($stock) {
                                            return $stock->quantity * $stock->unit_price;
                                        });
                                    @endphp
                                    <tr>
                                        <td>{{ $category->name }}</td>
                                        <td>{{ $categoryStocks->count() }}</td>
                                        <td>{{ number_format($totalValue, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
