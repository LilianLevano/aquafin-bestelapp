@extends('layouts.app')
@section('title', 'Hulp aanvragen')

@section('content')
<div class="centered">
    <div class="card" style="max-width:520px;width:100%;">
        <a href="{{ route('login') }}" class="back-link">← Terug naar login</a>
        <h1 class="h1">Hulp aanvragen</h1>

        <form method="POST" action="{{ route('hulp.store') }}" class="form">
            @csrf

            <div class="field">
                <label for="mail">Mail</label>
                <input id="mail" type="email" name="mail"
                       value="{{ old('mail') }}" required
                       class="{{ $errors->has('mail') ? 'is-invalid' : '' }}">
                @error('mail') <p class="error">{{ $message }}</p> @enderror
            </div>

            <div class="grid-2">
                <div class="field">
                    <label for="voornaam">Voornaam</label>
                    <input id="voornaam" name="voornaam"
                           value="{{ old('voornaam') }}" required
                           class="{{ $errors->has('voornaam') ? 'is-invalid' : '' }}">
                    @error('voornaam') <p class="error">{{ $message }}</p> @enderror
                </div>
                <div class="field">
                    <label for="achternaam">Achternaam</label>
                    <input id="achternaam" name="achternaam"
                           value="{{ old('achternaam') }}" required
                           class="{{ $errors->has('achternaam') ? 'is-invalid' : '' }}">
                    @error('achternaam') <p class="error">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="field">
                <label for="titel">Titel</label>
                <input id="titel" name="titel"
                       value="{{ old('titel') }}" required
                       class="{{ $errors->has('titel') ? 'is-invalid' : '' }}">
                @error('titel') <p class="error">{{ $message }}</p> @enderror
            </div>

            <div class="field">
                <label for="descriptie">Beschrijving</label>
                <textarea id="descriptie" name="descriptie" rows="5" required
                          class="{{ $errors->has('descriptie') ? 'is-invalid' : '' }}">{{ old('descriptie') }}</textarea>
                @error('descriptie') <p class="error">{{ $message }}</p> @enderror
            </div>

            <div class="row-end">
                <button type="submit" class="btn btn-primary">Verstuur</button>
            </div>
        </form>
    </div>
</div>

<script>
document.querySelector('.form').addEventListener('submit', function() {
    var btn = this.querySelector('button[type="submit"]');
    if (btn) {
        btn.disabled = true;
        btn.dataset.original = btn.textContent;
        btn.textContent = btn.textContent + '…';
    }
});
window.addEventListener('pageshow', function(e) {
    if (e.persisted) {
        document.querySelectorAll('button[type="submit"]').forEach(function(btn) {
            btn.disabled = false;
            if (btn.dataset.original) btn.textContent = btn.dataset.original;
        });
    }
});
</script>
@endsection
