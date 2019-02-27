<div class="card mb-3">
    <div class="card-header">
        <i class="fe mr-1 fe-menu"></i> Instellingen
    </div>

    <div class="list-group list-group-flush">
        <a href="{{ route('account.settings') }}" class="list-group-item list-group-item-action">
            <i class="text-secondary fe fe-info mr-1"></i> Account informatie
        </a>

        <a href="{{ route('account.settings', ['type' => 'beveiliging']) }}" class="list-group-item list-group-item-action">
            <i class="text-secondary fe fe-shield mr-1"></i> Account beveiliging
        </a>
    </div>
</div>

<div class="list-group">
    <a href="{{ route('users.destroy', $currentUser) }}" class="list-group-item list-group-item-danger list-group-item-action">
        <i class="fe fe-user-x mr-1"></i> Verwijder account
    </a>
</div>