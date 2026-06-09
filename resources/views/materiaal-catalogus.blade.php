@extends('layouts.app')

@section('content')
    <h2 class="mb-4"> Materiaal Catalogus</h2>

    <div class="row">

        <!-- Product 1 -->
        <div class="col-md-4 mb-4">
            <div class="card shadow h-100">
                <div class="card-body">
                    <h5 class="card-title">🔧 Veiligheidshelm</h5>
                    <p class="card-text">Beschermhelm voor op de werf.</p>
                    <span class="badge bg-success">Beschikbaar</span>
                </div>
                <div class="card-footer">
                    <a href="#" class="btn btn-primary w-100">Bestellen</a>
                </div>
            </div>
        </div>

        <!-- Product 2 -->
        <div class="col-md-4 mb-4">
            <div class="card shadow h-100">
                <div class="card-body">
                    <h5 class="card-title"> Veiligheidsvest</h5>
                    <p class="card-text">Fluoriserend vest voor zichtbaarheid.</p>
                    <span class="badge bg-success">Beschikbaar</span>
                </div>
                <div class="card-footer">
                    <a href="#" class="btn btn-primary w-100">Bestellen</a>
                </div>
            </div>
        </div>

        <!-- Product 3 -->
        <div class="col-md-4 mb-4">
            <div class="card shadow h-100">
                <div class="card-body">
                    <h5 class="card-title"> Werkhandschoenen</h5>
                    <p class="card-text">Stevige handschoenen voor zwaar werk.</p>
                    <span class="badge bg-warning text-dark">Beperkte voorraad</span>
                </div>
                <div class="card-footer">
                    <a href="#" class="btn btn-primary w-100">Bestellen</a>
                </div>
            </div>
        </div>

        <!-- Product 4 -->
        <div class="col-md-4 mb-4">
            <div class="card shadow h-100">
                <div class="card-body">
                    <h5 class="card-title"> Veiligheidsbril</h5>
                    <p class="card-text">Beschermt de ogen tegen spatten.</p>
                    <span class="badge bg-success">Beschikbaar</span>
                </div>
                <div class="card-footer">
                    <a href="#" class="btn btn-primary w-100">Bestellen</a>
                </div>
            </div>
        </div>

        <!-- Product 5 -->
        <div class="col-md-4 mb-4">
            <div class="card shadow h-100">
                <div class="card-body">
                    <h5 class="card-title"> Veiligheidsschoenen</h5>
                    <p class="card-text">Stevige schoenen met stalen neus.</p>
                    <span class="badge bg-danger">Niet beschikbaar</span>
                </div>
                <div class="card-footer">
                    <a href="#" class="btn btn-primary w-100 disabled">Bestellens
@endsection
