@extends('layouts.app')
@section('title', 'Hulp aanvraag')
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

    <form method="POST" action="{{ route('help-request.store') }}" class="form">
        @csrf


        <div class="field">
            <label for="email">Email</label>
            <input id="email" type="email" name="email"
                   value="{{ old('email') }}" required
                   class="{{ $errors->has('email') && $showHulp ? 'is-invalid' : '' }}">
            @if($showHulp)
                @error('email') <p class="error">{{ $message }}</p> @enderror
            @endif
        </div>

        <div class="grid-2">
            <div class="field">
                <label for="first_name">First Name</label>
                <input id="first_name" name="first_name"
                       value="{{ old('first_name') }}" required
                       class="{{ $errors->has('first_name') ? 'is-invalid' : '' }}">
                @error('first_name') <p class="error">{{ $message }}</p> @enderror
            </div>
            <div class="field">
                <label for="last_name">Last Name</label>
                <input id="last_name" name="last_name"
                       value="{{ old('last_name') }}" required
                       class="{{ $errors->has('last_name') ? 'is-invalid' : '' }}">
                @error('last_name') <p class="error">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="field">
            <label for="title">Title</label>
            <input id="title" name="title"
                   value="{{ old('title') }}" required
                   class="{{ $errors->has('title') ? 'is-invalid' : '' }}">
            @error('title') <p class="error">{{ $message }}</p> @enderror
        </div>

        <div class="field">
            <label for="description">Description</label>
            <textarea id="description" name="description" rows="4" required
                      class="{{ $errors->has('description') ? 'is-invalid' : '' }}">{{ old('description') }}</textarea>
            @error('description') <p class="error">{{ $message }}</p> @enderror
        </div>

        <div class="row-end">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>
</div>
@endsection


@push('scripts')

@endpush
