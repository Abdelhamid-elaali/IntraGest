@props([
    'disabled' => false,
    'rows' => 3,
    'size' => 'md',
    'resize' => true,
    'maxLength' => null,
    'showCount' => false
])

@php
    $baseClasses = 'block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 disabled:bg-gray-100 disabled:cursor-not-allowed';
    
    $sizes = [
        'sm' => 'px-3 py-2 text-sm leading-4',
        'md' => 'px-3 py-2 text-sm',
        'lg' => 'px-4 py-2 text-base'
    ];

    $resizeClasses = match($resize) {
        true => 'resize',
        false => 'resize-none',
        'vertical' => 'resize-y',
        'horizontal' => 'resize-x',
        default => 'resize'
    };

    $errorClasses = $attributes->get('error') 
        ? 'border-red-300 text-red-900 placeholder-red-300 focus:border-red-500 focus:ring-red-500' 
        : '';
@endphp

<div class="relative">
    <textarea
        rows="{{ $rows }}"
        {{ $disabled ? 'disabled' : '' }}
        {{ $maxLength ? 'maxlength=' . $maxLength : '' }}
        {{ $attributes->merge([
            'class' => $baseClasses . ' ' . 
                      'rounded-md' . ' ' . 
                      $sizes[$size] . ' ' . 
                      $resizeClasses . ' ' . 
                      $errorClasses
        ]) }}
    >{{ $slot }}</textarea>

    @if($showCount && $maxLength)
        <div class="absolute bottom-2 right-2">
            <span class="text-xs text-gray-500" x-data x-init="$el.textContent = $el.previousElementSibling.value.length + '/' + {{ $maxLength }}" x-on:input.window="if($event.target === $el.previousElementSibling) $el.textContent = $event.target.value.length + '/' + {{ $maxLength }}">
                0/{{ $maxLength }}
            </span>
        </div>
    @endif
</div>

@if($showCount && $maxLength)
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const textareas = document.querySelectorAll('textarea[maxlength]');
            textareas.forEach(textarea => {
                const counter = textarea.nextElementSibling?.querySelector('span');
                if (counter) {
                    textarea.addEventListener('input', function() {
                        counter.textContent = this.value.length + '/' + this.getAttribute('maxlength');
                    });
                }
            });
        });
    </script>
    @endpush
@endif
