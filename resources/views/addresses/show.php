@extends('layouts.app')
@section('title', __('Adres'))

@section('content')
    <div class="card" style="max-width:640px;margin:0 auto;">
        <div class="tabs">
            <a href="{{ route('admin.addresses.index') }}" class="tab">{{ __('Overzicht') }}</a>
            <a href="{{ route('admin.addresses.create') }}" class="tab">{{ __('Nieuw') }}</a>
            <button type="button" class="tab tab-active" disabled>{{ __('Bekijken') }}</button>
        </div>

        <h1 class="h1">{{ __('Adresgegevens') }}</h1>

        @if(session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        <table class="table" style="margin-bottom:24px;">
            <tr>
                <th style="width:180px;">{{ __('ID') }}</th>
                <td>{{ $address->id }}</td>
            </tr>
            <tr>
                <th>{{ __('Type') }}</th>
                <td>{{ $address->type }}</td>
            </tr>
            <tr>
                <th>{{ __('Straat') }}</th>
                <td>{{ $address->street }}</td>
            </tr>
            <tr>
                <th>{{ __('Huisnummer') }}</th>
                <td>{{ $address->house_number }}</td>
            </tr>
            <tr>
                <th>{{ __('Plaats') }}</th>
                <td>{{ $address->city }}</td>
            </tr>
            <tr>
                <th>{{ __('Postcode') }}</th>
                <td>{{ $address->postal_code }}</td>
            </tr>
            <tr>
                <th>{{ __('Land ISO') }}</th>
                <td>{{ $address->country_iso }}</td>
            </tr>
            <tr>
                <th>{{ __('Unitnummer') }}</th>
                <td>{{ $address->unit_number }}</td>
            </tr>
        </table>

        <div class="row-between">
            <a href="{{ route('admin.addresses.edit', $address->id) }}" class="btn btn-outline">{{ __('Bewerken') }}</a>
            <form id="delete-address" method="POST" action="{{ route('admin.addresses.destroy', $address->id) }}" style="display:inline" data-translation-confirm="{{ __('Dit adres verwijderen?') }}">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline btn-danger">{{ __('Verwijderen') }}</button>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const deleteForm = document.querySelector("#delete-address");

        if (deleteForm) {
            deleteForm.addEventListener("submit", (event) => {
                event.preventDefault();
                confirm(deleteForm.dataset.translationConfirm);
            });
        }
    </script>
@endpush
