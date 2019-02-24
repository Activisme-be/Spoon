@extends ('layouts.app')

@section('content')
    <div class="container-fluid py-3">
        <div class="page-header">
            <h1 class="page-title">Gebruikers</h1>
            <div class="page-subtitle">Informatie omtrent {{ $user->name }}</div>
        
            <div class="page-options d-flex">
                <a href="{{ route('users.index') }}" class="btn btn-secondary">
                    <i class="fe fe-list mr-1"></i> Overzicht
                </a>
            </div>
        </div>
    </div>

    <div class="container-fluid pb-3">
        <div class="row">
            <div class="col-md-3"> {{-- Sidenav --}}
                @include ('users.components.sidenav', ['user' => $user])
            </div> {{-- /// END sidenav --}}        
        
            <div class="col-9"> {{-- Page content --}}
                <form method="POST" action="{{ route('users.update', $user) }}" class="card card-body">
                    @csrf               {{-- Form field protection --}}
                    @method('PATCH')    {{-- HTTP method spoofing --}}
                    @form($user)        {{-- Bind user data to the form --}}

                    <h6 class="border-bottom border-gray pb-1 mb-3">Algemene informatie van <strong>{{ $user->name }}</strong></h6>
                    @include('flash::message') {{-- Flash session view partial --}}

                    <div class="form-row">
                        <div class="form-group col-6">
                            <label for="inputVoornaam">Voornaam @if (! $cantEdit) <span class="text-danger">*</span> @endif</label>
                            <input type="text" @if ($cantEdit) disabled @endif class="form-control @error('voornaam', 'is-invalid')" id="inputVoornaam" placeholder="Voornaam" @input('voornaam')>
                            @error('voornaam')
                        </div>

                        <div class="form-group col-6">
                            <label for="inputAchternaam">Achternaam @if (! $cantEdit) <span class="text-danger">*</span> @endif</label>
                            <input type="text" @if ($cantEdit) disabled @endif class="form-control @error('achternaam', 'is-invalid')" id="inputAchternaam" placeholder="Achternaam" @input('achternaam')>
                            @error('achternaam')
                        </div>

                        <div class="form-grroup col-12">
                            <label for="inputEmail">E-mail adres @if (! $cantEdit) <span class="text-danger">*</span> @endif</label>
                            <input type="email" @if ($cantEdit) disabled @endif class="form-control @error('email', 'is-invalid')" id="inputEmail" placeholder="E-mail adres" @input('email')>
                            @error('email')
                        </div>
                    </div>

                    <hr>

                    <div class="form-row">
                        <div class="form-group col-12 mb-0">
                            <button type="submit" @if ($cantEdit) disabled @endif class="btn btn-success">Aanpassen</button>
                            <button type="reset" @if ($cantEdit) disabled @endif class="btn btn-link text-decoration-none">Annuleren</button>
                        </div>
                    </div>

                </form>
            </div> {{-- /// END Page content --}}
        </div>
    </div>
@endsection