@extends('layouts.app')
@section('title', __('Adres'))

@section('content')
    <div class="card">
        <div class="tabs">
            <button type="button" class="tab tab-active">{{ __('Huidig') }}</button>
            <a href="{{ route('admin.addresses.create') }}" class="tab">{{ __('Nieuw') }}</a>
        </div>

        {{-- TABLE --}}
        <div id="section-table">
            <div class="row-between mb">
                <h1 class="h1">{{ __('Adressen') }}</h1>
                <button type="button" class="btn btn-outline btn-sm" onclick="location.reload()">↺ {{ __('Vernieuwen') }}</button>
            </div>

            @if(session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif

            <div class="mb">
                <input id="search-input" type="text" placeholder="{{ __('Zoek op naam...') }}"
                    oninput="filterTable(this.value)"
                    style="padding:8px 12px;border:1px solid var(--border);border-radius:8px;font:inherit;width:100%;max-width:300px;">
            </div>

            <table class="table" id="accounts-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>{{ __('Type') }}</th>
                        <th>{{ __('Straat') }}</th>
                        <th>{{ __('Huisnummer') }}</th>
                        <th>{{ __('Plaats') }}</th>
                        <th>{{ __('Postcode') }}</th>
                        <th>{{ __('Land ISO') }}</th>
                        <th>{{ __('Unitnummer') }}</th>
                        <th class="right">{{ __('Acties') }}</th>
                    </tr>
                </thead>
                <tbody id="accounts-tbody">
                    @if (!(empty($addresses) || $addresses->isEmpty()))
                        @forelse($addresses as $a)
                            <tr>
                                <td>{{ $a->id }}</td>
                                <td>{{ $a->type }}</td>
                                <td>{{ $a->street }}</td>
                                <td>{{ $a->house_number }}</td>
                                <td>{{ $a->city }}</td>
                                <td>{{ $a->postal_code }}</td>
                                <td>{{ $a->country_iso }}</td>
                                <td>{{ $a->unit_number }}</td>
                                <td class="right">
                                    <a href="{{route('admin.addresses.edit', $a->id)}}" class="link">{{ __('Bewerken') }}</a>

                                    <form method="POST" action="{{ route('admin.addresses.destroy', $a->id) }}" style="display:inline"
                                        onsubmit="return confirm('{{ __('Dit adres verwijderen?') }}');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="link link-danger">{{ __('Verwijderen') }}</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr id="empty-row"><td colspan="9" class="muted center">{{ __('Geen adressen om weer te geven.') }}</td></tr>
                        @endforelse
                    @endif
                </tbody>
            </table>
            <p id="no-results" class="muted center" style="display:none;padding:16px;">{{ __('Geen resultaten gevonden.') }}</p>
        </div>
    </div>
@endsection

@push('scripts')
    @vite('resources/js/account-index.js')
@endpush
