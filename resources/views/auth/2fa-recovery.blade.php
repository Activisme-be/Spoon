@extends ('layouts.auth', ['title' => 'Reset 2FA'])

@section('content')
    @php
        /** @var \Illuminate\Support\Collection $errors */
    @endphp

    <section class="h-100">
        <div class="container h-100">
            <div class="row justify-content-md-center h-100">
                <div class="card-wrapper">
                    <div class="brand tw-shadow">
                        <img src="{{ asset('img/logo.png') }}">
                    </div>
                    <div class="card fat">
                        <div class="card-body tw-shadow">
                            <h4 class="card-title">Reset 2FA</h4>
                            <form method="POST" action="{{ route('recovery.2fa.request') }}">
                                @csrf {{-- Form field protection --}}

                                @if (session()->has('status'))
                                    <div class="alert alert-info border-0 small" role="alert">
                                        {{ session()->get('status') }}
                                    </div>
                                @endif

                                <div class="form-group">
                                    <label for="password">Uw recovery token</label>

                                    <input id="password" type="password" class="form-control {{ $errors->has('recovery_token') ? ' is-invalid' : '' }}" name="recovery_token" value="" required autofocus>

                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('recovery_token') }}</strong>
                                    </span>
                                </div>

                                <div class="form-group no-margin">
                                    <button type="submit" class="btn btn-primary btn-block">
                                        Reset
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
