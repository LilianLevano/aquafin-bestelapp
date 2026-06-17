@extends('layouts.app')
@section('title', 'Bestellingen Overzicht')

@section('content')
<div style="padding: 2rem; max-width: 1200px; margin: 0 auto;">
    <h1 class="h1 mb-4">Bestellingen Overzicht</h1>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h1 mb-0">Mijn Bestellingen</h1>
        <a href="{{ route('technieker.orders.create') }}" class="btn btn-primary">
            + Nieuwe bestelling
        </a>
    </div>
    <div class="filter-zone">
        <div class="filter-item">
            <label>Zoeken</label>
            <input type="text" placeholder="Zoek op woord...">
        </div>

        <div class="filter-item">
            <label>Datum</label>
            <input type="date">
        </div>

        <div class="filter-item">
            <label>Regio</label>
            <select>
                <option>Alle regio's</option>
                <option>Brussel</option>
                <option>Antwerpen</option>
                <option>Gent</option>
                <option>Leuven</option>
            </select>
        </div>

        <button class="btn-primary">Filter</button>
    </div>
    <table class="manager-table">
        <thead>
            <tr>
                <th>ID</th>
                <th class="col-geplaatst">Geplaatst door</th>
                <th>Leverplaats</th>
                <th>Leverdatum</th>
                <th>Status</th>
            </tr>
        </thead>

        <div id="geen-data" class="hidden py-5 text-center text-muted fst-italic small"></div>
        <tbody>
            @foreach($orders as $order)
                <tr>
                    <td>{{$order->id}}</td>
                    <td class="col-geplaatst">{{$order->user->first_name . ' ' . $order->user->last_name  }}</td>

                    <td>{{$order->site->description}}</td>
                    <td>{{$order->delivery_date}}</td>
                    <td>{{ \Carbon\Carbon::parse($order->delivery_date)->isPast() ? 'Geleverd' : 'Aan het leveren' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p class="empty-message">Geen data om te tonen.</p>
@endsection

@push('scripts')
    @vite('resources/js/orders/orders-index.js')
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
