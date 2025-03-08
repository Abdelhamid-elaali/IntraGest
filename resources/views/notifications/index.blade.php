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
            @if($notifications->where('read_at', null)->count() > 0)
                <button onclick="markAllAsRead()" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Mark All as Read
                </button>
            @endif
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
