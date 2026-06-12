@extends('layouts.app')
@section('title', 'Edit Account')

@section('content')
    <button onclick="history.back()"
            style="display: inline-flex; align-items: center; gap: 6px; font-size: 13px; color: #6b7280; text-decoration: none; margin-bottom: 1rem;">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M19 12H5M12 5l-7 7 7 7"/>
        </svg>
        Terug
    </button>

    <div class="card account-card">
        <div class="account-header">
            <div class="account-avatar-group">
                <div>
                    <p class="account-name">{{ $account->first_name }} {{ $account->last_name }}</p>
                    <p class="account-id">#{{ $account->id }}</p>
                </div>
            </div>
            <span class="account-badge">{{ $account->role->name ?? 'Geen rol' }}</span>
        </div>

        <div class="account-details">
            <table>
                <tr>
                    <td class="label"><i class="ti ti-mail"></i> Email</td>
                    <td>{{ $account->email }}</td>
                </tr>
                <tr>
                    <td class="label"><i class="ti ti-phone"></i> Telefoon</td>
                    <td>{{ $account->phone_number }}</td>
                </tr>
                <tr>
                    <td class="label"><i class="ti ti-map-pin"></i> Locatie</td>
                    <td>{{ $account->site->description ?? 'Geen locatie' }}</td>
                </tr>
            </table>
        </div>

        <div class="account-actions">
            <a href="{{ route('admin.accounts.edit', $account) }}" class="btn btn-sm">
                <i class="ti ti-edit"></i> Bewerken
            </a>
            <form method="POST" action="{{ route('admin.accounts.destroy', $account) }}">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger">
                    <i class="ti ti-trash"></i> Verwijderen
                </button>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
    @vite('resources/js/account-edit.js')
@endpush
