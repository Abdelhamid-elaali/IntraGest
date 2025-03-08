@props([
    'id',
    'filters' => [],
    'searchPlaceholder' => 'Search...',
    'showSearch' => true
])

<div class="bg-white shadow rounded-lg mb-6">
    <div class="p-4 border-b border-gray-200">
        <div class="flex flex-col space-y-4 md:flex-row md:space-y-0 md:space-x-4">
            @if($showSearch)
                <div class="flex-1">
                    <div class="relative">
                        <input
                            type="text"
                            name="search"
                            id="{{ $id }}-search"
                            class="block w-full rounded-md border-gray-300 pl-10 pr-3 py-2 text-sm placeholder-gray-500 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="{{ $searchPlaceholder }}"
                        >
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                </div>
            @endif

            @foreach($filters as $filter)
                <div class="flex-none">
                    <x-dropdown align="left" width="48">
                        <x-slot name="trigger">
                            <button type="button" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                {{ $filter['label'] }}
                                <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="px-4 py-2">
                                @if($filter['type'] === 'select')
                                    @foreach($filter['options'] as $option)
                                        <label class="flex items-center py-1">
                                            <input
                                                type="checkbox"
                                                name="{{ $filter['name'] }}[]"
                                                value="{{ $option['value'] }}"
                                                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                            >
                                            <span class="ml-2 text-sm text-gray-700">{{ $option['label'] }}</span>
                                        </label>
                                    @endforeach
                                @elseif($filter['type'] === 'date')
                                    <div class="space-y-2">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">From</label>
                                            <input
                                                type="date"
                                                name="{{ $filter['name'] }}_from"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                            >
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">To</label>
                                            <input
                                                type="date"
                                                name="{{ $filter['name'] }}_to"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                            >
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </x-slot>
                    </x-dropdown>
                </div>
            @endforeach

            {{ $slot }}
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('{{ $id }}-search');
    if (searchInput) {
        searchInput.addEventListener('input', debounce(function(e) {
            const event = new CustomEvent('filter-changed', {
                detail: {
                    type: 'search',
                    value: e.target.value
                }
            });
            window.dispatchEvent(event);
        }, 300));
    }

    const filterInputs = document.querySelectorAll('[name$="_from"], [name$="_to"], [name$="[]"]');
    filterInputs.forEach(input => {
        input.addEventListener('change', function(e) {
            const event = new CustomEvent('filter-changed', {
                detail: {
                    type: 'filter',
                    name: e.target.name,
                    value: e.target.value,
                    checked: e.target.type === 'checkbox' ? e.target.checked : null
                }
            });
            window.dispatchEvent(event);
        });
    });
});

function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}
</script>
@endpush
