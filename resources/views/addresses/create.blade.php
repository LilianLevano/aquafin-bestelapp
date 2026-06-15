@extends('layouts.app')
@section('title', __('Nieuw adres'))

@section('content')
    <div class="card" style="max-width:640px;margin:0 auto;">
        <div class="tabs">
            <a href="{{ route('admin.addresses.index') }}" class="tab">{{ __('Overzicht') }}</a>
            <a href="{{ route('admin.addresses.create') }}" class="tab tab-active">{{ __('Nieuw') }}</a>
        </div>

        <h1 class="h1">{{ __('Nieuw adres') }}</h1>

        <form id="create-form" method="POST" action="{{ route('admin.addresses.store') }}" class="form">
            @csrf

            <div class="field">
                <label for="type">{{ __('Type') }}</label>
                <input id="type" name="type" value="{{ old('type') }}" required
                    class="{{ $errors->has('type') ? 'is-invalid' : '' }}">
                @error('type') <p class="error">{{ $message }}</p> @enderror
            </div>

            <div class="field">
                <label for="street">{{ __('Straat') }}</label>
                <input id="street" name="street" value="{{ old('street') }}" required
                    class="{{ $errors->has('street') ? 'is-invalid' : '' }}">
                @error('street') <p class="error">{{ $message }}</p> @enderror
            </div>

            <div class="grid-2">
                <div class="field">
                    <label for="house_number">{{ __('Huisnummer') }}</label>
                    <input id="house_number" name="house_number" value="{{ old('house_number') }}" required
                        class="{{ $errors->has('house_number') ? 'is-invalid' : '' }}">
                    @error('house_number') <p class="error">{{ $message }}</p> @enderror
                </div>
                <div class="field">
                    <label for="unit_number">{{ __('Unitnummer') }} <span class="muted">({{ __('optioneel') }})</span></label>
                    <input id="unit_number" name="unit_number" value="{{ old('unit_number') }}"
                        class="{{ $errors->has('unit_number') ? 'is-invalid' : '' }}">
                    @error('unit_number') <p class="error">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="field">
                <label for="city">{{ __('Plaats') }}</label>
                <input id="city" name="city" value="{{ old('city') }}" required
                    class="{{ $errors->has('city') ? 'is-invalid' : '' }}">
                @error('city') <p class="error">{{ $message }}</p> @enderror
            </div>

            <div class="field">
                <label for="postal_code">{{ __('Postcode') }}</label>
                <input id="postal_code" name="postal_code" value="{{ old('postal_code') }}" required
                    class="{{ $errors->has('postal_code') ? 'is-invalid' : '' }}">
                @error('postal_code') <p class="error">{{ $message }}</p> @enderror
            </div>

            <div class="field">
                <label for="country_iso">{{ __('Land ISO') }}</label>
                <input id="country_iso" name="country_iso" value="{{ old('country_iso') }}" required
                    class="{{ $errors->has('country_iso') ? 'is-invalid' : '' }}">
                @error('country_iso') <p class="error">{{ $message }}</p> @enderror
            </div>

            <div class="row-end">
                <a href="{{ route('admin.addresses.index') }}" class="btn btn-outline">{{ __('Annuleren') }}</a>
                <button id="submit-btn" type="submit" class="btn btn-primary">{{ __('Adres aanmaken') }}</button>
            </div>
        </form>
    </div>
@endsection
