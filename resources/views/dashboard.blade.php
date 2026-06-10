@use(App\Models\Role)
@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
    <div class="py-12" style="min-height: 75vh;">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg card" style="padding:48px 28px;text-align:center;">
                <h2 class="font-semibold text-xl text-gray-800 mb-2" style="margin-bottom:8px;">
                    {{ __('Dashboard') }}
                </h2>
                @php
                    $roleName = auth()->user()->role->name ?? null;
                @endphp
                <h1 class="h1" style="margin-bottom: 8px;">
                    Welcome, {{ auth()->user()->first_name ?? 'Guest' }}
                </h1>
                <p class="muted text-gray-600" style="margin-bottom: 32px;">
                    {{ __("You're logged in as '$roleName' ") }}
                </p>
                <p class="muted text-gray-600" style="margin-bottom:32px;">
                    What would you like to manage?
                </p>



                @if($roleName === Role::ADMIN)
                    <div style="display:flex;gap:16px;justify-content:center;flex-wrap:wrap;">
                        <a href="{{ route('admin.accounts.index') }}" class="btn btn-primary">Manage Accounts</a>
                        <a href="{{ route('admin.roles.index') }}" class="btn btn-outline">Manage Roles</a>
                    </div>
                @endif

                @if($roleName === Role::TECHNIEKER)
                    <div style="display:flex;gap:16px;justify-content:center;flex-wrap:wrap;">
                        <a href="{{ route('orders.index') }}" class="btn btn-primary">Bekijk alle bestellingen</a>
                        <a href="{{ route('orders.create') }}" class="btn btn-outline">Plaats een bestelling</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
