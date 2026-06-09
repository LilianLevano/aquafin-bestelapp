<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Aquafin bestelapp</title>
    @vite(['resources/css/app.css'])
</head>
<body>
  <h1>Welkom terug!</h1>
  <p>Click the button below to log in.</p>

 <a href="{{ route('login') }}" class="btn-primary">Login</a>

 <a href="/besteklijst" class="btn-primary" style="margin-left: 10px;">Besteklijst</a>
</a>
</body>
</html>
