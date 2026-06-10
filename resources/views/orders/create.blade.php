@extends('layouts.app')

@section('content')
    <h1>Bestelling plaatsen</h1>

    <section class="filter-zone">
        <div class="filter-item">
            <label>Filter</label>
            <select>
                <option>Alle categorieën</option>
                <option>Pompen</option>
                <option>Buizen</option>
            </select>
        </div>

        <div class="filter-item">
            <label>Seizoen</label>
            <select>
                <option>Winter</option>
                <option>Herfst</option>
                <option>Zomer</option>
                <option>Lente</option>
            </select>
        </div>

        <button class="btn-primary">Zoeken</button>
    </section>

    <form action="{{route('orders.create')}}" method="POST">
        @csrf

        <table class="manager-table">
            <button class="btn-primary">Plaats</button>
            <input type="date" id="delivery_date" name="delivery_date" required>

            <select name="site_id" id="site_id">
                @foreach($sites as $site)
                    <option value="{{$site->id}}" @selected($site->id == auth()->user()->site->id)>{{$site->locatie}} </option>
                @endforeach
            </select>

            <thead>
            <tr>
                <th>ID</th>
                <th>Materiaal</th>
                <th>Categorie</th>
                <th>Quantity</th>
                <th>Actie</th>
            </tr>
            </thead>

            <tbody>
                @foreach($materialen as $materiaal)
                    <tr>
                        <td>{{$materiaal->id}}</td>
                        <td>{{$materiaal->name}}</td>
                        <td>{{$materiaal->category->name}}</td>
                        <td><input type="number" value="0" min="0" name="quantity[{{ $materiaal->id }}]"></td>
                        <td><input type="checkbox" name="materialen[]" value="{{$materiaal->id}}"></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </form>

    <div class="center-button">
        <button class="btn-primary">Toon alles</button>
    </div>
@endsection
