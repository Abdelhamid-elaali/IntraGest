@extends('layouts.app')

@section('content')
<div class="container">
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Edit Criterion</h1>
        <p class="text-gray-600">Update the acceptance criterion for candidate evaluation.</p>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ url('/criteria/' . $criteria->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Criterion Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $criteria->name) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                    <select name="category" id="category" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                        <option value="">Select Category</option>
                        <option value="geographical" {{ old('category', $criteria->category) == 'geographical' ? 'selected' : '' }}>Geographical</option>
                        <option value="social" {{ old('category', $criteria->category) == 'social' ? 'selected' : '' }}>Social</option>
                        <option value="academic" {{ old('category', $criteria->category) == 'academic' ? 'selected' : '' }}>Academic</option>
                        <option value="physical" {{ old('category', $criteria->category) == 'physical' ? 'selected' : '' }}>Physical</option>
                        <option value="family" {{ old('category', $criteria->category) == 'family' ? 'selected' : '' }}>Family</option>
                    </select>
                    @error('category')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="weight" class="block text-sm font-medium text-gray-700 mb-1">Weight (%)</label>
                    <input type="number" name="weight" id="weight" value="{{ old('weight', $criteria->weight) }}" min="1" max="100" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                    <p class="text-xs text-gray-500 mt-1">The importance of this criterion relative to others in the same category.</p>
                    @error('weight')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" id="description" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">{{ old('description', $criteria->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-8 flex justify-end">
                <a href="{{ route('criteria.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium py-2 px-4 rounded-md mr-2">
                    Cancel
                </a>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-md">
                    Update Criterion
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
