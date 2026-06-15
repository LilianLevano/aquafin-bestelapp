@extends('layouts.app')
@section('title', 'Materialen')

@section('content')
    <div class="materials-container">

    <div style="display: flex; flex-direction: column; justify-content: center; align-items: center; margin-bottom: 1.5rem;">
        <h1>Materialen</h1>
        <a href="{{ route('admin.materials.create') }}"
           style="font-size: 14px; padding: 10px 16px; background: #16a34a; color: #fff; border-radius: 8px; text-decoration: none;">
            + Nieuw materiaal
        </a>
    </div>

    <div class="mb">
        <input type="text" id="search-materials" placeholder="Zoek een materiaal op naam..." autocomplete="off"
               style="margin-bottom: 0; padding: .5rem; width: 100%; position: relative;">
        <ul id="search-suggestions" style=" list-style: none; margin-bottom: 10px; padding: 0; border: 1px solid #ccc; border-top: none; position: absolute; background: white; width: 40%; z-index: 100; display: none; "></ul>
    </div>



        @if(session('success'))
            <p class="alert alert-success">{{session('success')}}</p>
        @endif

        @if(session('error'))
            <p class="alert alert-error">{{session('error')}}</p>
        @endif


        <div class="row-between mb">
            <button type="button" class="btn btn-outline btn-sm" onclick="location.reload()">↺ Refresh</button>
        </div>
        <div class="materials-table-wrapper">
            <table class="materials-table">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Naam</th>
                    <th class="category-col">Categorie</th>
                    <th class="col-action">Actie</th>
                </tr>
                </thead>
                <tbody id="materials-tbody">
                @forelse($materials as $material)
                    <tr data-id="{{ $material->id }}" data-name="{{ $material->name }}">
                        <td class="col-id">#{{ $material->id }}</td>
                        <td class="col-name">{{ $material->name }}</td>
                        <td class="category-col">
                            @if($material->category)
                                <span class="category-badge">{{ $material->category->name }}</span>
                            @else
                                <span class="category-badge no-category">Geen categorie</span>
                            @endif
                        </td>
                        <td class="col-action">
                            <div class="table-actions">
                                <a href="{{ route('admin.materials.show', $material->id) }}" class="btn-details">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                                    <span class="text-button">Details</span>
                                </a>
                                <a href="{{ route('admin.materials.edit', $material->id) }}" class="btn-edit">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                    <span class="text-button">Bewerken</span>
                                </a>
                                <form action="{{ route('admin.materials.destroy', $material->id) }}" method="POST"
                                      onsubmit="return confirm('Weet je zeker dat je dit materiaal wil verwijderen?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-delete">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4a1 1 0 011-1h4a1 1 0 011 1v2"/></svg>
                                        <span class="text-button">Verwijderen</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <p>Er zijn geen materialen beschikbaar.</p>
                @endforelse
                </tbody>
            </table>
        </div>

    </div>
@endsection

@push('scripts')
    @vite('resources/js/materials/materials-index.js')
@endpush
