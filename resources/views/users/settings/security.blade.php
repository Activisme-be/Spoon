@extends('layouts.app', ['title' => 'Account beveiliging'])

@section('content')
    @php
        /** @var \App\Models\User $currentUser */
    @endphp

   <div class="container-fluid py-3">
        <div class="page-header">
            <h1 class="page-title">{{ $currentUser->name }}</h1>
            <div class="page-subtitle">Account beveiliging</div>
        </div>
    </div>

    <div class="container-fluid pb-3">
        <div class="row">
            <div class="col-3"> {{-- sidenav --}}
                @include('users.components.settings-sidenav')
            </div> {{-- /// END sidenav --}}

            <div class="col-9"> {{-- Content --}}
                <form method="POST" action="{{ route('account.settings.security') }}" class="card border-0 shadow-sm card-body">
                    @csrf               {{-- Form field protection --}}
                    @form($currentUser) {{-- Bind the authenticated user data to the form --}}
                    @method ('PATCH')   {{-- HTTP method spoofing --}}

                    <h6 class="border-bottom border-gray pb-1 mb-3">Beveiligings instellingen</h6>
                    @include ('flash::message') {{-- Flash session view partial --}}

                    <div class="form-row">
                        <div class="form-group col-12">
                            <label for="inputCurrent">Huidig wachtwoord <span class="text-danger">*</span></label>
                            <input type="password" class="form-control @error('huidig_wachtwoord', 'is-invalid')" placeholder="Uw huidig wachtwoord" @input('huidig_wachtwoord')>
                            @error('huidig_wachtwoord')
                        </div>

                        <div class="form-group col-6">
                            <label for="inputWachtwoord">Nieuw wachtwoord <span class="text-danger">*</span></label>
                            <input type="password" class="form-control @error('wachtwoord', 'is-invalid')" placeholder="Uw nieuw wachtwoord" @input('wachtwoord')>
                            @error('wachtwoord')
                        </div>

                        <div class="form-group col-6">
                            <label for="inputBevestiging">Herhaal wachtwoord <span class="text-danger">*</span></label>
                            <input type="password" class="form-control @error('wachtwoord_confirmation', 'is-invalid')" placeholder="Herhaal nieuw wachtwoord" @input('wachtwoord_confirmation')>
                            @error('wachtwoord_confirmation')
                        </div>
                    </div>

                    <hr class="mt-0">

                    <div class="form-row">
                        <div class="form-group col-12 mb-0">
                            <button type="submit" class="btn btn-success">Opslaan</button>
                            <button type="reset" class="btn btn-light">Reset</button>
                        </div>
                    </div>
                </form>

                @includeWhen (config('platform.2fa.enabled'), 'users.settings.2fa')
            </div> {{-- /// END content --}}
        </div>
    </div>
@endsection
