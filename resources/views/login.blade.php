@extends('layouts.app')
@section('title', 'Login')

@section('content')
<div class="centered">
    <div class="card" style="max-width:420px;width:100%;">
        <h1 class="h1">Login</h1>

        @if(session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="form">
            @csrf

            <div class="field">
                <label for="mail">Email</label>
                <input id="mail" type="email" name="mail" value="{{ old('mail') }}" required autofocus>
                @error('mail') <p class="error">{{ $message }}</p> @enderror
            </div>

            <div class="field">
                <label for="password">Wachtwoord</label>
                <input id="password" type="password" name="password" required>
                @error('password') <p class="error">{{ $message }}</p> @enderror
            </div>

            <div class="row-between">
                <a href="{{ route('hulp.create') }}" class="link">Hulp</a>
                <button type="submit" class="btn btn-primary">Login</button>
            </div>
        </form>
    </div>
</div>
@endsection
