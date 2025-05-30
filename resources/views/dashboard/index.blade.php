@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Stats Grid -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <!-- Total Students -->
        <div class="bg-white rounded-lg shadow p-4 flex items-center space-x-3">
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
        <div class="bg-white rounded-lg shadow p-4 flex items-center space-x-3">
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
        <div class="bg-white rounded-lg shadow p-4 flex items-center space-x-3">
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
        <div class="bg-white rounded-lg shadow p-4 flex items-center space-x-3">
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

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
        <!-- Recent Stock Transactions -->
        <div class="bg-white rounded-lg shadow h-full lg:col-span-2">
            <div class="p-4 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-green-50 rounded-lg">
                            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Recent Stock Transactions</h3>
                    </div>
                    <a href="{{ route('stocks.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-800 flex items-center">
                        View All
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>
            <div class="p-4">
                @if($recentTransactions->isEmpty())
                    <div class="text-center py-8 bg-gray-50 rounded-lg">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No recent transactions</h3>
                        <p class="mt-1 text-sm text-gray-500">Transactions will appear here when available</p>
                        <div class="mt-6">
                            <a href="{{ route('stocks.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Add Stock
                            </a>
                        </div>
                    </div>
                @else
                    <div class="overflow-hidden">
                        <div class="flow-root">
                            <ul class="-my-5 divide-y divide-gray-200">
                                @foreach($recentTransactions as $transaction)
                                    <li class="py-4">
                                        <div class="flex items-center space-x-4">
                                            <div class="flex-shrink-0">
                                                <div class="relative">
                                                    <div class="h-10 w-10 rounded-full bg-gradient-to-br {{ $transaction->type == 'in' ? 'from-green-50 to-green-100' : 'from-red-50 to-red-100' }} flex items-center justify-center">
                                                        <svg class="h-5 w-5 {{ $transaction->type == 'in' ? 'text-green-600' : 'text-red-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            @if($transaction->type == 'in')
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                                                            @else
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                                                            @endif
                                                        </svg>
                                                    </div>
                                                    <span class="absolute -top-1 -right-1 h-4 w-4 rounded-full {{ $transaction->type == 'in' ? 'bg-green-500' : 'bg-red-500' }} flex items-center justify-center">
                                                        <span class="text-white text-xs font-bold">{{ $transaction->type == 'in' ? '+' : '-' }}</span>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <p class="truncate text-sm font-medium text-gray-900">
                                                    {{ $transaction->stock_name }}
                                                </p>
                                                <div class="flex items-center space-x-1 text-xs text-gray-500">
                                                    <span>{{ $transaction->quantity }} units</span>
                                                    <span>•</span>
                                                    <span>{{ $transaction->user_name }}</span>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="text-sm text-gray-500">
                                                    {{ date('M d', strtotime($transaction->created_at)) }}
                                                </div>
                                                <div class="text-xs text-gray-400">
                                                    {{ date('h:i A', strtotime($transaction->created_at)) }}
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="mt-6">
                            <a href="{{ route('stocks.index') }}" class="w-full flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                View all transactions
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Stock Expenses Statistics -->
        <div class="bg-white rounded-lg shadow h-full lg:col-span-1">
            <div class="p-4 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-blue-50 rounded-lg">
                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Inventory Expenses</h3>
                    </div>
                <div class="relative" x-data="{ open: false }" @click.away="open = false">
                    <button 
                        @click="open = !open"
                        class="inline-flex items-center justify-center px-3 py-1.5 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 focus:ring-offset-1 transition-all duration-200"
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

            <div class="p-4">
                <div class="flex justify-center mb-6">
                    <div class="w-48 h-48 relative">
                        <canvas id="expensesChart"></canvas>
                        <div class="absolute inset-0 flex items-center justify-center flex-col">
                            <span class="text-3xl font-bold text-gray-800">${{ number_format($stockStats->total_amount ?? 0) }}</span>
                            <span class="text-xs text-gray-500">Total Expenses</span>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100/50 p-4 rounded-lg hover:shadow-md transition-shadow duration-200">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center space-x-2">
                                <div class="p-2 bg-blue-100 rounded">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-gray-700">Supplies</span>
                            </div>
                            <span class="text-sm font-semibold text-blue-600">{{ $expenseStats['supplies'] }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $expenseStats['supplies'] }}%"></div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-purple-50 to-purple-100/50 p-4 rounded-lg hover:shadow-md transition-shadow duration-200">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center space-x-2">
                                <div class="p-2 bg-purple-100 rounded">
                                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-gray-700">Services</span>
                            </div>
                            <span class="text-sm font-semibold text-purple-600">{{ $expenseStats['services'] }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="bg-purple-600 h-2.5 rounded-full" style="width: {{ $expenseStats['services'] }}%"></div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-pink-50 to-pink-100/50 p-4 rounded-lg hover:shadow-md transition-shadow duration-200">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center space-x-2">
                                <div class="p-2 bg-pink-100 rounded">
                                    <svg class="w-4 h-4 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-gray-700">Other</span>
                            </div>
                            <span class="text-sm font-semibold text-pink-600">{{ $expenseStats['other'] }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="bg-pink-600 h-2.5 rounded-full" style="width: {{ $expenseStats['other'] }}%"></div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-6 pt-4 border-t border-gray-200">
                    <h4 class="text-sm font-medium text-gray-700 mb-3">Time Period</h4>
                    <div class="grid grid-cols-3 gap-2">
                        <button class="px-3 py-2 bg-blue-600 text-white text-xs font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Weekly</button>
                        <button class="px-3 py-2 bg-gray-100 text-gray-700 text-xs font-medium rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">Monthly</button>
                        <button class="px-3 py-2 bg-gray-100 text-gray-700 text-xs font-medium rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">Yearly</button>
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
