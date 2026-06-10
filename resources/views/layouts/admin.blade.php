<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — Aquafin</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @vite('resources/css/app.css')
</head>
<body class="bg-muted">
    <nav class="admin-nav">
        <div class="nav-inner">
            <div class="nav-left">
                <a href="{{ route('admin.accounts.index') }}" class="nav-brand">Admin</a>
                <a href="{{ route('admin.accounts.index') }}" class="nav-link {{ request()->is('admin/accounts*') ? 'active' : '' }}">Accounts</a>
                <a href="{{ route('admin.rollen.index') }}" class="nav-link {{ request()->is('admin/rollen*') ? 'active' : '' }}">Roles</a>
            </div>
            <div class="nav-right">
                <span class="muted">{{ auth()->user()->email ?? '' }}</span>
                <form method="POST" action="{{ route('logout') }}" style="display:inline">
                    @csrf
                    <button type="submit" class="btn btn-outline btn-sm">Log out</button>
                </form>
            </div>
        </div>
    </nav>

    <main class="container">
        @if(session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif
        @yield('content')
    </main>
</body>
</html>
