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
                <div class="text-2xl font-bold">{{ $stats['total_students'] }}</div>
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
                <div class="text-2xl font-bold">${{ number_format($stats['recent_payments'] ?? 0) }}</div>
            </div>
        </div>

        <!-- Available Rooms -->
        <div class="bg-white rounded-lg p-6 flex items-center space-x-4">
            <div class="p-3 bg-green-50 rounded-lg">
                <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <div class="text-sm text-gray-500">Available Rooms</div>
                <div class="text-2xl font-bold">{{ $stats['available_rooms'] }}</div>
            </div>
        </div>

        <!-- Total Rooms -->
        <div class="bg-white rounded-lg p-6 flex items-center space-x-4">
            <div class="p-3 bg-cyan-50 rounded-lg">
                <svg class="w-8 h-8 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
            <div>
                <div class="text-sm text-gray-500">Total Rooms</div>
                <div class="text-2xl font-bold">{{ $stats['total_rooms'] }}</div>
            </div>
        </div>
    </div>

    <!-- Recent Stock Transactions & Statistics -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Stock Transactions -->
        <div class="bg-white rounded-lg shadow-sm">
            <div class="flex items-center justify-between p-6 border-b border-gray-100">
                <div class="flex items-center space-x-3">
                    <div class="p-2 bg-green-50 rounded-lg">
                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                    </div>
                    <h2 class="text-lg font-semibold text-gray-900">Recent Stock Transactions</h2>
                </div>
            </div>
            <div class="p-6 space-y-3">
                @forelse($recentTransactions as $transaction)
                    <div class="flex items-center justify-between p-4 {{ $transaction->type === 'in' ? 'bg-gradient-to-br from-green-50 to-green-100/50' : 'bg-gradient-to-br from-red-50 to-red-100/50' }} rounded-lg group hover:shadow-sm transition-all duration-200">
                        <div class="flex items-center space-x-3">
                            <div class="p-1.5 {{ $transaction->type === 'in' ? 'bg-green-100' : 'bg-red-100' }} rounded group-hover:scale-110 transition-transform duration-200">
                                @if($transaction->type === 'in')
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m0-16l-4 4m4-4l4 4" />
                                    </svg>
                                @else
                                    <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 20V4m0 16l-4-4m4 4l4-4" />
                                    </svg>
                                @endif
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ $transaction->item_name }}</p>
                                <p class="text-sm text-gray-500">{{ $transaction->created_at->format('M d, Y') }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-lg font-bold {{ $transaction->type === 'in' ? 'text-green-600' : 'text-red-600' }}">
                                {{ $transaction->type === 'in' ? '+' : '-' }}{{ $transaction->quantity }}
                            </p>
                            <p class="text-sm font-medium {{ $transaction->type === 'in' ? 'text-green-500' : 'text-red-500' }}">
                                {{ $transaction->type === 'in' ? 'Received' : 'Used' }}
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center py-6 text-center">
                        <div class="p-3 bg-gray-100 rounded-full mb-3">
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                        </div>
                        <p class="text-gray-500 font-medium">No recent transactions</p>
                        <p class="text-gray-400 text-sm">Transactions will appear here when available</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Stock Expenses Statistics -->
        <div class="bg-white rounded-lg shadow-sm">
            <div class="flex items-center justify-between p-6 border-b border-gray-100">
                <div class="flex items-center space-x-3">
                    <div class="p-2 bg-blue-50 rounded-lg">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <h2 class="text-lg font-semibold text-gray-900">Stock Expenses Statistics</h2>
                </div>
                <div class="relative" x-data="{ open: false }" @click.away="open = false">
                    <button 
                        @click="open = !open"
                        class="inline-flex items-center justify-center px-3 py-1.5 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 transition-all duration-200"
                    >
                        <svg class="w-4 h-4 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span>Weekly</span>
                        <svg class="w-4 h-4 text-gray-400 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    
                    <!-- Dropdown Menu -->
                    <div 
                        x-show="open"
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95"
                        class="absolute right-0 mt-2 w-40 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50"
                    >
                        <div class="py-1" role="menu" aria-orientation="vertical">
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Weekly</a>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Monthly</a>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Yearly</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-3 gap-4">
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100/50 p-4 rounded-lg">
                        <div class="flex items-center justify-between mb-2">
                            <p class="text-sm font-medium text-gray-600">Supplies</p>
                            <div class="p-1.5 bg-blue-100 rounded">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                        </div>
                        <p class="text-2xl font-bold text-gray-900">{{ $expenseStats['supplies'] }}%</p>
                    </div>
                    <div class="bg-gradient-to-br from-purple-50 to-purple-100/50 p-4 rounded-lg">
                        <div class="flex items-center justify-between mb-2">
                            <p class="text-sm font-medium text-gray-600">Services</p>
                            <div class="p-1.5 bg-purple-100 rounded">
                                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                        </div>
                        <p class="text-2xl font-bold text-gray-900">{{ $expenseStats['services'] }}%</p>
                    </div>
                    <div class="bg-gradient-to-br from-pink-50 to-pink-100/50 p-4 rounded-lg">
                        <div class="flex items-center justify-between mb-2">
                            <p class="text-sm font-medium text-gray-600">Other</p>
                            <div class="p-1.5 bg-pink-100 rounded">
                                <svg class="w-4 h-4 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                            </div>
                        </div>
                        <p class="text-2xl font-bold text-gray-900">{{ $expenseStats['other'] }}%</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Expenses Chart
    const expensesCtx = document.getElementById('expensesChart').getContext('2d');
    const expensesChart = new Chart(expensesCtx, {
        type: 'pie',
        data: {
            labels: ['Supplies', 'Services', 'Other'],
            datasets: [{
                data: [{{ $expenseStats['supplies'] }}, {{ $expenseStats['services'] }}, {{ $expenseStats['other'] }}],
                backgroundColor: ['#3B82F6', '#06B6D4', '#EC4899'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right'
                }
            }
        }
    });

    // Time range selector for expenses
    document.getElementById('timeRange').addEventListener('change', function(e) {
        fetch(`/api/expenses-stats?range=${e.target.value}`)
            .then(response => response.json())
            .then(data => {
                expensesChart.data.datasets[0].data = [data.supplies, data.services, data.other];
                expensesChart.update();
            });
    });


                    }
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            }
        }
    });
</script>
@endpush
@endsection
