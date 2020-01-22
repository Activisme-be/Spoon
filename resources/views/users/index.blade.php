@extends('layouts.app', ['title' => 'Gebruikers'])

@section('content')
    @php
        /** @var \App\Models\User[]&Illuminate\Pagination\Paginator $users */
    @endphp

    <div class="container-fluid py-3">
        <div class="page-header">
            <h1 class="page-title">Gebruikers</h1>
            <div class="page-subtitle">beheerspaneel</div>

            <div class="page-options d-flex">
                <a href="{{ route('users.create') }}" class="btn btn-secondary mr-2">
                    <i class="fe fe-user-plus"></i>
                </a>

                <div class="btn-group">
                    <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fe mr-1 fe-filter"></i> Filter
                    </button>

                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="{{ route('users.index') }}">Alle logins</a>
                        <a class="dropdown-item" href="{{ route('users.index', ['filter' => 'actief']) }}">Actieve logins</a>
                        <a class="dropdown-item" href="{{ route('users.index', ['filter' => 'gedeactiveerd']) }}">Non-actieve logins</a>
                    </div>
                </div>

                <form method="GET" action="{{ route('users.search') }}" class="border-0 shadow-sm form-search form-inline ml-2">
                    <div class="form-group has-search">
                        <label for="search" class="sr-only">Zoek Gebruiker</label>
                        <span class="fe fe-search form-control-feedback"></span>
                        <input id="search" type="text" name="term" value="{{ request()->get('term') }}" placeholder="Zoeken" class="form-search border-0 form-control">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="container-fluid pb-3">
        <div class="card border-0 shadow-sm card-body">
            @include ('flash::message') {{-- Flash session view partial --}}

            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead>
                        <tr>
                            <th scope="col" class="border-top-0">#</th>
                            <th scope="col" class="border-top-0">Naam</th>
                            <th scope="col" class="border-top-0">Status</th>
                            <th scope="col" class="border-top-0">Email</th>
                            <th scope="col" class="border-top-0">Laatst aangemeld</th>
                            <th scope="col" class="border-top-0">&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user) {{-- Loop through the users --}}
                            <tr>
                                <td><strong>#{{ $user->id }}</strong></td>
                                <td>{{ $user->name }}</td>

                                <td> {{-- Status  indicator --}}
                                    @if ($user->isBanned()) {{-- The login is non active in the application --}}
                                        <span class="badge badge-deactivated"><i class="fe fe-lock mr-1"></i> non-actief</span>
                                    @else {{-- The user is active in the application --}}
                                        @if ($user->isOnline())
                                            <span class="badge badge-online">Online</span>
                                        @else
                                            <span class="badge badge-offline">Offline</span>
                                        @endif
                                    @endif
                                </td> {{-- // End status indicator --}}

                                <td><a href="mailto:{{ $user->email }}">{{ $user->email }}</a></td>
                                <td>{{ $user->last_login_at ? $user->last_login_at->diffForHumans() : '-' }}</td>

                                <td> {{-- Options --}}
                                    <span class="float-right">
                                        <a href="{{ route('users.show', $user) }}" class="mr-1 text-decoration-none text-secondary">
                                            <i class="fe fe-eye"></i>
                                        </a>

                                        @if ($user->isNotBanned()) {{-- The user is actually locked --}}
                                            <a href="{{ route('users.lock', $user) }}" class="text-decoration-none mr-1 text-danger @if ($currentUser->cannot('deactivate-user', $user)) disabled @endif">
                                                <i class="fe fe-lock"></i>
                                            </a>
                                        @elseif ($user->isBanned()) {{-- The user is locked in the application --}}
                                            <a href="{{ route('users.unlock', $user) }}" class="mr-1 @if ($currentUser->cannot('activate-user', $user)) disabled @endif text-decoration-none text-success">
                                                <i class="fe fe-unlock"></i>
                                            </a>
                                        @endif {{-- /// END lock check --}}

                                        <a href="{{ route('users.destroy', $user) }}" class="mr-1 text-decoration-none text-danger">
                                            <i class="fe fe-trash-2"></i>
                                        </a>
                                    </span>
                                </td> {{-- /// Options --}}
                            </tr>
                        @empty {{-- There are no users found with the matching criteria --}}
                            <tr>
                                <td colspan="6">
                                    <span class="text-secondary">
                                        @switch ($requestType)
                                            @case('search')         De zoekopdracht heeft geen resultaten opgeleverd.                   @break
                                            @case('actief')         Er zijn geen actieve gebruikers gevonden in de applicatie.          @break
                                            @case('gedeactiveerd')  Er zijn geen gedeactiveerde gebruikers gevonden in de applicatie.   @break
                                            @default                Er zijn geen gebruikers in de applicatie gevonden.
                                        @endswitch
                                    </span>
                                </td>
                            </tr>
                        @endforelse {{-- /// END users loop --}}
                    </tbody>
                </table>
            </div>

            {{ $users->links() }} {{-- Pagination view instance --}}
        </div>
    </div>
@endsection
