@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Room {{ $room->room_number }}</h1>
            <p class="text-gray-600">{{ ucfirst($room->type) }} Room</p>
        </div>
        <div class="flex gap-3">
            @can('update', $room)
            <a href="{{ route('rooms.edit', $room) }}" class="bg-indigo-500 hover:bg-indigo-600 text-white font-bold py-2 px-4 rounded">
                Edit Room
            </a>
            @endcan
            @if($room->isAvailable())
                @can('allocate', $room)
                <button onclick="showAllocationModal()" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">
                    Allocate Room
                </button>
                @endcan
            @endif
            @if($room->isAvailable() && !$room->isUnderMaintenance())
                @can('maintenance', $room)
                <form action="{{ route('rooms.maintenance', $room) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded">
                        Mark for Maintenance
                    </button>
                </form>
                @endcan
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Room Details -->
        <div class="col-span-2">
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Room Details</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Status</p>
                        <p class="font-medium">
                            @php
                                $statusColors = [
                                    'available' => 'bg-green-100 text-green-800',
                                    'occupied' => 'bg-orange-100 text-orange-800',
                                    'maintenance' => 'bg-red-100 text-red-800'
                                ];
                            @endphp
                            <span class="px-2 inline-flex text-sm leading-5 font-semibold rounded-full {{ $statusColors[$room->status] }}">
                                {{ ucfirst($room->status) }}
                            </span>
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Capacity</p>
                        <p class="font-medium">{{ $room->capacity }} persons</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Price per Month</p>
                        <p class="font-medium">${{ number_format($room->price_per_month, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Current Occupancy</p>
                        <p class="font-medium">{{ $room->currentOccupants->count() }}/{{ $room->capacity }}</p>
                    </div>
                </div>

                @if($room->amenities)
                <div class="mt-6">
                    <h3 class="text-lg font-medium text-gray-800 mb-2">Amenities</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($room->amenities as $amenity)
                        <span class="px-3 py-1 rounded-full text-sm bg-blue-100 text-blue-800">
                            {{ $amenity }}
                        </span>
                        @endforeach
                    </div>
                </div>
                @endif

                @if($room->description)
                <div class="mt-6">
                    <h3 class="text-lg font-medium text-gray-800 mb-2">Description</h3>
                    <p class="text-gray-600">{{ $room->description }}</p>
                </div>
                @endif
            </div>

            <!-- Allocation History -->
            <div class="bg-white rounded-lg shadow p-6 mt-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Allocation History</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Student</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Period</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Notes</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($room->allocations->sortByDesc('created_at') as $allocation)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $allocation->user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $allocation->user->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $allocation->start_date->format('M d, Y') }}
                                        @if($allocation->end_date)
                                            - {{ $allocation->end_date->format('M d, Y') }}
                                        @endif
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        Duration: {{ $allocation->getDuration() }} days
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $allocationColors = [
                                            'active' => 'bg-green-100 text-green-800',
                                            'completed' => 'bg-blue-100 text-blue-800',
                                            'cancelled' => 'bg-red-100 text-red-800'
                                        ];
                                    @endphp
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $allocationColors[$allocation->status] }}">
                                        {{ ucfirst($allocation->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ $allocation->notes ?: 'No notes' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    @if($allocation->isActive())
                                        @can('deallocate', $room)
                                        <form action="{{ route('rooms.deallocate', [$room, $allocation]) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-red-600 hover:text-red-900">Deallocate</button>
                                        </form>
                                        @endcan
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Current Occupants -->
        <div class="col-span-1">
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Current Occupants</h2>
                @if($room->currentOccupants->count() > 0)
                    <div class="space-y-4">
                        @foreach($room->currentOccupants as $occupant)
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0">
                                <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                    <span class="text-gray-600 font-medium">{{ substr($occupant->name, 0, 2) }}</span>
                                </div>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">{{ $occupant->name }}</p>
                                <p class="text-sm text-gray-500">{{ $occupant->email }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-sm">No current occupants</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Allocation Modal -->
<div id="allocation-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Allocate Room</h3>
            <form action="{{ route('rooms.allocate', $room) }}" method="POST">
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
function showAllocationModal() {
    const modal = document.getElementById('allocation-modal');
    
    // Fetch available students
    fetch('/api/students/available')
        .then(response => response.json())
        .then(students => {
            const select = modal.querySelector('select[name="user_id"]');
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
</script>
@endpush
@endsection
