@extends('layouts.auth')

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
                            <h4 class="card-title">Login</h4>
                            <form method="POST" action="{{ route('login') }}">
                                @csrf {{-- Form field protection --}}

                                <div class="form-group">
                                    <label for="email">E-Mail Address</label>

                                    <input id="email" type="email" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="" required autofocus>

                                    @if ($errors->has('email'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label for="password">Password
                                        <a href="{{ route('password.request') }}" class="float-right">
                                            Forgot Password?
                                        </a>
                                    </label>

                                    <input id="password" type="password" class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                    @if ($errors->has('password'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label>
                                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
                                    </label>
                                </div>

                                <div class="form-group no-margin">
                                    <button type="submit" class="btn btn-primary btn-block">
                                        Login
                                    </button>
                                </div>

                                @if (Route::has('register'))
                                    <div class="margin-top20 text-center">
                                        Don't have an account? <a href="{{ route('register') }}">Create One</a>
                                    </div>
                                @endif
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