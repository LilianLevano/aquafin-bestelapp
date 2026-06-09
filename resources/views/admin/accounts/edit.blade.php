@extends('layouts.admin')
@section('title', 'Edit Account')

@section('content')
<div class="card" style="max-width:560px;margin:0 auto;">
    <h1 class="h1">Edit Account</h1>

    <form id="edit-form" method="POST" action="{{ route('admin.accounts.update', $account) }}" class="form">
        @csrf @method('PUT')

        <div class="field">
            <label for="email">Mail</label>
            <input id="email" type="email" name="email"
                   value="{{ old('email', $account->email) }}" required
                   data-original="{{ $account->email }}"
                   class="{{ $errors->has('email') ? 'is-invalid' : '' }}">
            @error('email') <p class="error">{{ $message }}</p> @enderror
        </div>

        <div class="grid-2">
            <div class="field">
                <label for="first_name">First Name</label>
                <input id="first_name" name="first_name"
                       value="{{ old('first_name', $account->first_name) }}" required
                       data-original="{{ $account->first_name }}"
                       class="{{ $errors->has('first_name') ? 'is-invalid' : '' }}">
                @error('first_name') <p class="error">{{ $message }}</p> @enderror
            </div>
            <div class="field">
                <label for="last_name">Last Name</label>
                <input id="last_name" name="last_name"
                       value="{{ old('last_name', $account->last_name) }}" required
                       data-original="{{ $account->last_name }}"
                       class="{{ $errors->has('last_name') ? 'is-invalid' : '' }}">
                @error('last_name') <p class="error">{{ $message }}</p> @enderror
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
            <label for="site_id">Locatie: </label>
            <select id="site_id" name="site_id" required
                    class="{{ $errors->has('site_id') ? 'is-invalid' : '' }}">
                <option value="">— Select locatie —</option>
                @foreach($sites as $s)
                    <option value="{{ $s->id }}" @selected(old('site_id', $account->site_id) == $s->id)>{{ $s->locatie }}</option>
                @endforeach
            </select>
            @error('site_id') <p class="error">{{ $message }}</p> @enderror
        </div>

        <div class="field">
            <label for="password">New Password <span class="muted">(optional)</span></label>
            <div class="input-group">
                <input id="password" type="password" name="password"
                       class="{{ $errors->has('password') ? 'is-invalid' : '' }}">
                <button type="button" class="btn-toggle-pw" onclick="togglePw('password', this)">Show</button>
            </div>
            @error('password') <p class="error">{{ $message }}</p> @enderror
        </div>

        <div class="field">
            <label for="password_confirmation">Confirm Password</label>
            <div class="input-group">
                <input id="password_confirmation" type="password" name="password_confirmation">
                <button type="button" class="btn-toggle-pw" onclick="togglePw('password_confirmation', this)">Show</button>
            </div>
            @error('password_confirmation') <p class="error">{{ $message }}</p> @enderror
        </div>

        <div class="row-end">
            <a href="{{ route('admin.accounts.index') }}" class="btn btn-outline">Cancel</a>
            <button id="submit-btn" type="submit" class="btn btn-primary">Edit User</button>
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

function togglePw(id, btn) {
    var input = document.getElementById(id);
    input.type = input.type === 'password' ? 'text' : 'password';
    btn.textContent = input.type === 'password' ? 'Show' : 'Hide';
}

document.getElementById('password').addEventListener('blur', function() {
    if (!this.value) return;
    var v = this.value;
    var ok = v.length >= 8 && /[A-Z]/.test(v) && /[a-z]/.test(v) && /[0-9]/.test(v);
    setValidity(this, ok, 'Min. 8 characters, 1 uppercase, 1 lowercase, 1 number.');
});

document.getElementById('password_confirmation').addEventListener('blur', function() {
    var pw = document.getElementById('password').value;
    if (!pw) return;
    setValidity(this, this.value === pw, 'Passwords do not match.');
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
