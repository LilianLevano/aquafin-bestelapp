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

            <form action="{{ route('admin.materials.update', $materiaal->id) }}" method="POST">
                @csrf @method('PUT')

                {{-- Naam --}}
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; font-size: 13px; color: #374151; margin-bottom: 4px;">Naam</label>
                    <input type="text" name="name" value="{{ old('name', $materiaal->name) }}"
                           style="width: 100%; padding: 10px 12px; font-size: 14px; border: 1px solid #d1d5db; border-radius: 8px; box-sizing: border-box; outline: none;">
                    @error('name')
                    <span style="font-size: 12px; color: #dc2626;">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Categorie --}}
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; font-size: 13px; color: #374151; margin-bottom: 4px;">Categorie</label>
                    <select name="category_id"
                            style="width: 100%; padding: 10px 12px; font-size: 14px; border: 1px solid #d1d5db; border-radius: 8px; box-sizing: border-box; background: #fff; outline: none;">
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $materiaal->category_id == $category->id ? 'selected' : '' }}>
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
                    <textarea name="beschrijving" rows="4"
                              style="width: 100%; padding: 10px 12px; font-size: 14px; border: 1px solid #d1d5db; border-radius: 8px; box-sizing: border-box; resize: vertical; outline: none;">{{ old('beschrijving', $materiaal->beschrijving ?? '') }}</textarea>
                    @error('beschrijving')
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

            </form>
        </div>
    </div>
@endsection
