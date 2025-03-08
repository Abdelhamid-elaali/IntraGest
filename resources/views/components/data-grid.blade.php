@props([
    'title' => '',
    'description' => '',
    'headers' => [],
    'rows' => [],
    'filters' => [],
    'batchActions' => [],
    'exportFormats' => ['csv', 'excel', 'pdf'],
    'pagination' => null,
    'loading' => false
])

<div class="space-y-4">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            @if($title)
                <h2 class="text-lg font-medium text-gray-900">{{ $title }}</h2>
            @endif
            @if($description)
                <p class="mt-1 text-sm text-gray-500">{{ $description }}</p>
            @endif
        </div>
        <div class="mt-4 sm:mt-0 sm:flex sm:space-x-4">
            <!-- Export Dropdown -->
            @if(count($exportFormats) > 0)
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button type="button" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="-ml-1 mr-2 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Export
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="py-1">
                            @foreach($exportFormats as $format)
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">
                                    Export as {{ strtoupper($format) }}
                                </a>
                            @endforeach
                        </div>
                    </x-slot>
                </x-dropdown>
            @endif

            {{ $actions ?? '' }}
        </div>
    </div>

    <!-- Filters -->
    @if(count($filters) > 0)
        <x-filter :filters="$filters" />
    @endif

    <!-- Batch Actions -->
    @if(count($batchActions) > 0)
        <div x-data="{ selectedCount: 0 }" x-show="selectedCount > 0" class="bg-white px-4 py-3 border-b border-gray-200 sm:px-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <span class="text-sm font-medium text-gray-900" x-text="`${selectedCount} selected`"></span>
                </div>
                <div class="flex space-x-3">
                    @foreach($batchActions as $action)
                        <button
                            type="button"
                            class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                            @click="$dispatch('batch-action', { action: '{{ $action['name'] }}' })"
                        >
                            {{ $action['label'] }}
                        </button>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Table -->
    <x-table
        :headers="$headers"
        :rows="$rows"
        :selectable="count($batchActions) > 0"
        sortable
        :loading="$loading"
    >
        @if(isset($row_actions))
            <x-slot name="actions">
                {{ $row_actions }}
            </x-slot>
        @endif
    </x-table>

    <!-- Pagination -->
    @if($pagination)
        <div class="mt-4">
            {{ $pagination }}
        </div>
    @endif
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Update selected count for batch actions
    const checkboxes = document.querySelectorAll('tbody input[type="checkbox"]');
    const updateSelectedCount = () => {
        const selectedCount = Array.from(checkboxes).filter(cb => cb.checked).length;
        document.dispatchEvent(new CustomEvent('update-selected-count', { detail: { count: selectedCount } }));
    };

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectedCount);
    });

    // Handle batch actions
    window.addEventListener('batch-action', (e) => {
        const selectedIds = Array.from(checkboxes)
            .filter(cb => cb.checked)
            .map(cb => cb.value);

        // Dispatch event with selected IDs and action
        document.dispatchEvent(new CustomEvent('execute-batch-action', {
            detail: {
                action: e.detail.action,
                ids: selectedIds
            }
        }));
    });
});
</script>
@endpush
