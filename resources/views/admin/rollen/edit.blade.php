@extends('layouts.admin')
@section('title', 'Edit Role')

@section('content')
<div class="card" style="max-width:480px;margin:0 auto;">
    <h1 class="h1">Edit Role</h1>
    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif
    <form id="edit-form" method="POST" action="{{ route('admin.rollen.update', $role) }}" class="form">
        @csrf @method('PUT')

        <div class="field">
            <label for="name">Role Name</label>
            <input id="name" name="name"
                   value="{{ old('name', $role->name) }}" required autofocus
                   data-original="{{ $role->name }}"
                   class="{{ $errors->has('name') ? 'is-invalid' : '' }}">
            @error('name') <p class="error">{{ $message }}</p> @enderror
        </div>

        <div class="row-end">
            <a href="{{ route('admin.rollen.index') }}" class="btn btn-outline">Cancel</a>
            <button id="submit-btn" type="submit" class="btn btn-primary">Save Role</button>
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

document.getElementById('name').addEventListener('input', function() {
    this.classList.toggle('is-modified', this.value !== this.dataset.original);
});

document.getElementById('edit-form').addEventListener('submit', function(e) {
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
