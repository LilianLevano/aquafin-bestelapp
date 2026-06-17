@extends('layouts.app')
@section('title', 'Rollen')

@section('content')
    <style>
        .card {
            border-radius: 14px;
        }

        .table th {
            background-color: #eaf3ff;
            color: #005fa3;
        }

        .table tr:hover {
            background-color: #eaf3ff;
        }
    </style>

    <div class="card">
        <div class="tabs">
            <a href="{{ route('admin.roles.create') }}" class="btn-primary">
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
                <input type="text" id="search-roles" placeholder="Zoek rol..." autocomplete="off"
                       style="margin-bottom: 0; padding: .5rem; width: 100%; position: relative;">
                <ul id="search-suggestions" style=" list-style: none; margin-bottom: 10px; padding: 0; border: 1px solid #ccc; border-top: none; position: absolute; background: white; width: 40%; z-index: 100; display: none; "></ul>
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
                        <tr data-id="{{ $r->id }}" data-name="{{ $r->name }}">
                            <td>{{ $r->id }}</td>
                            <td>{{ $r->name }}</td>

                            <td class="right">
                                <button type="button" class="link"><a href="{{route('admin.roles.edit', $r->id)}}">Bewerk</a> </button>
                                <form method="POST" action="{{ route('admin.roles.destroy', $r->id) }}" style="display:inline"
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
    @vite('resources/js/roles/roles-index.js')
@endpush
