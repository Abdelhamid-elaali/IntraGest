@props([
    'disabled' => false,
    'placeholder' => 'Select an option',
    'size' => 'md',
    'leadingIcon' => null,
    'multiple' => false,
    'searchable' => false
])

@php
    $baseClasses = 'block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 disabled:bg-gray-100 disabled:cursor-not-allowed';
    $hasLeading = $leadingIcon;
    
    $sizes = [
        'sm' => 'pl-3 pr-10 py-1.5 text-sm leading-4',
        'md' => 'pl-3 pr-10 py-2 text-sm',
        'lg' => 'pl-4 pr-10 py-2 text-base'
    ];

    $roundedClasses = $hasLeading ? 'rounded-none rounded-r-md' : 'rounded-md';
    $errorClasses = $attributes->get('error') 
        ? 'border-red-300 text-red-900 focus:border-red-500 focus:ring-red-500' 
        : '';
@endphp

<div class="relative">
    @if($hasLeading)
        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
            <span class="text-gray-500 sm:text-sm">
                {{ $leadingIcon }}
            </span>
        </div>
    @endif

    <select
        {{ $disabled ? 'disabled' : '' }}
        {{ $multiple ? 'multiple' : '' }}
        {{ $attributes->merge([
            'class' => $baseClasses . ' ' . 
                      $roundedClasses . ' ' . 
                      $sizes[$size] . ' ' . 
                      $errorClasses . ' ' .
                      ($hasLeading ? 'pl-10' : '') . ' ' .
                      ($searchable ? 'select2' : '')
        ]) }}
    >
        @if(!$multiple && $placeholder)
            <option value="">{{ $placeholder }}</option>
        @endif
        {{ $slot }}
    </select>

    @if(!$multiple)
        <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
            <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
        </div>
    @endif
</div>

@if($searchable)
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Select2 on searchable selects
            $('.select2').select2({
                theme: 'tailwind',
                width: '100%'
            });
        });
    </script>
    @endpush
@endif
