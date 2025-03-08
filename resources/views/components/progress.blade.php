@props([
    'value' => 0,
    'max' => 100,
    'label' => null,
    'showValue' => true,
    'size' => 'md',
    'color' => 'blue',
    'animated' => false,
    'labelInside' => false
])

@php
$sizes = [
    'sm' => 'h-1.5',
    'md' => 'h-2.5',
    'lg' => 'h-4'
];

$colors = [
    'blue' => [
        'bg' => 'bg-blue-600',
        'light' => 'bg-blue-100'
    ],
    'green' => [
        'bg' => 'bg-green-600',
        'light' => 'bg-green-100'
    ],
    'yellow' => [
        'bg' => 'bg-yellow-600',
        'light' => 'bg-yellow-100'
    ],
    'red' => [
        'bg' => 'bg-red-600',
        'light' => 'bg-red-100'
    ],
    'purple' => [
        'bg' => 'bg-purple-600',
        'light' => 'bg-purple-100'
    ]
];

$percentage = ($value / $max) * 100;
$sizeClass = $sizes[$size] ?? $sizes['md'];
$colorClass = $colors[$color] ?? $colors['blue'];
@endphp

<div>
    @if($label && !$labelInside)
        <div class="flex justify-between items-center mb-1">
            <span class="text-sm font-medium text-gray-700">{{ $label }}</span>
            @if($showValue)
                <span class="text-sm font-medium text-gray-700">{{ $percentage }}%</span>
            @endif
        </div>
    @endif

    <div class="relative">
        <div class="overflow-hidden {{ $sizeClass }} rounded-full {{ $colorClass['light'] }}">
            <div
                class="rounded-full {{ $sizeClass }} {{ $colorClass['bg'] }} {{ $animated ? 'transition-all duration-500 ease-in-out' : '' }}"
                style="width: {{ $percentage }}%"
                role="progressbar"
                aria-valuenow="{{ $value }}"
                aria-valuemin="0"
                aria-valuemax="{{ $max }}"
            >
                @if($labelInside && ($size === 'lg'))
                    <div class="absolute inset-0 flex items-center justify-center">
                        <span class="text-xs font-medium text-white">
                            {{ $label ? $label . ': ' : '' }}{{ $percentage }}%
                        </span>
                    </div>
                @endif
            </div>
        </div>

        @if($animated && $percentage < 100)
            <div class="absolute top-0 left-0 w-full {{ $sizeClass }}">
                <div class="animate-pulse rounded-full {{ $sizeClass }} {{ $colorClass['bg'] }} opacity-30"
                     style="width: {{ min($percentage + 10, 100) }}%">
                </div>
            </div>
        @endif
    </div>
</div>
