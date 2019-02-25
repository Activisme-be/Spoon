<div class="card mb-3">
    <div class="card-header">
        <i class="fe fe-list mr-2"></i> Gegevens
    </div>

    <div class="list-group list-group-flush">
        <a href="{{ route('users.show', $user) }}" class="list-group-item list-group-item-action">
            <i class="fe fe-info text-secondary mr-1"></i> Algemene informatie
        </a>

        <a href="{{ route('user.activity', $user) }}" class="list-group-item list-group-item-action">
            <i class="fe fe-activity mr-1 text-secondary"></i> Activiteiten
        </a>
    </div>
</div>

<div class="card mb-3">
    <div class="card-header">
        <i class="fe fe-list mr-2"></i> Opties
    </div>

    <div class="list-group list-group-flush">
        <a href="mailto: {{ $user->email }}" class="list-group-item list-group-item-action">
            <i class="fe fe-mail text-secondary mr-2"></i> E-mail persoon
        </a>

        @if ($user->isBanned() && $currentUser->can('activate-user', $user))
            <a href="{{ route('users.unlock', $user) }}" class="list-group-item list-group-item-action">
                <i class="fe fe-unlock text-secondary mr-2"></i> Actieveer login
            </a>
        @elseif ($currentUser->can('deactivate-user', $user)) {{-- User is not banned --}}
            <a href="{{ route('users.lock', $user) }}" class="list-group-item list-group-item-action">
                <i class="fe fe-lock text-secondary mr-2"></i> Blokkeer login
            </a>
        @endif

        <a href="" class="list-group-item list-group-item-action">
            <i class="fe fe-user-x text-danger mr-2"></i> Verwijder login
        </a>
    </div>
</div>