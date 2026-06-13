@extends('layouts.app')
@section('title', 'Materialen')
@section('content')

    <div style="display: flex; flex-direction: column; justify-content: center; align-items: center; margin-bottom: 1.5rem;">
        <h1>Materialen</h1>
        <a href="{{ route('admin.materials.create') }}"
           style="font-size: 14px; padding: 10px 16px; background: #16a34a; color: #fff; border-radius: 8px; text-decoration: none;">
            + Nieuw materiaal
        </a>
    </div>

    <div class="mb">
        <input type="text" id="search-materials" placeholder="Zoek rol..." autocomplete="off"
               style="margin-bottom: 0; padding: .5rem; width: 100%; position: relative;">
        <ul id="search-suggestions" style=" list-style: none; margin-bottom: 10px; padding: 0; border: 1px solid #ccc; border-top: none; position: absolute; background: white; width: 40%; z-index: 100; display: none; "></ul>
    </div>

    <table class="manager-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Naam</th>
                <th>Categorie</th>
                <th>Actie</th>
            </tr>
        </thead>

        <tbody id="materials-tbody">

            @foreach($materials as $material)
                <tr data-id="{{ $material->id }}" data-name="{{ $material->name }}">
                    <td>{{$material->id}}</td>
                    <td>{{$material->name}}</td>
                    <td>{{$material->category->name ?? 'Geen categorie'}}</td>
                    <td>
                        <div style="display: flex; flex-direction: column; gap: 6px; align-items: flex-start;">
                            <a href="{{ route('admin.materials.show', $material->id) }}"
                               style="font-size: 14px; padding: 10px 16px; background: #2563eb; color: #fff; border-radius: 8px; text-decoration: none; width: 100%; text-align: center; box-sizing: border-box;">
                                Meer details
                            </a>
                            <div style="display: flex; gap: 6px; width: 100%;">
                                <a href="{{ route('admin.materials.edit', $material->id) }}"
                                   style="font-size: 14px; padding: 10px 16px; border: 1px solid #d1d5db; border-radius: 8px; color: #374151; text-decoration: none; flex: 1; text-align: center; box-sizing: border-box;">
                                    Bewerken
                                </a>
                                <form action="{{ route('admin.materials.destroy', $material->id) }}" method="POST" style="margin: 0; flex: 1;">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            style="font-size: 14px; padding: 10px 16px; border: 1px solid #fca5a5; border-radius: 8px; color: #dc2626; background: none; cursor: pointer; width: 100%; box-sizing: border-box;">
                                        Verwijderen
                                    </button>
                                </form>
                            </div>
                        </div>
                    </td>
                </tr>

            @endforeach

        </tbody>
    </table>
@endsection

@push('scripts')
    @vite('resources/js/materials-index.js')
@endpush
