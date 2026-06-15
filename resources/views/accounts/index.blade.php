@extends('layouts.app')
@section('title', 'Accounts')

@section('content')
    <div class="card">
        <div class="tabs">
            <a href="{{route('admin.accounts.create')}}" class="btn-primary">
                + Account
            </a>
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
                <input type="text" id="search-account" placeholder="Zoek een account op voornaam..." autocomplete="off"
                       style="margin-bottom: 0; padding: .5rem; width: 100%; position: relative;">
                <ul id="search-suggestions" style=" list-style: none; margin-bottom: 10px; padding: 0; border: 1px solid #ccc; border-top: none; position: absolute; background: white; width: 40%; z-index: 100; display: none; "></ul>
            </div>

            <table class="table" id="accounts-table">
                <thead>
                    <tr>
                        <th class="id-account">ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th class="extra-information">Email</th>
                        <th class="extra-information">Phone Number</th>
                        <th class="extra-information">Role</th>
                        <th class="extra-information">Site</th>
                        <th class="right">Actions</th>
                    </tr>
                </thead>
                <tbody id="accounts-tbody">
                    @forelse($accounts as $a)
                        <tr data-id="{{ $a->id }}" data-firstname="{{ $a->first_name }}" data-lastname="{{ $a->last_name }}">
                        <td class="id-account">{{ $a->id }}</td>
                        <td>{{ $a->first_name }}</td>
                        <td>{{ $a->last_name }}</td>
                        <td class="extra-information">{{ $a->email }}</td>
                        <td class="extra-information">{{ $a->phone_number }}</td>
                        <td class="extra-information">{{ $a->role->name ?? '—' }}</td>
                        <td class="extra-information">{{ $a->site->description }}</td>
                        <td class="right">

                            <a href="{{route('admin.accounts.show', $a->id)}}" class="show" >Meer details</a>

                            <a href="{{route('admin.accounts.edit', $a->id)}}" class="link">Bewerken</a>

                            <form method="POST" action="{{ route('admin.accounts.destroy', $a) }}" style="display:inline"
                                onsubmit="return confirm('Delete this account?');">
                                @csrf @method('DELETE')
                                <button type="submit"  class="link link-danger">Verwijderen</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr id="empty-row"><td colspan="7" class="muted center">No users to display.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <p id="no-results" class="muted center" style="display:none;padding:16px;">No results found.</p>
        </div>
    </div>
@endsection

@push('scripts')
    @vite('resources/js/account-index.js')
@endpush
