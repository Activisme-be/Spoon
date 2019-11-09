@extends ('layouts.app', ['title' => 'Systeem notificaties'])

@section('content')
    <div class="container-fluid py-3">
        <div class="page-header">
            <h1 class="page-title">Systeem notificaties</h1>
            <div class="page-subtitle">Nieuwe notificatie</div>
        </div>
    </div>

    <div class="container-fluid pb-3">
        <div class="row">
            <div class="col-3"> {{-- Sidenav --}}
                @include ('notifications.kiosk._partials.sidenav')
            </div> {{-- /// END sidenav --}}

            <div class="col-9"> {{-- content --}}
                <div class="card border-0 shadow-sm">
                    <form id="create-form" method="POST" action="{{ route('alerts.store') }}" class="card-body">
                        <h6 class="border-bottom border-gray pb-1 mb-3">Systeem notificatie verzenden.</h6>

                        @csrf {{-- Form field protection --}}

                        @if (session('flash_notification'))
                            @include ('flash::message') {{-- Session view partial --}}
                        @else {{-- No flash message if sound in the application. --}}
                            <div class="alert alert-info border-0 alert-important alert-dismissible fade show" role="alert">
                                <span class="font-weight-bold mr-2"><i class="fe fe-info mr-1"></i> info:</span>
                                Systeem notificaties worden verstuurd naar elke login van {{ config('app.name') }} en of de gegeven gebruikers groep.

                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        <div class="row mt-1">
                            <div class="col-3">
                                <h5>Notificatie voorkeuren</h5>
                            </div>

                            <div class="offset-1 col-8">
                                <div class="form-row">
                                    <div class="form-group col-12">
                                        <label for="method">Verzend als <span class="text-danger">*</span></label>

                                            <select class="form-control" id="method" @input('driver')>
                                        @options($drivers, 'driver', 'web')
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="mt-0">

                        <div class="row">
                            <div class="col-3">
                                <h5>Notificatie gegevens</h5>
                                <small class="text-secndary">
                                    <i class="fe fe-info mr-1"></i>
                                    Actie url en Actie text zijn optioneel omdat niet elke notificatie dit nodig heeft. Indien dit nodig is hou dan de tekst zo klein mogelijk.
                                </small>
                            </div>

                            <div class="offset-1 col-8">
                                <div class="form-row">
                                    <div class="form-group col-12">
                                        <label for="title">Titel <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('title', 'is-invalid')" id="title" @input('title') placeholder="Notificatie titel">
                                        @error('title')
                                    </div>
                                    <div class="form-group col-6">
                                        <label for="url">Actie URL</label>
                                        <input type="text" class="form-control @error('action_url', 'is-invalid')" id="url" @input('action_url') placeholder="https://">
                                        @error('action_url')
                                    </div>
                                    <div class="form-group col-6">
                                        <label for="text">Actie tekst</label>
                                        <input type="text" class="form-control @error('action_text', 'is-invalid')" id="text" @input('action_text') placeholder="bv. bekijk gebruiker">
                                        @error('action_text')
                                    </div>
                                    <div class="form-group col-12 mb-0">
                                        <label for="message">Bericht <span class="text-danger">*</span></label>
                                        <textarea class="form-control @error('message', 'is-invalid')" id="message" rows="4" placeholder="Notificatie bericht" @input('message')>@text('message')</textarea>
                                        @error('message')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="card-footer bg-card-footer border-top-0">
                        <div class="float-right">
                            <button form="create-form" type="submit" class="btn border-0 btn-success">
                                <i class="fe fe-send mr-2"></i> Verzenden
                            </button>

                            <button form="create-form" type="reset" class="btn btn-light border-0 btn-success">
                                <i class="fe fe-rotate-ccw text-danger"></i> Reset
                            </button>
                        </div>
                    </div>
                </div>
            </div> {{-- /// END content --}}
        </div>
    </div>
@endsection
