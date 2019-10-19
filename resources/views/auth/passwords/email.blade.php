@extends('layouts.auth', ['title' => 'Wachtwoord vergeten'])

@section('content')
    @php
        /** @var \Illuminate\Support\Collection $errors */
    @endphp

    <section class="h-100">
        <div class="container h-100">
            <div class="row justify-content-md-center h-100">
                <div class="card-wrapper">
                    <div class="brand">
                        <img src="{{ asset('img/logo.png') }}">
                    </div>
                    <div class="card fat">
                        <div class="card-body">
                            <h4 class="card-title">Forgot Password</h4>
                            <form method="POST" action="{{ route('password.email') }}">
                                @csrf {{-- Form field protection --}}

                                @if (session('status'))
                                    <div class="alert alert-success">
                                        <small>{{ session('status') }}</small>
                                    </div>
                                @endif

                                <div class="form-group">
                                    <label for="email">E-Mail Address</label>
                                    <input id="email" type="email" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="" required autofocus>

                                    @if ($errors->has('email'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @else
                                        <div class="form-text text-muted">
                                            <small>By clicking "Reset Password" we will send a password reset link</small>
                                        </div>
                                    @endif
                                </div>

                                <div class="form-group no-margin">
                                    <button type="submit" class="btn btn-primary btn-block">
                                        Reset Password
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
