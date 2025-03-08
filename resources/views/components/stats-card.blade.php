@props([
    'title',
    'value',
    'description' => '',
    'icon' => null,
    'trend' => null,
    'trendValue' => null,
    'color' => 'blue'
])

@php
$colors = [
    'blue' => 'bg-blue-500',
    'green' => 'bg-green-500',
    'yellow' => 'bg-yellow-500',
    'red' => 'bg-red-500',
    'purple' => 'bg-purple-500'
];

$bgColor = $colors[$color] ?? 'bg-blue-500';
$lightBgColor = str_replace('500', '50', $bgColor);
$textColor = str_replace('bg-', 'text-', $bgColor);
@endphp

<div class="bg-white overflow-hidden rounded-lg shadow">
    <div class="p-5">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="{{ $lightBgColor }} rounded-md p-3">
                    @if($icon)
                        <div class="{{ $textColor }} h-6 w-6">
                            {!! $icon !!}
                        </div>
                    @endif
                </div>
            </div>
            <div class="ml-5 w-0 flex-1">
                <dl>
                    <dt class="text-sm font-medium text-gray-500 truncate">
                        {{ $title }}
                    </dt>
                    <dd class="flex items-baseline">
                        <div class="text-2xl font-semibold text-gray-900">
                            {{ $value }}
                        </div>

                        @if($trend && $trendValue)
                            <div class="ml-2 flex items-baseline text-sm font-semibold {{ $trend === 'up' ? 'text-green-600' : 'text-red-600' }}">
                                <svg class="self-center flex-shrink-0 h-5 w-5 {{ $trend === 'up' ? 'text-green-500' : 'text-red-500' }}" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                    @if($trend === 'up')
                                        <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    @else
                                        <path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    @endif
                                </svg>
                                <span class="sr-only">
                                    {{ $trend === 'up' ? 'Increased' : 'Decreased' }} by
                                </span>
                                {{ $trendValue }}
                            </div>
                        @endif
                    </dd>
                    @if($description)
                        <dd class="mt-1 text-sm text-gray-500">
                            {{ $description }}
                        </dd>
                    @endif
                </dl>
            </div>
        </div>
    </div>
</div>
