@extends('layouts.app')

@section('content')
    <div class="page-header">
        <h1>Admin Category</h1>

        <a href="/admin/category/material" class="btn-primary">
            + Materiaal
        </a>
    </div>

    <table class="manager-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Naam</th>
                <th>Categorie</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
            <tr>
                <td>1</td>
                <td>Pomp</td>
                <td>Watermateriaal</td>
                <td>
                    <a href="/admin/category/edit" class="btn-edit">Edit</a>
                    <button class="btn-delete">Delete</button>
                </td>
            </tr>

            <tr>
                <td>2</td>
                <td>Buis</td>
                <td>Installatie</td>
                <td>
                    <a href="/admin/category/edit" class="btn-edit">Edit</a>
                    <button class="btn-delete">Delete</button>
                </td>
            </tr>

            <tr>
                <td>3</td>
                <td>Kabel</td>
                <td>Elektriciteit</td>
                <td>
                    <a href="/admin/category/edit" class="btn-edit">Edit</a>
                    <button class="btn-delete">Delete</button>
                </td>
            </tr>
        </tbody>
    </table>
@endsection
