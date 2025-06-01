@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Edit Absence Type</h1>
        <a href="{{ route('absence-types.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-400 focus:ring ring-gray-300 disabled:opacity-25 transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to List
        </a>
    </div>

    @if ($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
            <p class="font-bold">Please fix the following errors:</p>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
            <form action="{{ route('absence-types.update', $absenceType) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-6">
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Name <span class="text-red-600">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name', $absenceType->name) }}" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">E.g., Sick Leave, Vacation, Personal Leave</p>
                </div>
                
                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description</label>
                    <textarea name="description" id="description" rows="3" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white">{{ old('description', $absenceType->description) }}</textarea>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Provide a brief description of this absence type</p>
                </div>
                
                <div class="mb-6">
                    <label for="color" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Color <span class="text-red-600">*</span></label>
                    <div class="flex items-center">
                        <input type="color" name="color" id="color" value="{{ old('color', $absenceType->color) }}" class="h-10 w-10 border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600">
                        <span class="ml-2 text-sm text-gray-500 dark:text-gray-400">Choose a color to represent this absence type</span>
                    </div>
                </div>
                
                <div class="mb-6">
                    <div class="flex items-center">
                        <input type="checkbox" name="requires_documentation" id="requires_documentation" value="1" {{ old('requires_documentation', $absenceType->requires_documentation) ? 'checked' : '' }} class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded dark:bg-gray-700 dark:border-gray-600">
                        <label for="requires_documentation" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">Requires Documentation</label>
                    </div>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 ml-6">Check if this absence type requires supporting documentation (e.g., medical certificate)</p>
                </div>
                
                <div class="mb-6">
                    <label for="max_days_allowed" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Maximum Days Allowed</label>
                    <input type="number" name="max_days_allowed" id="max_days_allowed" value="{{ old('max_days_allowed', $absenceType->max_days_allowed) }}" min="1" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Maximum number of days allowed per year (leave empty for unlimited)</p>
                </div>
                
                <div class="flex justify-end">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:border-blue-800 focus:ring ring-blue-300 disabled:opacity-25 transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Update Absence Type
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
