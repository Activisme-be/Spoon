@extends ('layouts.app')

@section('content')
    <div class="container-fluid py-3">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="text-danger pb-1 mb-3"><i class="fe mr-1 fe-alert-triangle"></i> Ongeauthoriseerde handeling</h5>

                        <p class="card-text">
                            Helaas hebt u geen juiste machtingen voor het uitvoeren van de handeling in {{ config('app.name') }}. <br>
                            Indien u denkt dat dit een fout in de applicatie is kunt u contact opnemen met de webmaster. Zodat
                            hij het kan oplossen of kan nakijken voor jouw.
                        </p>

                        <hr class="mt-0">

                        <a href="{{ route('home') }}" class="card-link">
                            <i class="fe fe-chevrons-left mr-1"></i>Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection