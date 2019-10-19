@php
    /** @var \App\Models\User $currentUser */
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title }} | {{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="shortcut icon" type="image/png" href="{{ asset('favicon.ico') }}"/>
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <img src="{{ asset('img/logo.png') }}" width="35" height="35" class="mr-3 rounded d-inline-block align-top" alt="{{ config('app.name', 'Laravel') }}">
            <a class="navbar-brand mr-auto mr-lg-0" href="#">
                {{ config('app.name', 'Laravel') }} {{ $currentUser->cannot('on-kiosk', auth()->user()) ? '' : ' - Kiosk' }}
            </a>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarsExampleDefault">
                <ul class="navbar-nav mr-auto">
                    &nbsp;
                </ul>

                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('notifications.index') }}">
                            <i class="fe fe-bell mr-1"></i>
                            <span style="margin-top: -.25rem;" class="badge badge-pill align-middle badge-danger">
                                {{ $currentUser->unreadNotifications()->count() }}
                            </span>
                        </a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="accountDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{ $currentUser->name }}
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="accountDropdown">
                            @if ($currentUser->hasAnyRole(['webmaster', 'admin']))
                                <h6 class="dropdown-header font-weight-bold">Portalen</h6>

                                @if ($currentUser->can('on-kiosk', auth()->user()))
                                    <a class="dropdown-item" href="{{ route('home') }}">
                                        <i class="fe fe-log-out mr-1 text-secondary"></i> Verlaat kiosk
                                    </a>
                                @else {{-- Authenticated user is on the kiosk management portal --}}
                                    <a class="dropdown-item" href="{{ route('kiosk.dashboard') }}">
                                        <i class="fe fe-home mr-1 text-secondary"></i> Kiosk
                                    </a>
                                @endif
                            @endif

                            <div class="dropdown-divider"></div>
                            <h6 class="dropdown-header font-weight-bold">Account</h6>

                            <a class="dropdown-item" href=" {{ route('account.settings') }}">
                                <i class="fe fe-sliders mr-1 text-secondary"></i> Instellingen
                            </a>

                            <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fe text-danger mr-1 fe-power"></i> Afmelden
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf {{-- Form field protection --}}
                            </form>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>

        <div class="nav-scroller bg-white shadow-sm">
            <nav class="nav nav-underline">
                @if ($currentUser->can('on-application', auth()->user()))
                    @include ('layouts._navigation.application')
                @elseif ($currentUser->can('on-kiosk', auth()->user()))
                    @include ('layouts._navigation.kiosk')
                @endif
            </nav>
        </div>

        <main role="main">
            @yield('content')
        </main>

        <footer class="footer">
            <div class="container-fluid">
                <span class="copyright">&copy; {{ date('Y') }}, {{ config('app.name') }}</span>

                <div class="float-right">
                    <span class="copyright">v1.0.0</span>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>
