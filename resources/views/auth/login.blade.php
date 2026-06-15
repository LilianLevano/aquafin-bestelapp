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
                    </div>

                    <div class="field">
                        <label for="password">Password</label>
                        <input id="password" type="password" name="password" required
                            class="{{ $errors->has('password') && !$showHulp ? 'is-invalid' : '' }}">
                        @if(!$showHulp)
                            @error('password') <p class="error">{{ $message }}</p> @enderror
                        @endif
                    </div>

                    <div class="row-between">
                        <button type="button" class="link" onclick="toggleHulp(true)">Need help?</button>
                        <button type="submit" class="btn btn-primary">Login</button>
                    </div>
                </form>
            </div>

            {{-- HELP FORM --}}
            <div id="section-hulp" @if(!$showHulp) style="display:none" @endif>
                <button type="button" class="back-link" onclick="toggleHulp(false)">← Back to login</button>
                <h1 class="h1">Request Help</h1>

                <form method="POST" action="{{ route('help-requests.store') }}" class="form">
                    @csrf
                    <input type="hidden" name="_form" value="hulp">

                    <div class="field">
                        <label for="hulp-mail">Email</label>
                        <input id="hulp-mail" type="email" name="email"
                            value="{{ old('mail') }}" required
                            class="{{ $errors->has('mail') && $showHulp ? 'is-invalid' : '' }}">
                        @if($showHulp)
                            @error('mail') <p class="error">{{ $message }}</p> @enderror
                        @endif
                    </div>

                    <div class="grid-2">
                        <div class="field">
                            <label for="hulp-first_name">First Name</label>
                            <input id="hulp-first_name" name="first_name"
                                value="{{ old('first_name') }}" required
                                class="{{ $errors->has('first_name') ? 'is-invalid' : '' }}">
                            @error('first_name') <p class="error">{{ $message }}</p> @enderror
                        </div>
                        <div class="field">
                            <label for="hulp-last_name">Last Name</label>
                            <input id="hulp-last_name" name="last_name"
                                value="{{ old('last_name') }}" required
                                class="{{ $errors->has('last_name') ? 'is-invalid' : '' }}">
                            @error('last_name') <p class="error">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="field">
                        <label for="hulp-title">Title</label>
                        <input id="hulp-title" name="title"
                            value="{{ old('title') }}" required
                            class="{{ $errors->has('title') ? 'is-invalid' : '' }}">
                        @error('title') <p class="error">{{ $message }}</p> @enderror
                    </div>

                    <div class="field">
                        <label for="hulp-description">Description</label>
                        <textarea id="hulp-description" name="description" rows="4" required
                                class="{{ $errors->has('description') ? 'is-invalid' : '' }}">{{ old('description') }}</textarea>
                        @error('description') <p class="error">{{ $message }}</p> @enderror
                    </div>

                    <div class="row-end">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @vite('resources/js/auth-login.js')
@endpush
