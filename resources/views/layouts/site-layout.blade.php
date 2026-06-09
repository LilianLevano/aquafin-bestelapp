<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aquafin</title>

    @vite(['resources/css/app.css'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<header class="navbar">
    <h2>AQUAFIN</h2>

    <nav>
        <a href="/technieker">Home</a>
        <a href="#">Bestellingen</a>
        <a href="/admin/catalogus">Catalogus</a>
        <a href="/technieker/bestellen">Bestellen</a>
        <a href="#">Accounts</a>
        <a href="#">Rollen</a>
        <a href="#">Hulpaanvraag</a>
        <a href="/admin/aanvragen">Aanvragen</a>
    </nav>
</header>

<main class="manager-page">
    {{ $slot }}
</main>

<footer>
    Aquafin Bestelapp © 2026
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>