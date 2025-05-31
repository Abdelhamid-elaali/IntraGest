@extends('layouts.app')

@section('title', 'Add New Stock Item')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-semibold text-gray-800">Add New Stock Item</h2>
        <a href="{{ route('stocks.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Inventory
        </a>
    </div>

    @if(session('error'))
        <x-alert type="error" :auto-dismiss="true" :dismiss-after="4000" class="mb-4">
            {{ session('error') }}
        </x-alert>
    @endif

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="p-6">
            <form action="{{ route('stocks.store') }}" method="POST" class="space-y-6">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Item Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder="Enter item name" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                        <input type="text" name="category" id="category" value="{{ old('category') }}" placeholder="Enter category name" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                        @error('category')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" id="description" rows="3" placeholder="Enter item description" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Quantity -->
                    <div>
                        <label for="quantity" class="block text-sm font-medium text-gray-700">Initial Quantity</label>
                        <input type="number" name="quantity" id="quantity" value="{{ old('quantity', 0) }}" min="0" placeholder="Enter initial quantity" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                        @error('quantity')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Unit Type -->
                    <div>
                        <label for="unit_type" class="block text-sm font-medium text-gray-700">Unit Type</label>
                        <select name="unit_type" id="unit_type" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="piece" {{ old('unit_type') == 'piece' ? 'selected' : '' }}>Piece</option>
                            <option value="box" {{ old('unit_type') == 'box' ? 'selected' : '' }}>Box</option>
                            <option value="kg" {{ old('unit_type') == 'kg' ? 'selected' : '' }}>Kilogram</option>
                            <option value="liter" {{ old('unit_type') == 'liter' ? 'selected' : '' }}>Liter</option>
                            <option value="meter" {{ old('unit_type') == 'meter' ? 'selected' : '' }}>Meter</option>
                            <option value="pack" {{ old('unit_type') == 'pack' ? 'selected' : '' }}>Pack</option>
                        </select>
                        @error('unit_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Unit Price -->
                    <div>
                        <label for="unit_price" class="block text-sm font-medium text-gray-700">Unit Price</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">$</span>
                            </div>
                            <input type="number" name="unit_price" id="unit_price" value="{{ old('unit_price', 0) }}" min="0" step="0.01" placeholder="0.00" class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md" required>
                        </div>
                        @error('unit_price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Minimum Quantity -->
                    <div>
                        <label for="minimum_quantity" class="block text-sm font-medium text-gray-700">Minimum Quantity (Alert Level)</label>
                        <input type="number" name="minimum_quantity" id="minimum_quantity" value="{{ old('minimum_quantity', 10) }}" min="0" placeholder="Enter minimum stock level" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                        @error('minimum_quantity')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Maximum Quantity -->
                    <div>
                        <label for="maximum_quantity" class="block text-sm font-medium text-gray-700">Maximum Quantity (Capacity)</label>
                        <input type="number" name="maximum_quantity" id="maximum_quantity" value="{{ old('maximum_quantity', 100) }}" min="1" placeholder="Enter maximum stock capacity" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                        @error('maximum_quantity')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Expiry Date -->
                    <div>
                        <label for="expiry_date" class="block text-sm font-medium text-gray-700">Expiry Date (Optional)</label>
                        <input type="date" name="expiry_date" id="expiry_date" value="{{ old('expiry_date') }}" placeholder="YYYY-MM-DD" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        @error('expiry_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Supplier -->
                    <div>
                        <label for="supplier_id" class="block text-sm font-medium text-gray-700">Supplier</label>
                        <select name="supplier_id" id="supplier_id" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                            <option value="">Select a supplier</option>
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
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Create Stock Item
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
