@extends('layouts.admin')
@section('title', 'New Account')

@section('content')
<div class="card" style="max-width:640px;margin:0 auto;">

    <div class="tabs">
        <a href="{{ route('admin.accounts.index') }}" class="tab">Overview</a>
        <a href="{{ route('admin.accounts.create') }}" class="tab tab-active">New</a>
    </div>

    <h1 class="h1">New Account</h1>

    <form id="create-form" method="POST" action="{{ route('admin.accounts.store') }}" class="form">
        @csrf

        <div class="field">
            <label for="mail">Email</label>
            <input id="mail" type="email" name="mail" value="{{ old('mail') }}" required
                   class="{{ $errors->has('mail') ? 'is-invalid' : '' }}">
            @error('mail') <p class="error">{{ $message }}</p> @enderror
        </div>

        <div class="field">
            <label for="password">Password</label>
            <div class="input-group">
                <input id="password" type="password" name="password" required
                       class="{{ $errors->has('password') ? 'is-invalid' : '' }}">
                <button type="button" class="btn-toggle-pw" onclick="togglePw('password', this)">Show</button>
            </div>
            @error('password') <p class="error">{{ $message }}</p> @enderror
        </div>

        <div class="field">
            <label for="password_confirmation">Confirm Password</label>
            <div class="input-group">
                <input id="password_confirmation" type="password" name="password_confirmation" required
                       class="{{ $errors->has('password_confirmation') ? 'is-invalid' : '' }}">
                <button type="button" class="btn-toggle-pw" onclick="togglePw('password_confirmation', this)">Show</button>
            </div>
        </div>

        <div class="grid-2">
            <div class="field">
                <label for="voornaam">First Name</label>
                <input id="voornaam" name="voornaam" value="{{ old('voornaam') }}" required
                       class="{{ $errors->has('voornaam') ? 'is-invalid' : '' }}">
                @error('voornaam') <p class="error">{{ $message }}</p> @enderror
            </div>
            <div class="field">
                <label for="achternaam">Last Name</label>
                <input id="achternaam" name="achternaam" value="{{ old('achternaam') }}" required
                       class="{{ $errors->has('achternaam') ? 'is-invalid' : '' }}">
                @error('achternaam') <p class="error">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="field">
            <label for="role_id">Role</label>
            <select id="role_id" name="role_id" required
                    class="{{ $errors->has('role_id') ? 'is-invalid' : '' }}">
                <option value="">— Select role —</option>
                @foreach($roles as $r)
                    <option value="{{ $r->id }}" @selected(old('role_id') == $r->id)>{{ $r->name }}</option>
                @endforeach
            </select>
            @error('role_id') <p class="error">{{ $message }}</p> @enderror
        </div>

        <div class="row-end">
            <a href="{{ route('admin.accounts.index') }}" class="btn btn-outline">Cancel</a>
            <button id="submit-btn" type="submit" class="btn btn-primary">Create User</button>
        </div>
    </form>
</div>

<script>
function togglePw(id, btn) {
    var input = document.getElementById(id);
    input.type = input.type === 'password' ? 'text' : 'password';
    btn.textContent = input.type === 'password' ? 'Show' : 'Hide';
}

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
    setValidity(this, ok, 'Invalid email address.');
});

document.getElementById('voornaam').addEventListener('blur', function() {
    var ok = /^[A-Za-zÀ-ÿ\s\-']{2,}$/.test(this.value.trim());
    setValidity(this, ok, 'Min. 2 characters, letters only.');
});

document.getElementById('achternaam').addEventListener('blur', function() {
    var ok = /^[A-Za-zÀ-ÿ\s\-']{2,}$/.test(this.value.trim());
    setValidity(this, ok, 'Min. 2 characters, letters only.');
});

document.getElementById('password').addEventListener('blur', function() {
    var v = this.value;
    var ok = v.length >= 8 && /[A-Z]/.test(v) && /[a-z]/.test(v) && /[0-9]/.test(v);
    setValidity(this, ok, 'Min. 8 characters, 1 uppercase, 1 lowercase, 1 number.');
});

document.getElementById('password_confirmation').addEventListener('blur', function() {
    var ok = this.value === document.getElementById('password').value;
    setValidity(this, ok, 'Passwords do not match.');
});

document.getElementById('create-form').addEventListener('submit', function(e) {
    var valid = true;
    this.querySelectorAll('[required]').forEach(function(input) {
        if (!input.value.trim()) {
            input.classList.add('is-invalid');
            valid = false;
        }
    });
    var pw = document.getElementById('password').value;
    var pwc = document.getElementById('password_confirmation').value;
    if (pw !== pwc) {
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
