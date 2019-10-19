@extends('layouts.app', ['title' => 'Systeem notificaties'])

@section('content')
    @php
        /** @var \Illuminate\Notifications\DatabaseNotification $notification */
    @endphp

    <div class="container-fluid py-3">
        <div class="page-header">
            <h1 class="page-title">Systeem notificaties</h1>
            <div class="page-subtitle">Notificatie informatie</div>

            <div class="page-options d-flex">
                <a href="{{ route('alerts.overview') }}" class="btn btn-secondary shadow-sm border-0">
                    <i class="fe fe-list mr-2"></i> Overzicht
                </a>
            </div>
        </div>
    </div>

    <div class="container-fluid pb-3">
        <div class="row">
            <div class="col-3"> {{-- side navigation --}}
                @include ('notifications.kiosk._partials.sidenav')
            </div> {{-- /// END side navigation --}}

            <div class="col-9"> {{-- Content --}}
                <div class="card card-body shadow-sm border-0 mb-4"> {{-- Notification information --}}
                    <h6 class="border-bottom border-gray pb-1 mb-3">Notificatie gegevens</h6>

                    <div class="table-responsive">
                        <table class="table table-sm mb-0 table-borderless">
                            <tbody>
                                <tr>
                                    <td class="font-weight-bold float-left td-attribute">Titel:</th>
                                    <td class="float-left td-key">{{ $notification->title }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold float-left td-attribute">Kanaal:</th>
                                    <td class="float-left td-key">{{ ($notification->driver === 'database') ? 'Applicatie (database)' : ucfirst($notification->driver) }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold float-left td-attribute">Verzonden op:</th>
                                    <td class="float-left td-key">{{ $notification->created_at->format('d-m-Y H:i:s')}} ({{ $notification->created_at->diffForHumans()}})</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold float-left td-attribute">Notificatie bericht:</th>
                                    <td class="float-left td-key">{{ $notification->message }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div> {{-- /// END notification information --}}

                <div class="card card-body shadow-sm border-0"> {{-- User information --}}
                    <h6 class="border-bottom border-gray pb-1 mb-3">Gegevens verzender</h6>

                    <div class="table-responsive">
                        <table class="table table-sm mb-0 table-borderless">
                            <tbody>
                                <tr>
                                    <td class="font-weight-bold float-left td-attribute">Naam:</td>
                                    <td class="float-left td-key">{{ $notification->creator->name }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold float-left td-attribute">E-mail adres:</td>
                                    <td class="float-left td-key">{{ $notification->creator->email }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold float-left td-attribute">Functies:</td>
                                    <td class="float-left td-key">
                                        @foreach ($notification->creator->roles as $function) {{-- User function look --}}
                                            {{ $function->name }}{{ (! $loop->last) ? ',' : '' }}
                                        @endforeach {{-- /// END user function loop --}}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div> {{-- /// END user information --}}
            </div> {{-- /// END content --}}
        </div>
    </div>
@endsection
