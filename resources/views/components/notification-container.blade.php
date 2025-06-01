@if(session('notifications'))
    <div class="notification-container space-y-2 mb-4">
        @foreach(session('notifications') as $notification)
            <x-notification-banner 
                type="{{ $notification['type'] }}" 
                title="{{ $notification['title'] }}" 
                message="{{ $notification['message'] }}" 
            />
        @endforeach
    </div>
@endif
