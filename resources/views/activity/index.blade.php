@extends('layouts.app', ['title' => 'Audit'])

@section('content')
    @php
        /** @var \App\Models\User $currentUser */
        /** @var \Spatie\Activitylog\Models\Activity[]&\Illuminate\Pagination\Paginator $logs */
    @endphp

    <div class="container-fluid py-3">
        <div class="page-header">
            <h1 class="page-title">Activiteit audit</h1>
            <div class="page-subtitle">Overzicht</div>

            <div class="page-options d-flex">
                @if ($currentUser->hasRole('webmaster'))
                    <div class="btn-group">
                        <button type="button" class="btn btn-secondary shadow-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fe mr-1 fe-download"></i> Export
                        </button>

                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="{{ route('audit.export', ['filter' => '3-months'])}}">Afgelopen 3 maanden</a>
                            <a class="dropdown-item" href="{{ route('audit.export', ['filter' => '6-months']) }}">Afgelopen 6 maanden</a>
                            <a class="dropdown-item" href="{{ route('audit.export', ['filter' => 'recent-year']) }}">Afgelopen jaar</a>
                            <a class="dropdown-item" href="{{ route('audit.export') }}">Alle logs</a>
                        </div>
                    </div>
                @endif

                <form method="GET" action="{{ route('audit.search') }}" class="border-0 shadow-sm form-search form-inline ml-2">
                    <div class="form-group has-search">
                        <span class="fe fe-search form-control-feedback"></span>
                        <input type="text" name="term" value="{{ request()->get('term') }}" placeholder="Zoeken" class="form-search border-0 form-control">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="container-fluid pb-3">
        <div class="card card-body border-0 shadow-sm">
            @include ('activity._partials.table', ['logs' => $logs])
            {{ $logs->links() }} {{-- Pagination view instance --}}
        </div>
    </div>
@endsection
