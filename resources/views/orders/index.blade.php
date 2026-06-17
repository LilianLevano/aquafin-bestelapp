@extends('layouts.app')
@section('title', 'Bestellingen Overzicht')

@section('content')
<div style="padding: 2rem; max-width: 1200px; margin: 0 auto;">
    <h1 class="h1 mb-4">Bestellingen Overzicht</h1>

    <button type="button" class="btn-primary" onclick="openWeather()">
        Voorspelling weersomstandigheden
    </button>
     <div id="weather-section" style="display:none;">
    <h2>Voorspelling weersomstandigheden</h2>

    <button type="button" class="weather-tab active" onclick="showWeatherTable('week1', this)">
        Overzicht 1 week
    </button>

    <button type="button" class="weather-tab" onclick="showWeatherTable('week2', this)">
        Overzicht 2 weken
    </button>

    <p id="weather-error" class="weather-error" style="display:none;">
        Er ging iets mis bij het ophalen van de weersomstandigheden gegevens.
    </p>

    <div id="weather-table-container"></div>
</div>


    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h1 mb-0">Mijn Bestellingen</h1>
  <a href="{{ route('technieker.orders.create') }}" class="btn btn-primary">
            + Nieuwe bestelling
        </a>
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

    <button><a href="{{route('technieker.orders.create')}}">Plaats een nieuwe bestelling</a> </button>

    <table class="manager-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Geplaatst door</th>

                <th>Leverplaats</th>
                <th>Leverdatum</th>
                <th>Status</th>
            </tr>
        </thead>

        <tbody>


        @forelse($bestellingen ?? [] as $bestelling)
            <tr>
                <td>{{$bestelling->id}}</td>
                <td>{{$bestelling->user->first_name . ' ' . $bestelling->user->last_name  }}</td>
                <td>
                    {{ $bestelling->material->take(3)->map(fn($m) => $m->name . ' (x' . $m->pivot->quantity . ')')->implode(', ') . ($bestelling->materiaal->count() > 3 ? ', ...' : '') }}
                </td>
                <td>{{$bestelling->site->locatie}}</td>
                <td>{{$bestelling->delivery_date}}</td>
                <td>{{ \Carbon\Carbon::parse($bestelling->delivery_date)->isPast() ? 'Geleverd' : 'Aan het leveren' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="6">Geen bestellingen gevonden.</td>
            </tr>
        @endforelse
        </tbody>
    </table>

    <p class="empty-message">Geen data om te tonen.</p>

<script>
const weatherData = {
    week1: [
        { dag:'Maandag', min:8, max:14, vochtigheid:'78%', neerslag:'12 mm', risico:'hoog' },
        { dag:'Dinsdag', min:7, max:13, vochtigheid:'80%', neerslag:'9 mm', risico:'hoog' },
        { dag:'Woensdag', min:10, max:16, vochtigheid:'65%', neerslag:'3 mm', risico:'laag' },
        { dag:'Donderdag', min:9, max:15, vochtigheid:'70%', neerslag:'5 mm', risico:'laag' },
        { dag:'Vrijdag', min:11, max:17, vochtigheid:'60%', neerslag:'1 mm', risico:'laag' },
        { dag:'Zaterdag', min:8, max:12, vochtigheid:'85%', neerslag:'14 mm', risico:'hoog' },
        { dag:'Zondag', min:9, max:14, vochtigheid:'75%', neerslag:'6 mm', risico:'laag' }
    ],
    week2: [
        { dag:'Maandag', min:8, max:14, vochtigheid:'78%', neerslag:'12 mm', risico:'hoog' },
        { dag:'Dinsdag', min:7, max:13, vochtigheid:'80%', neerslag:'9 mm', risico:'hoog' },
        { dag:'Woensdag', min:10, max:16, vochtigheid:'65%', neerslag:'3 mm', risico:'laag' },
        { dag:'Donderdag', min:9, max:15, vochtigheid:'70%', neerslag:'5 mm', risico:'laag' },
        { dag:'Vrijdag', min:11, max:17, vochtigheid:'60%', neerslag:'1 mm', risico:'laag' },
        { dag:'Zaterdag', min:8, max:12, vochtigheid:'85%', neerslag:'14 mm', risico:'hoog' },
        { dag:'Zondag', min:9, max:14, vochtigheid:'75%', neerslag:'6 mm', risico:'laag' },
        { dag:'Maandag 2', min:10, max:15, vochtigheid:'72%', neerslag:'4 mm', risico:'laag' },
        { dag:'Dinsdag 2', min:11, max:18, vochtigheid:'58%', neerslag:'0 mm', risico:'laag' },
        { dag:'Woensdag 2', min:9, max:13, vochtigheid:'82%', neerslag:'11 mm', risico:'hoog' },
        { dag:'Donderdag 2', min:8, max:12, vochtigheid:'86%', neerslag:'15 mm', risico:'hoog' },
        { dag:'Vrijdag 2', min:10, max:16, vochtigheid:'68%', neerslag:'2 mm', risico:'laag' },
        { dag:'Zaterdag 2', min:12, max:19, vochtigheid:'55%', neerslag:'0 mm', risico:'laag' },
        { dag:'Zondag 2', min:10, max:15, vochtigheid:'73%', neerslag:'7 mm', risico:'laag' }
    ]
};

function openWeather() {
    try {
        document.getElementById('weather-error').style.display = 'none';
        document.getElementById('weather-section').style.display = 'block';

        localStorage.setItem('weatherData', JSON.stringify(weatherData));

        const risicoMaanden = weatherData.week2.filter(item => item.risico === 'hoog');
        localStorage.setItem('risicoMaanden', JSON.stringify(risicoMaanden));

        showWeatherTable('week1', document.querySelector('.weather-tab.active'));
    } catch(error) {
        document.getElementById('weather-error').style.display = 'block';
    }
}

function showWeatherTable(type, button) {
    document.querySelectorAll('.weather-tab').forEach(tab => tab.classList.remove('active'));
    button.classList.add('active');

    const savedData = JSON.parse(localStorage.getItem('weatherData')) || weatherData;
    const data = savedData[type];
    const container = document.getElementById('weather-table-container');

    if (!data || data.length === 0) {
        container.innerHTML = '<p class="weather-empty">Er zijn geen gegevens beschikbaar om te tonen.</p>';
        return;
    }

    let html = `
        <div class="weather-legend">
            <span>🟢 Laag risico</span>
            <span>⚠️ Hoog risico</span>
        </div>

        <h3>Trendgrafiek neerslag</h3>
        <div class="weather-chart">
    `;

    data.forEach(item => {
        const neerslag = parseInt(item.neerslag);
        html += `
            <div class="chart-bar ${item.risico === 'hoog' ? 'risk-bar' : ''}"
                 style="height:${neerslag * 6 + 20}px"
                 title="${item.dag}: ${item.neerslag}, vochtigheid ${item.vochtigheid}, risico ${item.risico}">
            </div>
        `;
    });

    html += `
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

