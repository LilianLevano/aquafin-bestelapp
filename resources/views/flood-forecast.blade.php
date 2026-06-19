@extends('layouts.app')

@section('content')
    <div id="forecast-container" class="weer-container">
        <h1 class="weer-title">Voorspelling weersomstandigheden</h1>

        <div class="weer-tab-bar">
            <button class="weer-tab active" id="tab-1week" value="7">
                Overzicht 1 week
            </button>
            <button class="weer-tab" id="tab-2weken" value="14">
                Overzicht 2 weken
            </button>
            <button class="btn-primary" id="reload-web-page">Herlaadt</button>
        </div>

        <div id="loading-state" class="weer-loading visually-hidden">
            <div class="weer-spinner"></div>
            <p>Gegevens laden...</p>
        </div>

       <div id="error-state" class="weer-error visually-hidden">
    Er ging iets mis bij het ophalen van de overstromingsgegevens.
</div>

        <div id="empty-state" class="weer-empty visually-hidden">
            Er zijn geen gegevens beschikbaar om te tonen.
        </div>

        <div id="data-content" class="visually-hidden">
            {{-- TABLE --}}
            <div class="weer-table-wrapper">
                <table class="weer-table">
                    <thead>
                        <tr>
                            <th>Datum</th>
                            <th>Min °C</th>
                            <th>Max °C</th>
                            <th>Vochtigheid (%)</th>
                            <th>Neerslag kans (%)</th>
                            <th>Neerslag (mm)</th>
                            <th>Risico</th>
                        </tr>
                    </thead>
                    <tbody id="weather-table-body"></tbody>
                </table>
            </div>

            <div class="weer-chart-selector">
                <button class="chart-type-btn active" id="btn-gemengd" value="mixed">
                    Gemengd
                </button>
                <button class="chart-type-btn" id="btn-lijn" value="line">
                    Lijn
                </button>
                <button class="chart-type-btn" id="btn-staaf" value="bar">
                    Staaf
                </button>
            </div>

            {{-- CHARTS --}}
            <div class="weer-charts-grid">
                <div id="trend-chart-block" class="weer-chart-block">
                    <h2>Trendgrafiek – Voorspeld risico</h2>
                    <canvas id="trendChart"></canvas>
                </div>
                <div id="evolution-chart-block" class="weer-chart-block">
                    <h2>Evolutiegrafiek – Min / Max / Gemiddelde</h2>
                    <canvas id="evolutionChart"></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script id="weather-forecast-cache-bootstrap">
        const weatherForecastData = @json(session('data', []));
        const scriptElement = document.getElementById("weather-forecast-cache-bootstrap");

        window.addEventListener("saveToCacheReady", () => {
            // Save to cache once /resources/js/app.js is loaded and window.saveToCache is defined
            if (typeof window.saveToCache === "function") {
                window.saveToCache("weather_forecast_cache", weatherForecastData);

                // Remove this script node from the DOM after caching is done
                if (scriptElement && scriptElement.parentNode) {
                    scriptElement.parentNode.removeChild(scriptElement);
                }
            }
        });
    </script>
    @vite('resources/js/flood-forecast.js')
@endpush
