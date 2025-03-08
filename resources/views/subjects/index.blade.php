@extends('layouts.app')

@section('title', 'Subjects')

@section('header', 'Subjects Management')

@section('content')
    <!-- Actions Bar -->
    <div class="mb-6 flex justify-between items-center">
        <div class="flex-1">
            <div class="max-w-lg flex gap-4">
                <x-input 
                    type="search" 
                    placeholder="Search subjects..." 
                    class="flex-1"
                />
                <x-select class="w-48">
                    <option value="">All Departments</option>
                    <option value="science">Science</option>
                    <option value="mathematics">Mathematics</option>
                    <option value="languages">Languages</option>
                    <option value="humanities">Humanities</option>
                </x-select>
            </div>
        </div>
        <div class="flex items-center gap-4">
            <x-button variant="success">
                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Add New Subject
            </x-button>
            <x-button variant="outline">
                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Export
            </x-button>
        </div>
    </div>

    <!-- Subjects Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Mathematics -->
        <x-card>
            <div class="flex items-start justify-between">
                <div>
                    <h3 class="text-lg font-medium text-gray-900">Mathematics</h3>
                    <p class="mt-1 text-sm text-gray-500">Advanced Mathematics</p>
                </div>
                <x-badge variant="primary">Active</x-badge>
            </div>
            <div class="mt-4 border-t border-gray-200 pt-4">
                <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Department</dt>
                        <dd class="mt-1 text-sm text-gray-900">Mathematics</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Credits</dt>
                        <dd class="mt-1 text-sm text-gray-900">4</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Students</dt>
                        <dd class="mt-1 text-sm text-gray-900">45</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Teachers</dt>
                        <dd class="mt-1 text-sm text-gray-900">2</dd>
                    </div>
                </dl>
            </div>
            <div class="mt-4 flex gap-2">
                <x-button variant="primary" size="sm" class="flex-1">View Details</x-button>
                <x-button variant="outline" size="sm" class="flex-1">Edit</x-button>
            </div>
        </x-card>

        <!-- Physics -->
        <x-card>
            <div class="flex items-start justify-between">
                <div>
                    <h3 class="text-lg font-medium text-gray-900">Physics</h3>
                    <p class="mt-1 text-sm text-gray-500">General Physics</p>
                </div>
                <x-badge variant="warning">Pending</x-badge>
            </div>
            <div class="mt-4 border-t border-gray-200 pt-4">
                <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Department</dt>
                        <dd class="mt-1 text-sm text-gray-900">Science</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Credits</dt>
                        <dd class="mt-1 text-sm text-gray-900">3</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Students</dt>
                        <dd class="mt-1 text-sm text-gray-900">38</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Teachers</dt>
                        <dd class="mt-1 text-sm text-gray-900">1</dd>
                    </div>
                </dl>
            </div>
            <div class="mt-4 flex gap-2">
                <x-button variant="primary" size="sm" class="flex-1">View Details</x-button>
                <x-button variant="outline" size="sm" class="flex-1">Edit</x-button>
            </div>
        </x-card>

        <!-- Literature -->
        <x-card>
            <div class="flex items-start justify-between">
                <div>
                    <h3 class="text-lg font-medium text-gray-900">Literature</h3>
                    <p class="mt-1 text-sm text-gray-500">World Literature</p>
                </div>
                <x-badge variant="success">Active</x-badge>
            </div>
            <div class="mt-4 border-t border-gray-200 pt-4">
                <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Department</dt>
                        <dd class="mt-1 text-sm text-gray-900">Languages</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Credits</dt>
                        <dd class="mt-1 text-sm text-gray-900">3</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Students</dt>
                        <dd class="mt-1 text-sm text-gray-900">32</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Teachers</dt>
                        <dd class="mt-1 text-sm text-gray-900">2</dd>
                    </div>
                </dl>
            </div>
            <div class="mt-4 flex gap-2">
                <x-button variant="primary" size="sm" class="flex-1">View Details</x-button>
                <x-button variant="outline" size="sm" class="flex-1">Edit</x-button>
            </div>
        </x-card>
    </div>

    <!-- Pagination -->
    <div class="mt-8 flex items-center justify-between border-t border-gray-200 bg-white px-4 py-3 sm:px-6">
        <div class="flex flex-1 justify-between sm:hidden">
            <x-button variant="outline" size="sm">Previous</x-button>
            <x-button variant="outline" size="sm">Next</x-button>
        </div>
        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-gray-700">
                    Showing <span class="font-medium">1</span> to <span class="font-medium">10</span> of <span class="font-medium">97</span> results
                </p>
            </div>
            <div>
                <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                    <x-button variant="outline" size="sm" class="rounded-l-md">Previous</x-button>
                    <x-button variant="primary" size="sm" class="mx-2">1</x-button>
                    <x-button variant="outline" size="sm" class="mx-2">2</x-button>
                    <x-button variant="outline" size="sm" class="mx-2">3</x-button>
                    <x-button variant="outline" size="sm" class="rounded-r-md">Next</x-button>
                </nav>
            </div>
        </div>
    </div>
@endsection
