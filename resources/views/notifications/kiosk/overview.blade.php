@extends('layouts.app', ['title' => 'Systeem notificaties'])

@section('content')
    @php
        /** @var \Illuminate\Notifications\DatabaseNotification[]&\Illuminate\Pagination\Paginator $notifications */
    @endphp

    <div class="container-fluid py-3">
        <div class="page-header">
            <h1 class="page-title">Systeem notificaties</h1>
            <div class="page-subtitle">Verzonden notificaties</div>
        </div>
    </div>

    <div class="container-fluid pb-3">
        <div class="row">
            <div class="col-3"> {{-- Sidenav --}}
                @include ('notifications.kiosk._partials.sidenav')
            </div> {{-- /// END sidenav --}}

            <div class="col-9"> {{-- content --}}
                <div class="card card-body shadow-sm border-0">
                    <h6 class="border-bottom border-gray pb-1 mb-3">Verzonden systeem notificaties.</h6>

                    @include('flash::message') {{-- Flash session view partial --}}
                    @include('notifications.kiosk._partials.table', ['notifications' => $notifications])

                    {{ $notifications->links() }} {{-- Pagination view instance --}}
                </div>
            </div> {{-- /// END content --}}
        </div>
    </div>
@endsection
