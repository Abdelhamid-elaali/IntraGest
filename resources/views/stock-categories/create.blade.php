@extends('layouts.app')

@section('title', 'Create Stock Category')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-semibold text-gray-800">Create Stock Category</h2>
    </div>

    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
            <p>{{ session('error') }}</p>
        </div>
    @endif

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="p-6">
            <form action="{{ route('stock-categories.store') }}" method="POST">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Category Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Category Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder="Enter category name" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Parent Category -->
                    <div>
                        <label for="parent_id" class="block text-sm font-medium text-gray-700">Parent Category</label>
                        <select name="parent_id" id="parent_id" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="">None (Main Category)</option>
                            @foreach($mainCategories as $parentCategory)
                                <option value="{{ $parentCategory->id }}" {{ old('parent_id') == $parentCategory->id ? 'selected' : '' }}>
                                    {{ $parentCategory->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('parent_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Leave empty to create a main category</p>
                    </div>

                    <!-- Description -->
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" id="description" rows="3" placeholder="Enter category description" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Icon -->
                    <div>
                        <label for="icon" class="block text-sm font-medium text-gray-700">Icon</label>
                        <input type="text" name="icon" id="icon" value="{{ old('icon') }}" placeholder="e.g. box, tag, folder" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        @error('icon')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Enter a FontAwesome icon name</p>
                    </div>

                    <!-- Color -->
                    <div>
                        <label for="color" class="block text-sm font-medium text-gray-700">Color</label>
                        <div class="mt-1 flex items-center">
                            <input type="color" name="color" id="color" value="{{ old('color', '#6c757d') }}" class="h-8 w-12 p-0 border-0 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 cursor-pointer">
                            <span class="ml-2 px-3 py-1 text-sm rounded-md border border-gray-300 bg-gray-50" id="colorHexValue">#6c757d</span>
                        </div>
                        @error('color')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <script>
                            document.getElementById('color').addEventListener('input', function(e) {
                                document.getElementById('colorHexValue').textContent = e.target.value;
                            });
                        </script>
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status <span class="text-red-500">*</span></label>
                        <select name="status" id="status" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                        
                    <!-- Notes -->
                    <div class="md:col-span-2">
                        <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                        <textarea name="notes" id="notes" rows="3" placeholder="Enter additional notes" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="flex justify-end mt-6">
                    <a href="{{ route('stock-categories.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-300 focus:outline-none focus:border-gray-300 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 mr-3">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Cancel
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Create Category
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
