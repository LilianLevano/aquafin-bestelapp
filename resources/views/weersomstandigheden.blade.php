<x-layouts.site-layout>

    <div class="weer-container">

        <h1 class="weer-title">Voorspelling weersomstandigheden</h1>

        <div class="weer-tab-bar">
            <button class="weer-tab active" id="tab-1week" onclick="switchTab('1week')">
                Overzicht 1 week
            </button>
            <button class="weer-tab" id="tab-2weken" onclick="switchTab('2weken')">
                Overzicht 2 weken
            </button>
        </div>

        <div id="loading-state" class="weer-loading" style="display:none;">
            <div class="weer-spinner"></div>
            <p>Gegevens laden...</p>
        </div>

     
        <div id="error-state" class="weer-error" style="display:none;">
            Er ging iets mis bij het ophalen van de weersomstandigheden gegevens.
        </div>

       
        <div id="empty-state" class="weer-empty" style="display:none;">
            Er zijn geen gegevens beschikbaar om te tonen.
        </div>

        <div id="data-content" style="display:none;">

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
                <button class="chart-type-btn active" id="btn-gemengd" onclick="selectChartType('gemengd')">
                    Gemengd
                </button>
                <button class="chart-type-btn" id="btn-lijn" onclick="selectChartType('lijn')">
                    Lijn
                </button>
                <button class="chart-type-btn" id="btn-staaf" onclick="selectChartType('staaf')">
                    Staaf
                </button>
            </div>

            {{-- CHARTS --}}
            <div class="weer-charts-grid">
                <div class="weer-chart-block">
                    <h2>Trendgrafiek – Voorspeld risico</h2>
                    <canvas id="trendChart"></canvas>
                </div>
                <div class="weer-chart-block">
                    <h2>Evolutiegrafiek – Min / Max / Gemiddelde</h2>
                    <canvas id="evolutieChart"></canvas>
                </div>
            </div>

        </div>
    </div>

   
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  
    @vite('resources/js/weersomstandigheden.js')

</x-layouts.site-layout>