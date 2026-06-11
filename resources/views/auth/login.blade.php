@extends('layouts.app')
@section('title', 'Login')

@php
    $showHulp = $errors->hasAny(['first_name', 'last_name', 'title', 'description']) || old('_form') === 'hulp';
@endphp

@section('content')
    <div class="centered">
        <div class="card" style="max-width:520px;width:100%;">

            {{-- LOGIN FORM --}}
            <div id="section-login" @if($showHulp) style="display:none" @endif>
                <h1 class="h1">Login</h1>

                @if(session('status'))
                    <div class="alert alert-success">{{ session('status') }}</div>
                @endif


                @if(session('error'))
                    <div class="alert alert-error">{{ session('error') }}</div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="form">
                    @csrf

                    <div class="field">
                        <label for="email">Email</label>
                        <input id="email" type="email" name="email"
                            value="{{ old('email') }}" required autofocus
                            class="{{ $errors->has('email') && !$showHulp ? 'is-invalid' : '' }}">
                        @if(!$showHulp)
                            @error('email') <p class="error">{{ $message }}</p> @enderror
                        @endif
                        <p id="check-input-email" style="display: none; color: #c61414; text-align: center;">De email veld moet gevuld worden</p>
                    </div>

                    <div class="field" id="password-field">
                        <label for="password">Password</label>
                        <input id="password" type="password" name="password" required
                            class="{{ $errors->has('password') && !$showHulp ? 'is-invalid' : '' }}">
                        @if(!$showHulp)
                            @error('password') <p class="error">{{ $message }}</p> @enderror
                        @endif
                        <p id="check-input-password" style="display: none; color: #c61414; text-align: center;">De wachtwoord veld moet gevuld worden</p>
                        <svg id="show-password" class="self-end justify-self-center hover:cursor-pointer" xmlns="http://www.w3.org/2000/svg" height="35px" viewBox="0 -960 960 960" width="35px" fill="#000000"><path id="path-svg" d="M602.83-377.17q50.5-50.5 50.5-122.83t-50.5-122.83q-50.5-50.5-122.83-50.5t-122.83 50.5q-50.5 50.5-50.5 122.83t50.5 122.83q50.5 50.5 122.83 50.5t122.83-50.5ZM401.5-421.5q-32.17-32.17-32.17-78.5t32.17-78.5q32.17-32.17 78.5-32.17t78.5 32.17q32.17 32.17 32.17 78.5t-32.17 78.5q-32.17 32.17-78.5 32.17t-78.5-32.17Zm-186.17 139Q96.67-365 40-500q56.67-135 175.33-217.5Q334-800 480-800t264.67 82.5Q863.33-635 920-500q-56.67 135-175.33 217.5Q626-200 480-200t-264.67-82.5ZM480-500Zm217.5 169.83q99.17-63.5 151.17-169.83-52-106.33-151.17-169.83-99.17-63.5-217.5-63.5t-217.5 63.5Q163.33-606.33 110.67-500q52.66 106.33 151.83 169.83 99.17 63.5 217.5 63.5t217.5-63.5Z"/></svg>
                    </div>

                    <div class="row-between">
                        <button type="button" class="link" onclick="toggleHulp(true)"><a href="{{route('help-request.create')}}"> Problemen met autorisatie?</a></button>
                        <button type="submit" class="btn btn-primary">Login</button>
                    </div>
                </form>
            </div>

            {{-- HELP FORM --}}
        </div>
    </div>
@endsection

@push('scripts')
    @vite('resources/js/auth-login.js')
@endpush
