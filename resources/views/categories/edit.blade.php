@extends('layouts.app')

@section('content')
    <div class="form-card">
        <h1>Category edit</h1>

        <div class="form-row">
            <label>Naam</label>
            <input type="text" class="text-field" placeholder="Pomp">
        </div>

        <div class="form-row">
            <label>Categorie</label>
            <select class="text-field">
                <option>Watermateriaal</option>
                <option>Installatie</option>
                <option>Elektriciteit</option>
            </select>
        </div>

        <div class="answer-button">
            <button class="btn-primary">Edit</button>
        </div>
    </div>
@endsection
