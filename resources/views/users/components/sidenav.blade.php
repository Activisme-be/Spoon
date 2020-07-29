@php
    /** @var \App\Domain\Auth\Models\User $currentUser */
    /** @var \App\Domain\Auth\Models\User $user */
@endphp

<div class="list-group mb-3 list-group-transparent">
    <a href="{{ route('users.show', $user) }}" class="list-group-item {{ active('users.show') }} list-group-item-action">
        <i class="fe fe-info text-secondary mr-1"></i> Algemene informatie
    </a>

    <a href="{{ route('users.activity', $user) }}" class="list-group-item {{ active('users.activity') }} list-group-item-action">
        <i class="fe fe-activity mr-1 text-secondary"></i> Activiteiten
    </a>

    <a href="mailto:{{ $user->email }}" class="list-group-item list-group-item-action">
        <i class="fe fe-mail text-secondary mr-2"></i> E-mail persoon
    </a>

    @if ($currentUser->can('impersonate', [$user]))
        <a href="{{ route('users.impersonate', $user) }}" class="list-group-item list-group-item-action">
            <i class="fe fe-log-in text-secondary mr-2"></i> Impersoneer gebruiker
        </a>
    @endif

    @if ($user->isBanned() && $currentUser->can('activate-user', $user))
        <a href="{{ route('users.unlock', $user) }}" class="list-group-item list-group-item-action">
            <i class="fe fe-unlock text-secondary mr-2"></i> Actieveer login
        </a>
    @elseif ($currentUser->can('deactivate-user', $user)) {{-- User is not banned --}}
        <a href="{{ route('users.lock', $user) }}" class="list-group-item {{ active('users.lock') }} list-group-item-action">
            <i class="fe fe-lock text-secondary mr-2"></i> Blokkeer login
        </a>
    @endif

    <a href="{{ route('users.destroy', $user) }}" class="list-group-item {{ active('users.destroy') }} list-group-item-action">
        <i class="fe fe-user-x text-danger mr-2"></i> Verwijder login
    </a>
</div>
