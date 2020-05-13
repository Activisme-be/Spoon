@php
    /** @var \App\Models\User $currentUser */
@endphp

<div class="list-group list-group-transparent">
    <a href="{{ route('account.settings') }}" class="{{ active('account.settings') }} list-group-item list-group-item-action">
        <i class="text-secondary fe fe-info mr-1"></i> Account informatie
    </a>

    <a href="{{ route('account.security') }}" class="{{ active('account.security') }} list-group-item list-group-item-action">
        <i class="text-secondary fe fe-shield mr-1"></i> Account beveiliging
    </a>

    <a href="{{ route('users.destroy', $currentUser) }}" class="{{ active('users.destroy') }} list-group-item list-group-item-action">
        <i class="fe fe-user-x mr-1"></i> Verwijder account
    </a>
</div>
