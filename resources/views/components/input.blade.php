@props([
    'type' => 'text',
    'disabled' => false,
    'leadingIcon' => null,
    'trailingIcon' => null,
    'leadingText' => null,
    'trailingText' => null,
    'size' => 'md'
])

@php
    $baseClasses = 'block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 disabled:bg-gray-100 disabled:cursor-not-allowed';
    $hasLeading = $leadingIcon || $leadingText;
    $hasTrailing = $trailingIcon || $trailingText || $attributes->get('error');
    
    $sizes = [
        'sm' => 'px-3 py-2 text-sm leading-4',
        'md' => 'px-3 py-2 text-sm',
        'lg' => 'px-4 py-2 text-base'
    ];

    $roundedClasses = match(true) {
        $hasLeading && $hasTrailing => 'rounded-none',
        $hasLeading => 'rounded-none rounded-r-md',
        $hasTrailing => 'rounded-none rounded-l-md',
        default => 'rounded-md'
    };

    $errorClasses = $attributes->get('error') 
        ? 'border-red-300 text-red-900 placeholder-red-300 focus:border-red-500 focus:ring-red-500' 
        : '';
@endphp

<div class="relative rounded-md shadow-sm">
    @if($hasLeading)
        <div class="absolute inset-y-0 left-0 flex items-center">
            @if($leadingIcon)
                <span class="pl-3 text-gray-500 sm:text-sm">
                    {{ $leadingIcon }}
                </span>
            @endif
            @if($leadingText)
                <span class="pl-3 text-gray-500 sm:text-sm border-r border-gray-300 pr-3">
                    {{ $leadingText }}
                </span>
            @endif
        </div>
    @endif

    <input
        type="{{ $type }}"
        {{ $disabled ? 'disabled' : '' }}
        {{ $attributes->merge([
            'class' => $baseClasses . ' ' . 
                      $roundedClasses . ' ' . 
                      $sizes[$size] . ' ' . 
                      $errorClasses . ' ' .
                      ($hasLeading ? 'pl-10' : '') . ' ' .
                      ($hasTrailing ? 'pr-10' : '')
        ]) }}
    >

    @if($hasTrailing)
        <div class="absolute inset-y-0 right-0 flex items-center">
            @if($trailingIcon)
                <span class="pr-3 text-gray-500 sm:text-sm">
                    {{ $trailingIcon }}
                </span>
            @endif
            @if($trailingText)
                <span class="pr-3 text-gray-500 sm:text-sm border-l border-gray-300 pl-3">
                    {{ $trailingText }}
                </span>
            @endif
            @if($attributes->get('error'))
                <div class="pr-3 pointer-events-none">
                    <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
            @endif
        </div>
    @endif
</div>
