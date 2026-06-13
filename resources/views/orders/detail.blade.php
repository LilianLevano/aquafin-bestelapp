@extends('layouts.app')

@section('content')
    <div class="order-container">

        <a href="{{ route('orders.index') }}" class="order-back-link">
            ← Bestellingen
        </a>

        <h1 class="order-title">Bestelling detail</h1>

        <div class="order-info-card">
            <div>
                <p class="info-label">ID</p>
                <p class="info-value">#{{ $order->id }}</p>
            </div>
            <div>
                <p class="info-label">Gebruiker</p>
                <p class="info-value">{{ $order->user->first_name . ' ' . $order->user->last_name }}</p>
            </div>
            <div>
                <p class="info-label">Leverdatum</p>
                <p class="info-value">{{ $order->delivery_date }}</p>
            </div>
            <div>
                <p class="info-label">Status</p>
                <td class="info-value">{{ \Carbon\Carbon::parse($order->delivery_date)->isPast() ? 'Geleverd' : 'Aan het leveren' }}</td>
            </div>
        </div>

        <p class="materials-section-label">Bestelde materialen</p>

        <div class="materials-table-wrapper">
            <table class="materials-table">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Naam</th>
                    <th>Categorie</th>

                </tr>
                </thead>
                <tbody>
                @forelse($order->materials as $material)
                    <tr>
                        <td class="col-id">#{{ $material->id }}</td>
                        <td class="col-name"><a href="{{ route('materials.show', $material->id) }}">{{ $material->name }}</a> </td>
                        <td>
                            @if($material->category)
                                <span class="category-badge">{{ $material->category->name }}</span>
                            @else
                                <span class="category-badge no-category">Geen categorie</span>
                            @endif
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="materials-empty">Deze bestelling heeft geen materialen.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

    </div>
@endsection
