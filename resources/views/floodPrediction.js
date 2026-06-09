import Chart from 'chart.js/auto';

let trendChart;
let evolutionChart;

let mixedMode = false;

let activeCharts = [];



let predictionData = {
    risk: [10, 20, 35, 40, 55, 65],
    rainfall: [5, 8, 10, 15, 12, 20],
    labels: [
        'Ma',
        'Di',
        'Wo',
        'Do',
        'Vr',
        'Za'
    ]
};



document.addEventListener(
    'DOMContentLoaded',
    async () => {

        initializeCharts();

        setupTabs();

        setupMixedMode();

        await fetchPredictionData();

    }
);



function initializeCharts() {

    renderTrendChart();

    renderEvolutionChart();

    showTrendOnly();

}



function renderTrendChart() {

    const ctx =
        document
            .getElementById('trendChart');

    trendChart = new Chart(ctx, {

        type: 'line',

        data: {

            labels:
                predictionData.labels,

            datasets: [

                {
                    label:
                        'Risico',

                    data:
                        predictionData.risk
                }

            ]

        }

    });

}


function renderEvolutionChart() {

    const ctx =
        document
            .getElementById(
                'evolutionChart'
            );

    evolutionChart = new Chart(ctx, {

        type: 'bar',

        data: {

            labels:
                predictionData.labels,

            datasets: [

                {
                    label:
                        'Neerslag',

                    data:
                        predictionData.rainfall
                }

            ]

        }

    });

}


function setupTabs() {

    document
        .getElementById(
            'trendBtn'
        )
        .addEventListener(
            'click',
            showTrendOnly
        );

    document
        .getElementById(
            'evolutionBtn'
        )
        .addEventListener(
            'click',
            showEvolutionOnly
        );

}

function showTrendOnly() {

    if(mixedMode) return;

    document
        .getElementById(
            'trendContainer'
        )
        .classList.remove(
            'hidden'
        );

    document
        .getElementById(
            'evolutionContainer'
        )
        .classList.add(
            'hidden'
        );

}

function showEvolutionOnly() {

    if(mixedMode) return;

    document
        .getElementById(
            'evolutionContainer'
        )
        .classList.remove(
            'hidden'
        );

    document
        .getElementById(
            'trendContainer'
        )
        .classList.add(
            'hidden'
        );

}



function setupMixedMode() {

    document
        .getElementById(
            'mixedMode'
        )
        .addEventListener(
            'change',
            toggleMixedMode
        );

}

function toggleMixedMode(event) {

    mixedMode =
        event.target.checked;

    if(mixedMode){

        enableMixedMode();

    } else {

        disableMixedMode();

    }

}

function enableMixedMode() {

    document
        .getElementById(
            'combinedContainer'
        )
        .classList.remove(
            'hidden'
        );

    addChartToCombined(
        'trend'
    );

    addChartToCombined(
        'evolution'
    );

}

function disableMixedMode() {

    document
        .getElementById(
            'combinedContainer'
        )
        .classList.add(
            'hidden'
        );

    document
        .getElementById(
            'combinedContainer'
        )
        .innerHTML = '';

    activeCharts = [];

    showTrendOnly();

}



function addChartToCombined(
    chartType
) {

    if(
        activeCharts.includes(
            chartType
        )
    ) {
        return;
    }

    activeCharts.push(
        chartType
    );

    const container =
        document
            .getElementById(
                'combinedContainer'
            );

    const chartCard =
        document
            .createElement('div');

    chartCard.className =
        'border p-4 rounded';

    chartCard.innerHTML = `
        <h3>${chartType}</h3>
    `;

    container.appendChild(
        chartCard
    );

}



function removeChartFromCombined(
    chartType
) {

    activeCharts =
        activeCharts.filter(
            chart =>
                chart !==
                chartType
        );

}


async function fetchPredictionData() {

    try {

    

        console.log(
            'Prediction data loaded'
        );

    } catch(error) {

        console.error(error);

    }

}