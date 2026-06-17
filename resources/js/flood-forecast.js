import { findIn, getForm, createWatchedObject, saveToCache, loadFromCache } from "./utilities";

const CACHE_KEY      = 'weather_forecast_cache';
const CACHE_DURATION = 30 * 60 * 1000; // 30 min

/**
 * API endpoint URL for fetching flood forecast data.
 * @type {string}
 */
const API_URL = '/api/technieker/flood-forecast';

/**
 * State object for managing the selected overview window (number of forecast days).
 * - value: Number of days to display (default: 7).
 * - dom: DOM reference to the active overview button element.
 * Calls handleOverviewChange on state changes.
 */
const overview = createWatchedObject({
    value: 7,
    dom: null
}, handleOverviewChange);

/**
 * State object for managing selected chart type and holding Chart.js and DOM references.
 * - value: Type of chart to show ("mixed", "line", "bar").
 * - doms: Buttons in the DOM for each chart type.
 * - charts: Chart block/canvas/context per chart type.
 * Calls handleChartChange on state changes.
 */
const chart = createWatchedObject({
    value: "mixed",
    doms: {
        mixed: null,
        line: null,
        bar: null
    },
    charts: {
        line: {
            block: null,
            canvas: null,
            context: null // Chart.js instance for evolution chart
        },
        bar: {
            block: null,
            canvas: null,
            context: null // Chart.js instance for trend chart
        }
    }
}, handleChartChange);

/**
 * State object reflecting the current UI/UX display state for the page.
 * - value: Must be "loading", "error", "empty", or "data".
 * - doms: Corresponding element references for each state section.
 * Calls handleStateChange on value changes.
 */
const state = createWatchedObject({
    value: "loading",
    doms: {
        loading: null,
        error: null,
        empty: null,
        data: null
    }
}, handleStateChange);

/**
 * Array holding processed daily forecast objects for the current API result; populated by loadWeatherData.
 * @type {Array<object>}
 */
let daily = [];

// Track the largest value of daily.length we've ever received
let maxFetchedDays = 0;

main();

/**
 * Main page initialization function.
 * Locates UI sections, binds all event handlers for tabs, chart buttons, reloads, etc.
 * Then triggers initial data load.
 *
 * Notes:
 * - Tab & chart DOM buttons are linked to watched state objects.
 * - All DOM lookups are performed safely.
 * - Listeners automatically update state and trigger render/updating logic as needed.
 * - Should be called once on page load.
 */
function main() {
    const main = findIn(document, "main");
    const forecastContainer = findIn(main, "#forecast-container");

    if (!main || !forecastContainer) return;

    // Tab & chart UI bindings
    const b_overviewOneWeek = findIn(forecastContainer, "#tab-1week");
    const b_overviewTwoWeek = findIn(forecastContainer, "#tab-2weken");
    const b_chartTypeMixed = findIn(forecastContainer, "#btn-gemengd");
    const b_chartTypeLine = findIn(forecastContainer, "#btn-lijn");
    const b_chartTypeBar = findIn(forecastContainer, "#btn-staaf");
    const b_reload = findIn(forecastContainer, "#reload-web-page");
    const s_loading = findIn(forecastContainer, "#loading-state");
    const s_error = findIn(forecastContainer, "#error-state");
    const s_empty = findIn(forecastContainer, "#empty-state");
    const s_data = findIn(forecastContainer, "#data-content");

    // Bind UI to observed state objects
    overview.dom = b_overviewOneWeek;
    chart.doms.mixed = b_chartTypeMixed;
    chart.doms.line = b_chartTypeLine;
    chart.doms.bar = b_chartTypeBar;
    chart.charts.line.block = findIn(forecastContainer, "#evolution-chart-block");
    chart.charts.line.canvas = findIn(chart.charts.line.block, "canvas");
    chart.charts.bar.block = findIn(forecastContainer, "#trend-chart-block");
    chart.charts.bar.canvas = findIn(chart.charts.bar.block, "canvas");
    state.doms.loading = s_loading;
    state.doms.error = s_error;
    state.doms.empty = s_empty;
    state.doms.data = s_data;

    // Register tab, chart, and reload event handling
    forecastContainer.addEventListener("click", event => {
        const target = event.target;

        // Handle switching forecast window (overview tab)
        if ([b_overviewOneWeek, b_overviewTwoWeek].includes(target)) {
            overview.dom.classList.remove("active");
            overview.value = parseInt(target.value);
            overview.dom = target;
            target.classList.add("active");
            updateAll();
        }

        // Handle switching chart type (mixed/line/bar)
        if ([b_chartTypeMixed, b_chartTypeBar, b_chartTypeLine].includes(target)) {
            chart.value = target.value;
        }

        // Handle reload button
        if (b_reload === target) {
            location.reload();
        }
    });

    loadWeatherData();
}

