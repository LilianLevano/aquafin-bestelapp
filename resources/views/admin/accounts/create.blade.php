@extends('layouts.admin')
@section('title', 'Nieuw account')

@section('content')
<div class="card" style="max-width:640px;margin:0 auto;">
    <h1 class="h1">Nieuw Account</h1>

    <form method="POST" action="{{ route('admin.accounts.store') }}" class="form">
        @csrf

        <div class="field">
            <label for="email">Mail</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required>
            @error('email') <p class="error">{{ $message }}</p> @enderror
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
                <label for="first_name">Voornaam</label>
                <input id="first_name" name="first_name" value="{{ old('first_name') }}" required>
                @error('first_name') <p class="error">{{ $message }}</p> @enderror
            </div>
            <div class="field">
                <label for="last_name">Achternaam</label>
                <input id="last_name" name="last_name" value="{{ old('last_name') }}" required>
                @error('last_name') <p class="error">{{ $message }}</p> @enderror
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
