@extends('layouts.auth', ['title' => 'Bevestig wachtwoord'])

@section('content')
    <section class="h-100">
        <div class="container h-100">
            <div class="row justify-content-md-center h-100">
                <div class="card-wrapper">
                    <div class="brand">
                        <img src="{{ asset('img/logo.jpg') }}">
                    </div>
                    <div class="card fat">
                        <div class="card-body">
                            <h4 class="card-title">Bevestig wachtwoord</h4>
                            <form method="POST" action="{{ route('password.confirm') }}">
                                @csrf {{-- Form field protection --}}

                                @if (session('status'))
                                    <div class="alert alert-success">
                                        <small>{{ session('status') }}</small>
                                    </div>
                                @endif

                                <p class="small card-text">
                                    We vragen je om je wachtwoord te bevestigen. Voor u verder gaat met de beveiligde handeling.
                                </p>

                                <hr class="mt-0">

                                <div class="form-group">
                                    <label for="password">Password
                                        @if (Route::has('password.request'))
                                            <a class="float-right" href="{{ route('password.request') }}">
                                                {{ __('Forgot Your Password?') }}
                                            </a>
                                        @endif
                                    </label>

                                    <input id="password" type="password" class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                    @if ($errors->has('password'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>


                                <div class="form-group no-margin">
                                    <button type="submit" class="btn btn-secondary text-white btn-block">
                                        Bevestig wachtwoord
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="footer">
                        Copyright &copy; {{ config('app.name') }} {{ date('Y') }}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
