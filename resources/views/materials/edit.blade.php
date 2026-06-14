@extends('layouts.app')
@section('title', 'Materiaal bewerken')
@section('content')
    <div style="max-width: 480px; margin: 2rem auto; padding: 0 1rem;">

        <a href="{{ route('admin.materials.index') }}"
           style="display: inline-flex; align-items: center; gap: 6px; font-size: 13px; color: #6b7280; text-decoration: none; margin-bottom: 1rem;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 12H5M12 5l-7 7 7 7"/>
            </svg>
            Terug
        </a>

        <div style="background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 1.5rem;">

            <h2 style="font-size: 18px; font-weight: 500; color: #111827; margin: 0 0 1.5rem;">Materiaal bewerken</h2>

            <form action="{{ route('admin.materials.update', $material->id) }}" method="POST" enctype="multipart/form-data"  x-data="{ sent: false }" @submit.prevent="sent = true; $el.submit()">
                @csrf @method('PUT')
                <fieldset :disabled="sent">
                {{-- Naam --}}
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; font-size: 13px; color: #374151; margin-bottom: 4px;">Naam</label>
                    <input data-original="{{ $material->name }}" type="text" name="name" id="name"  value="{{ old('name', $material->name) }}" style="width: 100%; padding: 10px 12px; font-size: 14px; border: 1px solid #d1d5db; border-radius: 8px; box-sizing: border-box; outline: none;">
                    <p id="name-error" style="display:none; color:red; font-size:14px;">
                        Materiaalnaam moet minstens 3 tekens bevatten.
                    </p>
                    @error('name')
                    <span style="font-size: 12px; color: #dc2626;">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Categorie --}}
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; font-size: 13px; color: #374151; margin-bottom: 4px;">Categorie</label>
                    <select name="category_id" data-original="{{ $material->category->id }}"
                            style="width: 100%; padding: 10px 12px; font-size: 14px; border: 1px solid #d1d5db; border-radius: 8px; box-sizing: border-box; background: #fff; outline: none;">
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $material->category_id == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                    <span style="font-size: 12px; color: #dc2626;">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Beschrijving --}}
                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; font-size: 13px; color: #374151; margin-bottom: 4px;">
                        Beschrijving
                        <span style="color: #9ca3af;">(optioneel)</span>
                    </label>
                    <textarea name="description" rows="4" id="description" data-original="{{ $material->description }}"
                              style="width: 100%; padding: 10px 12px; font-size: 14px; border: 1px solid #d1d5db; border-radius: 8px; box-sizing: border-box; resize: vertical; outline: none;">{{ old('beschrijving', $material->description ?? '') }}</textarea>
                    <p id="description-error" style="display:none; color:red; font-size:14px;">
                        Materiaalnaam moet minstens 5 tekens bevatten.
                    </p>
                    @error('description')
                    <span style="font-size: 12px; color: #dc2626;">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Afbeelding --}}
                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; font-size: 13px; color: #374151; margin-bottom: 8px;">
                        Afbeelding
                        <span style="color: #9ca3af;">(optioneel)</span>
                    </label>

                    @if($material->image_path)
                        <div style="margin-bottom: 10px; background: #f3f4f6; border: 1px solid #e5e7eb; border-radius: 8px; height: 140px; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                            <img src="{{ asset('storage/pictures-materials/' . $material->image_path) }}"
                                 alt="{{ $material->name }}"
                                 style="width: 100%; height: 100%; object-fit: contain;">
                        </div>
                    @endif

                    <input type="file" name="image" accept="image/*"
                           style="width: 100%; font-size: 13px; color: #374151;">
                    @error('image')
                    <span style="font-size: 12px; color: #dc2626;">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Acties --}}
                <div style="display: flex; justify-content: flex-end; gap: 8px; border-top: 1px solid #f3f4f6; padding-top: 1.25rem;">
                    <a href="{{ route('admin.materials.index') }}"
                       style="font-size: 14px; padding: 10px 16px; border: 1px solid #d1d5db; border-radius: 8px; color: #374151; text-decoration: none;">
                        Annuleren
                    </a>
                    <button type="submit"
                            style="font-size: 14px; padding: 10px 16px; background: #2563eb; color: #fff; border: none; border-radius: 8px; cursor: pointer;">
                        Opslaan
                    </button>
                </div>
                </fieldset>
            </form>
        </div>
    </div>
@endsection


@push('scripts')
    @vite('resources/js/materials-edit.js')
@endpush
