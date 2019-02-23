@extends('layouts.app')

@section('content')
    <div class="container-fluid py-3">
        <div class="page-header">
            <h1 class="page-title">Gebruikers</h1>
            <div class="page-subtitle">beheerspaneel</div>

            <div class="page-options d-flex">
                <a href="" class="btn btn-secondary mr-2">
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

                <form method="GET" action="" class="w-100 ml-2">
                    <input type="text" class="form-control" placeholder="Zoeken">
                </form>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="card card-body">
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
                                </td> {{-- // End status indicator --}}

                                <td><a href="mailto:{{ $user->email }}">{{ $user->email }}</a></td>
                                <td></td>

                                <td> {{-- Options --}}
                                    <span class="float-right">
                                        <a href="" class="mr-1 text-decoration-none text-secondary">
                                            <i class="fe fe-eye"></i>
                                        </a>

                                        <a href="" class="mr-1 text-decoration-none text-danger">
                                            <i class="fe fe-trash-2"></i>
                                        </a>
                                    </span>
                                </td> {{-- /// Options --}}
                            </tr>
                        @empty {{-- There are no users found with the matching criteria --}}
                        @endforelse {{-- /// END users loop --}}
                    </tbody>
                </table>
            </div>

            {{ $users->links() }} {{-- Pagination view instance --}}
        </div>
    </div>
@endsection