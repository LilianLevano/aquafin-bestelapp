@extends('layouts.admin')
@section('title', 'Nieuw account')

@section('content')
<div class="card" style="max-width:640px;margin:0 auto;">
    <h1 class="h1">Nieuw Account</h1>

    <form method="POST" action="{{ route('admin.accounts.store') }}" class="form">
        @csrf

        <div class="field">
            <label for="mail">Mail</label>
            <input id="mail" type="email" name="mail" value="{{ old('mail') }}" required>
            @error('mail') <p class="error">{{ $message }}</p> @enderror
        </div>

        <div class="field">
            <label for="password">Wachtwoord</label>
            <input id="password" type="password" name="password" required>
            @error('password') <p class="error">{{ $message }}</p> @enderror
        </div>

        <div class="field">
            <label for="password_confirmation">Confirm wachtwoord</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required>
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
            <label for="role_id">Rol</label>
            <select id="role_id" name="role_id" required>
                <option value="">— Kies rol —</option>
                @foreach($roles as $r)
                    <option value="{{ $r->id }}" @selected(old('role_id') == $r->id)>{{ $r->name }}</option>
                @endforeach
            </select>
            @error('role_id') <p class="error">{{ $message }}</p> @enderror
        </div>

        <div class="row-end">
            <a href="{{ route('admin.accounts.index') }}" class="btn btn-outline">Annuleren</a>
            <button type="submit" class="btn btn-primary">Maken</button>
        </div>
    </form>
</div>
@endsection
