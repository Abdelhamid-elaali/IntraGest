@extends('layouts.app')

@section('title', 'Room Management')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Room Management</h1>
            <p class="mt-1 text-sm text-gray-600">Manage room assignments and monitor occupancy</p>
        </div>
        <form action="{{ route('rooms.create') }}" method="GET">
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Add New Room
            </button>
        </form>
    </div>

    <!-- Room Status Overview -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4">
            <h3 class="text-lg font-semibold text-gray-700">Total Rooms</h3>
            <p class="text-3xl font-bold text-blue-600">{{ $rooms->count() }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <h3 class="text-lg font-semibold text-gray-700">Available</h3>
            <p class="text-3xl font-bold text-green-600">{{ $rooms->where('status', 'Available')->count() }}</p>
            <div class="mt-2 w-full bg-gray-200 rounded-full h-2.5">
                <div class="bg-green-600 h-2.5 rounded-full" style="width: {{ $rooms->count() > 0 ? ($rooms->where('status', 'Available')->count() / $rooms->count() * 100) : 0 }}%"></div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <h3 class="text-lg font-semibold text-gray-700">Occupied</h3>
            <p class="text-3xl font-bold text-red-600">{{ $rooms->where('status', 'Unavailable')->count() }}</p>
            <div class="mt-2 w-full bg-gray-200 rounded-full h-2.5">
                <div class="bg-red-600 h-2.5 rounded-full" style="width: {{ $rooms->count() > 0 ? ($rooms->where('status', 'Unavailable')->count() / $rooms->count() * 100) : 0 }}%"></div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <h3 class="text-lg font-semibold text-gray-700">Under Maintenance</h3>
            <p class="text-3xl font-bold text-yellow-600">{{ $rooms->where('maintenance_status', '!=', 'operational')->count() }}</p>
            <div class="mt-2 w-full bg-gray-200 rounded-full h-2.5">
                <div class="bg-yellow-600 h-2.5 rounded-full" style="width: {{ $rooms->count() > 0 ? ($rooms->where('maintenance_status', '!=', 'operational')->count() / $rooms->count() * 100) : 0 }}%"></div>
            </div>
        </div>
    </div>

    <!-- Room Filters -->
    <div class="mb-6 p-4 bg-gray-50 rounded-lg shadow">
        <h3 class="text-lg font-medium text-gray-700 mb-3">Filter Rooms</h3>
        <form action="{{ route('rooms.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="pavilion" class="block text-sm font-medium text-gray-700">Pavilion</label>
                <select id="pavilion" name="pavilion" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                    <option value="">All Pavilions</option>
                    <option value="Girls" {{ request('pavilion') == 'Girls' ? 'selected' : '' }}>Girls</option>
                    <option value="Boys" {{ request('pavilion') == 'Boys' ? 'selected' : '' }}>Boys</option>
                </select>
            </div>
            <div>
                <label for="floor" class="block text-sm font-medium text-gray-700">Floor</label>
                <select id="floor" name="floor" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                    <option value="">All Floors</option>
                    @foreach($rooms->pluck('floor')->unique()->sort() as $floor)
                        <option value="{{ $floor }}" {{ request('floor') == $floor ? 'selected' : '' }}>Floor {{ $floor }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                <select id="status" name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                    <option value="">All Status</option>
                    <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Available</option>
                    <option value="occupied" {{ request('status') == 'occupied' ? 'selected' : '' }}>Occupied</option>
                    <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>Under Maintenance</option>
                </select>
            </div>
            <div>
                <label for="accommodation_type" class="block text-sm font-medium text-gray-700">Accommodation Type</label>
                <select id="accommodation_type" name="accommodation_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                    <option value="">All Types</option>
                    <option value="single" {{ request('accommodation_type') == 'single' ? 'selected' : '' }}>Single</option>
                    <option value="double" {{ request('accommodation_type') == 'double' ? 'selected' : '' }}>Double</option>
                    <option value="triple" {{ request('accommodation_type') == 'triple' ? 'selected' : '' }}>Triple</option>
                </select>
            </div>
            
            <div class="md:col-span-4 flex justify-end">
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                    </svg>
                    Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Rooms Table -->
    <div class="overflow-x-auto bg-white rounded-lg shadow">
        @if(count($rooms) > 0)
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Room Details</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Occupancy</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($rooms as $room)
                        <tr class="hover:bg-gray-50 transition-colors duration-150 ease-in-out">
                            <!-- Room Details -->
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-lg font-bold text-gray-900">Room {{ $room['room_number'] }}</div>
                                        <div class="text-xs text-gray-500">{{ ucfirst($room['accommodation_type']) }} Accommodation</div>
                                        @if($room['description'])
                                            <div class="mt-1 text-xs italic text-gray-500 max-w-xs truncate">"{{ $room['description'] }}"</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            
                            <!-- Location -->
                            <td class="px-6 py-4">
                                <div class="flex items-center mb-2">
                                    <svg class="h-5 w-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                    <span class="text-sm font-medium text-gray-900">Floor {{ $room['floor'] }}</span>
                                </div>
                                <div class="flex items-center">
                                    <svg class="h-5 w-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span class="text-sm text-gray-700">{{ $room['pavilion'] }} Pavilion</span>
                                </div>
                            </td>
                            
                            <!-- Occupancy -->
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <div class="text-sm font-medium text-gray-900 mb-1">{{ $room['occupancy'] }}/{{ $room['capacity'] }} Occupants</div>
                                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                                        <div class="{{ $room['occupancy'] >= $room['capacity'] ? 'bg-red-600' : 'bg-blue-600' }} h-2.5 rounded-full" style="width: {{ ($room['occupancy'] / $room['capacity']) * 100 }}%"></div>
                                    </div>
                                    <div class="mt-2 text-xs text-gray-500">
                                        @if($room['occupancy'] >= $room['capacity'])
                                            <span class="text-red-600">Fully Occupied</span>
                                        @elseif($room['occupancy'] == 0)
                                            <span class="text-green-600">Empty</span>
                                        @else
                                            <span class="text-blue-600">{{ $room['capacity'] - $room['occupancy'] }} spaces available</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            
                            <!-- Status -->
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    @php
                                        $statusColor = 'bg-gray-100 text-gray-800';
                                        $dotColor = 'bg-gray-600';
                                        if($room['status'] === 'Available') {
                                            $statusColor = 'bg-green-100 text-green-800';
                                            $dotColor = 'bg-green-600';
                                        } elseif($room['status'] === 'Unavailable') {
                                            $statusColor = 'bg-red-100 text-red-800';
                                            $dotColor = 'bg-red-600';
                                        } elseif($room['maintenance_status'] !== 'operational') {
                                            $statusColor = 'bg-yellow-100 text-yellow-800';
                                            $dotColor = 'bg-yellow-500';
                                        }
                                    @endphp
                                    <div class="h-3 w-3 rounded-full {{ $dotColor }} mr-2"></div>
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $statusColor }}">
                                        {{ $room['status'] }}
                                        @if($room['maintenance_status'] !== 'operational')
                                            (Maintenance)
                                        @endif
                                    </span>
                                </div>
                                @if($room['current_allocation'])
                                    <div class="mt-2 text-xs text-gray-500">Assigned to: {{ $room['current_allocation']['student'] }}</div>
                                    <div class="text-xs text-gray-500">From: {{ $room['current_allocation']['start_date'] }}</div>
                                    @if($room['current_allocation']['end_date'])
                                        <div class="text-xs text-gray-500">Until: {{ $room['current_allocation']['end_date'] }}</div>
                                    @endif
                                @endif
                            </td>
                            
                            <!-- Actions -->
                            <td class="px-6 py-4">
                                <div class="flex flex-col space-y-2">
                                    <a href="{{ route('rooms.show', $room['id']) }}" class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        View
                                    </a>
                                    <a href="{{ route('rooms.edit', $room['id']) }}" class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        Edit
                                    </a>
                                    <form action="{{ route('rooms.destroy', $room['id']) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex w-full items-center justify-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500" onclick="return confirm('Are you sure you want to delete this room?')">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            Delete
                                        </button>
                                    </form>
                                    <button type="button" class="inline-flex items-center justify-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-yellow-700 bg-yellow-100 hover:bg-yellow-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 change-status" data-id="{{ $room['id'] }}" data-status="{{ $room['status'] }}">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                        </svg>
                                        Change Status
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="py-12 text-center">
                <div class="inline-block p-6 bg-white rounded-full">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <h3 class="mt-4 text-lg font-medium text-gray-900">No rooms found</h3>
                <p class="mt-1 text-sm text-gray-500">Get started by creating a new room.</p>
            </div>
        @endif
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const pavilionFilter = document.getElementById('pavilion-filter');
    const floorFilter = document.getElementById('floor-filter');
    const statusFilter = document.getElementById('status-filter');
    const applyFiltersBtn = document.getElementById('apply-filters');

    applyFiltersBtn.addEventListener('click', function() {
        // Get filter values
        const pavilion = pavilionFilter.value;
        const floor = floorFilter.value;
        const status = statusFilter.value;

        // Filter table rows
        const rows = document.querySelectorAll('tbody tr');
        rows.forEach(row => {
            let show = true;

            // Pavilion filter
            if (pavilion && row.querySelector('td:nth-child(3)').textContent.trim() !== pavilion) {
                show = false;
            }

            // Floor filter
            if (floor && row.querySelector('td:nth-child(2)').textContent.trim() !== floor) {
                show = false;
            }

            // Status filter
            if (status && !row.querySelector('td:nth-child(6)').textContent.trim().toLowerCase().includes(status)) {
                show = false;
            }

            row.style.display = show ? '' : 'none';
        });
    });

    // Status change buttons
    const statusButtons = document.querySelectorAll('.change-status');
    statusButtons.forEach(button => {
        button.addEventListener('click', function() {
            const roomId = this.getAttribute('data-id');
            const currentStatus = this.getAttribute('data-status');
            const newStatus = currentStatus === 'Available' ? 'Unavailable' : 'Available';
            
            if (confirm(`Are you sure you want to change the status to ${newStatus}?`)) {
                // Create a form and submit it
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/rooms/${roomId}/change-status`;
                
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = csrfToken;
                
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'PATCH';
                
                const statusInput = document.createElement('input');
                statusInput.type = 'hidden';
                statusInput.name = 'status';
                statusInput.value = newStatus;
                
                const maintenanceInput = document.createElement('input');
                maintenanceInput.type = 'hidden';
                maintenanceInput.name = 'maintenance_status';
                maintenanceInput.value = 'operational';
                
                form.appendChild(csrfInput);
                form.appendChild(methodInput);
                form.appendChild(statusInput);
                form.appendChild(maintenanceInput);
                
                document.body.appendChild(form);
                form.submit();
            }
        });
    });
});

// Filter functionality
document.getElementById('apply-filters').addEventListener('click', function() {
    const type = document.getElementById('type-filter').value;
    const status = document.getElementById('status-filter').value;
    const minPrice = document.getElementById('price-min').value;
    const maxPrice = document.getElementById('price-max').value;

    // Reload page with filter parameters
    const params = new URLSearchParams(window.location.search);
    if (type) params.set('type', type);
    if (status) params.set('status', status);
    if (minPrice) params.set('min_price', minPrice);
    if (maxPrice) params.set('max_price', maxPrice);

    window.location.search = params.toString();
});
</script>
@endpush
