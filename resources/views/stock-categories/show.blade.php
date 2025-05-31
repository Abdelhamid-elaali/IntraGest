@extends('layouts.app')

@section('title', 'Category Details')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">{{ $category->name }}</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('stock-categories.index') }}">Stock Categories</a></li>
        <li class="breadcrumb-item active">{{ $category->name }}</li>
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
                        @if($category->icon)
                            <i class="fas fa-{{ $category->icon }} me-1" style="color: {{ $category->color ?? '#6c757d' }}"></i>
                        @else
                            <i class="fas fa-tag me-1"></i>
                        @endif
                        Category Details
                    </div>
                    <div>
                        <a href="{{ route('stock-categories.edit', $category->id) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit me-1"></i> Edit
                        </a>
                        <button type="button" class="btn btn-danger btn-sm ms-1" data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="fas fa-trash me-1"></i> Delete
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h5 class="mb-3">Basic Information</h5>
                        <table class="table table-bordered">
                            <tr>
                                <th style="width: 40%">Name</th>
                                <td>{{ $category->name }}</td>
                            </tr>
                            <tr>
                                <th>Type</th>
                                <td>
                                    @if($category->parent_id)
                                        <span class="badge bg-secondary">Subcategory</span>
                                    @else
                                        <span class="badge bg-primary">Main Category</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Parent Category</th>
                                <td>
                                    @if($category->parent)
                                        <a href="{{ route('stock-categories.show', $category->parent->id) }}">
                                            {{ $category->parent->name }}
                                        </a>
                                    @else
                                        N/A
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    <span class="badge bg-{{ $category->status === 'active' ? 'success' : 'secondary' }}">
                                        {{ ucfirst($category->status) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Created</th>
                                <td>{{ $category->created_at->format('M d, Y H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Last Updated</th>
                                <td>{{ $category->updated_at->format('M d, Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                    
                    @if($category->description || $category->notes)
                    <div class="mb-3">
                        <h5 class="mb-3">Additional Information</h5>
                        @if($category->description)
                        <div class="mb-3">
                            <h6>Description</h6>
                            <p class="mb-0">{{ $category->description }}</p>
                        </div>
                        @endif
                        
                        @if($category->notes)
                        <div>
                            <h6>Notes</h6>
                            <p class="mb-0">{{ $category->notes }}</p>
                        </div>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-pie me-1"></i>
                    Category Statistics
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="card bg-primary text-white h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="me-3">
                                            <div class="text-white-75 small">Stock Items</div>
                                            <div class="text-lg fw-bold">{{ $category->stocks_count }}</div>
                                        </div>
                                        <i class="fas fa-boxes fa-2x text-white-50"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="card bg-info text-white h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="me-3">
                                            <div class="text-white-75 small">Subcategories</div>
                                            <div class="text-lg fw-bold">{{ $category->subcategories_count }}</div>
                                        </div>
                                        <i class="fas fa-tags fa-2x text-white-50"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="card bg-danger text-white h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="me-3">
                                            <div class="text-white-75 small">Low Stock</div>
                                            <div class="text-lg fw-bold">{{ $lowStockCount }}</div>
                                        </div>
                                        <i class="fas fa-exclamation-triangle fa-2x text-white-50"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="card bg-success text-white h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="me-3">
                                            <div class="text-white-75 small">Total Value</div>
                                            <div class="text-lg fw-bold">{{ number_format($totalValue, 2) }}</div>
                                        </div>
                                        <i class="fas fa-dollar-sign fa-2x text-white-50"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-8">
            @if($category->parent_id === null && $subcategories->count() > 0)
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fas fa-tags me-1"></i>
                        Subcategories
                    </div>
                    <div>
                        <a href="{{ route('stock-categories.create') }}?parent_id={{ $category->id }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus me-1"></i> Add Subcategory
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    <th>Stock Items</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($subcategories as $subcategory)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($subcategory->icon)
                                                <i class="fas fa-{{ $subcategory->icon }} me-2" style="color: {{ $subcategory->color ?? '#6c757d' }}"></i>
                                            @endif
                                            {{ $subcategory->name }}
                                        </div>
                                    </td>
                                    <td>{{ \Illuminate\Support\Str::limit($subcategory->description, 50) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $subcategory->status === 'active' ? 'success' : 'secondary' }}">
                                            {{ ucfirst($subcategory->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $subcategory->stocks_count }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('stock-categories.show', $subcategory->id) }}" class="btn btn-sm btn-info text-white">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('stock-categories.edit', $subcategory->id) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
            
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fas fa-boxes me-1"></i>
                        Stock Items
                    </div>
                    <div>
                        <a href="{{ route('stocks.create') }}?category_id={{ $category->id }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus me-1"></i> Add Stock Item
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <form action="{{ route('stock-categories.show', $category->id) }}" method="GET" class="row g-3">
                            <div class="col-md-3">
                                <select name="status" class="form-select" onchange="this.form.submit()">
                                    <option value="">All Statuses</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="stock_level" class="form-select" onchange="this.form.submit()">
                                    <option value="">All Stock Levels</option>
                                    <option value="low" {{ request('stock_level') == 'low' ? 'selected' : '' }}>Low Stock</option>
                                    <option value="normal" {{ request('stock_level') == 'normal' ? 'selected' : '' }}>Normal Stock</option>
                                    <option value="high" {{ request('stock_level') == 'high' ? 'selected' : '' }}>High Stock</option>
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
                                @if(request('search') || request('status') || request('stock_level'))
                                    <a href="{{ route('stock-categories.show', $category->id) }}" class="btn btn-outline-secondary">
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
                                    <th>Supplier</th>
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
                                    <td>
                                        @if($stock->supplier)
                                            <a href="{{ route('suppliers.show', $stock->supplier->id) }}">
                                                {{ $stock->supplier->name }}
                                            </a>
                                        @else
                                            N/A
                                        @endif
                                    </td>
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
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">No stock items found in this category.</td>
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
                    Are you sure you want to delete category <strong>{{ $category->name }}</strong>?
                    @if($category->subcategories_count > 0)
                        <div class="alert alert-warning mt-2">
                            <i class="fas fa-exclamation-triangle"></i> This category has {{ $category->subcategories_count }} subcategories that will also be affected.
                        </div>
                    @endif
                    @if($category->stocks_count > 0)
                        <div class="alert alert-danger mt-2">
                            <i class="fas fa-exclamation-triangle"></i> This category has {{ $category->stocks_count }} stock items associated with it.
                        </div>
                    @endif
                    <p class="text-danger mt-2">
                        <i class="fas fa-exclamation-triangle"></i> This action cannot be undone.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form action="{{ route('stock-categories.destroy', $category->id) }}" method="POST">
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
