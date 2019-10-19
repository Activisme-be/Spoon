@php
    /** @var \App\Models\User $currentUser */
@endphp

<div class="card mt-3 border-0 shadow-sm card-body">
    <h6 class="border-bottom border-gray pb-1 mb-3">2FA authenticatie</h6>

    @if (empty($currentUser->passwordSecurity))
        @if (session('error'))
            <div class="alert alert-danger border-0" role="alert">{{ session('error') }}</div>
        @endif

        @if (session('success'))
            <div class="alert border-0 alert-success" role="alert">{{ session('success') }}</div>
        @endif

        <p class="card-text">
            Two Factor Authentication (2FA) versterkt de veiligheid van jouw account, doormiddel van 2 methoden (ook wel 2 factoren) genoemd te vereisen om je identiteit te bevestigen.
            2FA beschermd je tegen phising, social engineering en een wachtwoord brute force bij zwakke wachtwoorden. Ook wordt het gebruik van gestolen wachtwoorden verhinderd.
        </p>

        <form class="form-horizontal" method="POST" action="{{ route('generate2faSecret') }}">
            @csrf {{-- Form field protection --}}

            <p class="card-text">
                Om 2FA in te schakelen op je account, voelt u de volgende stappen uit:
            </p>

            <ol class="list-unstyled mb-1 font-weight-bold">
                <li>1. Klik op de "Activeer 2FA" knop, Voor het genereren van een QR code met de unieke code voor je account.</li>
                <li>2. Verifieer de OTP van de Google Authenticator Mobiele App</li>
            </ol>

            <div class="form-row">
                <div class="form-group col-12">
                </div>
            </div>
            <div class="form-group mb-0">
                <button type="submit" class="btn btn-secondary mb-0">
                    <i class="fe fe-settings mr-2"></i> Activeer 2FA
                </button>
            </div>
        </form>
    @elseif (! $currentUser->passwordSecurity->google2fa_enable)
        @if (session('error'))
            <div class="alert alert-danger border-0" role="alert">{{ session('error') }}</div>
        @endif

        @if (session('success'))
            <div class="alert border-0 alert-success" role="alert">{{ session('success') }}</div>
        @endif

        <p class="card-text">
            Two Factor Authentication (2FA) versterkt de veiligheid van jouw account, doormiddel van 2 methodes (ook wel 2 factoren genoemd) te vereisen om je identiteit te bevestigen.
            2FA beschermd je tegen phising, social engineering en een wachtwoord brute force bij zwakke wachtwoorden. Ook wordt het gebruik van gestolen wachtwoorden verhinderd.
        </p>

        <p class="card-text mb-1 font-weight-bold">1. Scan deze code met de Google Authenticator App:</p>
        <img class="qr-2fa-code" src="{{ $google2faUrl }}" alt="2Fa {{ config('app.name') }}">
        <p class="card-text mt-1 font-weight-bold">2. Enter de code van de app om 2FA te installeren.</p>

        <form method="POST" action="{{ route('enable2fa') }}">
            @csrf {{-- Form field protection --}}

            <div class="form-row">
                <div class="form-group col-4">
                    <label for="verify-code" class="sr-only">Authenticator code</label>
                    <input id="verify-code" type="password" class="form-control @error('verify-code', 'is-invalid')" placeholder="Authenticator code" @input('verify-code') required>
                    @error('verify-code')
                </div>

                <div class="form-group col-12 mb-0">
                    <button type="submit" class="btn btn-secondary">
                        <i class="fe fe-check mr-2"></i> Installeer 2FA
                    </button>
                </div>
            </div>
        </form>
    @elseif ($currentUser->passwordSecurity->google2fa_enable)
        <p class="card-text text-success mb-3">
            <span class="font-weight-bold mr-2"><i class="fe fe-info mr-1"></i> Info:</span>
            2FA is momenteel geactiveerd op uw account.
        </p>

        <p class="card-text">
            Indien u 2FA wilt deactivaren op jouw account. Kan dat door jouw huidg wachtwoord op te geven ter controle.
        </p>

        <form method="POST" action="{{ route('disable2fa') }}">
            @csrf {{-- Form field protection --}}

            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="change-password" class="sr-only">Huidig wachtwoord</label>
                    <input id="current-password" placeholder="Huidig wachtwoord" type="password" class="form-control @error('current-password', 'is-invalid')" @input('current-password') required>
                    @error('current-password')
                </div>

                <div class="form-group col-md-9 mb-0">
                    <button type="submit" class="btn btn-danger">
                        <i class="fe fe-rotate-ccw mr-2"></i> Deactiveer 2FA
                    </button>
                </div>
            </div>
        </form>
    @endif
</div>
