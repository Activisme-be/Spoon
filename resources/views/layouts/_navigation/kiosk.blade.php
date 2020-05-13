@php
    /** @var \App\Models\User $currentUser */
@endphp

<a class="nav-link {{ active('kiosk.dashboard') }}" href="{{ route('kiosk.dashboard') }}">
    <i class="fe fe-home mr-1 icon-subnav"></i> Dashboard
</a>

@if ($currentUser->hasAnyRole(['admin', 'webmaster']))
    <a class="nav-link {{ active('users.*') }}" href="{{ route('users.index') }}">
        <i class="fe fe-users mr-1 icon-subnav"></i> Gebruikers
    </a>

    <a class="nav-link {{ active('audit.*') }}" href="{{ route('audit.overview') }}">
        <i class="fe fe-activity mr-1 icon-subnav"></i> Audit
    </a>
@endif

@if ($currentUser->hasRole('webmaster'))
    <a class="nav-link {{ active('alerts.*') }}" href="{{ route('alerts.index') }}">
        <i class="fe fe-bell mr-1 icon-subnav"></i> Alerts
    </a>
@endif
