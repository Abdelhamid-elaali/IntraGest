@props([
    'tabs' => [],
    'activeTab' => null,
    'vertical' => false,
    'contentClass' => ''
])

<div x-data="{ activeTab: '{{ $activeTab ?? array_key_first($tabs) }}' }" class="{{ $vertical ? 'sm:flex sm:space-x-8' : '' }}">
    <!-- Tab Navigation -->
    <div class="{{ $vertical ? 'sm:w-64 flex-shrink-0' : 'border-b border-gray-200' }}">
        <nav class="{{ $vertical ? 'space-y-1' : '-mb-px flex space-x-8' }}" aria-label="Tabs">
            @foreach($tabs as $id => $tab)
                <button
                    type="button"
                    @click="activeTab = '{{ $id }}'"
                    :class="[
                        activeTab === '{{ $id }}'
                            ? '{{ $vertical 
                                ? 'bg-gray-100 text-gray-900 hover:bg-gray-100' 
                                : 'border-blue-500 text-blue-600' }}'
                            : '{{ $vertical
                                ? 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'
                                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}',
                        '{{ $vertical
                            ? 'flex w-full items-center px-3 py-2 text-sm font-medium rounded-md'
                            : 'group inline-flex items-center py-4 px-1 border-b-2 font-medium text-sm' }}'
                    ]"
                    :aria-selected="activeTab === '{{ $id }}'"
                    aria-controls="tab-panel-{{ $id }}"
                    role="tab"
                >
                    @if(isset($tab['icon']))
                        <span class="{{ $vertical ? 'mr-3 h-6 w-6' : 'mr-2 -ml-0.5 h-5 w-5' }}" aria-hidden="true">
                            {!! $tab['icon'] !!}
                        </span>
                    @endif
                    {{ $tab['label'] }}
                    @if(isset($tab['badge']))
                        <span class="ml-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $tab['badge']['class'] ?? 'bg-gray-100 text-gray-900' }}">
                            {{ $tab['badge']['text'] }}
                        </span>
                    @endif
                </button>
            @endforeach
        </nav>
    </div>

    <!-- Tab Panels -->
    <div class="mt-2 {{ $vertical ? 'flex-1' : '' }}">
        @foreach($tabs as $id => $tab)
            <div
                x-show="activeTab === '{{ $id }}'"
                x-transition:enter="transition ease-in-out duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                role="tabpanel"
                id="tab-panel-{{ $id }}"
                aria-labelledby="tab-{{ $id }}"
                class="{{ $contentClass }}"
            >
                {{ $tab['content'] }}
            </div>
        @endforeach
    </div>
</div>
