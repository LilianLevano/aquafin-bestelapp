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

    <table class="manager-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Geplaatst door</th>
                <th>Items</th>
                <th>Leverplaats</th>
                <th>Status</th>
                <th>Datum</th>
            </tr>
        </thead>

        <tbody>
            <tr>
                <td>1</td>
                <td>Technieker Jan</td>
                <td>Pomp, Buis</td>
                <td>Brussel Noord</td>
                <td>Open</td>
                <td>12/06/2026</td>
            </tr>

            <tr>
                <td>2</td>
                <td>Technieker Sara</td>
                <td>Kabel</td>
                <td>Antwerpen Zuid</td>
                <td>Opgelost</td>
                <td>15/06/2026</td>
            </tr>

            <tr>
                <td>3</td>
                <td>Technieker Ali</td>
                <td>Filter</td>
                <td>Gent</td>
                <td>Open</td>
                <td>18/06/2026</td>
            </tr>
        </tbody>
    </table>

    <p class="empty-message">Geen data om te tonen</p>
@endsection

@push('scripts')
    @vite('resources/js/orders-index.js')
@endpush
