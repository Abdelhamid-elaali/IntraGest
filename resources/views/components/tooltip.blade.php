@props([
    'content',
    'position' => 'top',
    'trigger' => null,
    'width' => 'auto',
    'dark' => false
])

@php
$positions = [
    'top' => [
        'transform' => '-translate-x-1/2 -translate-y-full',
        'margin' => 'mb-2',
        'arrow' => 'bottom-0 left-1/2 -translate-x-1/2 translate-y-full border-t-gray-700'
    ],
    'bottom' => [
        'transform' => '-translate-x-1/2 translate-y-2',
        'margin' => 'mt-2',
        'arrow' => 'top-0 left-1/2 -translate-x-1/2 -translate-y-full border-b-gray-700'
    ],
    'left' => [
        'transform' => '-translate-x-full -translate-y-1/2',
        'margin' => 'mr-2',
        'arrow' => 'right-0 top-1/2 translate-x-full -translate-y-1/2 border-l-gray-700'
    ],
    'right' => [
        'transform' => 'translate-x-2 -translate-y-1/2',
        'margin' => 'ml-2',
        'arrow' => 'left-0 top-1/2 -translate-x-full -translate-y-1/2 border-r-gray-700'
    ]
];

$positionClass = $positions[$position] ?? $positions['top'];
$widthClass = $width === 'auto' ? '' : "w-{$width}";
$bgClass = $dark ? 'bg-gray-900' : 'bg-white border border-gray-200';
$textClass = $dark ? 'text-white' : 'text-gray-900';
@endphp

<div
    x-data="{ show: false }"
    @mouseover="show = true"
    @mouseleave="show = false"
    @focus="show = true"
    @blur="show = false"
    class="relative inline-block"
>
    <!-- Trigger -->
    <div class="cursor-help">
        {{ $trigger ?? $slot }}
    </div>

    <!-- Tooltip -->
    <div
        x-show="show"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute z-50 {{ $positionClass['margin'] }} {{ $positionClass['transform'] }}"
        style="display: none;"
    >
        <div class="relative {{ $widthClass }}">
            <div class="rounded-lg shadow-lg {{ $bgClass }} px-3 py-2">
                <div class="{{ $textClass }} text-sm">
                    {{ $content }}
                </div>
            </div>
            <!-- Arrow -->
            <div class="absolute w-2 h-2 transform rotate-45 {{ $positionClass['arrow'] }} {{ $dark ? 'bg-gray-900' : 'bg-white border border-gray-200' }}"></div>
        </div>
    </div>
</div>
