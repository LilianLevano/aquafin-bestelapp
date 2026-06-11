const CACHE_KEY      = 'weather_forecast_cache';
const CACHE_DURATION = 30 * 60 * 1000; // 30 min
const RISK_THRESHOLD = 70;
const API_URL        = '/api/weather/forecast'; // swap in when backend ready

let currentTab       = '1week';
let currentChartType = 'gemengd';
let allData          = [];
let trendChart       = null;
let evolutieChart    = null;


document.addEventListener('DOMContentLoaded', () => {
    loadWeatherData();
});

 
function switchTab(tab) {
    currentTab = tab;
    document.querySelectorAll('.weer-tab').forEach(b => b.classList.remove('active'));
    document.getElementById('tab-' + tab).classList.add('active');
    if (allData.length > 0) renderAll(allData);
}


function selectChartType(type) {
    currentChartType = type;
    document.querySelectorAll('.chart-type-btn').forEach(b => b.classList.remove('active'));
    document.getElementById('btn-' + type).classList.add('active');
    if (allData.length > 0) renderAll(allData);
}


function saveToCache(data) {
    localStorage.setItem(CACHE_KEY, JSON.stringify({
        timestamp: Date.now(),
        data
    }));
}

function loadFromCache() {
    const raw = localStorage.getItem(CACHE_KEY);
    if (!raw) return null;
    const payload = JSON.parse(raw);
    if ((Date.now() - payload.timestamp) > CACHE_DURATION) {
        localStorage.removeItem(CACHE_KEY);
        return null;
    }
    return payload.data;
}


async function loadWeatherData() {
    showState('loading');


    const cached = loadFromCache();
    if (cached) {
        allData = cached;
        renderAll(allData);
        showState('data');
        return;
    }

   
    try {
       
        await new Promise(r => setTimeout(r, 800)); // fake loading delay
        allData = generateMockData(14);
       

        if (!allData || allData.length === 0) {
            showState('empty');
            return;
        }

        saveToCache(allData);
        renderAll(allData);
        showState('data');

    } catch (error) {
        console.error(error);
        showState('error');
    }
}


function generateMockData(days) {
    const result = [];
    const today  = new Date();
    for (let i = 0; i < days; i++) {
        const d = new Date(today);
        d.setDate(today.getDate() + i);
        const minTemp    = Math.round(8  + Math.random() * 6);
        const maxTemp    = Math.round(minTemp + 4 + Math.random() * 6);
        const humidity   = Math.round(50 + Math.random() * 40);
        const rainChance = Math.round(Math.random() * 100);
        const rainMm     = parseFloat((Math.random() * 15).toFixed(1));
        const riskValue  = Math.round(rainChance * 0.5 + humidity * 0.3 + Math.random() * 20);
        result.push({
            date: d.toLocaleDateString('nl-BE'),
            minTemp, maxTemp, humidity, rainChance, rainMm,
            riskValue,
            isRisk: riskValue >= RISK_THRESHOLD
        });
    }
    return result;
}


function renderAll(data) {
    const days   = currentTab === '1week' ? 7 : 14;
    const sliced = data.slice(0, days);
    renderTable(sliced);
    renderTrendChart(sliced);
    renderEvolutieChart(sliced);
    exposeRiskDays(sliced);
}

function renderTable(data) {
    const tbody = document.getElementById('weather-table-body');
    tbody.innerHTML = '';
    data.forEach(day => {
        const tr = document.createElement('tr');
        if (day.isRisk) tr.classList.add('risk-row');
        tr.innerHTML = `
            <td>${day.date}</td>
            <td>${day.minTemp} °C</td>
            <td>${day.maxTemp} °C</td>
            <td>${day.humidity}%</td>
            <td>${day.rainChance}%</td>
            <td>${day.rainMm} mm</td>
            <td class="${day.isRisk ? 'risk-cell' : ''}">
                ${day.isRisk ? '⚠️ Risico' : 'Normaal'}
            </td>
        `;
        tbody.appendChild(tr);
    });
}


