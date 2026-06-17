@extends('layouts.app')
@section('title', 'Login')

@php
    // $hasLoginErrors = true if any login-related error or login form was last attempted
    $hasLoginErrors = $errors->hasAny(['email', 'password']) || old('_active-form') === 'login';
    // $hasHelpRequestErrors = true if any help form error or help form was last attempted
    $hasHelpRequestErrors = $errors->hasAny(['first_name', 'last_name', 'title', 'description']) || old('_active-form') === 'help';
    // session() helpers for displaying feedback
    $message = session('message', null);
    $success = session('success', null);
@endphp

@section('content')
    <input type="hidden" name="_generic-placeholder" value="{{ __('Het ___ veld moet gevuld worden') }}">
    <div class="centered">
        <div class="card" style="max-width:520px;width:100%;">
            {{-- LOGIN FORM --}}
            <div id="section-login">
                <h1 class="h1">{{ __('Login') }}</h1>

                @if(session('error'))
                    <div class="alert alert-error">{{ session('error') }}</div>
                @endif

                <form name="login" x-data="{ sent: false }" @submit.prevent="sent = true; $el.submit()" method="POST" action="{{ route('login') }}" class="form">
                    @csrf
                    <input type="hidden" name="_active-form" value="login">
                    <fieldset :disabled="sent">
                        <div class="field">
                            <label for="email">{{ __('Email') }}</label>
                            <input
                                id="email"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                data-translation="{{ __('email') }}"
                                required
                                autofocus
                                class="{{ $errors->has('email') && !$hasHelpRequestErrors ? 'is-invalid' : '' }}">
                        </div>

                        <div class="field" id="password-field">
                            <label for="password">{{ __('Wachtwoord') }}</label>
                            <input
                                id="password"
                                type="password"
                                name="password"
                                data-translation="{{ __('wachtwoord') }}"
                                required
                                class="{{ $errors->has('password') && !$hasHelpRequestErrors ? 'is-invalid' : '' }}">

                            <svg id="show-password" class="self-end justify-self-center hover:cursor-pointer" xmlns="http://www.w3.org/2000/svg" height="35px" viewBox="0 -960 960 960" width="35px" fill="#000000"><path id="path-svg" d="m634-422-48.67-48.67q20.34-63-27-108-47.33-45-107.66-26.66L402-654q17-10 36.83-14.67 19.84-4.66 41.17-4.66 72.33 0 122.83 50.5T653.33-500q0 21.33-5 41.5T634-422Zm128.67 128-46-45.33Q762-373 796.17-414.17q34.16-41.16 52.5-85.83-50-107.67-147.84-170.5-97.83-62.83-214.16-62.83-37.67 0-76.34 6.66Q371.67-720 346-710l-51.33-52q37-16.33 87.66-27.17Q433-800 483.33-800q145.67 0 264 82.17Q865.67-635.67 920-500q-25 62.33-64.83 114.5-39.84 52.17-92.5 91.5ZM808-61.33 640-226.67q-35 13-76.17 19.84Q522.67-200 480-200q-147.67 0-266.33-82.17Q95-364.33 40-500q20.33-52.33 54.67-100.5 34.33-48.17 82-90.17L56-812l46.67-47.33 750 750-44.67 48ZM222.67-644q-34.34 26.67-65.34 66.33-31 39.67-46.66 77.67 50.66 107.67 150.16 170.5t224.5 62.83q28.67 0 56.34-3.5 27.66-3.5 45-9.83L532-335.33q-11 4.33-25 6.5-14 2.16-27 2.16-71.67 0-122.5-50.16Q306.67-427 306.67-500q0-13.67 2.16-27 2.17-13.33 6.5-25l-92.66-92Zm309.66 125.67Zm-127.66 63.66Z"/></svg>
                        </div>

                        <div class="row-between">
                            <button id="toggle-help-request-on" type="button" class="link">{{ __('Problemen met autorisatie') }}?</button>
                            <button type="submit" class="btn btn-primary">{{ __('Log in') }}</button>
                        </div>
                    </fieldset>
                </form>
            </div>

            {{-- HELP FORM --}}
            <div id="section-help-request" class="visually-hidden">
                <h1 class="h1">{{ __('Hulp aanvragen') }}</h1>

                <form
                    name="help-request"
                    method="POST"
                    action="{{ route('help-requests.store') }}"
                    class="form">
                    @csrf
                    <input type="hidden" name="_active-form" value="help">

                    <div class="field">
                        <label for="hulp-mail">{{ __('Email') }}</label>
                        <input
                            id="hulp-mail"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            data-translation="{{ __('email') }}"
                            required
                            class="{{ $errors->has('email') && !$hasLoginErrors ? 'is-invalid' : '' }}">
                    </div>

                    <div class="grid-2">
                        <div class="field">
                            <label for="hulp-first_name">{{ __('Voornaam') }}</label>
                            <input
                                id="hulp-first_name"
                                name="first_name"
                                value="{{ old('first_name') }}"
                                data-translation="{{ __('voornaam') }}"
                                required
                                class="{{ $errors->has('first_name') && !$hasLoginErrors ? 'is-invalid' : '' }}">
                        </div>
                        <div class="field">
                            <label for="hulp-last_name">{{ __('Achternaam') }}</label>
                            <input
                                id="hulp-last_name"
                                name="last_name"
                                value="{{ old('last_name') }}"
                                data-translation="{{ __('achternaam') }}"
                                required
                                class="{{ $errors->has('last_name') && !$hasLoginErrors ? 'is-invalid' : '' }}">
                        </div>
                    </div>

                    <div class="field">
                        <label for="hulp-title">{{ __('Titel') }}</label>
                        <input
                            id="hulp-title"
                            name="title"
                            value="{{ old('title') }}"
                            data-translation="{{ __('titel') }}"
                            required
                            class="{{ $errors->has('title') && !$hasLoginErrors ? 'is-invalid' : '' }}">
                    </div>

                    <div class="field">
                        <label for="hulp-description">{{ __('Beschrijving') }}</label>
                        <textarea
                            id="hulp-description"
                            name="description"
                            data-translation="{{ __('beschrijving') }}"
                            rows="4"
                            required
                            class="{{ $errors->has('description') && !$hasLoginErrors ? 'is-invalid' : '' }}">{{ old('description') }}
                        </textarea>
                    </div>

                    <div class="row-between">
                        <button id="toggle-help-request-off" type="button" class="link">{{ __('← Terug naar login') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('Stuur') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @vite('resources/js/auth-login.js')
@endpush
