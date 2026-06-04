@extends('layouts.admin')
@section('title', 'Rollen')

@section('content')
<div class="card">
    <div class="row-between mb">
        <h1 class="h1">Rollen</h1>
        <a href="{{ route('admin.rollen.create') }}" class="btn btn-primary btn-sm">+ Rol</a>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>id</th><th>Naam</th><th class="right">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($roles as $r)
            <tr>
                <td>{{ $r->id }}</td>
                <td>{{ $r->name }}</td>
                <td class="right">
                    <a href="{{ route('admin.rollen.edit', $r) }}" class="link">edit</a>
                    <form method="POST" action="{{ route('admin.rollen.destroy', $r) }}" style="display:inline"
                          onsubmit="return confirm('Rol verwijderen?');">
                        @csrf @method('DELETE')
                        <button type="submit" class="link link-danger">delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="3" class="muted center">Geen rollen.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
