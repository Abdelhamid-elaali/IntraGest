@extends('layouts.app')

@section('title', 'Help & Support')

@section('content')
<div class="bg-white shadow-sm rounded-lg">
    <div class="p-6 border-b border-gray-200">
        <!-- Header Section -->
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-gray-900">How can we help you?</h2>
            <p class="mt-4 text-lg text-gray-600">Search our help center or browse categories below</p>
            
            <!-- Search Form -->
            <form action="{{ route('help-center.search') }}" method="GET" class="mt-6 max-w-2xl mx-auto">
                <div class="flex">
                    <div class="relative flex-grow">
                        <input type="text" name="q" placeholder="Search for help..." class="block w-full rounded-l-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm pl-10" required>
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

        <!-- Popular Articles -->
        <div class="mb-8">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Popular Articles</h3>
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                @forelse($popularArticles as $article)
                    <a href="{{ route('help-center.show', $article->slug) }}" class="block p-4 rounded-lg border border-gray-200 hover:border-blue-500 hover:shadow-md transition-all duration-200">
                        <h4 class="font-medium text-gray-900">{{ $article->title }}</h4>
                        <p class="mt-1 text-sm text-gray-500">{{ Str::limit($article->excerpt, 100) }}</p>
                    </a>
                @empty
                    <div class="col-span-3 text-center text-gray-500">
                        No articles found.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Help Categories -->
        <div>
            <h3 class="text-lg font-medium text-gray-900 mb-4">Browse by Category</h3>
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @forelse($categories as $category)
                    <div class="bg-gray-50 rounded-lg p-6">
                        <div class="flex items-center mb-4">
                            <span class="inline-flex items-center justify-center h-10 w-10 rounded-lg bg-blue-100 text-blue-600">
                                {!! $category->icon !!}
                            </span>
                            <h4 class="ml-3 text-lg font-medium text-gray-900">{{ $category->name }}</h4>
                        </div>
                        <ul class="space-y-2">
                            @foreach($category->articles->take(3) as $article)
                                <li>
                                    <a href="{{ route('help-center.show', $article->slug) }}" class="text-gray-600 hover:text-blue-600">
                                        {{ $article->title }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                        @if($category->articles->count() > 3)
                            <a href="{{ route('help-center.category', $category->slug) }}" class="mt-4 inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-800">
                                View all articles
                                <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        @endif
                    </div>
                @empty
                    <div class="col-span-3 text-center text-gray-500">
                        No categories found.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Contact Support -->
        <div class="mt-12 bg-gray-50 rounded-lg p-6">
            <div class="text-center">
                <h3 class="text-lg font-medium text-gray-900">Still need help?</h3>
                <p class="mt-2 text-sm text-gray-600">Contact our support team for personalized assistance</p>
                <a href="mailto:support@intragest.com" class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                    Contact Support
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
