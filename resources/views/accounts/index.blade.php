@extends('layouts.app')
@section('title', 'Accounts')

@section('content')
    <div class="card">
        <div class="tabs">
            <button type="button" class="tab tab-active">Current</button>
            <a href="{{ route('admin.accounts.create') }}" class="tab">New</a>
        </div>

        {{-- TABLE --}}
        <div id="section-table">
            <div class="row-between mb">
                <h1 class="h1">Accounts</h1>
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

            <table class="table" id="accounts-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Site</th>
                        <th class="right">Actions</th>
                    </tr>
                </thead>
                <tbody id="accounts-tbody">
                    @if (!(empty($accounts) || $accounts->isEmpty()))
                        @forelse($accounts as $a)
                            <tr>
                                <td>{{ $a->id }}</td>
                                <td>{{ $a->first_name }}</td>
                                <td>{{ $a->last_name }}</td>
                                <td>{{ $a->email }}</td>
                                <td>{{ $a->role->name ?? '—' }}</td>
                                <td>{{ $a->site->locatie }}</td>
                                <td class="right">
                                    <a href="{{route('admin.accounts.edit', $a->id)}}" class="link">Edit</a>

                                    <form method="POST" action="{{ route('admin.accounts.destroy', $a) }}" style="display:inline"
                                        onsubmit="return confirm('Delete this account?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="link link-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @empty <tr id="empty-row"><td colspan="7" class="muted center">No users to display.</td></tr>
                        @endforelse
                    @endif
                </tbody>
            </table>
            <p id="no-results" class="muted center" style="display:none;padding:16px;">No results found.</p>
        </div>
    </div>
@endsection

@push('scripts')
    @vite('resources/js/account-index.js')
@endpush
