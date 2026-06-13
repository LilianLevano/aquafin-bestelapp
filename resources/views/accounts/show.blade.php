@extends('layouts.app')
@section('title', 'Account detail')

@section('content')

    <div class="account-container">

        <a href="{{ route('admin.accounts.index') }}" class="account-back-link">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 12H5M12 5l-7 7 7 7"/>
            </svg>
            Accounts
        </a>



        <div class="account-card">

            <div class="account-header">
                <div class="account-header-info">
                    <h1 class="account-name">{{ $account->first_name . ' ' . $account->last_name }}</h1>
                    <p class="account-id">#{{ $account->id }}</p>
                </div>
                <span class="account-role-badge">{{ $account->role->name ?? 'Geen rol' }}</span>

            </div>

            <div class="account-fields">
                <div class="account-field">
                <span class="account-field-label">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                    E-mail
                </span>
                    <span class="account-field-value">{{ $account->email }}</span>
                </div>

                <div class="account-field">
                <span class="account-field-label">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.81a19.79 19.79 0 01-3.07-8.67A2 2 0 012 0h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.09 7.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/></svg>
                    Telefoon
                </span>
                    @if($account->phone_number)
                        <span class="account-field-value">{{ $account->phone_number }}</span>
                    @else
                        <span class="account-field-value muted">Niet opgegeven</span>
                    @endif
                </div>

                <div class="account-field">
                <span class="account-field-label">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    Locatie
                </span>
                    @if($account->site)
                        <span class="account-field-value">{{ $account->site->description }}</span>
                    @else
                        <span class="account-field-value muted">Geen locatie</span>
                    @endif
                </div>
            </div>

            <div class="account-actions">
                <a href="{{ route('admin.accounts.edit', $account->id) }}" class="btn-edit">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    Bewerken
                </a>

                <form action="{{ route('admin.accounts.destroy', $account->id) }}" method="POST"
                      onsubmit="return confirm('Weet je zeker dat je dit account wil verwijderen?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-delete">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4a1 1 0 011-1h4a1 1 0 011 1v2"/></svg>
                        Verwijderen
                    </button>
                </form>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    @vite('resources/js/account-edit.js')
@endpush
