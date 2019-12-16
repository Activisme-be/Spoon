@extends('layouts.auth', ['title' => 'Bevestig account'])

@section('content')
    <div class="container-fluid py-3">
        <h1 class="page-title">Bevestig uw account</h1>
    </div>

    <div class="container-fluid pb-3">
        <div class="card">
            <div class="card-body">
                @if (session('resent'))
                    <div class="alert alert-success border-0" role="alert">
                        Een nieuwe verificatie link is verzonden naar je email adres.
                    </div>
                @endif

                <p class="card-text">
                    Uw account is momenteel nog niet bevestigd in {{ config('app.name') }}. Om deze applicaite volledig te kunnen gebruiken moet
                    je je account bevestigen.
                </p>

                <p class="card-text">
                    Voor dat u doorgaat met het bevestigen van je account. Vragen we je voor alle zekerheid je email adres nog is na te kijken. <br>
                    Indien u geen mail ontvangt kunt u je spam folder nakijken. En of eventueel contact opnemen met de beheerder.
                </p>
            </div>

            <form class="card-footer bg-card-footer" method="POST" action="{{ route('verification.resend') }}">
                @csrf
                <button type="submit" class="btn btn-secondary">
                    <i class="fe fe-send"></i> Account bevestigen
                </button>
            </form>
        </div>
    </div>
@endsection
