@extends ('layouts.app')

@section ('content')
    <div class="container-fluid py-3">
        <div class="page-header">
            <h1 class="page-title">Portaal logins</h1>
            <div class="page-subtitle">{{ $user->name }} verwijderen</div>

            <div class="page-options d-flex">
                <a href="{{ route('users.index') }}" class="btn btn-secondary">
                    <i class="fe fe-list mr-1"></i> Overzicht
                </a>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3"> {{-- Sidenav --}}
                @include('users.components.sidenav', ['user' => $user])
            </div> {{-- /// END sidenav --}}

            <div class="col-md-9">
                <form method="POST" action="{{ route('users.destroy', $user) }}" class="card card-body">
                    @csrf {{-- Form field protection --}}
                    @method ('DELETE') {{-- HTTP method spoofing --}}

                    <h6 class="border-bottom border-gray pb-1 mb-3">Login verwijderen van <strong>{{ $user->name }}</strong></h6>
                    @include ('flash::message') {{-- Flash session view partial --}}

                    <p class="card-text text-danger">
                        <i class="fe fe-alert-triangle mr-1"></i> U staat op het punt om de login van {{ $user->name }} te verwijderen.
                    </p>

                    <p class="card-title">
                        Bij het verwijderen van de login. Worden de vrijwilligersploegen waar {{ $user->name }} leider van is,
                        losgekoppeld in de applicatie. Als ook worden alle gegevens van de personen genormaliseerd of verwijderd.
                        Vandaar dat wij u vragen om het onderstaande formulier in te vullen als controle.
                    </p>

                    <hr class="mt-0">

                    <div class="form-row">
                        <div class="form-group col-6">
                            <input type="password" placeholder="Uw wachtwoord ter bevestiging" class="form-control @error('wachtwoord', 'is-invalid')" @input('wachtwoord')>
                            @error('wachtwoord')
                        </div>

                        <div class="form-group col-12 mb-0">
                            <button type="submit" class="btn btn-danger">Verwijder</button>
                            <a href="{{ route('users.index') }}" class="btn btn-light">Annuleer</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection