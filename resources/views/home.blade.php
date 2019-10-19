@extends('layouts.app', ['title' => 'Dashboard'])

@section('content')
    @php
        /** @var \App\Models\User $currentUser */
    @endphp

    <div class="container-fluid py-3">
        <div class="page-header">
            <div class="page-title">{{ config('app.name') }}</div>
            <div class="page-subtitle">Dashboard</div>

            @if ($currentUser->hasAnyRole(['admin', 'webmaster']))
                <div class="page-options d-flex">
                    <a href="{{ route('kiosk.dashboard') }}" class="btn btn-secondary border-0 shadow-sm">
                        <i class="fe fe-corner-up-right mr-2"></i> Naar kiosk
                    </a>
                </div>
            @endif
        </div>
    </div>

    <div class="container-fluid pb-3">
        <div class="alert alert-success" role="alert">
            <span class="font-weight-bold mr-2"><i class="fe fe-info"></i> Success:</span>
            U bent nu ingelogd in {{ config('app.name', 'Laravel') }}. - Start met het bouwen van je applicatie
        </div>
    </div>
@endsection
