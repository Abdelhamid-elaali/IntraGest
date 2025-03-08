@props([
    'type' => 'info',
    'title' => '',
    'message' => '',
    'dismissible' => true,
    'autoClose' => false,
    'duration' => 5000
])

@php
$types = [
    'info' => [
        'bg' => 'bg-blue-50',
        'icon_bg' => 'bg-blue-400',
        'icon' => '<path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a.75.75 0 000 1.5h.253a.25.25 0 01.244.304l-.459 2.066A1.75 1.75 0 0010.747 15H11a.75.75 0 000-1.5h-.253a.25.25 0 01-.244-.304l.459-2.066A1.75 1.75 0 009.253 9H9z" clip-rule="evenodd" />',
        'title_color' => 'text-blue-800',
        'text_color' => 'text-blue-700',
        'button_color' => 'text-blue-500 hover:bg-blue-100'
    ],
    'success' => [
        'bg' => 'bg-green-50',
        'icon_bg' => 'bg-green-400',
        'icon' => '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />',
        'title_color' => 'text-green-800',
        'text_color' => 'text-green-700',
        'button_color' => 'text-green-500 hover:bg-green-100'
    ],
    'warning' => [
        'bg' => 'bg-yellow-50',
        'icon_bg' => 'bg-yellow-400',
        'icon' => '<path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />',
        'title_color' => 'text-yellow-800',
        'text_color' => 'text-yellow-700',
        'button_color' => 'text-yellow-500 hover:bg-yellow-100'
    ],
    'error' => [
        'bg' => 'bg-red-50',
        'icon_bg' => 'bg-red-400',
        'icon' => '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />',
        'title_color' => 'text-red-800',
        'text_color' => 'text-red-700',
        'button_color' => 'text-red-500 hover:bg-red-100'
    ]
];

$style = $types[$type] ?? $types['info'];
@endphp

<div
    x-data="{ 
        show: true,
        init() {
            @if($autoClose)
                setTimeout(() => this.show = false, {{ $duration }});
            @endif
        }
    }"
    x-show="show"
    x-transition:enter="transform ease-out duration-300 transition"
    x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
    x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
    x-transition:leave="transition ease-in duration-100"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="rounded-md p-4 {{ $style['bg'] }}"
>
    <div class="flex">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 {{ $style['title_color'] }}" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                {!! $style['icon'] !!}
            </svg>
        </div>
        <div class="ml-3 flex-1">
            @if($title)
                <h3 class="text-sm font-medium {{ $style['title_color'] }}">
                    {{ $title }}
                </h3>
            @endif
            <div class="text-sm {{ $style['text_color'] }} {{ $title ? 'mt-2' : '' }}">
                {{ $message }}
            </div>
        </div>
        @if($dismissible)
            <div class="ml-4 flex-shrink-0 flex">
                <button
                    type="button"
                    @click="show = false"
                    class="inline-flex rounded-md {{ $style['button_color'] }} focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-{{ $type }}-50 focus:ring-{{ $type }}-600"
                >
                    <span class="sr-only">Dismiss</span>
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        @endif
    </div>
</div>
