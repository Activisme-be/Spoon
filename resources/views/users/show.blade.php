@extends ('layouts.app', ['title' => $user->name])

@section('content')
    @php
        /** @var \App\Models\User $currentUser */
        /** @var \App\Models\User $user */
        /** @var \Spatie\Permission\Models\Role[] $roles */
    @endphp

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
                <div class="card shadow-sm border-0">
                    <form id="info-form" method="POST" action="{{ route('users.update', $user) }}" class="card-body">
                        @csrf               {{-- Form field protection --}}
                        @method('PATCH')    {{-- HTTP method spoofing --}}
                        @form($user)        {{-- Bind user data to the form --}}

                        <h6 class="border-bottom border-gray pb-1 mb-3">Algemene informatie van <strong>{{ $user->name }}</strong></h6>
                        @include('flash::message') {{-- Flash session view partial --}}

                        @if ($user->isBanned())
                            <div class="alert alert-danger alert-important">
                                <i class="fe fe-alert-triangle mr-1"></i> Deze gebruiker is tijdelijk gedeactiveerd!
                            </div>
                        @endif

                        <div class="form-row">
                            <div class="form-group col-6 @if (! $currentUser->hasAnyRole(['admin', 'webmaster'])) mb-0 @endif">
                                <label for="inputVoornaam">Voornaam <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control @error('voornaam', 'is-invalid')" id="inputVoornaam" placeholder="Voornaam" @input('voornaam')>
                                @error('voornaam')
                            </div>

                            <div class="form-group col-6">
                                <label for="inputAchternaam">Achternaam <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('achternaam', 'is-invalid')" id="inputAchternaam" placeholder="Achternaam" @input('achternaam')>
                                @error('achternaam')
                            </div>

                            <div class="form-grroup col-12">
                                <label for="inputEmail">E-mail adres <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email', 'is-invalid')" id="inputEmail" placeholder="E-mail adres" @input('email')>
                                @error('email')
                            </div>
                        </div>

                        <hr>

                        @if ($currentUser->hasAnyRole(['admin', 'webmaster']))
                            <div class="form-row">
                                <div class="form-group col-12 mb-0">
                                    <label for="permissions">Permissie functies <span class="text-danger">*</span></label>

                                    <select @input('roles[]') class="custom-select @error('roles[]', 'is-invalid')" multiple>
                                    @foreach ($roles as $role) {{-- Permission loop --}}
                                    <option value="{{ $role->name }}" @if ($currentUser->hasRole($role->name)) selected @endif>
                                        {{ ucfirst($role->name) }}
                                    </option>
                                    @endforeach {{-- /// END permission loop --}}
                                    </select>

                                    @error('roles[]') {{-- Validation error view partial --}}
                                </div>
                            </div>
                        @endif
                    </form>

                    <div class="card-footer bg-card-footer border-top-0">
                        <button form="info-form" type="submit" class="btn btn-success">Aanpassen</button>
                        <button form="info-form" type="reset" class="btn btn-link text-decoration-none">Annuleren</button>
                    </div>
                </div>
            </div> {{-- /// END Page content --}}
        </div>
    </div>
@endsection
