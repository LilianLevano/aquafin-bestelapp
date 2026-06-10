@extends('layouts.app')

@section('content')
    <div class="form-card">
        <h1>Materiaal aanmaken</h1>

        <div style="text-align:left; margin-bottom:20px;">
            <a href="{{ route('categories') }}" class="btn-primary">← Keer terug</a>
        </div>

        <form onsubmit="disableForm(event)">
            <label>Naam</label>
            <input type="text"
                    id="materiaalNaam"
                    class="text-field"
                    placeholder="Typ materiaal naam"
                    onblur="validateNaam()"
                    required>
            <p id="naamError" style="display:none; color:red; font-size:14px;">
                Materiaalnaam moet minstens 3 tekens bevatten.
            </p>

            <label>Categorie</label>
            <select id="materiaalCategorie" class="text-field" onblur="validateCategorie()" required>
                <option value="">Kies een categorie</option>
                <option>Watermateriaal</option>
                <option>Installatie</option>
                <option>Elektriciteit</option>
            </select>
            <p id="categorieError" style="display:none; color:red; font-size:14px;">
                Kies een geldige categorie.
            </p>

            <div class="answer-button">
                <button id="submitBtn" type="submit" class="btn-primary">
                    Aanmaken
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    @vite('resources/js/materials-create.js')
@endpush
