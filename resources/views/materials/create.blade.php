@extends('layouts.app')

@section('content')
    <div class="form-card">
        <h1>Materiaal aanmaken</h1>

        <div style="text-align:left; margin-bottom:20px;">
            <a href="{{ route('admin.materials.index') }}" class="btn-primary">← Keer terug</a>
        </div>

        <form onsubmit="disableForm(event)" action="{{route('admin.materials.store')}}" method="POST" >
            @csrf
            <label>Naam</label>
            <input type="text"
                    id="name"
                    name="name"
                    class="text-field"
                    placeholder="Typ materiaal naam"
                    onblur="validateNaam()"
                    required>
            <p id="naamError" style="display:none; color:red; font-size:14px;">
                Materiaalnaam moet minstens 3 tekens bevatten.
            </p>

            <label>Categorie</label>
            <select id="category_id" name="category_id" class="text-field" onblur="validateCategorie()" required>
                @foreach($categories as $category)
                    <option value="{{$category->id}}">{{$category->name}}</option>

                @endforeach
            </select>
            <p id="categorieError" style="display:none; color:red; font-size:14px;">
                Kies een geldige categorie.
            </p>

            <div class="answer-button">
                <button id="submitBtn" style="margin-top: 10px;" type="submit" class="btn-primary">
                    Aanmaken
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    @vite('resources/js/materials-create.js')
@endpush
