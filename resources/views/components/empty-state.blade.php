@props([
    'title',
    'description' => '',
    'icon' => null,
    'action' => null,
    'actionLabel' => '',
    'actionUrl' => '#',
    'image' => null
])

<div class="text-center py-12">
    @if($image)
        <img src="{{ $image }}" alt="Empty state illustration" class="mx-auto h-48 w-auto">
    @elseif($icon)
        <div class="mx-auto h-12 w-12 text-gray-400">
            {!! $icon !!}
        </div>
    @else
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
        </svg>
    @endif

    <h3 class="mt-4 text-lg font-medium text-gray-900">{{ $title }}</h3>

    @if($description)
        <p class="mt-2 text-sm text-gray-500">{{ $description }}</p>
    @endif

    @if($action)
        <div class="mt-6">
            <a href="{{ $actionUrl }}" {{ $action->attributes->merge(['class' => 'inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500']) }}>
                {{ $actionLabel }}
            </a>
        </div>
    @endif
</div>
