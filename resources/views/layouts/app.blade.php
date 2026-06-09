<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Aquawerf'))</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        @include('layouts.navigation')
        {{--
        <header class="navbar">
            <h2 class="navbar-brand">AQUAFIN</h2>

            <nav class="navbar-nav">
                <a href="/technieker">Home</a>
                <a href="/technieker/bestellen">Bestellen</a>
                <a href="/admin/catalogus">Catalogus</a>
                <a href="/admin/aanvragen">Aanvragen</a>
                <a href="#">Accounts</a>
                <a href="#">Rollen</a>
                <a href="#">Hulpaanvraag</a>
            </nav>

            <button class="navbar-toggle" onclick="toggleNav()" aria-label="Menu">☰</button>
        </header>
        --}}

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Toast notifications -->
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        <!-- Page Content -->
        <main class="manager-page">
          @yield('content')
          {{ $slot ?? '' }}
        </main>
    </div>

    @include('partials.footer')
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function toggleNav() {
    document.querySelector('.navbar-nav').classList.toggle('open');
}
</script>
</html>
