@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
<div class="bg-white shadow-sm rounded-lg">
    <div class="p-6 border-b border-gray-200">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-semibold text-gray-800">Notifications</h2>
                <p class="mt-1 text-sm text-gray-600">View and manage your notifications</p>
            </div>
            <div class="flex space-x-2">
                @if($notifications->where('read_at', null)->count() > 0)
                    <button onclick="markAllAsRead()" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Mark All as Read
                    </button>
                @endif
            </div>
        </div>
        
        <!-- Filters -->
        <div class="mb-6 bg-gray-50 p-4 rounded-lg border border-gray-200">
            <form action="{{ route('notifications.index') }}" method="GET" class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-[200px]">
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Notification Type</label>
                    <select id="type" name="type" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                        <option value="">All Types</option>
                        <option value="stock" {{ request('type') === 'stock' ? 'selected' : '' }}>Stock Alerts</option>
                        <option value="payment" {{ request('type') === 'payment' ? 'selected' : '' }}>Payment Reminders</option>
                        <option value="absence" {{ request('type') === 'absence' ? 'selected' : '' }}>Absence Alerts</option>
                        <option value="room" {{ request('type') === 'room' ? 'selected' : '' }}>Room Status</option>
                    </select>
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select id="status" name="status" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                        <option value="">All Statuses</option>
                        <option value="unread" {{ request('status') === 'unread' ? 'selected' : '' }}>Unread</option>
                        <option value="read" {{ request('status') === 'read' ? 'selected' : '' }}>Read</option>
                    </select>
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Date Range</label>
                    <select id="date" name="date" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                        <option value="">All Time</option>
                        <option value="today" {{ request('date') === 'today' ? 'selected' : '' }}>Today</option>
                        <option value="week" {{ request('date') === 'week' ? 'selected' : '' }}>This Week</option>
                        <option value="month" {{ request('date') === 'month' ? 'selected' : '' }}>This Month</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        Filter
                    </button>
                    @if(request()->has('type') || request()->has('status') || request()->has('date'))
                        <a href="{{ route('notifications.index') }}" class="ml-2 inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                            Clear
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <div class="space-y-4">
            @forelse($notifications as $notification)
                <div id="notification-{{ $notification->id }}" class="p-4 rounded-lg border {{ $notification->read_at ? 'bg-gray-50 border-gray-200' : 'bg-blue-50 border-blue-200' }}">
                    <div class="flex justify-between items-start">
                        <div class="flex-grow">
                            <h3 class="text-sm font-medium {{ $notification->read_at ? 'text-gray-900' : 'text-blue-900' }}">
                                {{ $notification->data['title'] ?? 'Notification' }}
                            </h3>
                            <p class="mt-1 text-sm {{ $notification->read_at ? 'text-gray-600' : 'text-blue-600' }}">
                                {{ $notification->data['message'] ?? '' }}
                            </p>
                            <p class="mt-2 text-xs {{ $notification->read_at ? 'text-gray-500' : 'text-blue-500' }}">
                                {{ $notification->created_at->diffForHumans() }}
                            </p>
                        </div>
                        @if(!$notification->read_at)
                            <button onclick="markAsRead('{{ $notification->id }}')" class="ml-4 text-sm text-blue-600 hover:text-blue-800">
                                Mark as Read
                            </button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-8 text-gray-500">
                    No notifications found.
                </div>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $notifications->links() }}
        </div>
    </div>
</div>

@push('scripts')
<script>
function markAsRead(id) {
    fetch(`{{ route('notifications.mark-as-read') }}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ id: id })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const notification = document.getElementById(`notification-${id}`);
            notification.classList.remove('bg-blue-50', 'border-blue-200');
            notification.classList.add('bg-gray-50', 'border-gray-200');
            
            // Update text colors
            notification.querySelectorAll('.text-blue-900').forEach(el => {
                el.classList.remove('text-blue-900');
                el.classList.add('text-gray-900');
            });
            notification.querySelectorAll('.text-blue-600').forEach(el => {
                el.classList.remove('text-blue-600');
                el.classList.add('text-gray-600');
            });
            notification.querySelectorAll('.text-blue-500').forEach(el => {
                el.classList.remove('text-blue-500');
                el.classList.add('text-gray-500');
            });
            
            // Remove the mark as read button
            notification.querySelector('button').remove();
        }
    });
}

function markAllAsRead() {
    fetch(`{{ route('notifications.mark-all-as-read') }}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update all unread notifications
            document.querySelectorAll('.bg-blue-50').forEach(notification => {
                notification.classList.remove('bg-blue-50', 'border-blue-200');
                notification.classList.add('bg-gray-50', 'border-gray-200');
                
                // Update text colors
                notification.querySelectorAll('.text-blue-900').forEach(el => {
                    el.classList.remove('text-blue-900');
                    el.classList.add('text-gray-900');
                });
                notification.querySelectorAll('.text-blue-600').forEach(el => {
                    el.classList.remove('text-blue-600');
                    el.classList.add('text-gray-600');
                });
                notification.querySelectorAll('.text-blue-500').forEach(el => {
                    el.classList.remove('text-blue-500');
                    el.classList.add('text-gray-500');
                });
                
                // Remove mark as read buttons
                notification.querySelector('button')?.remove();
            });
            
            // Hide the mark all as read button
            document.querySelector('button[onclick="markAllAsRead()"]').remove();
        }
    });
}
</script>
@endpush
@endsection
