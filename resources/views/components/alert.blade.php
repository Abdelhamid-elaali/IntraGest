@props([
    'type' => 'info',
    'dismissible' => false,
    'title' => null,
    'autoDismiss' => false,
    'dismissAfter' => 4000
])

@php
    $types = [
        'info' => [
            'bg' => 'bg-blue-100',
            'text' => 'text-blue-800',
            'icon' => '<svg class="h-5 w-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                      </svg>',
            'progress' => 'bg-blue-600'
        ],
        'success' => [
            'bg' => 'bg-green-100',
            'text' => 'text-green-800',
            'icon' => '<svg class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                      </svg>',
            'progress' => 'bg-green-600'
        ],
        'warning' => [
            'bg' => 'bg-yellow-100',
            'text' => 'text-yellow-800',
            'icon' => '<svg class="h-5 w-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                      </svg>',
            'progress' => 'bg-yellow-600'
        ],
        'error' => [
            'bg' => 'bg-red-100',
            'text' => 'text-red-800',
            'icon' => '<svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                      </svg>',
            'progress' => 'bg-red-600'
        ],
        'primary' => [
            'bg' => 'bg-purple-100',
            'text' => 'text-purple-800',
            'icon' => '<svg class="h-5 w-5 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                      </svg>',
            'progress' => 'bg-purple-600'
        ],
        'secondary' => [
            'bg' => 'bg-orange-100',
            'text' => 'text-orange-800',
            'icon' => '<svg class="h-5 w-5 text-orange-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                      </svg>',
            'progress' => 'bg-orange-600'
        ]
    ];
    
    $alertId = 'alert-' . uniqid();
@endphp

<style>
    .alert-progress-bar {
        position: absolute;
        bottom: 0;
        left: 0;
        height: 4px;
        width: 0%;
        transition: width linear;
    }
</style>

<div id="{{ $alertId }}" 
class="{{ $types[$type]['bg'] }} p-4 rounded-md shadow-sm relative overflow-hidden border-l-4 border-{{ explode('-', $types[$type]['text'])[1] }}-500 {{ $attributes->get('class') }}" 
role="alert"
x-data="{ 
    show: true,
    dismiss() {
        this.show = false;
        setTimeout(() => this.$el.remove(), 300);
    }
}" 
x-show="show"
x-transition:enter="transform transition ease-out duration-300"
x-transition:enter-start="opacity-0 translate-y-2"
x-transition:enter-end="opacity-100 translate-y-0"
x-transition:leave="transform transition ease-in duration-300"
x-transition:leave-start="opacity-100 translate-y-0"
x-transition:leave-end="opacity-0 translate-y-2">
    <div class="flex">
        <div class="flex-shrink-0">
            {!! $types[$type]['icon'] !!}
        </div>
        <div class="ml-3 flex-1">
            @if($title)
                <h3 class="text-sm font-medium {{ $types[$type]['text'] }}">
                    {{ $title }}
                </h3>
            @endif
            <div class="text-sm {{ $types[$type]['text'] }} mt-1">
                {{ $slot }}
            </div>
        </div>
        @if($dismissible)
            <div class="ml-auto pl-3">
                <div class="-mx-1.5 -my-1.5">
                    <button type="button" class="{{ $types[$type]['text'] }} rounded-md p-1.5 hover:bg-opacity-20 hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-{{ substr($types[$type]['bg'], 3) }} focus:ring-{{ substr($types[$type]['text'], 5) }}" @click="dismiss()">
                        <span class="sr-only">Dismiss</span>
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </div>
            </div>
        @endif
    </div>
    
    <!-- Progress bar for auto-dismiss -->
    @if($autoDismiss)
        <div id="{{ $alertId }}-progress" class="alert-progress-bar {{ $types[$type]['progress'] }}"></div>
    @endif
</div>

@if($autoDismiss)
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const alertElement = document.getElementById('{{ $alertId }}');
        const progressBar = document.getElementById('{{ $alertId }}-progress');
        const duration = {{ $dismissAfter }};
        
        if (alertElement && progressBar) {
            // Animate progress bar
            progressBar.style.transition = `width ${duration}ms linear`;
            setTimeout(() => {
                progressBar.style.width = '100%';
            }, 50); // Small delay to ensure transition works
            
            // Auto-dismiss after duration
            setTimeout(() => {
                const alert = Alpine.data(alertElement);
                if (alert) {
                    alert.dismiss();
                } else {
                    alertElement.remove();
                }
            }, duration);
        }
    });
</script>
@endif
