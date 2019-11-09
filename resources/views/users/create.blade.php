@extends('layouts.app', ['title' => 'Gebruiker toevoegen'])

@section('content')
	<div class="container-fluid py-3">
        <div class="page-header">
            <h1 class="page-title">Gebruikers</h1>
            <div class="page-subtitle">Creer een nieuwe gebruiker</div>

            <div class="page-options d-flex">
            	<a href="{{ route('users.index') }}" class="btn btn-secondary">
            		<i class="fe fe-list mr-1"></i> Overzicht
            	</a>
            </div>
        </div>
    </div>

    <div class="container-fluid pb-3">
        <div class="card border-0 shadow-sm">
            <form id="create-form" method="POST" action="{{ route('users.store') }}" class="card-body">
                @csrf {{-- Form field protection --}}

                <h6 class="border-bottom border-gray pb-1 mb-3">Nieuwe gebruiker toevoegen</h6>

                <div class="form-row">
                    <div class="form-group col-6">
                        <label for="inputVoornaam">Voornaam <span class="text-danger">*</span></label>
                        <input type="text" id="inputVoornaam" class="form-control @error('voornaam', 'is-invalid')" placeholder="Voornaam van de gebruiker" @input('voornaam')>
                        @error('voornaam')
                    </div>

                    <div class="form-group col-6">
                        <label for="inputAchternaam">Achternaam <span class="text-danger">*</span></label>
                        <input type="text" id="inputAchternaam" class="form-control @error('achternaam', 'is-invalid')" placeholder="Achternaam van de gebruiker" @input('achternaam')>
                        @error('achternaam')
                    </div>

                    <div class="form-group col-12">
                        <label for="inputEmail">Email adres <span class="text-danger">*</span></label>
                        <input type="email" id="inputEmail" class="form-control @error('email', 'is-invalid')" placeholder="E-mail adres van de gebruiker" @input('email')>
                        @error('email')
                    </div>
                </div>

                <hr class="mt-0">

                <div class="form-row">
                    <div class="form-group col-12">
                        <label for="permissions">Permissie functies <span class="text-danger">*</span></label>

                        <select @input('roles[]') class="custom-select @error('roles[]', 'is-invalid')" multiple>
                            @options($roles, 'roles[]', 'user')
                        </select>

                        @error('roles[]') {{-- Validation error view partial --}}
                    </div>
                </div>

                <hr class="mt-0">
            </form>
            <div class="card-footer bg-card-footer border-top-0">
                <button form="create-form" type="submit" class="btn btn-success">Opslaan</button>
                <button form="create-form" type="reset" class="btn btn-light">Reset</button>
            </div>
        </div>
    </div>
@endsection
