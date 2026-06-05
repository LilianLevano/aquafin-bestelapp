@extends('layouts.admin')
@section('title', 'Roles')

@section('content')
<div class="card">
    <div class="row-between mb">
        <h1 class="h1">Roles</h1>
        <a href="{{ route('admin.rollen.create') }}" class="btn btn-primary btn-sm">+ Role</a>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th><th>Name</th><th class="right">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($roles as $r)
            <tr>
                <td>{{ $r->id }}</td>
                <td>{{ $r->name }}</td>
                <td class="right">
                    <a href="{{ route('admin.rollen.edit', $r) }}" class="link">Edit</a>
                    <form method="POST" action="{{ route('admin.rollen.destroy', $r) }}" style="display:inline"
                          onsubmit="return confirm('Delete this role?');">
                        @csrf @method('DELETE')
                        <button type="submit" class="link link-danger">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="3" class="muted center">No roles found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
