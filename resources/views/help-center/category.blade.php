@extends('layouts.app')

@section('title', $category->name . ' - Help Center')

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
                    <h2 class="text-2xl font-semibold text-gray-800">{{ $category->name }}</h2>
                    <p class="mt-1 text-sm text-gray-600">{{ $category->description }}</p>
                </div>
            </div>

            <!-- Search Form -->
            <form action="{{ route('help-center.search') }}" method="GET" class="mt-6">
                <div class="flex">
                    <div class="relative flex-grow">
                        <input type="text" name="q" placeholder="Search in {{ $category->name }}..." class="block w-full rounded-l-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm pl-10">
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

        <!-- Articles List -->
        <div class="space-y-4">
            @forelse($articles as $article)
                <a href="{{ route('help-center.show', $article->slug) }}" class="block p-4 rounded-lg border border-gray-200 hover:border-blue-500 hover:shadow-md transition-all duration-200">
                    <h3 class="text-lg font-medium text-gray-900">{{ $article->title }}</h3>
                    @if($article->excerpt)
                        <p class="mt-1 text-sm text-gray-600">{{ $article->excerpt }}</p>
                    @endif
                    <div class="mt-2 flex items-center text-xs text-gray-500">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Last updated {{ $article->updated_at->diffForHumans() }}
                        
                        <span class="mx-2">•</span>
                        
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        {{ $article->view_count }} views
                    </div>
                </a>
            @empty
                <div class="text-center py-8 text-gray-500">
                    No articles found in this category.
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $articles->links() }}
        </div>
    </div>
</div>
@endsection
