@extends('layouts.admin')
@section('title', 'Accounts')

@section('content')
<div class="card">
    <div class="row-between mb">
        <h1 class="h1">Accounts</h1>
        <a href="{{ route('admin.accounts.create') }}" class="btn btn-primary btn-sm">+ Account</a>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>id</th><th>Voornaam</th><th>Achternaam</th>
                <th>Rol</th><th>Mail</th><th class="right">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($accounts as $a)
            <tr>
                <td>{{ $a->id }}</td>
                <td>{{ $a->voornaam }}</td>
                <td>{{ $a->achternaam }}</td>
                <td>{{ $a->role->name ?? '—' }}</td>
                <td>{{ $a->mail }}</td>
                <td class="right">
                    <a href="{{ route('admin.accounts.edit', $a) }}" class="link">edit</a>
                    <form method="POST" action="{{ route('admin.accounts.destroy', $a) }}" style="display:inline"
                          onsubmit="return confirm('Account verwijderen?');">
                        @csrf @method('DELETE')
                        <button type="submit" class="link link-danger">delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="muted center">Geen accounts.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
