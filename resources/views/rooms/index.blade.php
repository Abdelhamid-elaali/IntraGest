@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Room Management</h1>
        @can('create', App\Models\Room::class)
        <a href="{{ route('rooms.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
            Add New Room
        </a>
        @endcan
    </div>

    <!-- Room Status Overview -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4">
            <h3 class="text-lg font-semibold text-gray-700">Total Rooms</h3>
            <p class="text-3xl font-bold text-blue-600">{{ $rooms->count() }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <h3 class="text-lg font-semibold text-gray-700">Available</h3>
            <p class="text-3xl font-bold text-green-600">{{ $rooms->where('status', 'available')->count() }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <h3 class="text-lg font-semibold text-gray-700">Occupied</h3>
            <p class="text-3xl font-bold text-orange-600">{{ $rooms->where('status', 'occupied')->count() }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <h3 class="text-lg font-semibold text-gray-700">Under Maintenance</h3>
            <p class="text-3xl font-bold text-red-600">{{ $rooms->where('status', 'maintenance')->count() }}</p>
        </div>
    </div>

    <!-- Room Filters -->
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Type</label>
                <select id="type-filter" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">All Types</option>
                    <option value="single">Single</option>
                    <option value="double">Double</option>
                    <option value="triple">Triple</option>
                    <option value="quad">Quad</option>
                    <option value="suite">Suite</option>
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
            <div>
                <label class="block text-sm font-medium text-gray-700">Price Range</label>
                <div class="flex gap-2">
                    <input type="number" id="price-min" placeholder="Min" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <input type="number" id="price-max" placeholder="Max" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
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
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Room</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Occupancy</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price/Month</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Current Occupant</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($rooms as $room)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $room['room_number'] }}</div>
                        <div class="text-sm text-gray-500">Capacity: {{ $room['capacity'] }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                            {{ ucfirst($room['type']) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @php
                            $statusColors = [
                                'available' => 'bg-green-100 text-green-800',
                                'occupied' => 'bg-orange-100 text-orange-800',
                                'maintenance' => 'bg-red-100 text-red-800'
                            ];
                        @endphp
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColors[$room['status']] }}">
                            {{ ucfirst($room['status']) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $room['occupancy'] }}/{{ $room['capacity'] }}</div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ ($room['occupancy'] / $room['capacity']) * 100 }}%"></div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        ${{ number_format($room['price'], 2) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($room['current_allocation'])
                            <div class="text-sm text-gray-900">{{ $room['current_allocation']['student'] }}</div>
                            <div class="text-xs text-gray-500">
                                {{ $room['current_allocation']['start_date'] }} 
                                @if($room['current_allocation']['end_date'])
                                    - {{ $room['current_allocation']['end_date'] }}
                                @endif
                            </div>
                        @else
                            <span class="text-sm text-gray-500">No current occupant</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="{{ route('rooms.show', $room['id']) }}" class="text-blue-600 hover:text-blue-900 mr-3">View</a>
                        @can('update', App\Models\Room::find($room['id']))
                        <a href="{{ route('rooms.edit', $room['id']) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                        @endcan
                        @if($room['status'] === 'available')
                        <a href="#" onclick="showAllocationModal({{ $room['id'] }})" class="text-green-600 hover:text-green-900">Allocate</a>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Allocation Modal -->
<div id="allocation-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Allocate Room</h3>
            <form id="allocation-form" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Student</label>
                    <select name="user_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Select Student</option>
                        <!-- Will be populated via AJAX -->
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Start Date</label>
                    <input type="date" name="start_date" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">End Date</label>
                    <input type="date" name="end_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Notes</label>
                    <textarea name="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="hideAllocationModal()" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">Cancel</button>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">Allocate</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function showAllocationModal(roomId) {
    const modal = document.getElementById('allocation-modal');
    const form = document.getElementById('allocation-form');
    form.action = `/rooms/${roomId}/allocate`;
    
    // Fetch available students
    fetch('/api/students/available')
        .then(response => response.json())
        .then(students => {
            const select = form.querySelector('select[name="user_id"]');
            select.innerHTML = '<option value="">Select Student</option>';
            students.forEach(student => {
                const option = document.createElement('option');
                option.value = student.id;
                option.textContent = student.name;
                select.appendChild(option);
            });
        });

    modal.classList.remove('hidden');
}

function hideAllocationModal() {
    document.getElementById('allocation-modal').classList.add('hidden');
}

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
@endsection
