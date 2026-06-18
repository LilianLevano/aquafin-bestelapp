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
console.log(data)
const datumInput = document.getElementById('delivery_date')
let dayType = null;
datumInput.addEventListener('change', ()=>{

    let dayFound = false;
    let dayData = null;

    for(const datum of data){
        if (datumInput.value === datum.date){
            dayFound = true;
            dayData = datum
        }
    }

    if(dayData.maxTemp >= 40){
        dayType = "HOT"
    }else if(dayData.humidity > 90){
        dayType = "HUMID"
    }else if (dayData.rainMm > 3){
        dayType = "RAINY"
    }else if (dayData.riskValue > 70){
        dayType = "RISK_DAY"
    }else{
        dayType = "NORMAL"
    }
    console.log(dayType)
    renderPriorityList(dayType, materials)


})

const priorityTbody = document.getElementById('priority-list-tbody')
const priorityList = document.getElementById('priority-list')
function renderPriorityList(dayType, materials){
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
                        <td>${material.name}</td>
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

function hideFromMainTable(id) {
    const row = document.querySelector(`#materials-table tbody tr[data-id="${id}"]`);
    if (row) row.remove();
}

function showInMainTable(id) {
    const row = document.querySelector(`#materials-table tbody tr[data-id="${id}"]`);
    if (row) row.style.display = '';
}

function removeFromPriority(id) {
    showInMainTable(id);

    const row = document.querySelector(`#priority-list-tbody input[data-id="${id}"]`)?.closest('tr');
    if (row) row.remove();
}


