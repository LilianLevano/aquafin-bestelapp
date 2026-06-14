@extends('layouts.app')
@section('title', 'Create Role')

@section('content')
    <div class="card" style="max-width:480px;margin:0 auto;">
        <div class="tabs">
            <a href="{{ route('admin.roles.index') }}" class="tab">Huidig</a>

        </div>

        <h1 class="h1">Create Role</h1>
        @if(session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif
        <form x-data="{ sent: false }" @submit.prevent="sent = true; $el.submit()" id="create-form" method="POST" action="{{ route('admin.roles.store') }}" class="form">
            @csrf
        <fieldset :disabled="sent">


            <div class="field">
                <label for="name">Role Name</label>
                <input id="name" name="name" value="{{ old('name') }}" required autofocus
                    class="{{ $errors->has('name') ? 'is-invalid' : '' }}">
                @error('name') <p class="error">{{ $message }}</p> @enderror
            </div>

            <div class="row-end">
                <a href="{{ route('admin.roles.index') }}" class="btn btn-outline">Annuleer</a>
                <button id="submit-btn" type="submit" class="btn btn-primary">+ Maak Rol</button>
            </div>
        </fieldset>
        </form>
    </div>
@endsection

@push('scripts')
    @vite('resources/js/roles-create.js')
@endpush
