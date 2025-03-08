@extends('layouts.app')

@section('title', 'Search Results - Help Center')

@section('content')
<div class="bg-white shadow-sm rounded-lg">
    <div class="p-6 border-b border-gray-200">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center space-x-3">
                <a href="{{ route('help-center.index') }}" class="text-blue-600 hover:text-blue-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <div>
                    <h2 class="text-2xl font-semibold text-gray-800">Search Results</h2>
                    <p class="mt-1 text-sm text-gray-600">
                        @if($articles->total() > 0)
                            Found {{ $articles->total() }} {{ Str::plural('result', $articles->total()) }} for "{{ $query }}"
                        @else
                            No results found for "{{ $query }}"
                        @endif
                    </p>
                </div>
            </div>

            <!-- Search Form -->
            <form action="{{ route('help-center.search') }}" method="GET" class="mt-6">
                <div class="flex">
                    <div class="relative flex-grow">
                        <input type="text" name="q" value="{{ $query }}" class="block w-full rounded-l-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm pl-10">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-r-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Search
                    </button>
                </div>
            </form>
        </div>

        <!-- Search Results -->
        <div class="space-y-4">
            @forelse($articles as $article)
                <a href="{{ route('help-center.show', $article->slug) }}" class="block p-4 rounded-lg border border-gray-200 hover:border-blue-500 hover:shadow-md transition-all duration-200">
                    <div class="flex items-start justify-between">
                        <div class="flex-grow">
                            <h3 class="text-lg font-medium text-gray-900">{{ $article->title }}</h3>
                            <div class="mt-1 flex items-center text-sm text-gray-500">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $article->category->name }}
                                </span>
                                <span class="mx-2">•</span>
                                Last updated {{ $article->updated_at->diffForHumans() }}
                            </div>
                            @if($article->excerpt)
                                <p class="mt-2 text-sm text-gray-600">{{ $article->excerpt }}</p>
                            @endif
                        </div>
                        <svg class="w-5 h-5 text-gray-400 ml-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>
                </a>
            @empty
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No results found</h3>
                    <p class="mt-1 text-sm text-gray-500">Try adjusting your search terms or browse our help categories.</p>
                    <div class="mt-6">
                        <a href="{{ route('help-center.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                            Browse Help Center
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($articles->hasPages())
            <div class="mt-6">
                {{ $articles->appends(['q' => $query])->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
