@extends('layouts.app')
@section('title', 'Maak Categorie')

@section('content')
    <div class="card" style="max-width:480px;margin:0 auto;">
        <div class="tabs">
            <a href="{{ route('admin.categories.index') }}" class="tab">Huidig</a>
        </div>

        <h1 class="h1">Maak een Categorie</h1>

        @if(session('succes'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">{{ session('status') }}</div>
        @endif

        <form x-data="{ sent: false }" @submit.prevent="sent = true; $el.submit()" id="create-form" method="POST" action="{{ route('admin.categories.store') }}" class="form">
            @csrf
            <fieldset :disabled="sent">
                <div class="field">
                    <label for="name">Categorie Name</label>
                    <input id="name" name="name" value="{{ old('name') }}" required autofocus
                           class="{{ $errors->has('name') ? 'is-invalid' : '' }}">
                    <p id="check-input-name" style="display: none; color: #c61414; text-align: center;">De naam veld moet minstens 2 tekens bevatten.</p>
                    @error('name') <p class="error">{{ $message }}</p> @enderror
                </div>

                <div class="row-end">
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline">Annuleer</a>
                    <button id="submit-btn" type="submit" class="btn btn-primary">+ Maak categorie</button>
                </div>
            </fieldset>
        </form>
    </div>
@endsection

@push('scripts')
    @vite('resources/js/categories/category.js')
@endpush
