@extends('layouts.app')

@section('content')
    <div class="container-fluid py-3">
        <div class="page-header">
            <h1 class="page-title">Notificaties</h1>

            <div class="page-options">
                <a href="{{ route('notifications.markAll') }}" class="btn @if ($unreadCount === 0) disabled @endif btn-outline-secondary">
                    <i class="fe fe-bell-off mr-1"></i> Markeer alles als gelezen
                </a>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-3"> {{-- Sidebar --}}
                <div class="list-group mb-3">
                    <a href="{{ route('notifications.index') }}" class="list-group-item list-group-item-action">
                        Ongelezen notificaties
                    </a>
                    <a href="{{ route('notifications.index', ['type' => 'alle']) }}" class="list-group-item list-group-item-action">
                        Alle notificaties
                    </a>
                </div>
            </div> {{-- /// END sidebar --}}

            <div class="col-9"> {{-- Content --}}
                @if ($unreadCount > 0) {{-- There are notifications found in the application --}}
                    <div class="card card-body">
                        <h6 class="border-bottom border-gray pb-1 mb-2">
                            @if ($type === 'alle')
                                Alle notificaties
                            @else 
                                Ongelezen notificaties
                            @endif
                        </h6>

                        @foreach ($notifications as $notification)
                            <div class="media small text-muted pt-2">
                                <img src="{{ avatar($notification->notifiable) }}" alt="32x32" alt="{{ $notification->notifiable->name }}" class="mr-2 shadow-sm rounded" style="width: 32px; height: 32px;">
                                <div class="card w-100 card-text border-0 mb-0">
                                    <div class="w-100">
                                        <strong class="float-left text-gray-dark">{{ $notification->notifiable->name }}</strong> - {{ $notification->created_at->diffForHumans() }}</strong>

                                        @if ($notification->unread()) 
                                            <div class="float-right">
                                                <a href="{{ route('notifications.markAsRead', $notification) }}" class="no-underline"><i class="fe fe-check"></i> 
                                                    Markeer als gelezen
                                                </a>
                                            </div> 
                                        @endif
                                    </div>

                                    {{ $notification->data['message'] }}
                                </div>
                            </div>

                            @if (! $loop->last) {{-- This notification is not the latest so we need a breakline --}}
                                <hr class="mt-2 mb-0">
                            @endif
                        @endforeach

                        {{ $notifications->links() }} {{-- Paginator view instance --}}
                    </div>
                @else {{-- There are no notifications found in the application --}}
                    <div class="blankslate">
                        <h3>Geen notificaties</h3>
                        <p class="pt-2">
                            @if ($type === 'alle')
                                Het lijkt erop dat we momenteel nog geen notificaties hebben voor je in de applicatie.
                            @else {{-- Unread notifications --}}
                                Het lijkt erop dat je al je notificaties hebt gelezen in de applicatie.
                            @endif
                        </p>
                    </div>

                @endif {{-- /// END notification loop --}}
            </div> {{-- /// END content --}}
        </div>
    </div>
@endsection