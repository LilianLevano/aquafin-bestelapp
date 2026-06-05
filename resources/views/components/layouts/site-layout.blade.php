<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Aquafin</title>
    @vite(['resources/css/app.css'])
</head>
<body>

<header class="navbar">
    <h2>AQUAFIN</h2>

    <nav>
        <a href="/technieker">Home</a>
        <a href="/technieker/bestellen">Bestellen</a>
        <a href="/admin/catalogus">Catalogus</a>
        <a href="/admin/aanvragen">Aanvragen</a>
        <a href="#">Accounts</a>
        <a href="#">Rollen</a>
        <a href="#">Hulpaanvraag</a>
    </nav>
</header>

<main class="manager-page">
    {{ $slot }}
</main>

<footer>
    Aquafin Bestelapp © 2026
</footer>

</body>
</html>