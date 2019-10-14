@extends('layouts.auth', ['title' => 'register'])

@section('content')
    <section class="h-100">
        <div class="container h-100">
            <div class="row justify-content-md-center h-100">
                <div class="card-wrapper">
                    <div class="brand tw-shadow">
                        <img src="{{ asset('img/logo.png') }}">
                    </div>
                    <div class="card fat">
                        <div class="card-body tw-shadow">
                            <h4 class="card-title">Register</h4>
                            <form method="POST" action="{{ route('register') }}">
                                @csrf {{-- Form field protection --}}

                                <div class="form-row">
                                    <div class="form-group col-12">
                                        <label for="name">{{ __('Firstname') }}</label>
                                        <input id="name" type="text" class="form-control @error('voornaam', 'is-invalid')" required autocomplete="name" autofocus @input('voornaam')>
                                        @error('voornaam')
                                    </div>

                                    <div class="form-group col-12">
                                        <label for="name">{{ __('Lastname') }}</label>
                                        <input id="name" type="text" class="form-control @error('achternaam', 'is-invalid')" required autocomplete="name" autofocus @input('achternaam')>
                                        @error('achternaam')
                                    </div>

                                    <div class="form-group col-12">
                                        <label for="email">{{ __('E-Mail Address') }}</label>
                                        <input id="email" type="email" class="form-control @error('email', 'is-invalid')" name="email" value="{{ old('email') }}" required autocomplete="email">
                                        @error('email')
                                    </div>

                                    <div class="form-group col-12">
                                        <label for="password">{{ __('Password') }}</label>
                                        <input id="password" type="password" class="form-control @error('password', 'is-invalid')" @input('password') required autocomplete="new-password">
                                        @error('password')
                                    </div>

                                    <div class="form-group col-12">
                                        <label for="password-confirm">{{ __('Confirm Password') }}</label>
                                        <input id="password-confirm" type="password" class="form-control"@input('password_confirmation') required autocomplete="new-password">
                                    </div>
                                </div>

                                <div class="form-group no-margin">
                                    <button type="submit" class="btn btn-primary btn-block">
                                        Register
                                    </button>

                                    <div class="margin-top20 text-center">
                                        Already have an account? <a href="{{ route('login') }}">Login</a>
                                    </div>
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
