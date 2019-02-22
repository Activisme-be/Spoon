@extends('layouts.app')

@section('content')
<div class="container-fluid py-3">
    <div class="alert alert-success" role="alert">
        <span class="font-weight-bold mr-2"><i class="fe fe-info"></i> Success:</span>
        U bent nu ingelogd in {{ config('app.name', 'Laravel') }}. - Start met het bouwen van je applicatie
    </div>
</div>
@endsection
