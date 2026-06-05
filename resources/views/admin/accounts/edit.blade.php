@extends('layouts.admin')
@section('title', 'Edit account')

@section('content')
<div class="card" style="max-width:560px;margin:0 auto;">
    <h1 class="h1">Edit Account</h1>

    <form id="edit-form" method="POST" action="{{ route('admin.accounts.update', $account) }}" class="form">
        @csrf @method('PUT')

        <div class="field">
            <label for="mail">Mail</label>
            <input id="mail" type="email" name="mail"
                   value="{{ old('mail', $account->mail) }}" required
                   data-original="{{ $account->mail }}"
                   class="{{ $errors->has('mail') ? 'is-invalid' : '' }}">
            @error('mail') <p class="error">{{ $message }}</p> @enderror
        </div>

        <div class="grid-2">
            <div class="field">
                <label for="voornaam">Voornaam</label>
                <input id="voornaam" name="voornaam"
                       value="{{ old('voornaam', $account->voornaam) }}" required
                       data-original="{{ $account->voornaam }}"
                       class="{{ $errors->has('voornaam') ? 'is-invalid' : '' }}">
                @error('voornaam') <p class="error">{{ $message }}</p> @enderror
            </div>
            <div class="field">
                <label for="achternaam">Achternaam</label>
                <input id="achternaam" name="achternaam"
                       value="{{ old('achternaam', $account->achternaam) }}" required
                       data-original="{{ $account->achternaam }}"
                       class="{{ $errors->has('achternaam') ? 'is-invalid' : '' }}">
                @error('achternaam') <p class="error">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="field">
            <label for="role_id">Rol</label>
            <select id="role_id" name="role_id" required
                    data-original="{{ $account->role_id }}"
                    class="{{ $errors->has('role_id') ? 'is-invalid' : '' }}">
                @foreach($roles as $r)
                    <option value="{{ $r->id }}" @selected(old('role_id', $account->role_id) == $r->id)>{{ $r->name }}</option>
                @endforeach
            </select>
            @error('role_id') <p class="error">{{ $message }}</p> @enderror
        </div>

        <div class="field">
            <label for="password">Nieuw wachtwoord <span class="muted">(optioneel)</span></label>
            <input id="password" type="password" name="password"
                   class="{{ $errors->has('password') ? 'is-invalid' : '' }}">
            @error('password') <p class="error">{{ $message }}</p> @enderror
        </div>

        <div class="field">
            <label for="password_confirmation">Bevestig wachtwoord</label>
            <input id="password_confirmation" type="password" name="password_confirmation">
        </div>

        <div class="row-end">
            <a href="{{ route('admin.accounts.index') }}" class="btn btn-outline">Annuleren</a>
            <button id="submit-btn" type="submit" class="btn btn-primary">Opslaan</button>
        </div>
    </form>
</div>

<script>
function setValidity(input, valid, message) {
    var field = input.closest('.field');
    var existing = field.querySelector('.error-js');
    if (valid) {
        input.classList.remove('is-invalid');
        if (existing) existing.remove();
    } else {
        input.classList.add('is-invalid');
        if (!existing) {
            var p = document.createElement('p');
            p.className = 'error error-js';
            p.textContent = message;
            field.appendChild(p);
        }
    }
}

document.getElementById('mail').addEventListener('blur', function() {
    var ok = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.value.trim());
    setValidity(this, ok, 'Ongeldig emailadres.');
});

document.getElementById('voornaam').addEventListener('blur', function() {
    var ok = /^[A-Za-zÀ-ÿ\s\-']{2,}$/.test(this.value.trim());
    setValidity(this, ok, 'Min. 2 letters, alleen letters toegestaan.');
});

document.getElementById('achternaam').addEventListener('blur', function() {
    var ok = /^[A-Za-zÀ-ÿ\s\-']{2,}$/.test(this.value.trim());
    setValidity(this, ok, 'Min. 2 letters, alleen letters toegestaan.');
});

document.getElementById('password').addEventListener('blur', function() {
    if (!this.value) return;
    var v = this.value;
    var ok = v.length >= 8 && /[A-Z]/.test(v) && /[a-z]/.test(v) && /[0-9]/.test(v);
    setValidity(this, ok, 'Min. 8 tekens, 1 hoofdletter, 1 kleine letter, 1 cijfer.');
});

document.getElementById('password_confirmation').addEventListener('blur', function() {
    var pw = document.getElementById('password').value;
    if (!pw) return;
    setValidity(this, this.value === pw, 'Wachtwoorden komen niet overeen.');
});

document.querySelectorAll('[data-original]').forEach(function(input) {
    input.addEventListener('input', function() {
        var changed = this.value !== this.dataset.original;
        this.classList.toggle('is-modified', changed);
    });
});

document.getElementById('edit-form').addEventListener('submit', function(e) {
    var valid = true;
    this.querySelectorAll('[required]').forEach(function(input) {
        if (!input.value.trim()) {
            input.classList.add('is-invalid');
            valid = false;
        }
    });
    var pw = document.getElementById('password').value;
    var pwc = document.getElementById('password_confirmation').value;
    if (pw && pw !== pwc) {
        document.getElementById('password_confirmation').classList.add('is-invalid');
        valid = false;
    }
    if (!valid) { e.preventDefault(); return; }
    var btn = document.getElementById('submit-btn');
    btn.disabled = true;
    btn.dataset.original = btn.textContent;
    btn.textContent = btn.textContent + '…';
});

window.addEventListener('pageshow', function(e) {
    if (e.persisted) {
        var btn = document.getElementById('submit-btn');
        btn.disabled = false;
        if (btn.dataset.original) btn.textContent = btn.dataset.original;
    }
});
</script>
@endsection
