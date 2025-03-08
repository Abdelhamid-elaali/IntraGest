@props([
    'headers' => [],
    'rows' => [],
    'striped' => true,
    'hover' => true,
    'responsive' => true,
    'selectable' => false,
    'sortable' => false,
    'actions' => [],
    'emptyState' => null,
    'loading' => false
])

@php
    $sortedColumn = request()->query('sort_by');
    $sortDirection = request()->query('sort_direction', 'asc');
@endphp

<div class="{{ $responsive ? 'overflow-x-auto' : '' }} shadow ring-1 ring-black ring-opacity-5 rounded-lg">
    @if($loading)
        <div class="w-full h-64 flex items-center justify-center bg-white">
            <x-spinner size="lg" />
        </div>
    @else
        <table class="min-w-full divide-y divide-gray-300">
            @if(count($headers) > 0)
                <thead class="bg-gray-50">
                    <tr>
                        @if($selectable)
                            <th scope="col" class="relative w-12 px-6 py-3">
                                <input type="checkbox" class="absolute left-4 top-1/2 -mt-2 h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            </th>
                        @endif

                        @foreach($headers as $key => $header)
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                @if($sortable && isset($header['sortable']) && $header['sortable'])
                                    <a href="?sort_by={{ $key }}&sort_direction={{ $sortedColumn === $key && $sortDirection === 'asc' ? 'desc' : 'asc' }}" class="group inline-flex">
                                        {{ is_array($header) ? $header['label'] : $header }}
                                        <span class="ml-2 flex-none rounded {{ $sortedColumn === $key ? 'bg-gray-200 text-gray-900 group-hover:bg-gray-300' : 'invisible text-gray-400 group-hover:visible group-focus:visible' }}">
                                            @if($sortedColumn === $key && $sortDirection === 'desc')
                                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            @else
                                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd" />
                                                </svg>
                                            @endif
                                        </span>
                                    </a>
                                @else
                                    {{ is_array($header) ? $header['label'] : $header }}
                                @endif
                            </th>
                        @endforeach

                        @if(count($actions) > 0)
                            <th scope="col" class="relative px-6 py-3">
                                <span class="sr-only">Actions</span>
                            </th>
                        @endif
                    </tr>
                </thead>

                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($rows as $row)
                        <tr class="{{ $striped && $loop->odd ? 'bg-gray-50' : '' }} {{ $hover ? 'hover:bg-gray-100' : '' }}">
                            @if($selectable)
                                <td class="relative w-12 px-6 py-4 whitespace-nowrap">
                                    <input type="checkbox" class="absolute left-4 top-1/2 -mt-2 h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                </td>
                            @endif

                            @foreach($headers as $key => $header)
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $row[$key] ?? '' }}
                                </td>
                            @endforeach

                            @if(count($actions) > 0)
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                    @foreach($actions as $action)
                                        <a href="{{ $action['url']($row) }}" class="text-{{ $action['color'] ?? 'blue' }}-600 hover:text-{{ $action['color'] ?? 'blue' }}-900">
                                            {{ $action['label'] }}
                                        </a>
                                    @endforeach
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ count($headers) + ($selectable ? 1 : 0) + (count($actions) > 0 ? 1 : 0) }}" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                @if($emptyState)
                                    {{ $emptyState }}
                                @else
                                    <x-empty-state
                                        title="No items found"
                                        description="There are no items to display at this time."
                                    />
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            @endif
        </table>
    @endif
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle select all checkbox
    const selectAllCheckbox = document.querySelector('thead input[type="checkbox"]');
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('tbody input[type="checkbox"]');
            checkboxes.forEach(checkbox => checkbox.checked = this.checked);
        });
    }

    // Handle individual checkboxes
    const checkboxes = document.querySelectorAll('tbody input[type="checkbox"]');
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const allChecked = Array.from(checkboxes).every(cb => cb.checked);
            if (selectAllCheckbox) {
                selectAllCheckbox.checked = allChecked;
                selectAllCheckbox.indeterminate = !allChecked && Array.from(checkboxes).some(cb => cb.checked);
            }
        });
    });
});
</script>
@endpush
