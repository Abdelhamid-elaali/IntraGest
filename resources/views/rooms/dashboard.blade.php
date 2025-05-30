@extends('layouts.app')

@section('title', 'Room Dashboard')

@section('styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Room Dashboard</h1>
            <p class="mt-1 text-sm text-gray-600">Real-time monitoring of room occupancy and availability</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('rooms.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path>
                </svg>
                Room List
            </a>
            <a href="{{ route('rooms.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Add New Room
            </a>
        </div>
    </div>

    <!-- Room Status Overview -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4">
            <h3 class="text-lg font-semibold text-gray-700">Total Rooms</h3>
            <p class="text-3xl font-bold text-blue-600">{{ $totalRooms }}</p>
            <div class="mt-2 text-sm text-gray-500">Overall capacity: {{ $totalRooms * 2 }} interns</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <h3 class="text-lg font-semibold text-gray-700">Available</h3>
            <p class="text-3xl font-bold text-green-600">{{ $availableRooms }}</p>
            <div class="mt-2 w-full bg-gray-200 rounded-full h-2.5">
                <div class="bg-green-600 h-2.5 rounded-full" style="width: {{ $totalRooms > 0 ? ($availableRooms / $totalRooms * 100) : 0 }}%"></div>
            </div>
            <div class="mt-2 text-sm text-gray-500">{{ $totalRooms > 0 ? round(($availableRooms / $totalRooms * 100), 1) : 0 }}% of total rooms</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <h3 class="text-lg font-semibold text-gray-700">Occupied</h3>
            <p class="text-3xl font-bold text-red-600">{{ $occupiedRooms }}</p>
            <div class="mt-2 w-full bg-gray-200 rounded-full h-2.5">
                <div class="bg-red-600 h-2.5 rounded-full" style="width: {{ $totalRooms > 0 ? ($occupiedRooms / $totalRooms * 100) : 0 }}%"></div>
            </div>
            <div class="mt-2 text-sm text-gray-500">Occupancy rate: {{ $occupancyRate }}%</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <h3 class="text-lg font-semibold text-gray-700">Under Maintenance</h3>
            <p class="text-3xl font-bold text-yellow-600">{{ $maintenanceRooms }}</p>
            <div class="mt-2 w-full bg-gray-200 rounded-full h-2.5">
                <div class="bg-yellow-600 h-2.5 rounded-full" style="width: {{ $totalRooms > 0 ? ($maintenanceRooms / $totalRooms * 100) : 0 }}%"></div>
            </div>
            <div class="mt-2 text-sm text-gray-500">{{ $totalRooms > 0 ? round(($maintenanceRooms / $totalRooms * 100), 1) : 0 }}% of total rooms</div>
        </div>
    </div>

    <!-- Room Distribution Charts -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <!-- Rooms by Pavilion -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Rooms by Pavilion</h3>
            <div class="h-64">
                <canvas id="pavilionChart"></canvas>
            </div>
        </div>

        <!-- Rooms by Floor -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Rooms by Floor</h3>
            <div class="h-64">
                <canvas id="floorChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Room Status Legend -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Room Status Legend</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="flex items-center">
                <div class="w-6 h-6 rounded-full bg-green-500 mr-3"></div>
                <span class="text-sm text-gray-700">Available - Room is free and can be assigned</span>
            </div>
            <div class="flex items-center">
                <div class="w-6 h-6 rounded-full bg-red-500 mr-3"></div>
                <span class="text-sm text-gray-700">Busy - Room is currently occupied</span>
            </div>
            <div class="flex items-center">
                <div class="w-6 h-6 rounded-full bg-yellow-500 mr-3"></div>
                <span class="text-sm text-gray-700">Maintenance - Room is under maintenance</span>
            </div>
        </div>
    </div>

    <!-- Recent Room Assignments -->
    <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Recent Room Assignments</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Room</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Intern</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assigned Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Duration</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <!-- This would be populated with actual data in a real implementation -->
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">Room 101</div>
                            <div class="text-sm text-gray-500">Girls Pavilion, Floor 1</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">Jane Doe</div>
                            <div class="text-sm text-gray-500">ID: STU-2023-001</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            May 15, 2025
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            3 months
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Active
                            </span>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">Room 205</div>
                            <div class="text-sm text-gray-500">Boys Pavilion, Floor 2</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">John Smith</div>
                            <div class="text-sm text-gray-500">ID: STU-2023-042</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            May 10, 2025
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            6 months
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Active
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Rooms by Pavilion Chart
        const pavilionCtx = document.getElementById('pavilionChart').getContext('2d');
        const pavilionData = @json($roomsByPavilion);
        
        new Chart(pavilionCtx, {
            type: 'pie',
            data: {
                labels: pavilionData.map(item => item.pavilion),
                datasets: [{
                    data: pavilionData.map(item => item.total),
                    backgroundColor: [
                        '#F472B6', // Pink for Girls
                        '#60A5FA'  // Blue for Boys
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                    }
                }
            }
        });

        // Rooms by Floor Chart
        const floorCtx = document.getElementById('floorChart').getContext('2d');
        const floorData = @json($roomsByFloor);
        
        new Chart(floorCtx, {
            type: 'bar',
            data: {
                labels: floorData.map(item => `Floor ${item.floor}`),
                datasets: [{
                    label: 'Number of Rooms',
                    data: floorData.map(item => item.total),
                    backgroundColor: '#4F46E5',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Rooms'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Floor'
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
