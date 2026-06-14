@extends('layouts.app')
@section('title', 'Rollen')

@section('content')
    <div class="card">
        <div class="tabs">

            <a href="{{route('admin.roles.create')}}" class="btn-primary">
                + Rol
            </a>
        </div>

        {{-- TABLE --}}
        <div id="section-table">
            <div class="row-between mb">
                <h1 class="h1">Rollen</h1>
                <button type="button" class="btn btn-outline btn-sm" onclick="location.reload()">↺ Herlaadt</button>
            </div>

            @if(session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif

            @if(session('error'))
                <div class="alert alert-error">{{ session('error') }}</div>
            @endif

            <div class="mb">
                <input id="search-input" type="text" placeholder="Search by name..."
                    oninput="filterTable(this.value)"
                    style="padding:8px 12px;border:1px solid var(--border);border-radius:8px;font:inherit;width:100%;max-width:300px;">
            </div>

            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Naam</th>

                        <th class="right">Actie</th>
                    </tr>
                </thead>
                <tbody id="roles-tbody">
                    @forelse($roles as $r)
                    <tr>
                        <td>{{ $r->id }}</td>
                        <td>{{ $r->name }}</td>

                        <td class="right">
                            <button type="button" class="link"><a href="{{route('admin.roles.edit', $r->id)}}">Bewerk</a> </button>
                            <form method="POST" action="{{ route('admin.roles.destroy', $r) }}" style="display:inline"
                                onsubmit="return confirm('Delete this role?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="link link-danger">Verwijder</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr id="empty-row"><td colspan="4" class="muted center">Geen rollen gevonden.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <p id="no-results" class="muted center" style="display:none;padding:16px;">Geen resultaat gevonden.</p>
        </div>
    </div>
@endsection

@push('scripts')
    @vite('resources/js/roles-index.js')
@endpush
