@extends('layouts.app')
@section('title', 'Edit Account')

@section('content')
    <div class="card" style="max-width:560px;margin:0 auto;">
        <h1 class="h1">{{$account->first_name . ' ' . $account->last_name}} </h1>
        <p>#{{$account->id}}</p>
        <p>{{$account->role->name ?? 'Geen rol'}}</p>
        <p>{{$account->email}}</p>
        <p>{{$account->site->description ?? 'Geen locatie'}}</p>
        <p>{{$account->phone_number}}</p>

        {{-- Back button --}}
        <a href="{{ route('admin.accounts.index') }}"
           style="display: inline-flex; align-items: center; gap: 6px; font-size: 13px; color: #6b7280; text-decoration: none; margin-bottom: 1rem;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 12H5M12 5l-7 7 7 7"/>
            </svg>
            Terug
        </a>
    </div>


@endsection

@push('scripts')
    @vite('resources/js/account-edit.js')
@endpush
