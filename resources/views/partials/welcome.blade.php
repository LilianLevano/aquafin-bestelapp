<div style="text-align:center; margin-top:100px;">
    <h1>Welkom {{ Auth::user()->role->name }}</h1>

    <p>Welkom op de Aquafin bestel app.</p>
    <a href="{{ route('login') }}" class="btn">Login</a>
</div>
