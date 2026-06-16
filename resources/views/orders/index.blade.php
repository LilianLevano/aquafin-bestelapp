@extends('layouts.app')
@section('title', 'Mijn Bestellingen')

@section('content')
    <h1>Bestellingen Overzicht</h1>

    <button type="button" class="btn-primary" onclick="openWeather()">
        Voorspelling weersomstandigheden
    </button>


    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h1 mb-0">Mijn Bestellingen</h1>
        <a href="{{ route('orders.create') }}" class="btn btn-primary">
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

    <button><a href="{{route('orders.create')}}">Plaats een nieuwe bestelling</a> </button>

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


        @foreach($bestellingen as $bestelling)
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

        <h3>Evolutiegrafiek min/max temperatuur</h3>
        <div class="weather-chart">
    `;

    data.forEach(item => {
        const gemiddelde = (item.min + item.max) / 2;
        html += `
            <div class="chart-bar temp-bar"
                 style="height:${gemiddelde * 5}px"
                 title="${item.dag}: min ${item.min}°C, max ${item.max}°C, gemiddelde ${gemiddelde}°C">
            </div>
        `;
    });

    html += `
        </div>

        <table class="manager-table">
            <thead>
                <tr>
                    <th>Dag</th>
                    <th>Min °C</th>
                    <th>Max °C</th>
                    <th>Vochtigheid</th>
                    <th>Neerslag</th>
                    <th>Risico</th>
                </tr>
            </thead>
            <tbody>
    `;

    data.forEach(item => {
        html += `
            <tr class="${item.risico === 'hoog' ? 'risk-day' : ''}"
                title="${item.dag}: ${item.neerslag} neerslag en ${item.vochtigheid} vochtigheid">
                <td>${item.dag}</td>
                <td>${item.min}</td>
                <td>${item.max}</td>
                <td>${item.vochtigheid}</td>
                <td>${item.neerslag}</td>
                <td>${item.risico === 'hoog' ? '⚠️ Hoog' : 'Laag'}</td>
            </tr>
        `;
    });

    html += `
            </tbody>
        </table>
    `;

    container.innerHTML = html;
}
</script>

@endsection

@push('scripts')
    @vite('resources/js/orders-index.js')
@endpush