import { findIn, fetchWithCache, fetchWithoutCache, createWatchedObject } from "./utilities.js";
import { CACHE_KEY_WEATHER_FORECAST, CACHE_DURATION_WEATHER_FORECAST } from "./constants/cache.js";
import { API_URL_WEATHER_FORECAST } from "./constants/api.js";

/**
 * State object for managing the selected overview window (number of forecast days).
 * When the state changes, the handler {@link handleOverviewChange} is triggered to update the data and UI.
 *
 * @typedef {Object} OverviewState
 * @property {number} value - Number of days to display (default: 7).
 * @property {HTMLElement | null} dom - DOM reference to the active overview button element.
 */
const overview = createWatchedObject({
    value: 7,
    dom: null
}, handleOverviewChange);

/**
 * State object for managing the selected chart type and holding Chart.js and DOM references.
 * When the chart state changes, the handler {@link handleChartChange} is triggered to update the UI highlights and toggle chart block visibility accordingly.
 *
 * @typedef {Object} ChartState
 * @property {string} value - Type of chart to show ("mixed", "line", "bar").
 * @property {Object} doms - Mapping of chart type keys to DOM button elements (mixed, line, bar).
 * @property {Object} charts - Structure holding chart blocks, canvases, and Chart.js context instances:
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
            block: null, // Chart container block DOM node for evolution chart
            canvas: null, // <canvas> element for evolution chart
            context: null // Chart.js instance for evolution chart
        },
        bar: {
            block: null, // Chart container block DOM node for trend chart
            canvas: null, // <canvas> element for trend chart
            context: null // Chart.js instance for trend chart
        }
    }
}, handleChartChange);

/**
 * State object reflecting the current UI/UX display state for the page.
 * When the fetch state changes, the handler {@link handleStateChange} is triggered to ensure only the relevant content section's DOM is visible.
 *
 * @typedef {Object} UiState
 * @property {string} value - One of "loading", "error", "empty", or "data". Indicates which section is shown.
 * @property {Object} doms - Object with references to DOM containers for all UI sections ("loading", "error", "empty", "data").
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
 * State object holding the full array of processed daily forecast objects, populated by weather data API.
 * The `value` property always contains the largest API result ever seen (allows subsetting for tabs).
 * When the weather data state changes, the handler {@link handleDailyChange} is triggered to update the UI.
 *
 * @typedef {Object} DailyState
 * @property {Array<Object>|null} value - Array of processed forecast day objects (after API/caching fetch).
 */
const daily = createWatchedObject({
    value: null
}, handleDailyChange);

/**
 * Tracks the largest value of daily.value.length returned thus far from the weather API.
 * Used so that changes in overview/tabs do not throw away already-fetched available data.
 *
 * @type {number}
 */
let maxFetchedDays = 0;

await main();

/**
 * Main page initialization function.
 * - Locates UI container elements (main, forecast area, tabs, charts, error/loading/data containers)
 * - Binds UI controls (overview tabs, chart tabs, reload button) to their watched objects
 * - Registers a click delegate for all tab, chart, and reload buttons.
 * - Associates the state/dom structure (see objects above) to persist references as user interacts
 *
 * Should be called once when the page loads.
 */
async function main() {
    const main = findIn(document, "main");
    const forecastContainer = findIn(main, "#forecast-container");

    if (!main || !forecastContainer) return;

    // DOM lookups (buttons & content sections)
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

    // Bind initial overview and chart types to DOM nodes
    overview.dom = b_overviewOneWeek;
    chart.doms.mixed = b_chartTypeMixed;
    chart.doms.line = b_chartTypeLine;
    chart.doms.bar = b_chartTypeBar;

    // Bind chart containers and canvases
    chart.charts.line.block = findIn(forecastContainer, "#evolution-chart-block");
    chart.charts.line.canvas = findIn(chart.charts.line.block, "canvas");
    chart.charts.bar.block = findIn(forecastContainer, "#trend-chart-block");
    chart.charts.bar.canvas = findIn(chart.charts.bar.block, "canvas");

    // Bind UI state doms
    state.doms.loading = s_loading;
    state.doms.error = s_error;
    state.doms.empty = s_empty;
    state.doms.data = s_data;

    // Register tab, chart, and reload event handling
    forecastContainer.addEventListener("click", event => {
        const target = event.target;

        // Overview tabs: switch forecast section (number of days in view)
        if ([b_overviewOneWeek, b_overviewTwoWeek].includes(target)) {
            overview.dom.classList.remove("active");
            overview.value = parseInt(target.value);
            overview.dom = target;
            target.classList.add("active");
        }

        // Chart type tabs: switch charts between mixed/line/bar modes
        if ([b_chartTypeMixed, b_chartTypeBar, b_chartTypeLine].includes(target)) {
            chart.value = target.value;
        }

        // Reload button: refresh full web page
        if (b_reload === target) {
            location.reload();
        }
    });

    daily.value = await loadWeatherData();
}

