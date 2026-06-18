import {initFuzzySearch} from "../fuzzy-search.js";
import {loadFromCache} from "../utilities.js";
import {loadWeatherData} from "../flood-forecast.js";

document.addEventListener('DOMContentLoaded', () => {
    initFuzzySearch({
        inputId:       'search-materials',
        suggestionsId: 'search-suggestions',
        tbodyId:       'materials-tbody',
        keys:           ['name'],
    })
})
loadWeatherData(true);

const data = loadFromCache('weather_forecast_cache', 30 * 60 * 1000)
const datumInput = document.getElementById('delivery_date')
let dayType = null;
datumInput.addEventListener('change', ()=>{

    let dayData = null;
    const alertData = document.getElementById('alert-data')
    try {
        for (const datum of data) {
            if (datumInput.value === datum.date) {
                dayData = datum;
            }
        }

        if (!dayData) throw new Error('Geen data voor deze datum');

        if (dayData.maxTemp >= 40) {
            dayType = "HOT";
        } else if (dayData.humidity > 90) {
            dayType = "HUMID";
        } else if (dayData.rainMm > 3) {
            dayType = "RAINY";
        } else if (dayData.riskValue > 70) {
            dayType = "RISK_DAY";
        } else {
            dayType = "NORMAL";
        }
        console.log(dayType)
        alertData.style.display = 'none';
        renderPriorityList(dayType, materials);
    } catch (e) {

        alertData.style.display = 'block'
        console.error('Fout bij datumverwerking:', e.message);
    }

})

function hideFromMainTable(id) {
    const row = document.querySelector(`#materials-table tbody tr[data-id="${id}"]`);
    if (!row) return;

    row.querySelectorAll('input[name]').forEach(input => {
        input.dataset.oldName = input.name;
        input.removeAttribute('name');
    });

    row.style.display = 'none';
}

function restoreMainTable() {
    document.querySelectorAll('#materials-table tbody tr').forEach(row => {

        row.querySelectorAll('input[data-old-name]').forEach(input => {
            input.name = input.dataset.oldName;
            delete input.dataset.oldName;
        });

        row.style.display = '';
    });
}

const priorityTbody = document.getElementById('priority-list-tbody')
const priorityList = document.getElementById('priority-list')
function renderPriorityList(dayType, materials){
    priorityTbody.innerHTML = '';  // ← vide la priority list
    restoreMainTable();             // ← remet tout dans le main
    priorityList.style.display = 'block';


    for (const material of materials) {
        if (material.type === "NORMAL"){
            continue
        }
        if (material.type === dayType || material.type === "ALWAYS"){
            const tr = document.createElement("tr");

            tr.dataset.naam = material.name.toLowerCase();
            tr.dataset.categorie = material.category?.name ?? "";

            tr.innerHTML = `
                        <td>${material.id}</td>
                        <td><a href="/technieker/materials/${material.id}">${material.name}</a> </td>
                        <td class="category-material">${material.category.name ?? ''}</td>
                        <td>
                            <input type="number"
                                   value="0"
                                   name=quantity[${material.id}]
                                   class="form-control form-control-sm quantity-input"
                                   data-id="${material.id}"
                                   data-naam="${material.name}">
                        </td>
                        <td>
                            <input type="checkbox"
                                    name="materials[]"
                                     value=${material.id}
                                   class="form-check-input material-checkbox"
                                   data-id="${material.id}"
                                   data-naam="${material.name}">
                        </td>`;

            priorityTbody.appendChild(tr);
            hideFromMainTable(material.id);
        }
    }
}


