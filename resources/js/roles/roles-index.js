
window.filterTable = function(query) {
    var rows = document.querySelectorAll('#roles-tbody tr:not(#empty-row)');
    var q = query.toLowerCase().trim();
    var visible = 0;
    rows.forEach(function(row) {
        var name = row.cells[1] ? row.cells[1].textContent.toLowerCase() : '';
        var match = name.includes(q);
        row.style.display = match ? '' : 'none';
        if (match) visible++;
    });
    document.getElementById('no-results').style.display = visible === 0 && q !== '' ? 'block' : 'none';
}

import { initFuzzySearch } from '../fuzzy-search.js'
document.addEventListener('DOMContentLoaded', () => {
    initFuzzySearch({
        inputId:       'search-roles',
        suggestionsId: 'search-suggestions',
        tbodyId:       'roles-tbody',
        keys:          ['name'],
    })
})


