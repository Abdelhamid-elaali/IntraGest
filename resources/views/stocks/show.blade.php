@extends('layouts.app')

@section('title', 'Stock Details')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-semibold text-gray-800">Stock Details: {{ $stock->name }}</h2>
        <div class="flex space-x-2">
            <a href="{{ route('stocks.edit', $stock) }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 active:bg-yellow-900 focus:outline-none focus:border-yellow-900 focus:ring ring-yellow-300 disabled:opacity-25 transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit
            </a>
            <a href="{{ route('stocks.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to List
            </a>
        </div>
    </div>

    @if(session('success'))
        <x-alert type="success" :auto-dismiss="true" :dismiss-after="4000" class="mb-4">
            {{ session('success') }}
        </x-alert>
    @endif

    @if(session('error'))
        <x-alert type="error" :auto-dismiss="true" :dismiss-after="4000" class="mb-4">
            {{ session('error') }}
        </x-alert>
    @endif

    <!-- Stock Details Card -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Item Information</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Basic Info -->
                <div class="space-y-4">
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Item Code</h4>
                        <p class="mt-1 text-sm text-gray-900">{{ $stock->code }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Name</h4>
                        <p class="mt-1 text-sm text-gray-900">{{ $stock->name }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Category</h4>
                        <p class="mt-1 text-sm text-gray-900">{{ $stock->category }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Description</h4>
                        <p class="mt-1 text-sm text-gray-900">{{ $stock->description ?? 'No description provided' }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Supplier</h4>
                        <p class="mt-1 text-sm text-gray-900">{{ $stock->supplier->name ?? 'No supplier' }}</p>
                    </div>
                </div>

                <!-- Stock Levels -->
                <div class="space-y-4">
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Current Quantity</h4>
                        <div class="flex items-center mt-1">
                            <span class="text-2xl font-bold {{ $stock->quantity <= $stock->minimum_quantity ? 'text-red-600' : 'text-green-600' }}">
                                {{ $stock->quantity }}
                            </span>
                            <span class="ml-1 text-sm text-gray-500">{{ $stock->unit_type }}</span>
                        </div>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Stock Level</h4>
                        <div class="mt-1 w-full bg-gray-200 rounded-full h-2.5">
                            <div class="h-2.5 rounded-full {{ $stockPercentage < 25 ? 'bg-red-600' : ($stockPercentage < 50 ? 'bg-yellow-400' : 'bg-green-600') }}" style="width: {{ $stockPercentage }}%"></div>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">{{ $stockPercentage }}% of maximum capacity ({{ $stock->maximum_quantity }} {{ $stock->unit_type }})</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Unit Price</h4>
                        <p class="mt-1 text-sm text-gray-900">${{ number_format($stock->unit_price, 2) }} per {{ $stock->unit_type }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Total Value</h4>
                        <p class="mt-1 text-sm text-gray-900">${{ number_format($stock->quantity * $stock->unit_price, 2) }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Expiry Date</h4>
                        <p class="mt-1 text-sm {{ $stock->expiry_date && strtotime($stock->expiry_date) < time() ? 'text-red-600' : 'text-gray-900' }}">
                            {{ $stock->expiry_date ? date('F j, Y', strtotime($stock->expiry_date)) : 'No expiry date' }}
                            @if($stock->expiry_date && strtotime($stock->expiry_date) < time())
                                <span class="text-red-600 font-semibold"> (EXPIRED)</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stock Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Add Stock Form -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">Add Stock</h3>
            </div>
            <div class="p-6">
                <form action="{{ route('stocks.add', $stock) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="add_quantity" class="block text-sm font-medium text-gray-700">Quantity to Add</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <input type="number" name="quantity" id="add_quantity" min="1" value="1" class="focus:ring-blue-500 focus:border-blue-500 block w-full pr-12 sm:text-sm border-gray-300 rounded-md" required>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">{{ $stock->unit_type }}</span>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label for="add_unit_price" class="block text-sm font-medium text-gray-700">Unit Price</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">$</span>
                            </div>
                            <input type="number" name="unit_price" id="add_unit_price" value="{{ $stock->unit_price }}" min="0" step="0.01" class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md" required>
                        </div>
                    </div>
                    <div>
                        <label for="add_reference_number" class="block text-sm font-medium text-gray-700">Reference Number (Optional)</label>
                        <input type="text" name="reference_number" id="add_reference_number" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    </div>
                    <div>
                        <label for="add_notes" class="block text-sm font-medium text-gray-700">Notes (Optional)</label>
                        <textarea name="notes" id="add_notes" rows="2" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
                    </div>
                    <div>
                        <label for="add_expiry_date" class="block text-sm font-medium text-gray-700">Expiry Date (Optional)</label>
                        <input type="date" name="expiry_date" id="add_expiry_date" value="{{ $stock->expiry_date ? date('Y-m-d', strtotime($stock->expiry_date)) : '' }}" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    </div>
                    <div class="pt-2">
                        <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Add Stock
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Remove Stock Form -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">Remove Stock</h3>
            </div>
            <div class="p-6">
                <form action="{{ route('stocks.remove', $stock) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="remove_quantity" class="block text-sm font-medium text-gray-700">Quantity to Remove</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <input type="number" name="quantity" id="remove_quantity" min="1" max="{{ $stock->quantity }}" value="1" class="focus:ring-blue-500 focus:border-blue-500 block w-full pr-12 sm:text-sm border-gray-300 rounded-md" required>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">{{ $stock->unit_type }}</span>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label for="remove_reference_number" class="block text-sm font-medium text-gray-700">Reference Number (Optional)</label>
                        <input type="text" name="reference_number" id="remove_reference_number" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    </div>
                    <div>
                        <label for="remove_notes" class="block text-sm font-medium text-gray-700">Notes (Optional)</label>
                        <textarea name="notes" id="remove_notes" rows="2" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
                    </div>
                    <div class="pt-2">
                        <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring ring-red-300 disabled:opacity-25 transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                            </svg>
                            Remove Stock
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Transaction History -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Transaction History</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Price</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reference</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notes</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($transactions as $transaction)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ date('M d, Y H:i', strtotime($transaction->transaction_date)) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $transaction->type == 'in' ? 'bg-green-100 text-green-800' : 
                                       ($transaction->type == 'out' ? 'bg-red-100 text-red-800' : 
                                       ($transaction->type == 'initial' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800')) }}">
                                    {{ ucfirst($transaction->type) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $transaction->quantity }} {{ $stock->unit_type }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                ${{ number_format($transaction->unit_price, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                ${{ number_format($transaction->quantity * $transaction->unit_price, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $transaction->reference_number }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $transaction->user->name ?? 'System' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $transaction->notes ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                No transactions found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4">
            {{ $transactions->links() }}
        </div>
    </div>

    <!-- Delete Stock Button -->
    <div class="flex justify-end">
        <form action="{{ route('stocks.destroy', $stock) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this stock item? This action cannot be undone.');">
            @csrf
            @method('DELETE')
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring ring-red-300 disabled:opacity-25 transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
                Delete Stock Item
            </button>
        </form>
    </div>
</div>
@endsection
