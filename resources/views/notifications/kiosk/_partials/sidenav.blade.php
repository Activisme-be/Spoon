<div class="list-group list-group-transparent">
    <a href="{{ route('alerts.index') }}" class="{{ active('alerts.index') }} list-group-item list-group-item-action">
        <i class="fe fe-bell text-muted mr-2"></i> Notificatie verzenden
    </a>

    <a href="{{ route('alerts.overview') }}" class="{{ active('alerts.overview') }} list-group-item list-group-item-action d-flex justify-content-between align-items-center">
        <span class="float-left"><i class="fe fe-list text-muted mr-2"></i> Verzonden notificaties</span>
        <span class="badge badge-primary badge-pill">{{ $notifications_count }}</span>
    </a>
</div>
