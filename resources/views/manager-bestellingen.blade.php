@extends('layouts.app')

@section('content')
    <h1>Bestellingen</h1>

    <table class="manager-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Leverdatum</th>
                <th>Door wie</th>
                <th>Actie</th>
            </tr>
        </thead>

        <tbody>
            <tr>
                <td>1</td>
                <td>12/06/2026</td>
                <td>Technieker Jan</td>
                <td>
                    <a class="btn-primary" href="/manager/bestelling-detail">
                        Meer detail
                    </a>
                </td>
            </tr>
        </tbody>
    </table>
@endsection
