@extends('layouts.app')

@section('content')
    <div class="page-header">
        <h1>Admin Category</h1>

        <a href="{{route('admin.categories.create')}}" class="btn-primary">
            + Materiaal
        </a>
    </div>

    <table class="manager-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Naam</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
        @forelse($categories as $category)
            <tr>
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