/**
 * Handler for overview (forecast) state changes.
 * Triggers a fresh daily data fetch for the new number of days.
 *
 * @param {string} prop - Name of the changed property
 * @param {*} newValue - New value for the property
 * @param {*} oldValue - Previous value
 */
async function handleOverviewChange(prop, newValue, oldValue) {
    // console.log(`overview[${prop}] changed from`, oldValue, 'to', newValue);

    if (prop === "value") {
        const data = await fetchWithoutCache(
            CACHE_KEY_WEATHER_FORECAST,
            `${API_URL_WEATHER_FORECAST}?days_ahead=${overview.value}`
        );

        if (data) {
            daily.value = data.daily;
        }
        updateAll();
    }
}

/**
 * Handler for chart type state changes.
 * Updates UI highlights to reflect selected chart type and toggles visibility of corresponding chart blocks.
 * - If "mixed", both charts are shown.
 * - If "line" or "bar", only the selected chart is shown, the other is hidden.
 *
 * @param {string} prop - Name of the changed property
 * @param {*} newValue - New value for the property
 * @param {*} oldValue - Previous value
 */
function handleChartChange(prop, newValue, oldValue) {
    // console.log(`chart[${prop}] changed from`, oldValue, 'to', newValue);

    if (prop === "value") {
        // Highlight and un-highlight chart buttons
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
 * Handler for changes to the fetch state.
 * Ensures only the appropriate DOM section (loading, empty, error, data) is visible.
 *
 * @param {string} prop - Name of the changed property
 * @param {*} newValue - New value for the property
 * @param {*} oldValue - Previous value
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
 * Handler for processed daily forecast data changes.
 * Also updates maxFetchedDays tracker to ensure app caching logic works as intended.
 *
 * @param {string} prop - Name of the changed property
 * @param {*} newValue - New value for the property
 * @param {*} oldValue - Previous value
 */
function handleDailyChange(prop, newValue, oldValue) {
    // console.log(`overview[${prop}] changed from`, oldValue, 'to', newValue);

    if (prop === "value") {
        maxFetchedDays = Math.max(
            Array.isArray(newValue) ? newValue.length : 0,
            Array.isArray(oldValue) ? oldValue.length : 0,
            maxFetchedDays
        );
    }
}

/**
 * Fetches weather forecast data from backend API for the selected forecast window.
 * Populates `daily` with processed data and triggers rendering of all major UI components.
 * Ensures that the `daily.value` always holds the largest length result fetched so far, never throwing away available days.
 *
 * Sets the UI state for loading, error, empty, or data as appropriate.
 *
 * @async
 * @returns {Promise<Array<Object> | null>} Resolves to the latest daily data array
 */
async function loadWeatherData() {
    state.value = "loading";

    try {
      const data = await fetchWithCache(
    CACHE_KEY_WEATHER_FORECAST,
    CACHE_DURATION_WEATHER_FORECAST,
    `${API_URL_WEATHER_FORECAST}?days_ahead=${overview.value}`
        );

        if (!data || !data.daily) {
            state.value = "empty";
            return null;
        }

        if (data.daily && data.daily.length >= overview.value) {
            daily.value = data.daily;

            renderAll();
            state.value = "data";
            return data.daily;
        }

        // If daily is not yet defined or empty, just replace it and update maxFetchedDays
        if (!Array.isArray(daily.value) || daily.value.length === 0) {
            daily.value = data.daily;
        } else {
            // Merge the new data in, ensuring full set for largest daily window ever requested
            if (data.daily.length >= maxFetchedDays) {
                for (const newDay of data.daily) {
                    const idx = daily.value.findIndex(item => item.date === newDay.date);

                    if (idx !== -1) {
                        daily.value[idx] = newDay;
                    } else {
                        daily.value.push(newDay);
                    }
                }
            } else {
                // If data.daily is shorter, only update the matching days
                for (const newDay of data.daily) {
                    const idx = daily.value.findIndex(item => item.date === newDay.date);

                    if (idx !== -1) {
                        daily.value[idx] = newDay;
                    } else {
                        daily.value.push(newDay);
                    }
                }
            }
        }

        renderAll();
        state.value = "data";
        return daily.value;
    } catch (error) {
        console.error(error);
        state.value = "error";
        return null;
    }
}

/**
 * Called after successfully loading daily weather data.
 * Slices current daily data to the displayed overview length and re-renders all main UI components:
 * - Weather/Flood data table
 * - "Trend" bar chart with risk scores
 * - "Evolution" min/max/avg temperature chart
 * - Extracts list of current risk days to global variable
 */
function renderAll() {
    if (!Array.isArray(daily.value) || daily.value.length === 0) return;

    const sliced = daily.value.slice(0, overview.value);
    updateTable(sliced);
    renderTrendChart(sliced);
    renderEvolutionChart(sliced);
    updateRiskDays();
}

/**
 * Updates main UI after a settings change (such as overview tab or chart style).
 * Uses pre-fetched daily data to update visible charts and tables.
 * Also updates the exported window.riskDays list.
 */
function updateAll() {
    if (!Array.isArray(daily.value) || daily.value.length === 0) return;

    const sliced = daily.value.slice(0, overview.value);
    updateTable(sliced);
    updateTrendChart(sliced);
    updateEvolutionChart(sliced);
    updateRiskDays();
}

/**
 * Fills the weather table body with rows based on the provided daily forecast records.
 * Highlights risk days visually. Table requires a <tbody id="weather-table-body">.
 *
 * @param {Array<Object>} data - Array of processed, per-day weather/risk objects to render for the table.
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
 * Initializes the "trend" bar chart (risk score per day, colored for risks).
 * Chart is rendered using Chart.js and stored in `chart.charts.bar.context`.
 * Destroys previous chart instance.
 *
 * @param {Array<Object>} data - Processed daily data slice to visualize as chart bars
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
                pointBackgroundColor: bgColors,
                tension: 0.3
            }]
        },
        options: {
            plugins: {
                legend: { display: true },
                tooltip: {
                    callbacks: {
                        /**
                         * Tooltip callback for bar chart showing risk/weather for the selected day.
                         *
                         * @param {*} ctx - Tooltip context from Chart.js
                         */
                        label: ctx => {
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
 * Updates the "trend" bar chart to reflect a new data slice (e.g., if overview tab changed).
 * Chart.js instance (`chart.charts.bar.context`) is kept intact; only data/appearance is refreshed.
 *
 * @param {Array<Object>} data - Slice of daily data to render in the trend chart
 */
function updateTrendChart(data) {
    if (!chart.charts.bar.context) return;

    const labels = data.map(d => d.date);
    const values = data.map(d => d.riskValue);
    const bgColors = data.map(d =>
        d.isRisk ? "rgba(220,38,38,0.75)" : "rgba(59,130,246,0.75)"
    );
    const borderColors = data.map(d =>
        d.isRisk ? "rgba(220,38,38,1)" : "rgba(59,130,246,1)"
    );

    // Update existing chart with new data and appearance
    chart.charts.bar.context.data.labels = labels;
    chart.charts.bar.context.data.datasets[0].data = values;
    chart.charts.bar.context.data.datasets[0].backgroundColor = bgColors;
    chart.charts.bar.context.data.datasets[0].borderColor = borderColors;
    chart.charts.bar.context.data.datasets[0].pointBackgroundColor = bgColors;
    chart.charts.bar.context.update();
}

/**
 * Initializes the "evolution" line+bar chart, which charts daily min/max/average temperatures,
 * coloring risk days distinctly. Chart is rendered with Chart.js and instance saved in
 * chart.charts.line.context.
 * Destroys previous chart instance.
 *
 * @param {Array<Object>} data - Processed daily data to graph on lines/bars
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
    type: "line",
    borderColor: "rgba(22,163,74,1)",
    backgroundColor: "rgba(22,163,74,0.08)",
    pointBackgroundColor: data.map((d) =>
        d.isRisk ? "rgba(220,38,38,1)" : "rgba(22,163,74,1)"
    ),
    tension: 0.3
}
            ]
        },
        options: {
            plugins: {
                legend: { display: true },
                tooltip: {
                    callbacks: {
                        /**
                         * Tooltip for line chart - shows risk flag if relevant
                         *
                         * @param {Array<Object>} items - Tooltip context array
                         */
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
 * Updates the "evolution" line chart (min/max/average temp) for the new daily data.
 * The instance (`chart.charts.line.context`) is not destroyed; dataset data and colors are refreshed.
 *
 * @param {Array<Object>} data - Current data slice to graph
 */
function updateEvolutionChart(data) {
    if (!chart.charts.line.context) return;

    const labels = data.map(d => d.date);
    const minTemps = data.map(d => d.minTemp);
    const maxTemps = data.map(d => d.maxTemp);
    const avgTemps = data.map(d =>
        (d.minTemp != null && d.maxTemp != null)
            ? parseFloat(((d.minTemp + d.maxTemp) / 2).toFixed(1))
            : null
    );
    // riskBg is unused but could be used for background color overlays.
    // const riskBg = data.map((d) => d.isRisk ? "rgba(220,38,38,0.12)" : "rgba(0,0,0,0)");

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
chart.charts.line.context.data.datasets[2].pointBackgroundColor = data.map(d =>
    d.isRisk ? "rgba(220,38,38,1)" : "rgba(22,163,74,1)"
);
    chart.charts.line.context.update();
}

/**
 * Extracts and exposes the current list of risk days (forecast day objects where isRisk is true).
 * The list is assigned to window.riskDays for consumption by external code (scripts, analytics, etc).
 */
function updateRiskDays() {
    if (!Array.isArray(daily.value)) {
        return;
    }
    window.riskDays = daily.value.filter(d => d.isRisk);
}
