@extends('layouts.app')
@section('title', 'Bestelling Details')

@section('content')
    <div style="padding: 2rem; max-width: 900px; margin: 0 auto;">
        <div class="d-flex align-items-center gap-2 mb-4">
            <a href="{{ route('manager.orders.index') }}" class="btn btn-sm btn-outline-secondary">← Terug</a>
            <h1 class="h4 mb-0">Bestelling details</h1>
        </div>

        @if (session('success') === false)
            <div class="alert alert-danger d-flex align-items-start gap-2">
                <span>⚠️</span>
                <p class="mb-0 small">{{ session('message') }}</p>
            </div>
        @elseif (isset($order) && isset($materials))
            {{-- KAART --}}
            <div id="detail-kaart">

                {{-- Bestelling info --}}
                <div class="card mb-4">
                    <div class="card-header fw-semibold">📋 Bestellingsinformatie</div>
                    <div class="card-body row g-3">
                        <div class="col-sm-4">
                            <div class="text-muted small text-uppercase fw-semibold mb-1">Bestelling ID</div>
                            <div class="font-monospace">#{{ $order->id }}</div>
                        </div>
                        <div class="col-sm-4">
                            <div class="text-muted small text-uppercase fw-semibold mb-1">Datum van bestelling</div>
                            <div>{{ \Carbon\Carbon::parse($order->datum)->format('d/m/Y H:i') }}</div>
                        </div>
                        <div class="col-sm-4">
                            <div class="text-muted small text-uppercase fw-semibold mb-1">Leverdatum</div>
                            <div>
                                @if ($order->delivery_date)
                                    {{ \Carbon\Carbon::parse($order->delivery_date)->format('d/m/Y') }}
                                @else
                                    —
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Technieker info --}}
                <div class="card mb-4">
                    <div class="card-header fw-semibold">👤 Technieker</div>
                    <div class="card-body row g-3">
                        <div class="col-sm-6">
                            <div class="text-muted small text-uppercase fw-semibold mb-1">Volledige naam</div>
                            <div class="fw-medium">{{ $order->technieker_naam ?? '—' }}</div>
                        </div>
                        <div class="col-sm-6">
                            <div class="text-muted small text-uppercase fw-semibold mb-1">E-mailadres</div>
                            <div>{{ $order->technieker_email ?? '—' }}</div>
                        </div>
                    </div>
                </div>

                {{-- Materialen --}}
                <div class="card">
                    <div class="card-header fw-semibold">📦 Bestelde materialen</div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Naam</th>
                                    <th>Categorie</th>
                                    <th>Hoeveelheid</th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse($materials as $m)
                                <tr>
                                    <td class="font-monospace text-muted small">{{ $m->materiaal_id }}</td>
                                    <td class="fw-medium">{{ $m->naam }}</td>
                                    <td>
                                        <span class="badge bg-primary bg-opacity-10 text-primary">
                                            {{ $m->categorie ?? '—' }}
                                        </span>
                                    </td>
                                    <td>{{ $m->hoeveelheid }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4">
                                        <div class="py-4 text-center text-muted fst-italic small">
                                            Geen materialen gevonden voor deze bestelling.
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @else
            <div class="text-center py-5 text-muted small">
                <div class="spinner-border spinner-border-sm text-primary mb-2" role="status"></div>
                <div>Details laden…</div>
            </div>
        @endif
    </div>
@endsection
