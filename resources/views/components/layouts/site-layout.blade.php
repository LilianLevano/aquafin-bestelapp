<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aquafin</title>
    @vite(['resources/css/app.css'])
</head>
<body>

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

<main class="manager-page">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif

    {{ $slot }}
</main>

<footer class="site-footer">
    Aquafin Bestelapp © 2026
</footer>

<script>
function toggleNav() {
    document.querySelector('.navbar-nav').classList.toggle('open');
}
</script>

</body>
</html>