/**
 * Watched-object handler for changes to overview state.
 * (Currently stubbed to console, but may be extended for advanced tab sync logic.)
 *
 * @param {string} prop - Changed property
 * @param {*} newValue - New value for the property
 * @param {*} oldValue - Previous value
 */
function handleOverviewChange(prop, newValue, oldValue) {
    // console.log(`overview[${prop}] changed from`, oldValue, 'to', newValue);

    if (prop === "value") {
        loadWeatherData();
    }
}

/**
 * Watched-object handler for changes to chart state.
 * Updates tab UI highlight and correct chart block visibility.
 *
 * - Swaps chart button styling.
 * - Switches visibility of corresponding chart blocks to match selection.
 * - For "mixed", shows both charts; for "line"/"bar", hides the other.
 *
 * @param {string} prop
 * @param {*} newValue
 * @param {*} oldValue
 */
function handleChartChange(prop, newValue, oldValue) {
    // console.log(`chart[${prop}] changed from`, oldValue, 'to', newValue);

    if (prop === "value") {
        // Highlight the active chart button.
        if (chart.doms[oldValue]) chart.doms[oldValue].classList.remove("active");
        if (chart.doms[newValue]) chart.doms[newValue].classList.add("active");

        switch (newValue) {
            case "mixed":
                // Show both charts at once
                chart.charts.line.block.classList.remove("visually-hidden");
                chart.charts.bar.block.classList.remove("visually-hidden");
                break;
            case "line":
            case "bar":
                // Show only the selected chart, hide others
                Object.entries(chart.charts).forEach(([key, container]) => {
                    if (!container) return;
                    if (key === newValue) {
                        container.block.classList.remove("visually-hidden");
                    } else {
                        container.block.classList.add("visually-hidden");
                    }
                });
                break;
            default:
                console.warn(`Unhandled chart case: ${newValue}`);
        }
    }
}

/**
 * Watched-object handler for changes to global state value.
 * Ensures that only the relevant content section is visible for the state.
 *
 * @param {string} prop
 * @param {*} newValue
 * @param {*} oldValue
 */
function handleStateChange(prop, newValue, oldValue) {
    // console.log(`state[${prop}] changed from`, oldValue, 'to', newValue);

    if (prop === "value") {
        switch (newValue) {
            case "loading":
            case "error":
            case "empty":
            case "data":
                // Hide the previously active section, show current.
                if (state.doms[oldValue]) {
                    state.doms[oldValue].classList.remove("active");
                    state.doms[oldValue].classList.add("visually-hidden");
                }
                if (state.doms[newValue]) {
                    state.doms[newValue].classList.add("active");
                    state.doms[newValue].classList.remove("visually-hidden");
                }
                break;
            default:
                console.warn(`Unhandled state case: ${newValue}`);
        }
    }
}

/**
 * Fetches weather forecast data from backend for the selected window and updates state.
 * Populates `daily` with processed data, then triggers rendering. Sets daily to always be the largest API returned set.
 * Sets empty, error, and success states.
 *
 * @async
 */
async function loadWeatherData() {
    state.value = "loading";
    const cached = loadFromCache(CACHE_KEY, CACHE_DURATION);

    if (cached && cached.length >= overview.value) {
        daily = cached;
        renderAll();
        state.value = "data";
        return;
    }

    try {
        const { message, data, success, errors, exception } = await getForm(
            `${API_URL}?days_ahead=${overview.value}`
        );
        console.log(message);

        if (!success || (!data && !data.daily)) {
            state.value = "empty";
            return;
        }

        // If daily is not yet defined or empty, just replace it and update maxFetchedDays
        if (!Array.isArray(daily) || daily.length === 0) {
            daily = data.daily;
            maxFetchedDays = data.daily.length;
        } else {
            // When processed is longer than or equal to what we've seen before, update all/extend
            if (data.daily.length >= maxFetchedDays) {
                for (const newDay of data.daily) {
                    const idx = daily.findIndex(item => item.date === newDay.date);
                    if (idx !== -1) {
                        daily[idx] = newDay;
                    } else {
                        daily.push(newDay);
                    }
                }
                maxFetchedDays = data.daily.length;
            } else {
                // processed is shorter (less days than previously seen): only update those days
                for (const newDay of data.daily) {
                    const idx = daily.findIndex(item => item.date === newDay.date);
                    if (idx !== -1) {
                        daily[idx] = newDay;
                    } else {
                        daily.push(newDay);
                    }
                }
                // maxFetchedDays stays the same because we still have more days present in daily
            }
        }

        saveToCache(CACHE_KEY, daily);
        renderAll();
        state.value = "data";
    } catch (error) {
        console.error(error);
        state.value = "error";
    }
}

