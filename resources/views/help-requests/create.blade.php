@extends('layouts.app')
@section('title', 'Hulp aanvraag aanmaken')

@php
    $showHulp = $errors->hasAny(['first_name', 'last_name', 'title', 'description']) || old('_form') === 'hulp';
@endphp

@section('content')
    <div id="section-hulp" @if(!$showHulp) @endif>
        {{-- Back button --}}
        <a href="{{ route('login') }}"
        style="display: inline-flex; align-items: center; gap: 6px; font-size: 13px; color: #6b7280; text-decoration: none; margin-bottom: 1rem;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 12H5M12 5l-7 7 7 7"/>
            </svg>
            Terug
        </a>
        <h1 class="h1">Request Help</h1>

        <form method="POST" action="{{ route('help-requests.store') }}" class="form" x-data="{ sent: false }" @submit.prevent="sent = true; $el.submit()">
            @csrf

            <fieldset :disabled="sent">
                <div class="field">
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email"
                        value="{{ old('email') }}" required
                        class="{{ $errors->has('email') && $showHulp ? 'is-invalid' : '' }}">
                    @if($showHulp)
                        @error('email') <p class="error">{{ $message }}</p> @enderror
                    @endif
                    <p id="check-input-email" style="display: none; color: #c61414; text-align: center;">De email veld moet gevuld worden</p>
                    <p id="check-format-email" style="display: none; color: #c61414; text-align: center;">De formaat van de email is fout.</p>
                </div>

                <div class="grid-2">
                    <div class="field">
                        <label for="first_name">First Name</label>
                        <input id="first_name" name="first_name"
                            value="{{ old('first_name') }}" required minlength="2"
                            class="{{ $errors->has('first_name') ? 'is-invalid' : '' }}">
                        @error('first_name') <p class="error">{{ $message }}</p> @enderror
                        <p id="check-input-first-name" style="display: none; color: #c61414; text-align: center;">De voornaam veld moet gevuld worden</p>
                        <p id="min-length-first-name-input" style="display: none; color: #c61414; text-align: center;">De voornaam bevat minstens 2 tekens.</p>
                    </div>
                    <div class="field">
                        <label for="last_name">Last Name</label>
                        <input id="last_name" name="last_name"
                            value="{{ old('last_name') }}" required minlength="2"
                            class="{{ $errors->has('last_name') ? 'is-invalid' : '' }}">
                        @error('last_name') <p class="error">{{ $message }}</p> @enderror
                        <p id="check-input-last-name" style="display: none; color: #c61414; text-align: center;">De achternaam veld moet gevuld worden</p>
                        <p id="min-length-last-name-input" style="display: none; color: #c61414; text-align: center;">De achternaam bevat minstens 2 tekens.</p>
                    </div>
                </div>

                <div class="field" id="title-field">
                    <label for="title">Title</label>
                    <input id="title" name="title"
                        value="{{ old('title') }}" required minlength="2" maxlength="50"
                        class="{{ $errors->has('title') ? 'is-invalid' : '' }}">
                    @error('title') <p class="error">{{ $message }}</p> @enderror
                    <p id="check-input-title" style="display: none; color: #c61414; text-align: center;">De titel veld moet gevuld worden</p>
                    <p id="max-length-title-input" style="display: none; color: #c61414; text-align: center;">De titel kan maximaal 50 tekens bevatten.</p>
                </div>

                <div class="field">
                    <label for="description">Beschrijving</label>
                    <textarea id="description" name="description" rows="4" required maxlength="400"
                            class="{{ $errors->has('description') ? 'is-invalid' : '' }}">{{ old('description') }}</textarea>
                    @error('description') <p class="error">{{ $message }}</p> @enderror
                    <p id="check-input-description" style="display: none; color: #c61414; text-align: center;">De beschrijving veld moet gevuld worden</p>
                    <p id="max-length-description-input" style="display: none; color: #c61414; text-align: center;">De beschrijving kan maximaal 400 tekens bevatten.</p>
                </div>

                <div class="row-end">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </fieldset>
        </form>
    </div>
@endsection

@push('scripts')
    @vite('resources/js/help-requests/help-request-create.js')
@endpush
