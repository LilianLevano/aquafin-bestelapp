@extends('layouts.app')
@section('title', 'Bestelling Details')

@section('content')
<div style="padding: 2rem; max-width: 900px; margin: 0 auto;">

    <div class="d-flex align-items-center gap-2 mb-4">
        <a href="{{ route('bestellingen.index') }}" class="btn btn-sm btn-outline-secondary">← Terug</a>
        <h1 class="h4 mb-0">Bestelling details</h1>
    </div>

    {{-- LOADING --}}
    <div id="loading" class="text-center py-5 text-muted small">
        <div class="spinner-border spinner-border-sm text-primary mb-2" role="status"></div>
        <div>Details laden…</div>
    </div>

    {{-- ERROR --}}
    <div id="error-blok" class="hidden alert alert-danger d-flex align-items-start gap-2">
        <span>⚠️</span>
        <p id="error-tekst" class="mb-0 small"></p>
    </div>

    {{-- KAART --}}
    <div id="detail-kaart" class="hidden">

        {{-- Bestelling info --}}
        <div class="card mb-4">
            <div class="card-header fw-semibold">📋 Bestellingsinformatie</div>
            <div class="card-body row g-3">
                <div class="col-sm-4">
                    <div class="text-muted small text-uppercase fw-semibold mb-1">Bestelling ID</div>
                    <div id="detail-id" class="font-monospace"></div>
                </div>
                <div class="col-sm-4">
                    <div class="text-muted small text-uppercase fw-semibold mb-1">Datum van bestelling</div>
                    <div id="detail-datum"></div>
                </div>
                <div class="col-sm-4">
                    <div class="text-muted small text-uppercase fw-semibold mb-1">Leverdatum</div>
                    <div id="detail-leverdatum"></div>
                </div>
            </div>
        </div>

        {{-- Technieker info --}}
        <div class="card mb-4">
            <div class="card-header fw-semibold">👤 Technieker</div>
            <div class="card-body row g-3">
                <div class="col-sm-6">
                    <div class="text-muted small text-uppercase fw-semibold mb-1">Volledige naam</div>
                    <div id="detail-naam" class="fw-medium"></div>
                </div>
                <div class="col-sm-6">
                    <div class="text-muted small text-uppercase fw-semibold mb-1">E-mailadres</div>
                    <div id="detail-email"></div>
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
                    <tbody id="materialen-body"></tbody>
                </table>
            </div>
            <div id="geen-materialen" class="hidden py-4 text-center text-muted fst-italic small">
                Geen materialen gevonden voor deze bestelling.
            </div>
        </div>

    </div>
</div>

<script>
const bestellingId = window.location.pathname.split('/').filter(Boolean).pop();

document.addEventListener('DOMContentLoaded', () => laadDetail(bestellingId));

async function laadDetail(id) {
    try {
        const res = await fetch(`/bestellingen/${id}`, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });

        const json = await res.json();

        if (!res.ok || !json.success) {
            throw new Error(json.message || 'Er ging iets mis met het ophalen van de bestellingsdetails.');
        }

        vulKaartIn(json.bestelling, json.materialen);

    } catch (err) {
        document.getElementById('error-tekst').textContent = err.message;
        document.getElementById('error-blok').classList.remove('hidden');
    } finally {
        document.getElementById('loading').classList.add('hidden');
    }
}

function vulKaartIn(b, materialen) {
    document.getElementById('detail-id').textContent         = '#' + b.id;
    document.getElementById('detail-datum').textContent      = formatDatumTijd(b.datum);
    document.getElementById('detail-leverdatum').textContent = b.delivery_date ? formatDatum(b.delivery_date) : '—';
    document.getElementById('detail-naam').textContent       = b.technieker_naam  || '—';
    document.getElementById('detail-email').textContent      = b.technieker_email || '—';

    const body = document.getElementById('materialen-body');
    body.innerHTML = '';

    if (!materialen || !materialen.length) {
        document.getElementById('geen-materialen').classList.remove('hidden');
        return;
    }

    materialen.forEach(m => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td class="font-monospace text-muted small">${esc(String(m.materiaal_id))}</td>
            <td class="fw-medium">${esc(m.naam)}</td>
            <td><span class="badge bg-primary bg-opacity-10 text-primary">${esc(m.categorie ?? '—')}</span></td>
            <td>${esc(String(m.hoeveelheid))}</td>
        `;
        body.appendChild(tr);
    });

    document.getElementById('detail-kaart').classList.remove('hidden');
}

function esc(str) {
    if (!str) return '—';
    return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}
function formatDatum(iso) {
    if (!iso) return '—';
    const [y,m,d] = iso.split('-');
    return `${d}/${m}/${y}`;
}
function formatDatumTijd(iso) {
    if (!iso) return '—';
    return new Date(iso).toLocaleString('nl-BE', {
        day:'2-digit', month:'2-digit', year:'numeric',
        hour:'2-digit', minute:'2-digit'
    });
}
</script>
@endsection