/**
 * Invoked after loading new weather data.
 * Slices current `daily` to length of current overview, then renders:
 * - Main table
 * - Bar trend chart
 * - Line evolution chart
 * - Export of risk day list to window
 */
function renderAll() {
    const sliced = daily.slice(0, overview.value);
    updateTable(sliced);
    renderTrendChart(sliced);
    renderEvolutionChart(sliced);
    updateRiskDays();
}

/**
 * Updates UI after a settings change (tab or chart style).
 * Uses new overview window, but does not fetch data.
 * Slices current `daily` to length of current overview, then Updates:
 * - Main table
 * - Bar trend chart
 * - Line evolution chart
 * - Risk day list on window
 */
function updateAll() {
    const sliced = daily.slice(0, overview.value);
    updateTable(sliced);
    updateTrendChart(sliced);
    updateEvolutionChart(sliced);
    updateRiskDays();
}

/**
 * Fills the weather table body with rows based on latest data.
 * Risk days ("isRisk") are visually distinguished.
 * Table expects `<tbody id="weather-table-body">` in DOM.
 *
 * @param {object[]} data - Processed daily data slice to graph
 */
function updateTable(data) {
    const tbody = document.getElementById("weather-table-body");
    if (!tbody) return;
    tbody.innerHTML = "";
    data.forEach(day => {
        const tr = document.createElement("tr");
        if (day.isRisk) tr.classList.add("risk-row");
        tr.innerHTML = `
            <td>${day.date}</td>
            <td>${day.minTemp != null ? day.minTemp + " °C" : "-"}</td>
            <td>${day.maxTemp != null ? day.maxTemp + " °C" : "-"}</td>
            <td>${day.humidity != null ? day.humidity + "%" : "-"}</td>
            <td>${day.rainChance != null ? day.rainChance + "%" : "-"}</td>
            <td>${day.rainMm != null ? day.rainMm + " mm" : "-"}</td>
            <td class="${day.isRisk ? "risk-cell" : ""}">
                ${day.isRisk ? "⚠️ Risico" : "Normaal"}
            </td>
        `;
        tbody.appendChild(tr);
    });
}

/**
 * Initializes the "trend" bar chart with risk scores, setting custom colors for risk days.
 * Chart is rendered using Chart.js and stored in `chart.charts.bar.context`.
 * Destroys previous chart instance.
 *
 * @param {object[]} data - Processed daily data slice to graph
 */
function renderTrendChart(data) {
    // Defensive destroy if instance exists (optional best practice)
    if (chart.charts.bar.context && chart.charts.bar.context instanceof Chart) {
        chart.charts.bar.context.destroy();
    }
    const labels = data.map(d => d.date);
    const values = data.map(d => d.riskValue);
    const bgColors = data.map(d =>
        d.isRisk ? "rgba(220,38,38,0.75)" : "rgba(59,130,246,0.75)"
    );
    const borderColors = data.map(d =>
        d.isRisk ? "rgba(220,38,38,1)" : "rgba(59,130,246,1)"
    );

    chart.charts.bar.context = new Chart(chart.charts.bar.canvas, {
        type: "bar",
        data: {
            labels,
            datasets: [{
                label: "Risicowaarde",
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
                            // Tooltip shows risk information and weather for the day
                            const d = data[ctx.dataIndex];
                            return [
                                `Risicowaarde: ${d.riskValue}`,
                                `Neerslag: ${d.rainMm} mm`,
                                `Neerslag kans: ${d.rainChance}%`,
                                `Vochtigheid: ${d.humidity}%`,
                                d.isRisk ? "⚠️ Risicodag!" : "✅ Normaal"
                            ];
                        }
                    }
                }
            },
            scales: { y: { beginAtZero: true, max: 100 } },
            responsive: true,
            maintainAspectRatio: false
        }
    });
}

/**
 * Updates the "trend" bar chart if forecast window is changed, without re-instantiating the Chart.
 * Colors, data, and tooltips all refreshed according to slicing and risks.
 *
 * @param {object[]} data - Processed daily data slice to graph
 */
function updateTrendChart(data) {
    const labels = data.map(d => d.date);
    const values = data.map(d => d.riskValue);
    const bgColors = data.map(d =>
        d.isRisk ? "rgba(220,38,38,0.75)" : "rgba(59,130,246,0.75)"
    );
    const borderColors = data.map(d =>
        d.isRisk ? "rgba(220,38,38,1)" : "rgba(59,130,246,1)"
    );

    // Update existing
    chart.charts.bar.context.data.labels = labels;
    chart.charts.bar.context.data.datasets[0].data = values;
    chart.charts.bar.context.data.datasets[0].backgroundColor = bgColors;
    chart.charts.bar.context.data.datasets[0].borderColor = borderColors;
    chart.charts.bar.context.data.datasets[0].pointBackgroundColor = bgColors;
    chart.charts.bar.context.update();
}

