@extends('layouts.app')
@section('title', 'Hulp aanvragen')

@section('content')
<div class="centered">
    <div class="card" style="max-width:520px;width:100%;">
        <a href="{{ route('login') }}" class="back-link">← Back</a>
        <h1 class="h1">Hulp aanvragen</h1>

        <form method="POST" action="{{ route('hulp.store') }}" class="form">
            @csrf

            <div class="field">
                <label for="mail">Mail</label>
                <input id="mail" type="email" name="mail" value="{{ old('mail') }}" required>
                @error('mail') <p class="error">{{ $message }}</p> @enderror
            </div>

            <div class="grid-2">
                <div class="field">
                    <label for="voornaam">Voornaam</label>
                    <input id="voornaam" name="voornaam" value="{{ old('voornaam') }}" required>
                    @error('voornaam') <p class="error">{{ $message }}</p> @enderror
                </div>
                <div class="field">
                    <label for="achternaam">Achternaam</label>
                    <input id="achternaam" name="achternaam" value="{{ old('achternaam') }}" required>
                    @error('achternaam') <p class="error">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="field">
                <label for="titel">Titel</label>
                <input id="titel" name="titel" value="{{ old('titel') }}" required>
                @error('titel') <p class="error">{{ $message }}</p> @enderror
            </div>

            <div class="field">
                <label for="descriptie">Descriptie</label>
                <textarea id="descriptie" name="descriptie" rows="5" required>{{ old('descriptie') }}</textarea>
                @error('descriptie') <p class="error">{{ $message }}</p> @enderror
            </div>

            <div class="row-end">
                <button type="submit" class="btn btn-primary">Verstuur</button>
            </div>
        </form>
    </div>
</div>
@endsection
