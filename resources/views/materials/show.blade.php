@extends('layouts.app')

@section('content')
    <div style="max-width: 420px; margin: 2rem auto; padding: 0 1rem;">
        <div style="background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; overflow: hidden;">

            {{-- Placeholder image --}}
            <div style="background: #f3f4f6; border-bottom: 1px solid #e5e7eb; height: 140px; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 6px; color: #9ca3af;">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="18" height="18" rx="2"/>
                    <circle cx="8.5" cy="8.5" r="1.5"/>
                    <path d="M21 15l-5-5L5 21"/>
                </svg>
                <span style="font-size: 12px;">Afbeelding niet beschikbaar</span>
            </div>

            <div style="padding: 1rem;">

                {{-- Naam + categorie --}}
                <div style="display: flex; align-items: flex-start; justify-content: space-between; gap: 8px; margin-bottom: 6px;">
                    <span style="font-size: 16px; font-weight: 500; color: #111827;">{{ $material->name }}</span>
                    <span style="flex-shrink: 0; background: #eff6ff; color: #1d4ed8; font-size: 11px; padding: 2px 8px; border-radius: 6px;">
          {{ $material->category->name ?? 'Geen categorie' }}
        </span>
                </div>

                {{-- ID --}}
                <span style="font-size: 11px; color: #9ca3af; font-family: monospace;">#{{ $material->id }}</span>

                {{-- Beschrijving --}}
                <div style="background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; padding: 0.75rem; margin-top: 0.875rem;">
                    <p style="font-size: 11px; color: #6b7280; margin: 0 0 4px;">Beschrijving</p>
                    <p style="font-size: 13px; color: #9ca3af; margin: 0; font-style: italic;">
                        {{ $material->description ?? 'Geen beschrijving beschikbaar.' }}
                    </p>
                </div>


            </div>

            {{-- Back button --}}
            <button onclick="history.back()"
               style="display: inline-flex; align-items: center; gap: 6px; font-size: 13px; color: #6b7280; text-decoration: none; margin-bottom: 1rem;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 12H5M12 5l-7 7 7 7"/>
                </svg>
                Terug
            </button>
        </div>

    </div>
@endsection
