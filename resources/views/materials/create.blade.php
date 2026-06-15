@extends('layouts.app')

@section('content')
    <div class="form-card">
        <h1>Materiaal aanmaken</h1>

        <div style="text-align:left; margin-bottom:20px;">
            <a href="{{ route('admin.materials.index') }}" class="btn-primary">← Keer terug</a>
        </div>

        <form x-data="{ sent: false }" @submit.prevent="sent = true; $el.submit()" onsubmit="disableForm(event)" action="{{route('admin.materials.store')}}" method="POST" enctype="multipart/form-data" >
            @csrf

            <fieldset :disabled="sent">
            <label>Naam</label>
            <input type="text"
                    id="name"
                    name="name"
                    class="text-field"
                    placeholder="Typ materiaal naam"
                    onblur="validateNaam()"
                    required
                    min="2">
            <p id="name-error" style="display:none; color:red; font-size:14px;">
                Materiaalnaam moet minstens 3 tekens bevatten.
            </p>

            <label>Categorie</label>
            <select id="category_id" name="category_id" class="text-field" onblur="validateCategorie()" required>
                @foreach($categories as $category)
                    <option value="{{$category->id}}">{{$category->name}}</option>

                @endforeach
            </select>
            <p id="categorieError" style="display:none; color:red; font-size:14px;">
                Kies een geldige categorie.
            </p>

            {{-- Beschrijving --}}
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-size: 13px; color: #374151; margin-bottom: 4px;">Beschrijving</label>
                <textarea name="description" id="description" required rows="4" style="width: 100%; padding: 10px 12px; font-size: 14px; border: 1px solid #d1d5db; border-radius: 8px; box-sizing: border-box; resize: vertical; outline: none;">{{ old('beschrijving') }}</textarea>
                @error('description')
                <span style="font-size: 12px; color: #dc2626;">{{ $message }}</span>
                @enderror
                <p id="description-error" style="display:none; color:red; font-size:14px;">
                    Materiaalnaam moet minstens 5 tekens bevatten.
                </p>
            </div>

            <input type="file" name="image" accept="image/*" required
                   style="width: 100%; font-size: 13px; color: #374151;">
            @error('image')
            <span style="font-size: 12px; color: #dc2626;">{{ $message }}</span>
            @enderror

            <div class="answer-button">
                <button id="submitBtn" style="margin-top: 10px;" type="submit" class="btn-primary">
                    Aanmaken
                </button>
            </div>
            </fieldset>
        </form>
    </div>
@endsection

@push('scripts')
    @vite('resources/js/materials/materials-create.js')
@endpush
