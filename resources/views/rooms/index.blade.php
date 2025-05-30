@extends('layouts.app')

@section('title', 'Room Management')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Room Management</h1>
            <p class="mt-1 text-sm text-gray-600">Manage room assignments and monitor occupancy</p>
        </div>
        <a href="{{ route('rooms.create') }}" class="inline-flex items-center justify-center rounded-md border border-transparent bg-blue-600 py-2.5 px-6 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Add New Room
        </a>
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
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Pavilion</label>
                <select id="pavilion-filter" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">All Pavilions</option>
                    <option value="Girls">Girls</option>
                    <option value="Boys">Boys</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Floor</label>
                <select id="floor-filter" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">All Floors</option>
                    @foreach($rooms->pluck('floor')->unique()->sort() as $floor)
                        <option value="{{ $floor }}">Floor {{ $floor }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Status</label>
                <select id="status-filter" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">All Status</option>
                    <option value="available">Available</option>
                    <option value="occupied">Occupied</option>
                    <option value="maintenance">Under Maintenance</option>
                </select>
            </div>

            <div class="flex items-end">
                <button id="apply-filters" class="w-full bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                    Apply Filters
                </button>
            </div>
        </div>
    </div>

    <!-- Rooms Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Room No.</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Floor</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pavilion</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Accommodation Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Number of Interns</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($rooms as $room)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $room['room_number'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $room['floor'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $room['pavilion'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $room['accommodation_type'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <span class="text-sm text-gray-500">{{ $room['occupancy'] }}/{{ $room['capacity'] }}</span>
                                <div class="ml-2 w-16 bg-gray-200 rounded-full h-2.5">
                                    <div class="{{ $room['occupancy'] >= $room['capacity'] ? 'bg-red-600' : 'bg-blue-600' }} h-2.5 rounded-full" style="width: {{ ($room['occupancy'] / $room['capacity']) * 100 }}%"></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusColor = 'bg-gray-100 text-gray-800';
                                if($room['status'] === 'Available') {
                                    $statusColor = 'bg-green-100 text-green-800';
                                } elseif($room['status'] === 'Unavailable') {
                                    $statusColor = 'bg-red-100 text-red-800';
                                } elseif($room['maintenance_status'] !== 'operational') {
                                    $statusColor = 'bg-yellow-100 text-yellow-800';
                                }
                            @endphp
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColor }}">
                                {{ $room['status'] }}
                                @if($room['maintenance_status'] !== 'operational')
                                    (Maintenance)
                                @endif
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $room['description'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('rooms.show', $room['id']) }}" class="text-blue-600 hover:text-blue-900">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                                <a href="{{ route('rooms.edit', $room['id']) }}" class="text-indigo-600 hover:text-indigo-900">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                                <form action="{{ route('rooms.destroy', $room['id']) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this room?')">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                                <button type="button" class="text-yellow-600 hover:text-yellow-900 change-status" data-id="{{ $room['id'] }}" data-status="{{ $room['status'] }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
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
