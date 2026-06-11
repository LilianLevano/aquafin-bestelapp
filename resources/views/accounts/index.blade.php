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
                    <tr>
                        <td class="id-account">{{ $a->id }}</td>
                        <td>{{ $a->first_name }}</td>
                        <td>{{ $a->last_name }}</td>
                        <td class="extra-information">{{ $a->email }}</td>
                        <td class="extra-information">{{ $a->phone_number }}</td>
                        <td class="extra-information">{{ $a->role->name ?? '—' }}</td>
                        <td class="extra-information">{{ $a->site->description }}</td>
                        <td class="right">

                            <a href="{{route('admin.accounts.show', $a->id)}}" class="show" >Meer details</a>

                            <a href="{{route('admin.accounts.edit', $a->id)}}" class="link">Edit</a>

                            <form method="POST" action="{{ route('admin.accounts.destroy', $a) }}" style="display:inline"
                                onsubmit="return confirm('Delete this account?');">
                                @csrf @method('DELETE')
                                <button type="submit"  class="link link-danger">Delete</button>
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


<style>


    @media screen and (max-width: 1015px){
        .table, th{
            color: red; !important;
        }

        #accounts-table{
            td{
                padding: 5px;
            }
        }
    }

    @media screen and (min-width: 956px ){
        .show{
            display: none;
        }
    }

    @media screen and (max-width:956px ){
        .extra-information{
            display: none;
        }

        .link{
            display: none;
        }
    }


    @media screen and (max-width: 454px ){
        #accounts-table{
            td{
                width: fit-content;
                padding: 2px;
            }
        }

        .id-account{
            display: none;
        }
    }

    @media screen and  (max-width: 412px){

    }




</style>

@push('scripts')
    @vite('resources/js/account-index.js')
@endpush
