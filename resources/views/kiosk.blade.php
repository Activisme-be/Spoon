@php
use Illuminate\Notifications\DatabaseNotification;
@endphp

@extends('layouts.app', ['title' => 'Kiosk dashboard'])

@section('content')
    @php
        /** @see \App\Composers\KioskComposer */
        /** @var \stdClass $audit */
        /** @var \stdClass $notifications */
        /** @var \stdClass $users */

        /** @var \Illuminate\Notifications\DatabaseNotification[]&\Illuminate\Pagination\Paginator $alerts */
        /** @var \Spatie\Activitylog\Models\Activity[]&\Illuminate\Pagination\Paginator $logs */
    @endphp

    <div class="container-fluid py-3">
        <div class="page-header">
            <div class="page-title">{{ config('app.name') }} - Kiosk</div>
            <div class="page-subtitle">Dashboard</div>

            <div class="page-options d-flex">
                <a href="{{ route('home') }}" class="btn btn-danger border-0 shadow-sm">
                    <i class="fe fe-rotate-ccw mr-2"></i> Verlaat kiosk
                </a>
            </div>
        </div>
    </div>

    <div class="container-fluid pb-3">
        <div class="row"> {{-- Widgets- --}}
            <div class="col-4"> {{-- User widget --}}
                <div class="card border-0 shadow-sm mb-4 p-2">
                    <div class="d-flex align-items-center">
                        <span class="stamp stamp-md shadow-sm stamp-bg-brand mr-3">
                            <i class="fe fe-users"></i>
                        </span>

                        <div>
                            <h5 class="m-0">{{ $users->total }} <small>gebruikers</small></h5>
                            <small class="text-muted">waarvan {{ $users->deactivated_count }} gedeactiveerd</small>
                        </div>
                    </div>
                </div> {{-- /// END user widget --}}
            </div> {{-- /// END widgets --}}

            <div class="col-4"> {{-- Audit widget --}}
                <div class="card border-0 shadow-sm mb-4 p-2">
                    <div class="d-flex align-items-center">
                        <span class="stamp stamp-md shadow-sm stamp-bg-brand mr-3">
                            <i class="fe fe-activity"></i>
                        </span>

                        <div>
                            <h5 class="m-0">{{ $audit->total }} <small>Audit logs</small></h5>
                            <small class="text-muted">waarvan {{ $audit->total_today }} vandaag</small>
                        </div>
                    </div>
                </div>
            </div> {{-- /// END audit widget --}}

            <div class="col-4"> {{-- system notifications widget --}}
                <div class="card border-0 shadow-sm mb-4 p-2">
                    <div class="d-flex align-items-center">
                        <span class="stamp stamp-md shadow-sm stamp-bg-brand mr-3">
                            <i class="fe fe-bell"></i>
                        </span>

                        <div>
                            <h5 class="m-0">{{ $notifications->total }} <small>Systeem notificaties</small></h5>
                            <small class="text-muted">waarvan {{ $notifications->total_today}} vandaag verzonden</small>
                        </div>
                    </div>
                </div>
            </div> {{-- /// END system notifications widget --}}
        </div> {{-- /// END widgets --}}

        <div class="row">
            @if ($audit->total > 0) {{-- There are logs found in the application. --}}
                <div class="col-12"> {{-- Short activity overview --}}
                    <div class="card card-body border-0 shadow-sm @if ($notifications->total > 0) mb-4 @endif">
                        <h6 class="border-bottom border-gray pb-1 mb-3">
                            <i class="fe fe-brand fe-activity mr-1"></i> Recente activiteit

                            @if ($audit->total >= 7) {{-- More than 7 logged activity logs so display the overview link --}}
                                <a href="{{ route('audit.overview') }}" class="text-decoration-none float-right small text-muted">
                                    <i class="fe fe-list mr-2"></i> Overzicht
                                </a>
                            @endif
                        </h6>

                        @include('activity._partials.table', ['logs' => $logs])
                    </div>
                </div> {{-- /// END Short activity overview --}}
            @endif

            @if ($notifications->total >= 7 && $currentUser->hasRole('webmaster')) {{-- More than 7 logged system notifications so display the overview link --}}
                <div class="col-12"> {{-- Short system alert overview --}}
                    <div class="card card-body border-0 shadow-sm">
                        <h6 class="border-bottom border-gray pb-1 mb-3">
                            <i class="fe fe-brand fe-bell mr-1"></i> Verzonden systeem notificaties

                            <div class="float-right">
                                <a href="{{ route('alerts.index') }}" class="small text-decoration-none text-muted">
                                    <i class="fe fe-plus mr-1"></i> Notificatie verzenden
                                </a>

                                @if ($notifications->total >= 7)
                                    <a href="{{ route('alerts.overview') }}" class="small ml-2 text-decoration-none text-muted">
                                        <i class="fe fe-list mr-1"></i> Overzicht
                                    </a>
                                @endif
                            </div>
                        </h6>

                        @include('notifications.kiosk._partials.table', ['notifications' => $alerts])
                    </div>
                </div> {{-- /// END short system alert overview --}}
            @endif
        </div>
    </div>
@endsection
