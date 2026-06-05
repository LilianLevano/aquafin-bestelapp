<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Magazijnier - Aquafin</title>

    @vite(['resources/css/app.css'])
</head>
<body>

<header class="navbar">
    <h2>AQUAFIN</h2>

    <nav>
        <a class="nav-button" href="/magazijnier">Home</a>
        <a class="nav-button" href="/magazijnier/bestellingen">Bestellingen</a>
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