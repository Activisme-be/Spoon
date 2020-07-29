@extends ('layouts.app', ['title' => 'Activiteiten log'])

@section('content')
    @php
        /** @var \App\Domain\Auth\Models\User $user */
        /** @var Spatie\Activitylog\Models\Activity[]&\Illuminate\Pagination\Paginator $activities */
    @endphp

    <div class="container-fluid py-3">
        <div class="page-header">
            <h1 class="page-title">Gebruikers</h1>
            <div class="page-subtitle">Activiteiten logboek voor {{ $user->name }}</div>

            <div class="page-options d-flex">
                <a href="{{ route('users.index') }}" class="btn btn-secondary">
                    <i class="fe fe-list mr-1"></i> Overzicht
                </a>
            </div>
        </div>
    </div>

    <div class="container-fluid pb-3">
        <div class="row">
            <div class="col-3"> {{-- Sidebar --}}
                @include ('users.components.sidenav', ['user' => $user])
            </div> {{-- /// END sidebar --}}

            <div class="col-9"> {{-- Content --}}
                <div class="card shadow-sm border-0 card-body">
                    <h6 class="border-bottom border-gray pb-1 mb-3">Activiteiten logboek van <strong>{{ $user->name }}</strong></h6>

                    <div class="table-responsive">
                        <table class="table @if (count($activities) > 0) table-hover @endif mb-0 table-sm">
                            <thead>
                                <tr>
                                    <th scope="col" class="border-top-0">Categorie</th>
                                    <th scope="col" class="border-top-0">Handeling</th>
                                    <th scope="col" class="border-top-0">Datum</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($activities as $activity) {{-- Loop trough the activities --}}
                                    <tr>
                                        <td>{{ $activity->log_name }}</td>
                                        <td>{{ $activity->description }}</td>
                                        <td>{{ $activity->created_at->diffForHumans() }}</td>
                                    </tr>
                                @empty {{-- There are no activities found --}}
                                    <tr>
                                        <td colspan="3">
                                            <span class="text-secondary">Er zijn geen gelogde activiteiten voor {{ $user->name }} momenteel.</span>
                                        </td>
                                    </tr>
                                @endforelse {{-- /// END activity loop --}}
                            </tbody>
                        </table>
                    </div>

                    {{ $activities->links() }} {{-- Pagination view instance --}}
                </div>
            </div> {{-- /// End content --}}
        </div>
    </div>
@endsection
