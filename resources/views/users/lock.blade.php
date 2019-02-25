@extends('layouts.app')

@section('content')
    <div class="container-fluid py-3">
        <div class="page-header">
            <h1 class="page-title">Gebruikers</h1>
            <div class="page-subtitle">Deactiveer login voor {{ $userEntity->name }}</div>
        
            <div class="page-options d-flex">
                <a href="{{ route('users.index') }}" class="btn btn-secondary">
                    <i class="fe fe-list mr-1"></i> Overzicht
                </a>
            </div>
        </div>
    </div>

    <div class="container-fluid pb-3">
        <div class="row">
            <div class="col-3"> {{-- Sidenav --}}
                @include ('users.components.sidenav', ['user' => $userEntity])
            </div> {{-- /// End sidenav --}}

            <div class="col-9"> {{-- Content --}}
                <form action="{{ route('users.lock.store', $userEntity) }}" method="POST" class="card card-body">
                    @csrf {{-- Form field protection --}}
                    <h6 class="border-bottom border-gray pb-1 mb-3">Deactiveer login van <strong>{{ $userEntity->name }}</strong></h6>

                    <p class="card-text text-danger">
                        <i class="fe mr-1 fe-alert-triangle"></i> Bij het blokkeren van de login zorg je ervoor dat {{ $userEntity->name }}
                        verhinder je dat hij/zij zich nog kan inloggen op de applicatie.
                    </p>

                    <p class="card-text">
                        Vandaar dat we je vragen om twee keer na te denken voor u overgaat tot de blokkering van de login. <br>
                        Indien u wilt verder gaan met de activatie vragen wij je je wachtwoord en een redevoering in het onderstaande formulier
                        in te vullen ter controle en informatie. De rede zal ook meegedeeld aan {{ $userEntity->name }}.
                    </p>

                    <hr class="mt-0">

                    <div class="form-row">
                        <div class="form-group col-6">
                            <input type="password" placeholder="Uw wachtwoord ter bevestiging" class="form-control @error('wachtwoord', 'is-invalid')" @input('wachtwoord')>
                            @error('wachtwoord')
                        </div>

                        <div class="form-group col-12">
                            <textarea placeholder="Beschrijving van de rede voor de blokkering" class="form-control @error('reden', 'is-invalid')" @input('reden') rows="5">{{ old('reden') }}</textarea>
                            @error('reden') {{-- Validation error view partial --}}
                        </div>


                        <div class="form-group col-12 mb-0">
                            <button type="submit" class="btn btn-danger">Deactiveer</button>
                            <a href="{{ route('users.index') }}" class="btn btn-light">Annuleer</a>
                        </div>
                    </div>
                </form> 
            </div> {{-- /// END content --}}
        </div>
    </div>
@endsection