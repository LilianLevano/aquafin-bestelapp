@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')
<div class="card" style="max-width:600px;margin:0 auto;text-align:center;padding:48px 28px;">
    <h1 class="h1" style="margin-bottom:8px;">Welcome, {{ auth()->user()->voornaam ?? 'Admin' }}</h1>
    <p class="muted" style="margin-bottom:32px;">What would you like to manage?</p>

    <div style="display:flex;gap:16px;justify-content:center;flex-wrap:wrap;">
        <a href="{{ route('admin.accounts.index') }}" class="btn btn-primary">Manage Accounts</a>
        <a href="{{ route('admin.rollen.index') }}" class="btn btn-outline">Manage Roles</a>
    </div>
</div>
@endsection
