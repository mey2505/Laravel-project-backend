@extends('layouts.admin')

@section('title', 'Notifications')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Notifications</h3>
        <div class="card-tools">
            @if(auth()->user()->unreadNotifications->count() > 0)
            <form action="{{ route('admin.notifications.mark-all-read') }}" method="POST" style="display:inline">
                @csrf
                <button class="btn btn-sm btn-default">Mark All Read</button>
            </form>
            @endif
        </div>
    </div>
    <div class="card-body p-0">
        <div class="list-group list-group-flush">
            @forelse($notifications as $notification)
            <div class="list-group-item {{ is_null($notification->read_at) ? 'bg-light' : '' }}">
                <div class="d-flex w-100 justify-content-between align-items-start">
                    <div>
                        <strong class="mb-1">
                            @switch(class_basename($notification->type))
                                @case('NewOrderNotification') <i class="fas fa-shopping-cart text-info mr-1"></i> @break
                                @case('LowStockNotification') <i class="fas fa-exclamation-triangle text-warning mr-1"></i> @break
                                @case('NewCustomerNotification') <i class="fas fa-user-plus text-success mr-1"></i> @break
                                @default <i class="fas fa-bell text-secondary mr-1"></i>
                            @endswitch
                            {{ $notification->data['message'] ?? 'Notification' }}
                        </strong>
                        @if(!empty($notification->data['details']))
                        <p class="mb-1 text-muted small">{{ $notification->data['details'] }}</p>
                        @endif
                    </div>
                    <div class="text-right">
                        <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                        @if(is_null($notification->read_at))
                        <form action="{{ route('admin.notifications.mark-read', $notification->id) }}" method="POST" class="mt-1">
                            @csrf
                            <button class="btn btn-xs btn-outline-secondary">Mark Read</button>
                        </form>
                        @else
                        <span class="badge badge-secondary mt-1">Read</span>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="list-group-item text-center text-muted py-4">
                <i class="fas fa-bell-slash fa-2x mb-2 d-block"></i>
                No notifications yet.
            </div>
            @endforelse
        </div>
    </div>
    <div class="card-footer clearfix">
        {{ $notifications->links() }}
    </div>
</div>
@endsection
