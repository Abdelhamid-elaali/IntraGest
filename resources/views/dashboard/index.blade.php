@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <!-- Total Students -->
        <div class="bg-white rounded-lg p-6 flex items-center space-x-4">
            <div class="p-3 bg-orange-50 rounded-lg">
                <svg class="w-8 h-8 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <div>
                <div class="text-sm text-gray-500">Total Students</div>
                <div class="text-2xl font-bold">2,453</div>
            </div>
        </div>

        <!-- Recent Payments -->
        <div class="bg-white rounded-lg p-6 flex items-center space-x-4">
            <div class="p-3 bg-blue-50 rounded-lg">
                <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <div class="text-sm text-gray-500">Recent Payments</div>
                <div class="text-2xl font-bold">$5,600</div>
            </div>
        </div>

        <!-- Available Rooms -->
        <div class="bg-white rounded-lg p-6 flex items-center space-x-4">
            <div class="p-3 bg-pink-50 rounded-lg">
                <svg class="w-8 h-8 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
            </div>
            <div>
                <div class="text-sm text-gray-500">Available Rooms</div>
                <div class="text-2xl font-bold">25</div>
            </div>
        </div>

        <!-- Active Staff -->
        <div class="bg-white rounded-lg p-6 flex items-center space-x-4">
            <div class="p-3 bg-cyan-50 rounded-lg">
                <svg class="w-8 h-8 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
            <div>
                <div class="text-sm text-gray-500">Active Staff</div>
                <div class="text-2xl font-bold">57/920</div>
            </div>
        </div>
    </div>

    <!-- Recent Stock Transactions & Statistics -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Stock Transactions -->
        <div class="bg-white rounded-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-lg font-semibold">Recent Stock Transactions</h2>
            </div>
            <div class="space-y-4">
                <!-- Transaction Item -->
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-4">
                        <div class="p-2 bg-blue-100 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <div class="font-medium">Spotify Subscription</div>
                            <div class="text-sm text-gray-500">Jan 25, 2024</div>
                        </div>
                    </div>
                    <div class="text-green-500 font-medium">+$350</div>
                </div>

                <!-- Transaction Item -->
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-4">
                        <div class="p-2 bg-purple-100 rounded-lg">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                        <div>
                            <div class="font-medium">Mobile Service</div>
                            <div class="text-sm text-gray-500">Jan 24, 2024</div>
                        </div>
                    </div>
                    <div class="text-red-500 font-medium">-$150</div>
                </div>

                <!-- Transaction Item -->
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-4">
                        <div class="p-2 bg-pink-100 rounded-lg">
                            <svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                        </div>
                        <div>
                            <div class="font-medium">Emily Wilson</div>
                            <div class="text-sm text-gray-500">Jan 23, 2024</div>
                        </div>
                    </div>
                    <div class="text-green-500 font-medium">+$980</div>
                </div>
            </div>
        </div>

        <!-- Stock Expenses Statistics -->
        <div class="bg-white rounded-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-lg font-semibold">Stock Expenses Statistics</h2>
                <button class="text-sm text-blue-600 hover:text-blue-800">Weekly ↓</button>
            </div>
            <div class="flex space-x-8">
                <!-- Pie Chart -->
                <div class="flex-1">
                    <canvas id="expensesChart" class="w-full h-64"></canvas>
                </div>
                <!-- Legend -->
                <div class="space-y-4">
                    <div class="flex items-center space-x-2">
                        <div class="w-4 h-4 rounded-full bg-blue-500"></div>
                        <div class="text-sm">
                            <div class="font-medium">Supplies</div>
                            <div class="text-gray-500">63%</div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="w-4 h-4 rounded-full bg-cyan-400"></div>
                        <div class="text-sm">
                            <div class="font-medium">Services</div>
                            <div class="text-gray-500">35%</div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="w-4 h-4 rounded-full bg-pink-400"></div>
                        <div class="text-sm">
                            <div class="font-medium">Other</div>
                            <div class="text-gray-500">28%</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Weekly Absences Chart -->
    <div class="bg-white rounded-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-lg font-semibold">Weekly Absences</h2>
        </div>
        <canvas id="absencesChart" class="w-full h-64"></canvas>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Expenses Pie Chart
    new Chart(document.getElementById('expensesChart'), {
        type: 'pie',
        data: {
            labels: ['Supplies', 'Services', 'Other'],
            datasets: [{
                data: [63, 35, 28],
                backgroundColor: ['#3b82f6', '#22d3ee', '#f472b6'],
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });

    // Weekly Absences Chart
    new Chart(document.getElementById('absencesChart'), {
        type: 'bar',
        data: {
            labels: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
            datasets: [{
                label: 'Students',
                data: [15, 12, 18, 14, 10, 15, 12],
                backgroundColor: '#3b82f6',
                borderRadius: 4,
            }, {
                label: 'Staff',
                data: [8, 9, 12, 8, 7, 10, 8],
                backgroundColor: '#22d3ee',
                borderRadius: 4,
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        display: true,
                        color: '#f3f4f6'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
</script>
@endpush
@endsection
