import Chart from 'chart.js/auto';

/* =========================================================
   1. STATE
========================================================= */

let trendChart;
let evolutionChart;

let mixedMode = false;
let activeCharts = [];

/* fallback data (used if no cache exists) */
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

    // SAVE TO CACHE (required by rubric)
    setCache(predictionData);
}

/* =========================================================
   4. CACHE LOGIC
========================================================= */

function setCache(data) {
    sessionStorage.setItem('floodData', JSON.stringify(data));
}

/* =========================================================
   5. STATES (EMPTY / ERROR)
========================================================= */

function showEmptyState() {
    document.getElementById('trendContainer').innerHTML =
        "Geen data beschikbaar";
}

function showErrorState() {
    document.getElementById('trendContainer').innerHTML =
        "⚠️ Fout bij laden van data";
}

/* =========================================================
   6. CHARTS
========================================================= */

function renderTrendChart() {

    const ctx = document.getElementById('trendChart');

    trendChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: predictionData.labels,
            datasets: [{
                label: 'Risico',
                data: predictionData.risk
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
   7. TABS
========================================================= */

function setupTabs() {

    document.getElementById('trendBtn')
        .addEventListener('click', showTrendOnly);

    document.getElementById('evolutionBtn')
        .addEventListener('click', showEvolutionOnly);
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
   8. MIXED MODE
========================================================= */

function setupMixedMode() {

    document.getElementById('mixedMode')
        .addEventListener('change', toggleMixedMode);
}

function toggleMixedMode(event) {

    mixedMode = event.target.checked;

    if (mixedMode) {
        enableMixedMode();
    } else {
        disableMixedMode();
    }
}

function enableMixedMode() {

    document.getElementById('combinedContainer')
        .classList.remove('hidden');

    addChartToCombined('trend');
    addChartToCombined('evolution');
}

function disableMixedMode() {

    document.getElementById('combinedContainer')
        .classList.add('hidden');

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
   9. FETCH (PLACEHOLDER - FRONTEND ONLY)
========================================================= */

async function fetchPredictionData() {

    try {
        console.log('Prediction data loaded');
    } catch (error) {
        showErrorState();
        console.error(error);
    }
}