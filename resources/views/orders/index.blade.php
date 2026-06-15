@extends('layouts.app')
@section('title', 'Bestellingen Overzicht')

@section('content')
<div style="padding: 2rem; max-width: 1200px; margin: 0 auto;">
    <h1 class="h1 mb-4">Bestellingen Overzicht</h1>

    {{-- ERROR TOAST --}}
    <div id="error-toast"
         class="alert alert-danger d-flex align-items-center gap-2 hidden"
         style="position:fixed;top:1rem;right:1rem;z-index:9999;max-width:420px;">
        <span>⚠️</span>
        <span id="error-toast-text" class="flex-grow-1 small"></span>
        <button type="button" class="btn-close" onclick="hideToast()"></button>
    </div>

    {{-- FILTERS --}}
    <div class="card mb-4">
        <div class="card-body d-flex flex-wrap gap-3 align-items-end">
            <div>
                <label for="datum-filter" class="form-label small fw-semibold mb-1">Datum</label>
                <input type="date" id="datum-filter" class="form-control form-control-sm">
            </div>
            <div style="flex:1;min-width:200px;">
                <label for="zoek-filter" class="form-label small fw-semibold mb-1">Zoeken</label>
                <input type="text" id="zoek-filter"
                       placeholder="Zoeken op naam, ID of materiaal…"
                       class="form-control form-control-sm">
            </div>
        </div>
    </div>

    {{-- TABEL --}}
    <div class="card">
        <div id="loading" class="text-center py-5 text-muted small @if(isset($success) && $success) hidden @endif">
            <div class="spinner-border spinner-border-sm text-primary mb-2" role="status"></div>
            <div>Bestellingen laden…</div>
        </div>

        <div id="geen-data" class="hidden py-5 text-center text-muted fst-italic small"></div>

        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Geplaatst door</th>
                        <th>Items</th>
                        <th>Datum</th>
                        <th>Acties</th>
                    </tr>
                </thead>
                <tbody id="bestellingen-body">
                    @forelse($orders as $o)
                        <tr>
                            <td class="font-monospace text-muted">#{{ $o->id }}</td>
                            <td class="fw-medium">{{ $o->geplaatst_door ?? '—'  }}</td>
                            <td class="text-muted small" style="max-width:280px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap"
                                title="${esc(b.items)}">{{ $o->items ?? '—' }}</td>
                            <td class="text-muted small">{{ \Carbon\Carbon::parse($o->datum)->format('d/m/Y H:i') }}</td>
                            <td>
                                <a href="/manager/orders/{{ $o->id }}" class="btn btn-sm btn-outline-primary">
                                    Meer details →
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr id="empty-row"><td colspan="5" class="muted center">No orders to display.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    @vite('resources/js/orders-index.js')
    <script>
        let alleBestellingen = [];
        let huidigeDatum = '';

        document.addEventListener('DOMContentLoaded', () => {
            const vandaag = new Date().toISOString().split('T')[0];
            const datumInput = document.getElementById('datum-filter');
            datumInput.value = vandaag;
            huidigeDatum = vandaag;

            datumInput.addEventListener('change', e => {
                huidigeDatum = e.target.value;
                // Use full page reload: let PHP/Blade render the updated table from the controller
                window.location.search = '?datum=' + encodeURIComponent(huidigeDatum);
            });

            document.getElementById('zoek-filter').addEventListener('input', filterLokaal);
        });

        // Only filter in DOM; no AJAX as controller outputs HTML not JSON
        function filterLokaal() {
            const zoek = document.getElementById('zoek-filter').value.toLowerCase();
            const rijen = document.querySelectorAll('#bestellingen-body tr');
            let n = 0;
            rijen.forEach(tr => {
                const cells = Array.from(tr.querySelectorAll("td"));
                if (tr.id === "empty-row") {
                    // always show empty row if filtering hides everything
                    tr.classList.toggle('d-none', n !== 0);
                    return;
                }
                let match = cells.some(td => td.textContent.toLowerCase().includes(zoek));
                tr.classList.toggle('d-none', !match);
                if (match) n++;
            });
            const geenData = document.getElementById('geen-data');
            if (n === 0) {
                geenData.textContent = 'Geen resultaten gevonden voor uw zoekopdracht.';
                geenData.classList.remove('hidden');
                // Hide the static 'no orders' row if filtering yields zero
                const emptyRow = document.getElementById('empty-row');
                if (emptyRow) emptyRow.classList.add('d-none');
            } else {
                geenData.classList.add('hidden');
                // Show empty row only if needed (no search)
                const emptyRow = document.getElementById('empty-row');
                if (emptyRow && n > 0) emptyRow.classList.add('d-none');
            }
        }

        function toonLeeg(tekst) {
            const el = document.getElementById('geen-data');
            el.textContent = tekst;
            el.classList.remove('hidden');
        }

        function toonToast(bericht) {
            document.getElementById('error-toast-text').textContent = bericht;
            document.getElementById('error-toast').classList.remove('hidden');
            setTimeout(hideToast, 7000);
        }

        function hideToast() {
            document.getElementById('error-toast').classList.add('hidden');
        }

        function toonLoading(actief) {
            document.getElementById('loading').classList.toggle('hidden', !actief);
        }

        function esc(str) {
            if (!str) return '—';
            return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
        }

        function formatDatum(iso) {
            if (!iso) return '';
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
@endpush
