import Chart from 'chart.js/auto';

/* =========================================================
   1. STATE
========================================================= */

let trendChart;
let evolutionChart;

let mixedMode = false;
let activeCharts = [];

let predictionData = {
    labels: ['Ma', 'Di', 'Wo', 'Do', 'Vr', 'Za'],
    risk: [10, 20, 35, 40, 55, 65],
    rainfall: [5, 8, 10, 15, 12, 20],

    min: [10, 15, 20],
    max: [30, 50, 70],
    avg: [20, 30, 40]
};

/* =========================================================
   2. START APP
========================================================= */

document.addEventListener('DOMContentLoaded', () => {

    const cachedData = sessionStorage.getItem('floodData');

    if (cachedData) {
        predictionData = JSON.parse(cachedData);
    }

    initializeApp();
});

/* =========================================================
   3. INIT
========================================================= */

function initializeApp() {
    setupTabs();
    setupMixedMode();
    renderAllCharts();
    setCache(predictionData);
}

/* =========================================================
   4. CACHE
========================================================= */

function setCache(data) {
    sessionStorage.setItem('floodData', JSON.stringify(data));
}

/* =========================================================
   5. STATES
========================================================= */

function showEmptyState() {
    document.getElementById('trendContainer').innerHTML =
        `<div class="p-4 text-gray-500 bg-gray-100 rounded">Geen data beschikbaar</div>`;
}

function showErrorState() {
    document.getElementById('trendContainer').innerHTML =
        `<div class="p-4 text-red-600 bg-red-100 rounded">⚠️ Fout bij laden van data</div>`;
}

/* =========================================================
   6. RISK LOGIC
========================================================= */

function isRiskMonth(value) {
    return value >= 40;
}

function getRiskMonths() {
    return predictionData.labels.filter((_, i) =>
        predictionData.risk[i] >= 40
    );
}

window.riskMonths = getRiskMonths;

/* =========================================================
   7. CHARTS
========================================================= */

function renderTrendChart() {

    const ctx = document.getElementById('trendChart');

    trendChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: predictionData.labels,
            datasets: [{
                label: 'Risico',
                data: predictionData.risk,

                pointBackgroundColor: predictionData.risk.map(v =>
                    isRiskMonth(v) ? 'red' : 'blue'
                ),

                pointRadius: predictionData.risk.map(v =>
                    isRiskMonth(v) ? 6 : 3
                ),

                borderColor: predictionData.risk.map(v =>
                    isRiskMonth(v) ? 'red' : 'blue'
                ),

                borderWidth: 2
            }]
        },
        options: {
            plugins: {
                tooltip: {
                    enabled: true,
                    callbacks: {
                        label: function (context) {
                            return `Risico: ${context.parsed.y}`;
                        }
                    }
                }
            }
        }
    });
}

function renderEvolutionChart() {

    const ctx = document.getElementById('evolutionChart');

    evolutionChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: predictionData.labels,
            datasets: [
                { label: 'Min', data: predictionData.min },
                { label: 'Max', data: predictionData.max },
                { label: 'Gemiddelde', data: predictionData.avg }
            ]
        },
        options: {
            plugins: {
                tooltip: {
                    enabled: true,
                    callbacks: {
                        label: function (context) {
                            return `${context.dataset.label}: ${context.parsed.y}`;
                        }
                    }
                }
            }
        }
    });
}

function renderAllCharts() {
    renderTrendChart();
    renderEvolutionChart();
}

/* =========================================================
   8. TABS
========================================================= */

function setupTabs() {

    document.getElementById('trendBtn')
        .addEventListener('click', () => {
            setActiveTab('trendBtn');
            showTrendOnly();
        });

    document.getElementById('evolutionBtn')
        .addEventListener('click', () => {
            setActiveTab('evolutionBtn');
            showEvolutionOnly();
        });
}

function setActiveTab(activeId) {

    document.getElementById('trendBtn').classList.remove('bg-blue-700');
    document.getElementById('evolutionBtn').classList.remove('bg-blue-700');

    document.getElementById(activeId).classList.add('bg-blue-700');
}

function showTrendOnly() {

    if (mixedMode) return;

    document.getElementById('trendContainer').classList.remove('hidden');
    document.getElementById('evolutionContainer').classList.add('hidden');
}

function showEvolutionOnly() {

    if (mixedMode) return;

    document.getElementById('evolutionContainer').classList.remove('hidden');
    document.getElementById('trendContainer').classList.add('hidden');
}

/* =========================================================
   9. MIXED MODE
========================================================= */

function setupMixedMode() {

    document.getElementById('mixedMode')
        .addEventListener('change', toggleMixedMode);
}

function toggleMixedMode(event) {

    mixedMode = event.target.checked;

    styleMixedToggle();

    if (mixedMode) enableMixedMode();
    else disableMixedMode();
}

function styleMixedToggle() {

    const el = document.getElementById('mixedMode');

    el.parentElement.classList.toggle(
        'text-green-600',
        el.checked
    );
}

function enableMixedMode() {

    document.getElementById('combinedContainer').classList.remove('hidden');

    renderLegend();

    addChartToCombined('trend');
    addChartToCombined('evolution');
}

function renderLegend() {

    const container = document.getElementById('combinedContainer');

    const legend = document.createElement('div');

    legend.className = "p-2 border mb-2 bg-gray-100";

    legend.innerHTML = `
        <strong>Legenda</strong><br>
        🔵 Risico (Trend)<br>
        🟢 Neerslag (Evolutie)
    `;

    container.prepend(legend);
}

function disableMixedMode() {

    document.getElementById('combinedContainer').classList.add('hidden');
    document.getElementById('combinedContainer').innerHTML = '';

    activeCharts = [];

    showTrendOnly();
}

function addChartToCombined(type) {

    if (activeCharts.includes(type)) return;

    activeCharts.push(type);

    const container = document.getElementById('combinedContainer');

    const card = document.createElement('div');
    card.className = 'border p-4 rounded';
    card.innerHTML = `<h3>${type}</h3>`;

    container.appendChild(card);
}

function removeChartFromCombined(type) {
    activeCharts = activeCharts.filter(c => c !== type);
}

/* =========================================================
   10. FETCH (PLACEHOLDER)
========================================================= */

async function fetchPredictionData() {
    try {
        console.log('Prediction data loaded');
    } catch (error) {
        showErrorState();
        console.error(error);
    }
}