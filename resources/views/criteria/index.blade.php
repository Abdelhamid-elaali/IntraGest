@extends('layouts.app')

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle delete form submissions
    document.querySelectorAll('form[action*="criteria/"]').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (confirm('Are you sure you want to delete this criterion? This action cannot be undone.')) {
                const form = this;
                const submitButton = form.querySelector('button[type="submit"]');
                const originalButtonText = submitButton.innerHTML;
                
                // Disable submit button to prevent double submission
                submitButton.disabled = true;
                submitButton.innerHTML = '<svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Deleting...';
                
                // Submit the form via fetch API
                fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: '_method=DELETE&_token=' + document.querySelector('input[name="_token"]').value
                })
                .then(response => {
                    if (response.redirected) {
                        window.location.href = response.url;
                    } else if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    } else {
                        window.location.reload();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while deleting the criterion. Please try again.');
                    submitButton.disabled = false;
                    submitButton.innerHTML = originalButtonText;
                });
            }
        });
    });
});
</script>
@endpush

@section('content')
<div class="container">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Acceptance Criteria</h1>
        <a href="{{ route('criteria.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
            <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Add New Criteria
        </a>
    </div>

    @if(session('success'))
    <x-alert type="success" :auto-dismiss="true" :dismiss-after="4000" class="mb-4">
        {{ session('success') }}
    </x-alert>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Geographical Criteria Card -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-blue-50 px-4 py-3 border-b border-blue-100">
                <h3 class="text-lg font-medium text-blue-800">Geographical Criteria</h3>
            </div>
            <div class="p-4">
                <ul class="space-y-2">
                    @forelse($geographicalCriteria as $criterion)
                    <li class="flex justify-between items-center border-b pb-2">
                        <div>
                            <p class="font-medium text-gray-800">{{ $criterion->name }}</p>
                            <p class="text-sm text-gray-600">Weight: {{ $criterion->weight }}%</p>
                        </div>
                        <div class="flex space-x-2">
                            <a href="{{ route('criteria.edit', $criterion->id) }}" class="text-indigo-600 hover:text-indigo-900">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>
                            <form action="{{ route('criteria.destroy', $criterion->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </li>
                    @empty
                    <li class="text-center py-4 text-gray-500">
                        No geographical criteria defined yet.
                    </li>
                    @endforelse
                </ul>
            </div>
        </div>

        <!-- Social Criteria Card -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-green-50 px-4 py-3 border-b border-green-100">
                <h3 class="text-lg font-medium text-green-800">Social Criteria</h3>
            </div>
            <div class="p-4">
                <ul class="space-y-2">
                    @forelse($socialCriteria as $criterion)
                    <li class="flex justify-between items-center border-b pb-2">
                        <div>
                            <p class="font-medium text-gray-800">{{ $criterion->name }}</p>
                            <p class="text-sm text-gray-600">Weight: {{ $criterion->weight }}%</p>
                        </div>
                        <div class="flex space-x-2">
                            <a href="{{ route('criteria.edit', $criterion->id) }}" class="text-indigo-600 hover:text-indigo-900">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>
                            <form action="{{ route('criteria.destroy', $criterion->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </li>
                    @empty
                    <li class="text-center py-4 text-gray-500">
                        No social criteria defined yet.
                    </li>
                    @endforelse
                </ul>
            </div>
        </div>

        <!-- Academic Criteria Card -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-purple-50 px-4 py-3 border-b border-purple-100">
                <h3 class="text-lg font-medium text-purple-800">Academic Criteria</h3>
            </div>
            <div class="p-4">
                <ul class="space-y-2">
                    @forelse($academicCriteria as $criterion)
                    <li class="flex justify-between items-center border-b pb-2">
                        <div>
                            <p class="font-medium text-gray-800">{{ $criterion->name }}</p>
                            <p class="text-sm text-gray-600">Weight: {{ $criterion->weight }}%</p>
                        </div>
                        <div class="flex space-x-2">
                            <a href="{{ route('criteria.edit', $criterion->id) }}" class="text-indigo-600 hover:text-indigo-900">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>
                            <form action="{{ route('criteria.destroy', $criterion->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </li>
                    @empty
                    <li class="text-center py-4 text-gray-500">
                        No academic criteria defined yet.
                    </li>
                    @endforelse
                </ul>
            </div>
        </div>

        <!-- Physical Criteria Card -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-yellow-50 px-4 py-3 border-b border-yellow-100">
                <h3 class="text-lg font-medium text-yellow-800">Physical Criteria</h3>
            </div>
            <div class="p-4">
                <ul class="space-y-2">
                    @forelse($physicalCriteria as $criterion)
                    <li class="flex justify-between items-center border-b pb-2">
                        <div>
                            <p class="font-medium text-gray-800">{{ $criterion->name }}</p>
                            <p class="text-sm text-gray-600">Weight: {{ $criterion->weight }}%</p>
                        </div>
                        <div class="flex space-x-2">
                            <a href="{{ route('criteria.edit', $criterion->id) }}" class="text-indigo-600 hover:text-indigo-900">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>
                            <form action="{{ route('criteria.destroy', $criterion->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </li>
                    @empty
                    <li class="text-center py-4 text-gray-500">
                        No physical criteria defined yet.
                    </li>
                    @endforelse
                </ul>
            </div>
        </div>

        <!-- Family Criteria Card -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-red-50 px-4 py-3 border-b border-red-100">
                <h3 class="text-lg font-medium text-red-800">Family Criteria</h3>
            </div>
            <div class="p-4">
                <ul class="space-y-2">
                    @forelse($familyCriteria as $criterion)
                    <li class="flex justify-between items-center border-b pb-2">
                        <div>
                            <p class="font-medium text-gray-800">{{ $criterion->name }}</p>
                            <p class="text-sm text-gray-600">Weight: {{ $criterion->weight }}%</p>
                        </div>
                        <div class="flex space-x-2">
                            <a href="{{ route('criteria.edit', $criterion->id) }}" class="text-indigo-600 hover:text-indigo-900">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>
                            <form action="{{ route('criteria.destroy', $criterion->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </li>
                    @empty
                    <li class="text-center py-4 text-gray-500">
                        No family criteria defined yet.
                    </li>
                    @endforelse
                </ul>
            </div>
        </div>

        <!-- Overall Weighting Card -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-gray-50 px-4 py-3 border-b border-gray-100">
                <h3 class="text-lg font-medium text-gray-800">Overall Category Weights</h3>
            </div>
            <div class="p-4">
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-blue-700 font-medium">Geographical</span>
                        <span class="text-gray-700">{{ $categoryWeights['geographical'] ?? 0 }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $categoryWeights['geographical'] ?? 0 }}%"></div>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-green-700 font-medium">Social</span>
                        <span class="text-gray-700">{{ $categoryWeights['social'] ?? 0 }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div class="bg-green-600 h-2.5 rounded-full" style="width: {{ $categoryWeights['social'] ?? 0 }}%"></div>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-purple-700 font-medium">Academic</span>
                        <span class="text-gray-700">{{ $categoryWeights['academic'] ?? 0 }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div class="bg-purple-600 h-2.5 rounded-full" style="width: {{ $categoryWeights['academic'] ?? 0 }}%"></div>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-yellow-700 font-medium">Physical</span>
                        <span class="text-gray-700">{{ $categoryWeights['physical'] ?? 0 }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div class="bg-yellow-600 h-2.5 rounded-full" style="width: {{ $categoryWeights['physical'] ?? 0 }}%"></div>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-red-700 font-medium">Family</span>
                        <span class="text-gray-700">{{ $categoryWeights['family'] ?? 0 }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div class="bg-red-600 h-2.5 rounded-full" style="width: {{ $categoryWeights['family'] ?? 0 }}%"></div>
                    </div>
                </div>
                
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <a href="{{ route('criteria.weights') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-white hover:text-gray-800 border hover:border-gray-800 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                        Adjust Category Weights
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
