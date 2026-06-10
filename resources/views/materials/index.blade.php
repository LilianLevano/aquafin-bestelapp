@extends('layouts.app')
@section('title', 'Materialen')
@section('content')
    <h1>Materialen</h1>

    <table class="manager-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Naam</th>
                <th>Categorie</th>
                <th>Actie</th>
            </tr>
        </thead>

        <tbody>

            @foreach($materialen as $materiaal)
                <tr>
                    <td>{{$materiaal->id}}</td>
                    <td>{{$materiaal->name}}</td>
                    <td>{{$materiaal->category->name}}</td>
                    <td><button class="btn-primary"><a href="{{route('admin.materials.show', $materiaal->id)}}">Meer details</a></button></td>
                </tr>

            @endforeach

        </tbody>
    </table>
@endsection
