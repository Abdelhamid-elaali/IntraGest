@props([
    'label' => null,
    'for' => null,
    'error' => null,
    'helpText' => null,
    'required' => false,
    'inline' => false,
    'labelClass' => ''
])

<div {{ $attributes->merge(['class' => $inline ? 'sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start' : '']) }}>
    @if($label)
        <label 
            for="{{ $for }}" 
            class="{{ $inline ? 'block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2' : 'block text-sm font-medium text-gray-700 mb-1' }} {{ $labelClass }}"
        >
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    <div class="{{ $inline ? 'mt-1 sm:mt-0 sm:col-span-2' : '' }}">
        {{ $slot }}

        @if($error)
            <p class="mt-2 text-sm text-red-600">{{ $error }}</p>
        @endif

        @if($helpText)
            <p class="mt-2 text-sm text-gray-500">{{ $helpText }}</p>
        @endif
    </div>
</div>
