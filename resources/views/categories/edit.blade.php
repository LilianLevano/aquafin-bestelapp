@extends('layouts.app')
@section('title', 'Edit Categorie')

@section('content')
    <div class="card" style="max-width:480px;margin:0 auto;">
        <h1 class="h1">Edit Categorie</h1>
        @if(session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif
        <form id="edit-form" method="POST" action="{{ route('admin.categories.update', $category->id) }}" class="form">
            @csrf @method('PUT')

            <div class="field">
                <label for="name">Categorie Naam</label>
                <input id="name" name="name"
                       value="{{ old('name', $category->name) }}" required autofocus
                       data-original="{{ $category->name }}"
                       class="{{ $errors->has('name') ? 'is-invalid' : '' }}">
                <p id="check-input-name" style="display: none; color: #c61414; text-align: center;">De naam veld moet minstens 2 tekens bevatten.</p>
                @error('name') <p class="error">{{ $message }}</p> @enderror
            </div>

            <div class="row-end">
                <a href="{{ route('admin.categories.index') }}" class="btn btn-outline">Cancel</a>
                <button id="submit-btn" type="submit" class="btn btn-primary">Save Categorie</button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    @vite('resources/js/categories/category.js')
@endpush
