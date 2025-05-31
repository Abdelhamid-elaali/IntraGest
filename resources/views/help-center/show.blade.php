@extends('layouts.app')

@section('title', $article->title . ' - Help Center')

@section('content')
<div class="bg-white shadow-sm rounded-lg">
    <div class="p-6 border-b border-gray-200">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center space-x-3 mb-4">
                <a href="{{ route('help-center.category', $article->category->slug) }}" class="text-blue-600 hover:text-blue-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <div>
                    <div class="text-sm text-gray-500">
                        <a href="{{ route('help-center.index') }}" class="hover:text-blue-600">Help Center</a>
                        <span class="mx-2">/</span>
                        <a href="{{ route('help-center.category', $article->category->slug) }}" class="hover:text-blue-600">{{ $article->category->name }}</a>
                    </div>
                    <h2 class="text-2xl font-semibold text-gray-800 mt-1">{{ $article->title }}</h2>
                </div>
            </div>

            <div class="flex items-center text-sm text-gray-500">
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
        </div>

        <!-- Article Content -->
        <div class="prose max-w-none">
            {!! Illuminate\Support\Str::markdown($article->content) !!}
        </div>

        <!-- Related Articles -->
        @if($article->category->articles->where('id', '!=', $article->id)->count() > 0)
            <div class="mt-12 pt-8 border-t border-gray-200">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Related Articles</h3>
                <div class="grid gap-4 md:grid-cols-2">
                    @foreach($article->category->articles->where('id', '!=', $article->id)->take(4) as $relatedArticle)
                        <a href="{{ route('help-center.show', $relatedArticle->slug) }}" class="block p-4 rounded-lg border border-gray-200 hover:border-blue-500 hover:shadow-md transition-all duration-200">
                            <h4 class="font-medium text-gray-900">{{ $relatedArticle->title }}</h4>
                            @if($relatedArticle->excerpt)
                                <p class="mt-1 text-sm text-gray-600">{{ Str::limit($relatedArticle->excerpt, 100) }}</p>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Helpful Section -->
        <div class="mt-12 pt-8 border-t border-gray-200">
            <div class="text-center">
                <h3 class="text-lg font-medium text-gray-900">Was this article helpful?</h3>
                <div class="mt-4 space-x-4">
                    <a href="mailto:support@intragest.com?subject=Feedback: {{ $article->title }}&body=Article URL: {{ url()->current() }}%0D%0A%0D%0AYour feedback:" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        Send Feedback
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
