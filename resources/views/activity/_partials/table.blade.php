@php
    /** @var \Spatie\Activitylog\Models\Activity[]&\Illuminate\Pagination\Paginator $logs */
@endphp

<div class="table-responsive">
    <table class="table table-sm mb-0 table-hover">
        <thead>
            <tr>
                <th class="border-top-0" scope="col">Persoon</th>
                <th class="border-top-0" scope="col">Categorie</th>
                <th class="border-top-0" scope="col">Handeling</th>
                    <th class="border-top-0" scope="col">Datum</th>
            </tr>
        </thead>
        <tbody>
                @forelse ($logs as $log) {{-- Loop trough the application logs --}}
                    <tr>
                        <td class="font-weight-bold">
                            <a href="{{ route('users.show', $log->causer) }}" class="text-secondary text-decoration-none">
                                {{ ucfirst($log->causer->name) ?? 'Verwijderde gebruiker' }}
                            </a>
                        </td>
                        <td>{{ ucfirst($log->log_name) }}</td>
                        <td>{{ ucfirst($log->description) }}</td>
                        <td>{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                    </tr>
                @empty {{-- There are no logs found in the application --}}
                    <tr>
                        <td class="text-secondary" colspan="4">
                            Er zijn nog geen applicatie logs van {{ config('app.name') }}
                        </td>
                    </tr>
                @endforelse {{-- /// END application logs loop --}}
        </tbody>
    </table>
</div>