function renderTrendChart(data) {
    const labels    = data.map(d => d.date);
    const values    = data.map(d => d.riskValue);
    const bgColors  = data.map(d =>
        d.isRisk ? 'rgba(220,38,38,0.75)' : 'rgba(59,130,246,0.75)'
    );
    const borderColors = data.map(d =>
        d.isRisk ? 'rgba(220,38,38,1)' : 'rgba(59,130,246,1)'
    );

    const type = currentChartType === 'lijn' ? 'line'
               : currentChartType === 'staaf' ? 'bar'
               : 'bar'; // gemengd defaults trend to bar

    if (trendChart) trendChart.destroy();

    trendChart = new Chart(document.getElementById('trendChart'), {
        type,
        data: {
            labels,
            datasets: [{
                label: 'Risicowaarde',
                data: values,
                backgroundColor: bgColors,
                borderColor: borderColors,
                borderWidth: 2,
                borderRadius: 4,
                pointBackgroundColor: bgColors, // for line mode
                tension: 0.3
            }]
        },
        options: {
            plugins: {
                legend: { display: true },
                tooltip: {
                    callbacks: {
                        label: ctx => {
                            const d = data[ctx.dataIndex];
                            return [
                                `Risicowaarde: ${d.riskValue}`,
                                `Neerslag: ${d.rainMm} mm`,
                                `Neerslag kans: ${d.rainChance}%`,
                                `Vochtigheid: ${d.humidity}%`,
                                d.isRisk ? '⚠️ Risicodag!' : '✅ Normaal'
                            ];
                        }
                    }
                }
            },
            scales: { y: { beginAtZero: true, max: 100 } }
        }
    });
}


function renderEvolutieChart(data) {
    const labels   = data.map(d => d.date);
    const minTemps = data.map(d => d.minTemp);
    const maxTemps = data.map(d => d.maxTemp);
    const avgTemps = data.map(d =>
        parseFloat(((d.minTemp + d.maxTemp) / 2).toFixed(1))
    );
    const riskBg = data.map(d =>
        d.isRisk ? 'rgba(220,38,38,0.12)' : 'rgba(0,0,0,0)'
    );

   
    const avgType = currentChartType === 'gemengd' ? 'bar'
                  : currentChartType === 'staaf'   ? 'bar' : 'line';

    if (evolutieChart) evolutieChart.destroy();

    evolutieChart = new Chart(document.getElementById('evolutieChart'), {
        type: 'line', // base type; avg dataset overrides per-dataset
        data: {
            labels,
            datasets: [
                {
                    label: 'Min °C',
                    data: minTemps,
                    type: 'line',
                    borderColor: 'rgba(59,130,246,1)',
                    backgroundColor: 'rgba(59,130,246,0.08)',
                    tension: 0.3,
                    pointBackgroundColor: data.map(d =>
                        d.isRisk ? 'rgba(220,38,38,1)' : 'rgba(59,130,246,1)'
                    )
                },
                {
                    label: 'Max °C',
                    data: maxTemps,
                    type: 'line',
                    borderColor: 'rgba(234,88,12,1)',
                    backgroundColor: 'rgba(234,88,12,0.08)',
                    tension: 0.3,
                    pointBackgroundColor: data.map(d =>
                        d.isRisk ? 'rgba(220,38,38,1)' : 'rgba(234,88,12,1)'
                    )
                },
                {
                    label: 'Gemiddelde °C',
                    data: avgTemps,
                    type: avgType,
                    borderColor: 'rgba(22,163,74,1)',
                    backgroundColor: data.map(d =>
                        d.isRisk ? 'rgba(220,38,38,0.5)' : 'rgba(22,163,74,0.4)'
                    ),
                    borderDash: avgType === 'line' ? [5, 5] : [],
                    tension: 0.3,
                    borderRadius: 4
                }
            ]
        },
        options: {
            plugins: {
                legend: { display: true },
                tooltip: {
                    callbacks: {
                        afterBody: items => {
                            const d = data[items[0].dataIndex];
                            return d.isRisk
                                ? [`⚠️ Risicodag! (score: ${d.riskValue})`]
                                : [];
                        }
                    }
                }
            }
        }
    });
}


function exposeRiskDays(data) {
    window.riskDays = data.filter(d => d.isRisk);
    // Other modules can read: window.riskDays
}

function showState(state) {
    document.getElementById('loading-state').style.display =
        state === 'loading' ? 'flex' : 'none';
    document.getElementById('error-state').style.display  =
        state === 'error'   ? 'block' : 'none';
    document.getElementById('empty-state').style.display  =
        state === 'empty'   ? 'block' : 'none';
    document.getElementById('data-content').style.display =
        state === 'data'    ? 'block' : 'none';
}