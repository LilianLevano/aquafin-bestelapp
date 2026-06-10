@extends('layouts.app')
@section('title', 'Edit Role')

@section('content')
    <div class="card" style="max-width:480px;margin:0 auto;">
        <h1 class="h1">Edit Role</h1>
        @if(session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif
        <form id="edit-form" method="POST" action="{{ route('admin.roles.update', $role) }}" class="form">
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
                <a href="{{ route('admin.roles.index') }}" class="btn btn-outline">Cancel</a>
                <button id="submit-btn" type="submit" class="btn btn-primary">Save Role</button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    @vite('resources/js/roles-edit.js')
@endpush
