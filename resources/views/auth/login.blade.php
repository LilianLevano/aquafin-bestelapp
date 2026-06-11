@extends('layouts.app')
@section('title', 'Login')

@section('content')
    <div id="toast" class="toast" role="alert" aria-live="assertive"></div>

    <div class="centered">
        <div class="card" style="max-width:520px;width:100%;">

            {{-- LOGIN FORM --}}
            <div id="section-login">
                <h1 class="h1">Login</h1>

                @if(session('status'))
                    <div class="alert alert-success">{{ session('status') }}</div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="form">
                    @csrf

                    <div class="field">
                        <label for="email">Email</label>
                        <input id="email" type="email" name="email"
                            value="{{ old('email') }}" required autofocus
                            class="{{ $errors->has('email') ? 'is-invalid' : '' }}">
                        @error('email') <p class="error">{{ $message }}</p> @enderror
                    </div>

                    <div class="field">
                        <label for="password">Wachtwoord</label>
                        <input id="password" type="password" name="password" required
                            class="{{ $errors->has('password') ? 'is-invalid' : '' }}">
                        @error('password') <p class="error">{{ $message }}</p> @enderror
                    </div>

                    <div class="row-between">
                        <button type="button" class="link" onclick="toggleHulp(true)">Hulp nodig?</button>
                        <button type="submit" class="btn btn-primary">Login</button>
                    </div>
                </form>
            </div>

            {{-- HULP FORM --}}
            <div id="section-hulp" style="display:none">
                <button type="button" class="back-link" onclick="toggleHulp(false)">← Keer terug naar het login formulier</button>
                <h1 class="h1">Hulp aanvragen</h1>

                <form id="form-hulp" method="POST" action="{{ route('hulp.store') }}" class="form">
                    @csrf

                    <div class="field">
                        <label for="hulp-email">Email</label>
                        <input id="hulp-email" type="email" name="email" required>
                    </div>

                    <div class="grid-2">
                        <div class="field">
                            <label for="hulp-first_name">Voornaam</label>
                            <input id="hulp-first_name" name="first_name" required>
                        </div>
                        <div class="field">
                            <label for="hulp-last_name">Familienaam</label>
                            <input id="hulp-last_name" name="last_name" required>
                        </div>
                    </div>

                    <div class="field">
                        <label for="hulp-category">Probleemcategorie</label>
                        <select id="hulp-category" name="category" required>
                            <option value="" disabled selected>Kies een categorie…</option>
                            <option value="loginprobleem">Loginprobleem</option>
                            <option value="accountbeheer">Accountbeheer</option>
                            <option value="bestelling">Bestelling / Levering</option>
                            <option value="technisch">Technisch probleem</option>
                            <option value="overig">Overig</option>
                        </select>
                    </div>

                    <div class="field">
                        <label for="hulp-description">Beschrijving</label>
                        <textarea id="hulp-description" name="description" rows="4" required></textarea>
                    </div>

                    <div class="row-end">
                        <button type="submit" class="btn btn-primary">Stuur verzoek</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @vite('resources/js/auth-login.js')
@endpush
