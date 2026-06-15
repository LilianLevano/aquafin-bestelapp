@extends('layouts.app')
@section('title', 'Roles')

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
            <button type="button" class="tab tab-active">Current</button>
            <a href="{{ route('admin.roles.create') }}" class="tab">New</a>
        </div>

        {{-- TABLE --}}
        <div id="section-table">
            <div class="row-between mb">
                <h1 class="h1">Roles</h1>
                <button type="button" class="btn btn-outline btn-sm" onclick="location.reload()">↺ Refresh</button>
            </div>

            @if(session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
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
                        <th>Name</th>

                        <th class="right">Actions</th>
                    </tr>
                </thead>
                <tbody id="roles-tbody">
                    @forelse($roles as $r)
                    <tr>
                        <td>{{ $r->id }}</td>
                        <td>{{ $r->name }}</td>

                        <td class="right">
                            <button type="button" class="link"><a href="{{route('admin.roles.edit', $r->id)}}">Edit</a> </button>
                            <form method="POST" action="{{ route('admin.roles.destroy', $r) }}" style="display:inline"
                                onsubmit="return confirm('Delete this role?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="link link-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr id="empty-row"><td colspan="4" class="muted center">No roles found.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <p id="no-results" class="muted center" style="display:none;padding:16px;">No results found.</p>
        </div>
    </div>

@endsection

@push('scripts')
    @vite('resources/js/roles-index.js')
@endpush
