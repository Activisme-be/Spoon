@extends('layouts.app', ['title' => 'Account informatie'])

@section('content')
    @php
        /** @var \App\Models\User $currentUser */
    @endphp

    <div class="container-fluid py-3">
        <div class="page-header">
            <h1 class="page-title">{{ $currentUser->name }}</h1>
            <div class="page-subtitle">Informatie instellingen</div>
        </div>
    </div>

    <div class="container-fluid pb-3">
        <div class="row">
            <div class="col-3"> {{-- Sidebar --}}
                @include ('users.components.settings-sidenav')
            </div> {{-- /// END sidebar --}}

            <div class="col-9"> {{-- Content --}}
               <div class="card border-0 shadow-sm">
                   <form method="POST" id="information-form" action="{{ route('account.settings.info') }}" class="card-body">
                       @csrf                {{-- Form field protection --}}
                       @form($currentUser)  {{-- Bind the current user data to the form --}}
                       @method('PATCH')     {{-- HTTP method spoofing --}}

                       <h6 class="border-bottom border-gray pb-1 mb-3">Informatie instellingen</h6>
                       @include('flash::message') {{-- Flash session view partial --}}

                       <div class="form-row">
                           <div class="form-group col-6">
                               <label for="inputFirstname">Voornaam <span class="text-danger">*</span></label>
                               <input type="text" placeholder="Uw voornaam" class="form-control @error('voornaam', 'is-invalid')" @input('voornaam')>
                               @error('voornaam')
                           </div>

                           <div class="form-group col-6">
                               <label for="inputLastname">Achternaam <span class="text-danger">*</span></label>
                               <input type="text" placeholder="Uw achternaam" class="form-control @error('achternaam', 'is-invalid')" @input('achternaam')>
                               @error('achternaam')
                           </div>

                           <div class="form-group col-12 mb-0">
                               <label for="inputEmail">Email adres <span class="text-danger">*</span></label>
                               <input type="email" placeholder="Uw email adres" class="form-control @error('email', 'is-invalid')" @input('email')>
                               @error('email')
                           </div>
                       </div>
                   </form>
                   <div class="card-footer bg-card-footer border-top-0">
                       <button type="submit" form="information-form" class="btn btn-success">Opslaan</button>
                       <button type="reset" form="information-form" class="btn btn-light">Reset</button>
                   </div>
               </div>
            </div> {{-- /// END content --}}
        </div>
    </div>
@endsection
