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

    <form action="{{route('orders.store')}}" method="POST">
        @csrf

        <table class="manager-table">
            <button class="btn-primary">Plaats</button>


            <div class="max-w-[550px] flex flex-col items-center gap-2 mx-auto my-4">
                <input type="date" id="delivery_date" name="delivery_date" required>

                <select name="site_id" id="site_id">
                    @foreach($sites as $site)
                        <option value="{{$site->id}}" @selected($site->id == auth()->user()->site->id)>{{$site->description}} </option>
                    @endforeach
                </select>
            </div>



            <thead>
            <tr>
                <th class="extra-information" >ID</th>
                <th>Materiaal</th>
                <th class="extra-information">Categorie</th>
                <th>Quantity</th>
                <th>Actie</th>
            </tr>
            </thead>

            <tbody>
                @foreach($materials as $material)
                    <tr>
                        <td class="extra-information">{{$material->id}}</td>
                        <td ><a href="{{route('materials.show', $material->id)}}">{{$material->name}}</a> </td>
                        <td class="extra-information">{{$material->category->name}}</td>
                        <td><input class="quantity" type="number" value="0" min="0" name="quantity[{{ $material->id }}]"></td>
                        <td><input class="checkbox-materials" type="checkbox" name="materials[]" value="{{$material->id}}"></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </form>

    <div class="center-button">
        <button class="btn-primary">Toon alles</button>
    </div>
@endsection
