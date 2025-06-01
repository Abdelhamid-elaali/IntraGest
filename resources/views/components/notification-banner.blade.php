@props(['type' => 'info', 'title', 'message', 'dismissible' => true])

@php
    $bgColor = match($type) {
        'success' => 'bg-green-100 dark:bg-green-800',
        'error' => 'bg-red-100 dark:bg-red-800',
        'warning' => 'bg-yellow-100 dark:bg-yellow-800',
        default => 'bg-blue-100 dark:bg-blue-800',
    };
    
    $textColor = match($type) {
        'success' => 'text-green-800 dark:text-green-100',
        'error' => 'text-red-800 dark:text-red-100',
        'warning' => 'text-yellow-800 dark:text-yellow-100',
        default => 'text-blue-800 dark:text-blue-100',
    };
    
    $borderColor = match($type) {
        'success' => 'border-green-200 dark:border-green-700',
        'error' => 'border-red-200 dark:border-red-700',
        'warning' => 'border-yellow-200 dark:border-yellow-700',
        default => 'border-blue-200 dark:border-blue-700',
    };
    
    $iconClass = match($type) {
        'success' => 'fa-check-circle',
        'error' => 'fa-exclamation-circle',
        'warning' => 'fa-exclamation-triangle',
        default => 'fa-info-circle',
    };
@endphp

<div {{ $attributes->merge(['class' => "rounded-md p-4 mb-4 border {$bgColor} {$borderColor}"]) }} role="alert">
    <div class="flex">
        <div class="flex-shrink-0">
            <i class="fas {{ $iconClass }} {{ $textColor }}"></i>
        </div>
        <div class="ml-3 flex-1">
            <h3 class="text-sm font-medium {{ $textColor }}">
                {{ $title }}
            </h3>
            <div class="mt-2 text-sm {{ $textColor }}">
                <p>{{ $message }}</p>
            </div>
        </div>
        @if($dismissible)
        <div class="ml-auto pl-3">
            <div class="-mx-1.5 -my-1.5">
                <button type="button" class="{{ $textColor }} rounded-md p-1.5 hover:bg-opacity-20 hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-{{ $type }}-50 focus:ring-{{ $type }}-600" onclick="this.parentElement.parentElement.parentElement.remove()">
                    <span class="sr-only">Dismiss</span>
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        @endif
    </div>
</div>
