@extends('layouts.app')
@section('title', 'Edit Role')

@section('content')
    <div class="card" style="max-width:480px;margin:0 auto;">
        <h1 class="h1">Edit Role</h1>

        @if(session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        <form x-data="{ sent: false }" @submit.prevent="sent = true; $el.submit()" id="edit-form" method="POST" action="{{ route('admin.roles.update', $role->id) }}" class="form">
            @csrf @method('PUT')

            <fieldset :disabled="sent">
                <div class="field">
                    <label for="name">Role Name</label>
                    <input id="name" name="name"
                        value="{{ old('name', $role->name) }}" required autofocus
                        data-original="{{ $role->name }}"
                        class="{{ $errors->has('name') ? 'is-invalid' : '' }}">
                    <p id="name-error" style="display:none; color:red; font-size:14px;">
                        Rolnaam moet minstens 2 tekens bevatten.
                    </p>
                    @error('name') <p class="error">{{ $message }}</p> @enderror
                </div>

                <div class="row-end">
                    <a href="{{ route('admin.roles.index') }}" class="btn btn-outline">Cancel</a>
                    <button id="submit-btn" type="submit" class="btn btn-primary">Save Role</button>
                </div>
            </fieldset>
        </form>
    </div>
@endsection

@push('scripts')
    @vite('resources/js/roles/roles-edit.js')
@endpush
