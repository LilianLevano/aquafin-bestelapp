@extends('layouts.app')
@section('title', 'Mijn Bestellingen')

@section('content')
    <h1>Bestellingen Overzicht</h1>


    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h1 mb-0">Mijn Bestellingen</h1>
        <a href="{{ route('orders.create') }}" class="btn btn-primary">
            + Nieuwe bestelling
        </a>
    </div>
    <div class="filter-zone">
        <div class="filter-item">
            <label>Zoeken</label>
            <input type="text" placeholder="Zoek op woord...">
        </div>

        <div class="filter-item">
            <label>Datum</label>
            <input type="date">
        </div>

        <div class="filter-item">
            <label>Regio</label>
            <select>
                <option>Alle regio's</option>
                <option>Brussel</option>
                <option>Antwerpen</option>
                <option>Gent</option>
                <option>Leuven</option>
            </select>
        </div>

        <button class="btn-primary">Filter</button>
    </div>
    <table class="manager-table">
        <thead>
            <tr>
                <th>ID</th>
                <th class="col-geplaatst">Geplaatst door</th>

                <th>Leverplaats</th>
                <th>Leverdatum</th>
                <th>Status</th>
            </tr>
        </thead>


        <tbody>


        @foreach($orders as $order)
            <tr>
                <td>{{$order->id}}</td>
                <td class="col-geplaatst">{{$order->user->first_name . ' ' . $order->user->last_name  }}</td>

                <td>{{$order->site->description}}</td>
                <td>{{$order->delivery_date}}</td>
                <td>{{ \Carbon\Carbon::parse($order->delivery_date)->isPast() ? 'Geleverd' : 'Aan het leveren' }}</td>
            </tr>

        @endforeach

        </tbody>
    </table>

    <p class="empty-message">Geen data om te tonen.</p>
@endsection

@push('scripts')
    @vite('resources/js/orders-index.js')
@endpush
