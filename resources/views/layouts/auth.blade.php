<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8">
        <meta name="author" content="{{ config('app.name') }}">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name') }} - @yield('title') </title>

        {{-- Favicons --}}
        <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
        <link rel="icon" href="{{  asset('favicon.ico') }}" type="image/x-icon">

        {{-- Styles --}}
        <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
        <link rel="shortcut icon" type="image/png" href="{{ asset('img/favicon.ico') }}">
    </head>
    <body class="my-login-page">
        <div id="app">
            @yield('content') {{-- Page content --}}
        </div>

        {{-- Javascript --}}
        <script src="{{ asset('js/auth.js') }}"></script>
    </body>
</html>