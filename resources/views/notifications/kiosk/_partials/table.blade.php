@php
    /** @var \Illuminate\Notifications\DatabaseNotification[]&\Illuminate\Pagination\Paginator $notifications */
@endphp

<div class="table-responsive">
    <table class="table table-hover table-sm mb-2">
        <thead>
            <tr>
                <th class="border-top-0" scope="row">Verzonden door</th>
                <th class="border-top-0" scope="row">Verzend methode</th>
                <th class="border-top-0" scope="row">Titel</th>
                <th class="border-top-0" scope="row">Datum</th>
                <th class="border-top-0" scope="row">&nbsp;</th> {{-- Column specified for the method shortcuts --}}
            </tr>
        </thead>
        <tbody>
            @forelse ($notifications as $notification) {{-- Loop trough the system notifications --}}
                <tr>
                    <td class="font-weight-bold">
                        <a href="{{ route('users.show', $notification->creator) }}" class="text-decoration-none text-dark">
                            {{ $notification->creator->name }}
                        </a>
                    </td>

                    <td>
                        <span class="badge badge-info text-white">
                            {{ ($notification->driver === 'database') ? 'Applicatie' : ucfirst($notification->driver) }}
                        </span>
                    </td>
                    <td> {{ ucfirst($notification->title) }}</td>
                    <td>{{ $notification->created_at->format('d-m-Y h:i:s') }}</td>

                    <td>
                        <span class="float-right">
                            <a href="{{ route('alerts.show', $notification) }}" class="text-decoration-none text-secondary">
                                <i class="fe fe-eye"></i>
                            </a>
                        </span>
                    </td>
                </tr>
            @empty {{-- No notification are found --}}
                <tr>
                    <td colspan="5" class="text-secondary">Er zijn geen systeem notificaties gevonden.</td>
                </tr>
            @endforelse {{-- /// END notification loop --}}
        </tbody>
    </table>
</div>
