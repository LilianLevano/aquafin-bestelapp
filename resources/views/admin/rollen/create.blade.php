@extends('layouts.admin')
@section('title', 'Maak rol')

@section('content')
<div class="card" style="max-width:480px;margin:0 auto;">
    <h1 class="h1">Maak Rol</h1>

    <form method="POST" action="{{ route('admin.rollen.store') }}" class="form">
        @csrf

        <div class="field">
            <label for="name">Naam Rol</label>
            <input id="name" name="name" value="{{ old('name') }}" required autofocus>
            @error('name') <p class="error">{{ $message }}</p> @enderror
        </div>

        <div class="row-end">
            <a href="{{ route('admin.rollen.index') }}" class="btn btn-outline">Annuleren</a>
            <button type="submit" class="btn btn-primary">+ Maken</button>
        </div>
    </form>
</div>
@endsection
