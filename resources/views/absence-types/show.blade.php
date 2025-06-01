@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Absence Type Details</h1>
        <div class="flex space-x-3">
            <a href="{{ route('absence-types.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-400 focus:ring ring-gray-300 disabled:opacity-25 transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to List
            </a>
            <a href="{{ route('absence-types.edit', $absenceType) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:border-blue-800 focus:ring ring-blue-300 disabled:opacity-25 transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Basic Information</h3>
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <div class="mb-4">
                                <span class="block text-sm font-medium text-gray-500 dark:text-gray-400">Name</span>
                                <span class="block mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $absenceType->name }}</span>
                            </div>
                            
                            <div class="mb-4">
                                <span class="block text-sm font-medium text-gray-500 dark:text-gray-400">Description</span>
                                <span class="block mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $absenceType->description ?: 'No description provided' }}</span>
                            </div>
                            
                            <div class="mb-4">
                                <span class="block text-sm font-medium text-gray-500 dark:text-gray-400">Color</span>
                                <div class="flex items-center mt-1">
                                    <div class="w-6 h-6 rounded-full mr-2" style="background-color: {{ $absenceType->color }};"></div>
                                    <span class="text-sm text-gray-900 dark:text-gray-100">{{ $absenceType->color }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Requirements</h3>
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <div class="mb-4">
                                <span class="block text-sm font-medium text-gray-500 dark:text-gray-400">Documentation Required</span>
                                <span class="block mt-1 text-sm text-gray-900 dark:text-gray-100">
                                    @if($absenceType->requires_documentation)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            Required
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                            Not Required
                                        </span>
                                    @endif
                                </span>
                            </div>
                            
                            <div>
                                <span class="block text-sm font-medium text-gray-500 dark:text-gray-400">Maximum Days Allowed</span>
                                <span class="block mt-1 text-sm text-gray-900 dark:text-gray-100">
                                    @if($absenceType->max_days_allowed)
                                        {{ $absenceType->max_days_allowed }} days per year
                                    @else
                                        Unlimited
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div>
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Usage Statistics</h3>
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <div class="mb-4">
                                <span class="block text-sm font-medium text-gray-500 dark:text-gray-400">Total Absences</span>
                                <span class="block mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $absenceType->absences->count() }}</span>
                            </div>
                            
                            <div>
                                <span class="block text-sm font-medium text-gray-500 dark:text-gray-400">Status Breakdown</span>
                                <div class="mt-2 grid grid-cols-3 gap-2">
                                    <div class="bg-blue-50 p-2 rounded-md">
                                        <span class="block text-xs font-medium text-blue-800">Pending</span>
                                        <span class="block text-lg font-semibold text-blue-800">{{ $absenceType->absences->where('status', 'pending')->count() }}</span>
                                    </div>
                                    <div class="bg-green-50 p-2 rounded-md">
                                        <span class="block text-xs font-medium text-green-800">Approved</span>
                                        <span class="block text-lg font-semibold text-green-800">{{ $absenceType->absences->where('status', 'approved')->count() }}</span>
                                    </div>
                                    <div class="bg-red-50 p-2 rounded-md">
                                        <span class="block text-xs font-medium text-red-800">Rejected</span>
                                        <span class="block text-lg font-semibold text-red-800">{{ $absenceType->absences->where('status', 'rejected')->count() }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Metadata</h3>
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <div class="mb-4">
                                <span class="block text-sm font-medium text-gray-500 dark:text-gray-400">Created</span>
                                <span class="block mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $absenceType->created_at->format('M d, Y H:i') }}</span>
                            </div>
                            
                            <div>
                                <span class="block text-sm font-medium text-gray-500 dark:text-gray-400">Last Updated</span>
                                <span class="block mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $absenceType->updated_at->format('M d, Y H:i') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-8 border-t border-gray-200 dark:border-gray-700 pt-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Actions</h3>
                <div class="flex space-x-3">
                    <a href="{{ route('absence-types.edit', $absenceType) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:border-blue-800 focus:ring ring-blue-300 disabled:opacity-25 transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit
                    </a>
                    
                    <form action="{{ route('absence-types.destroy', $absenceType) }}" method="POST" class="inline-block">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-800 focus:outline-none focus:border-red-800 focus:ring ring-red-300 disabled:opacity-25 transition" onclick="return confirm('Are you sure you want to delete this absence type? This action cannot be undone.')">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
