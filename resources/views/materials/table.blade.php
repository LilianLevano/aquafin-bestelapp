@extends('layouts.app')

@section('content')
    <h1>Category</h1>

    <table class="manager-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Naam</th>
                <th>Categorie</th>
            </tr>
        </thead>

        <tbody>
            <tr>
                <td>1</td>
                <td>Pomp</td>
                <td>Watermateriaal</td>
            </tr>

            <tr>
                <td>2</td>
                <td>Buis</td>
                <td>Installatie</td>
            </tr>

            <tr>
                <td>3</td>
                <td>Kabel</td>
                <td>Elektriciteit</td>
            </tr>
        </tbody>
    </table>
@endsection
