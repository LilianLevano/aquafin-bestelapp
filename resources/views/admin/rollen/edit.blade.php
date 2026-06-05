@extends('layouts.admin')
@section('title', 'Edit Role')

@section('content')
<div class="card" style="max-width:480px;margin:0 auto;">
    <h1 class="h1">Edit Role</h1>

    <form method="POST" action="{{ route('admin.rollen.update', $role) }}" class="form">
        @csrf @method('PUT')

        <div class="field">
            <label for="name">Role Name</label>
            <input id="name" name="name" value="{{ old('name', $role->name) }}" required autofocus>
            @error('name') <p class="error">{{ $message }}</p> @enderror
        </div>

        <div class="row-end">
            <a href="{{ route('admin.rollen.index') }}" class="btn btn-outline">Cancel</a>
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
    </form>
</div>
@endsection
