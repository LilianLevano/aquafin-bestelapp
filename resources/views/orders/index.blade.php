@extends('layouts.app')

@section('content')
    <h1>Bestellingen Overzicht</h1>

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

    <button><a href="{{route('orders.create')}}">Plaats een nieuwe bestelling</a> </button>

    <table class="manager-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Geplaatst door</th>
                <th>Items</th>
                <th>Leverplaats</th>
                <th>Leverdatum</th>
                <th>Status</th>
            </tr>
        </thead>


        <tbody>


        @foreach($bestellingen as $bestelling)
            <tr>
                <td>{{$bestelling->id}}</td>
                <td>{{$bestelling->user->first_name . ' ' . $bestelling->user->last_name  }}</td>
                <td>
                    {{ $bestelling->material->take(3)->map(fn($m) => $m->name . ' (x' . $m->pivot->quantity . ')')->implode(', ') . ($bestelling->materiaal->count() > 3 ? ', ...' : '') }}
                </td>
                <td>{{$bestelling->site->locatie}}</td>
                <td>{{$bestelling->delivery_date}}</td>
                <td>{{ \Carbon\Carbon::parse($bestelling->delivery_date)->isPast() ? 'Geleverd' : 'Aan het leveren' }}</td>
            </tr>

        @endforeach

        </tbody>
    </table>

    <p class="empty-message">Geen data om te tonen.</p>
@endsection

@push('scripts')
    @vite('resources/js/orders-index.js')
@endpush