/**
 * Initializes the "evolution" line chart for daily min/max/average temperature, coloring risk days.
 * Chart is rendered using Chart.js and stored in `chart.charts.line.context`.
 * Destroys previous chart instance.
 *
 * @param {object[]} data - Processed daily data slice to graph
 */
function renderEvolutionChart(data) {
    // Defensive destroy if instance exists (optional best practice)
    if (chart.charts.line.context && chart.charts.line.context instanceof Chart) {
        chart.charts.line.context.destroy();
    }
    const labels = data.map(d => d.date);
    const minTemps = data.map(d => d.minTemp);
    const maxTemps = data.map(d => d.maxTemp);
    const avgTemps = data.map(d =>
        (d.minTemp != null && d.maxTemp != null)
            ? parseFloat(((d.minTemp + d.maxTemp) / 2).toFixed(1))
            : null
    );

    chart.charts.line.context = new Chart(chart.charts.line.canvas, {
        type: "line",
        data: {
            labels,
            datasets: [
                {
                    label: "Min °C",
                    data: minTemps,
                    type: "line",
                    borderColor: "rgba(59,130,246,1)",
                    backgroundColor: "rgba(59,130,246,0.08)",
                    tension: 0.3,
                    pointBackgroundColor: data.map((d) =>
                        d.isRisk ? "rgba(220,38,38,1)" : "rgba(59,130,246,1)"
                    )
                },
                {
                    label: "Max °C",
                    data: maxTemps,
                    type: "line",
                    borderColor: "rgba(234,88,12,1)",
                    backgroundColor: "rgba(234,88,12,0.08)",
                    tension: 0.3,
                    pointBackgroundColor: data.map((d) =>
                        d.isRisk ? "rgba(220,38,38,1)" : "rgba(234,88,12,1)"
                    )
                },
                {
                    label: "Gemiddelde °C",
                    data: avgTemps,
                    type: "bar",
                    borderColor: "rgba(22,163,74,1)",
                    backgroundColor: data.map((d) =>
                        d.isRisk ? "rgba(220,38,38,0.5)" : "rgba(22,163,74,0.4)"
                    ),
                    borderDash: chart.value === "line" ? [5, 5] : [],
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
            },
            responsive: true,
            maintainAspectRatio: false
        }
    });
}

/**
 * Updates the "evolution" line chart if forecast window is changed, without re-instantiating the Chart.
 * Data, point backgrounds, and average bar colors all refreshed according to slicing and risks.
 *
 * @param {object[]} data - Processed daily data slice to graph
 */
function updateEvolutionChart(data) {
    const labels = data.map(d => d.date);
    const minTemps = data.map(d => d.minTemp);
    const maxTemps = data.map(d => d.maxTemp);
    const avgTemps = data.map(d =>
        (d.minTemp != null && d.maxTemp != null)
            ? parseFloat(((d.minTemp + d.maxTemp) / 2).toFixed(1))
            : null
    );
    const riskBg = data.map((d) =>
        d.isRisk ? "rgba(220,38,38,0.12)" : "rgba(0,0,0,0)"
    );

    // Update existing chart datasets and styling
    chart.charts.line.context.data.labels = labels;
    chart.charts.line.context.data.datasets[0].data = minTemps;
    chart.charts.line.context.data.datasets[0].pointBackgroundColor = data.map(d =>
        d.isRisk ? "rgba(220,38,38,1)" : "rgba(59,130,246,1)"
    );
    chart.charts.line.context.data.datasets[1].data = maxTemps;
    chart.charts.line.context.data.datasets[1].pointBackgroundColor = data.map(d =>
        d.isRisk ? "rgba(220,38,38,1)" : "rgba(234,88,12,1)"
    );
    chart.charts.line.context.data.datasets[2].data = avgTemps;
    chart.charts.line.context.data.datasets[2].backgroundColor = data.map(d =>
        d.isRisk ? "rgba(220,38,38,0.5)" : "rgba(22,163,74,0.4)"
    );
    chart.charts.line.context.update();
}

/**
 * Exports current risk days (as objects from data where isRisk is true) to global window object.
 * Allows other modules/scripts to check or query risk periods if desired.
 */
function updateRiskDays() {
    window.riskDays = daily.filter(d => d.isRisk);
}
