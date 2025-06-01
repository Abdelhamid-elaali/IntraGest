@extends('layouts.app')

@section('title', 'Notification Dashboard')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Notification Dashboard</h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Monitor all system alerts in real-time</p>
            </div>
            <div class="flex space-x-2">
                <div class="relative" x-data="{ open: false }" @click.away="open = false">
                    <button @click="open = !open" type="button" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        Run Manual Check
                        <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 focus:outline-none z-10" role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
                        <div class="py-1" role="none">
                            <a href="{{ route('notifications.check-stock') }}" class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">
                                <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                                Check Stock Levels
                            </a>
                            <a href="{{ route('notifications.check-payments') }}" class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">
                                <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Check Payments
                            </a>
                            <a href="{{ route('notifications.check-absences') }}" class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">
                                <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Check Absences
                            </a>
                        </div>
                    </div>
                </div>
                <button id="markAllAsRead" onclick="markAllAsRead()" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Mark All as Read
                </button>
            </div>
        </div>
        
        <!-- Notification Container -->
        <x-notification-container />
        
        <div class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
            <!-- Stock Alerts -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
                <div class="p-5 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-red-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Critical Stock Alerts</dt>
                                <dd>
                                    <div class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ $criticalStockCount ?? 0 }}</div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="px-5 py-3 bg-gray-50 dark:bg-gray-700">
                    <div class="text-sm">
                        <a href="{{ route('stocks.index', ['filter' => 'critical']) }}" class="font-medium text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300">View all critical stock</a>
                    </div>
                </div>
            </div>

            <!-- Payment Alerts -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
                <div class="p-5 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Overdue Payments</dt>
                                <dd>
                                    <div class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ $overduePaymentsCount ?? 0 }}</div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="px-5 py-3 bg-gray-50 dark:bg-gray-700">
                    <div class="text-sm">
                        <a href="{{ route('payments.index', ['filter' => 'overdue']) }}" class="font-medium text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300">View all overdue payments</a>
                    </div>
                </div>
            </div>

            <!-- Absence Alerts -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
                <div class="p-5 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-red-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Unjustified Absences</dt>
                                <dd>
                                    <div class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ $unjustifiedAbsencesCount ?? 0 }}</div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="px-5 py-3 bg-gray-50 dark:bg-gray-700">
                    <div class="text-sm">
                        <a href="{{ route('absences.index', ['filter' => 'unjustified']) }}" class="font-medium text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300">View all unjustified absences</a>
                    </div>
                </div>
            </div>

            <!-- Room Status -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
                <div class="p-5 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Available Rooms</dt>
                                <dd>
                                    <div class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ $availableRoomsCount ?? 0 }}</div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="px-5 py-3 bg-gray-50 dark:bg-gray-700">
                    <div class="text-sm">
                        <a href="{{ route('rooms.index') }}" class="font-medium text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300">View all rooms</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Notifications -->
        <div class="mt-8">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Recent Notifications</h2>
            <div class="mt-2 bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-md">
                <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($notifications ?? [] as $notification)
                        <li>
                            <a href="{{ $notification->data['action_url'] ?? '#' }}" class="block hover:bg-gray-50 dark:hover:bg-gray-700">
                                <div class="px-4 py-4 sm:px-6">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                @if(isset($notification->data['color']))
                                                    <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-{{ $notification->data['color'] }}-100 dark:bg-{{ $notification->data['color'] }}-800">
                                                        <i class="fas fa-{{ $notification->data['icon'] ?? 'bell' }} text-{{ $notification->data['color'] }}-600 dark:text-{{ $notification->data['color'] }}-200"></i>
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-blue-100 dark:bg-blue-800">
                                                        <i class="fas fa-bell text-blue-600 dark:text-blue-200"></i>
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="ml-4">
                                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                    {{ $notification->data['title'] ?? 'Notification' }}
                                                </p>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                                    {{ $notification->data['message'] ?? '' }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="ml-2 flex-shrink-0 flex">
                                            <p class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                                                {{ $notification->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </li>
                    @empty
                        <li class="px-4 py-6 text-center text-gray-500 dark:text-gray-400">
                            No recent notifications
                        </li>
                    @endforelse
                </ul>
                @if(isset($notifications) && count($notifications) > 0)
                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 text-right sm:px-6">
                        <a href="{{ route('notifications.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            View All Notifications
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
