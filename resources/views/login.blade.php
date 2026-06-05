@extends('layouts.app')
@section('title', 'Login')

@php
    $showHulp = $errors->hasAny(['voornaam', 'achternaam', 'titel', 'descriptie']) || old('_form') === 'hulp';
@endphp

@section('content')
<div class="centered">
    <div class="card" style="max-width:520px;width:100%;">

        {{-- LOGIN FORM --}}
        <div id="section-login" @if($showHulp) style="display:none" @endif>
            <h1 class="h1">Login</h1>

            @if(session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="form">
                @csrf

                <div class="field">
                    <label for="mail">Email</label>
                    <input id="mail" type="email" name="mail"
                           value="{{ old('mail') }}" required autofocus
                           class="{{ $errors->has('mail') && !$showHulp ? 'is-invalid' : '' }}">
                    @if(!$showHulp)
                        @error('mail') <p class="error">{{ $message }}</p> @enderror
                    @endif
                </div>

                <div class="field">
                    <label for="password">Password</label>
                    <input id="password" type="password" name="password" required
                           class="{{ $errors->has('password') && !$showHulp ? 'is-invalid' : '' }}">
                    @if(!$showHulp)
                        @error('password') <p class="error">{{ $message }}</p> @enderror
                    @endif
                </div>

                <div class="row-between">
                    <button type="button" class="link" onclick="toggleHulp(true)">Need help?</button>
                    <button type="submit" class="btn btn-primary">Login</button>
                </div>
            </form>
        </div>

        {{-- HELP FORM --}}
        <div id="section-hulp" @if(!$showHulp) style="display:none" @endif>
            <button type="button" class="back-link" onclick="toggleHulp(false)">← Back to login</button>
            <h1 class="h1">Request Help</h1>

            <form method="POST" action="{{ route('hulp.store') }}" class="form">
                @csrf
                <input type="hidden" name="_form" value="hulp">

                <div class="field">
                    <label for="hulp-mail">Email</label>
                    <input id="hulp-mail" type="email" name="mail"
                           value="{{ old('mail') }}" required
                           class="{{ $errors->has('mail') && $showHulp ? 'is-invalid' : '' }}">
                    @if($showHulp)
                        @error('mail') <p class="error">{{ $message }}</p> @enderror
                    @endif
                </div>

                <div class="grid-2">
                    <div class="field">
                        <label for="hulp-voornaam">First Name</label>
                        <input id="hulp-voornaam" name="voornaam"
                               value="{{ old('voornaam') }}" required
                               class="{{ $errors->has('voornaam') ? 'is-invalid' : '' }}">
                        @error('voornaam') <p class="error">{{ $message }}</p> @enderror
                    </div>
                    <div class="field">
                        <label for="hulp-achternaam">Last Name</label>
                        <input id="hulp-achternaam" name="achternaam"
                               value="{{ old('achternaam') }}" required
                               class="{{ $errors->has('achternaam') ? 'is-invalid' : '' }}">
                        @error('achternaam') <p class="error">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="field">
                    <label for="hulp-titel">Title</label>
                    <input id="hulp-titel" name="titel"
                           value="{{ old('titel') }}" required
                           class="{{ $errors->has('titel') ? 'is-invalid' : '' }}">
                    @error('titel') <p class="error">{{ $message }}</p> @enderror
                </div>

                <div class="field">
                    <label for="hulp-descriptie">Description</label>
                    <textarea id="hulp-descriptie" name="descriptie" rows="4" required
                              class="{{ $errors->has('descriptie') ? 'is-invalid' : '' }}">{{ old('descriptie') }}</textarea>
                    @error('descriptie') <p class="error">{{ $message }}</p> @enderror
                </div>

                <div class="row-end">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>

    </div>
</div>

<script>
function toggleHulp(show) {
    document.getElementById('section-login').style.display = show ? 'none' : 'block';
    document.getElementById('section-hulp').style.display = show ? 'block' : 'none';
}

document.querySelectorAll('.form').forEach(function(form) {
    form.addEventListener('submit', function() {
        var btn = this.querySelector('button[type="submit"]');
        if (btn) {
            btn.disabled = true;
            btn.dataset.original = btn.textContent;
            btn.textContent = btn.textContent + '…';
        }
    });
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
