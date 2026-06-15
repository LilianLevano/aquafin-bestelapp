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

    <div class="mb">
        <input type="text" id="search-materials" placeholder="Zoek materiaal..." autocomplete="off"
               style="margin-bottom: 0; padding: .5rem; width: 100%; position: relative;">
        <ul id="search-suggestions" style=" list-style: none; margin-bottom: 10px; padding: 0; border: 1px solid #ccc; border-top: none; position: absolute; background: white; width: 40%; z-index: 100; display: none; "></ul>
    </div>

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

            <tbody id="materials-tbody">
                @foreach($materials as $material)
                    <tr data-id="{{ $material->id }}" data-name="{{ $material->name }}">
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

@push('scripts')
    @vite('resources/js/orders-create.js')
@endpush
