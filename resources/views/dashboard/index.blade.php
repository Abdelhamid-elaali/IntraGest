@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<!-- Hidden JSON data for Alpine.js initialization -->
<div id="dashboard-stats" class="hidden">{{ json_encode($stats) }}</div>
<div id="expense-stats" class="hidden">{{ json_encode($expenseStats) }}</div>
<div id="recent-transactions" class="hidden">{{ json_encode($recentTransactions) }}</div>
<div 
    x-data="dashboard()" 
    x-init="initDashboard()"
    class="space-y-6">
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
                <div class="text-sm text-gray-500">Total Trainees</div>
                <div class="text-2xl font-bold" x-text="stats.total_students">{{ $stats['total_students'] }}</div>
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
                <div class="text-2xl font-bold">$<span x-text="formatNumber(stats.recent_payments)">{{ number_format($stats['recent_payments'] ?? 0) }}</span></div>
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
                <div class="text-2xl font-bold" x-text="stats.available_rooms">{{ $stats['available_rooms'] }}</div>
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
                <div class="text-2xl font-bold" x-text="stats.total_rooms">{{ $stats['total_rooms'] }}</div>
            </div>
        </div>
    </div>

<!-- Main Content Grid -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <!-- Recent Stock Transactions -->
    <div class="bg-white rounded-lg shadow lg:col-span-1 mb-0 max-h-[calc(100vh-100px)] overflow-auto">
        <div class="p-2 border-b border-gray-100">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="p-2 bg-green-50 rounded-lg">
                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">Recent Stock Transactions</h3>
                </div>
            </div>
        </div>
        <div class="p-4">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <template x-if="recentTransactions.length === 0">
                            <tr>
                                <td colspan="5" class="px-4 py-4 text-center text-sm text-gray-500">No recent transactions</td>
                            </tr>
                        </template>
                        <template x-for="transaction in recentTransactions" :key="transaction.id">
                            <tr>
                                <td class="px-4 py-2 whitespace-nowrap" x-text="transaction.stock_name"></td>
                                <td class="px-4 py-2 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                          :class="transaction.type === 'in' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                                          x-text="transaction.type.charAt(0).toUpperCase() + transaction.type.slice(1)">
                                    </span>
                                </td>
                                <td class="px-4 py-2 whitespace-nowrap" x-text="transaction.quantity"></td>
                                <td class="px-4 py-2 whitespace-nowrap" x-text="formatDate(transaction.created_at)"></td>
                                <td class="px-4 py-2 whitespace-nowrap" x-text="transaction.user_name"></td>
                            </tr>
                        </template>
                        <!-- Fallback for initial page load -->
                        <template x-if="recentTransactions.length === 0">
                            @forelse($recentTransactions as $transaction)
                            <tr>
                                <td class="px-4 py-2 whitespace-nowrap">{{ $transaction->stock_name }}</td>
                                <td class="px-4 py-2 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $transaction->type === 'in' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ ucfirst($transaction->type) }}
                                    </span>
                                </td>
                                <td class="px-4 py-2 whitespace-nowrap">{{ $transaction->quantity }}</td>
                                <td class="px-4 py-2 whitespace-nowrap">{{ date('M d, Y', strtotime($transaction->created_at)) }}</td>
                                <td class="px-4 py-2 whitespace-nowrap">{{ $transaction->user_name }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-4 py-2 text-center text-sm text-gray-500">No recent transactions</td>
                            </tr>
                            @endforelse
                        </template>
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                <a href="{{ route('stocks.index') }}" class="w-full flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    View all transactions
                </a>
            </div>
        </div>
    </div>

    <!-- Inventory Expenses -->
    <div class="bg-white rounded-lg shadow max-h-[calc(100vh-100px)] overflow-auto lg:col-span-1">
        <div class="p-2 border-b border-gray-100">
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
                    <!-- Dropdown content can be added here if needed -->
                </div>
            </div>
        </div>
        <div class="p-4">
            <div class="text-2xl font-semibold text-gray-900">$0</div>
            <div class="text-sm text-gray-500">Total Expenses</div>
            <div class="mt-6 space-y-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                        <span class="text-sm text-gray-600">Supplies</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="text-sm text-gray-600" x-text="expenseStats.supplies + '%'">{{ $expenseStats['supplies'] }}%</span>
                        <div class="w-24 bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-500 h-2 rounded-full" x-bind:style="'width: ' + expenseStats.supplies + '%'"></div>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <div class="w-2 h-2 bg-purple-500 rounded-full"></div>
                        <span class="text-sm text-gray-600">Services</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="text-sm text-gray-600" x-text="expenseStats.services + '%'">{{ $expenseStats['services'] }}%</span>
                        <div class="w-24 bg-gray-200 rounded-full h-2">
                            <div class="bg-purple-500 h-2 rounded-full" x-bind:style="'width: ' + expenseStats.services + '%'"></div>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <div class="w-2 h-2 bg-pink-500 rounded-full"></div>
                        <span class="text-sm text-gray-600">Other</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="text-sm text-gray-600" x-text="expenseStats.other + '%'">{{ $expenseStats['other'] }}%</span>
                        <div class="w-24 bg-gray-200 rounded-full h-2">
                            <div class="bg-pink-500 h-2 rounded-full" x-bind:style="'width: ' + expenseStats.other + '%'"></div> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('expensesChart').getContext('2d');
        window.expensesChart = new Chart(ctx, {
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
    });

    // Time range selector for expenses
    document.getElementById('timeRange').addEventListener('change', function(e) {
        fetch(`/api/expenses-stats?range=${e.target.value}`)
            .then(response => response.json())
            .then(data => {
                window.expensesChart.data.datasets[0].data = [data.supplies, data.services, data.other];
                window.expensesChart.update();
                
                // Also update the Alpine.js data
                const dashboard = Alpine.store('dashboard');
                if (dashboard) {
                    dashboard.expenseStats = {
                        supplies: data.supplies,
                        services: data.services,
                        other: data.other
                    };
                }
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
