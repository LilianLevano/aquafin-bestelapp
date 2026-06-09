@extends('layouts.app')
@section('title', 'Create Role')

@section('content')
<div class="card" style="max-width:480px;margin:0 auto;">

    <div class="tabs">
        <a href="{{ route('admin.rollen.index') }}" class="tab">Overview</a>
        <a href="{{ route('admin.rollen.create') }}" class="tab tab-active">New</a>
    </div>

    <h1 class="h1">Create Role</h1>
    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif
    <form id="create-form" method="POST" action="{{ route('admin.rollen.store') }}" class="form">
        @csrf

        <div class="field">
            <label for="name">Role Name</label>
            <input id="name" name="name" value="{{ old('name') }}" required autofocus
                   class="{{ $errors->has('name') ? 'is-invalid' : '' }}">
            @error('name') <p class="error">{{ $message }}</p> @enderror
        </div>

        <div class="row-end">
            <a href="{{ route('admin.rollen.index') }}" class="btn btn-outline">Cancel</a>
            <button id="submit-btn" type="submit" class="btn btn-primary">+ Create Role</button>
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

document.getElementById('name').addEventListener('blur', function() {
    var ok = this.value.trim().length >= 2;
    setValidity(this, ok, 'Role name must be at least 2 characters.');
});

document.getElementById('value').addEventListener('blur', function() {
    if (!this.value.trim()) return;
    var ok = /^[a-z0-9_\-]{2,}$/.test(this.value.trim());
    setValidity(this, ok, 'Role value must be lowercase letters, numbers, - or _ only.');
});

document.getElementById('create-form').addEventListener('submit', function(e) {
    var valid = true;
    this.querySelectorAll('[required]').forEach(function(input) {
        if (!input.value.trim()) {
            input.classList.add('is-invalid');
            valid = false;
        }
    });
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
