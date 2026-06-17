import {addFuzzySearch} from "../utils.js";
addFuzzySearch('search-order','search-suggestions', 'orders-tbody', ['firstname', 'lastname', 'deliverysite'],)


const searchInput = document.querySelector('input[type="text"]');
const dateInput = document.querySelector('input[type="date"]');
const regioSelect = document.querySelector('select');
const rows = document.querySelectorAll('.manager-table tbody tr');
const emptyMessage = document.querySelector('.empty-message');

function filterTable() {
    let visibleRows = 0;

    rows.forEach(row => {
        const rowText = row.innerText.toLowerCase();
        const searchValue = searchInput.value.toLowerCase();
        const dateValue = dateInput.value;
        const regioValue = regioSelect.value.toLowerCase();

        const matchSearch = rowText.includes(searchValue);
        const matchRegio = regioValue === "alle regio's" || rowText.includes(regioValue);
        const matchDate = dateValue === "" || rowText.includes(dateValue);

        if (matchSearch && matchRegio && matchDate) {
            row.style.display = "";
            visibleRows++;
        } else {
            row.style.display = "none";
        }
    });

    emptyMessage.style.display = visibleRows === 0 ? "block" : "none";
}

searchInput.addEventListener('input', filterTable);
dateInput.addEventListener('change', filterTable);
regioSelect.addEventListener('change', filterTable);





