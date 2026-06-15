@extends('layouts.app')
@section('title', 'Categorieën')

@section('content')
    <div class="page-header">
        <h1>Admin Categorieën</h1>

        <a href="{{route('admin.categories.create')}}" class="btn-primary">
            + Categorie
        </a>
    </div>

    <div class="mb">
        <input type="text" id="search-categories" placeholder="Zoek een categorie op naam..." autocomplete="off"
               style="margin-bottom: 0; padding: .5rem; width: 100%; position: relative;">
        <ul id="search-suggestions" style=" list-style: none; margin-bottom: 10px; padding: 0; border: 1px solid #ccc; border-top: none; position: absolute; background: white; width: 40%; z-index: 100; display: none; "></ul>
    </div>

    <table class="manager-table" id="categories-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Naam</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody id="categories-tbody">
        @forelse($categories as $category)
            <tr data-id="{{$category->id}}" data-name="{{$category->name}}">
                <td>{{$category->id}}</td>
                <td>{{$category->name}}</td>
                <td><div class="table-actions">

                        <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn-edit">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            <span class="text-button">Bewerken</span>
                        </a>
                        <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST"
                              onsubmit="return confirm('Weet je zeker dat je dit categorie wil verwijderen?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-delete">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4a1 1 0 011-1h4a1 1 0 011 1v2"/></svg>
                                <span class="text-button">Verwijderen</span>
                            </button>
                        </form>
                    </div></td>
            </tr>

        @empty
            <p>There are no categories to display.</p>
        @endforelse
        </tbody>
    </table>
@endsection

@push('scripts')
    @vite('resources/js/category.js')
@endpush
