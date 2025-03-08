@props([
    'header' => null,
    'footer' => null,
    'variant' => 'default'
])

@php
    $variants = [
        'default' => 'bg-white',
        'primary' => 'bg-blue-50',
        'success' => 'bg-green-50',
        'warning' => 'bg-yellow-50',
        'danger' => 'bg-red-50'
    ];

    $headerClass = match($variant) {
        'primary' => 'border-b border-blue-200 text-blue-800',
        'success' => 'border-b border-green-200 text-green-800',
        'warning' => 'border-b border-yellow-200 text-yellow-800',
        'danger' => 'border-b border-red-200 text-red-800',
        default => 'border-b border-gray-200 text-gray-800'
    };

    $footerClass = match($variant) {
        'primary' => 'border-t border-blue-200 bg-blue-50',
        'success' => 'border-t border-green-200 bg-green-50',
        'warning' => 'border-t border-yellow-200 bg-yellow-50',
        'danger' => 'border-t border-red-200 bg-red-50',
        default => 'border-t border-gray-200 bg-gray-50'
    };
@endphp

<div {{ $attributes->merge(['class' => 'rounded-lg shadow-sm border border-gray-200 ' . $variants[$variant]]) }}>
    @if($header)
        <div class="px-4 py-5 sm:px-6 {{ $headerClass }}">
            {{ $header }}
        </div>
    @endif

    <div class="px-4 py-5 sm:p-6">
        {{ $slot }}
    </div>

    @if($footer)
        <div class="px-4 py-4 sm:px-6 {{ $footerClass }}">
            {{ $footer }}
        </div>
    @endif
</div>
