window.filterTable = function(query) {
    var rows = document.querySelectorAll('#accounts-tbody tr:not(#empty-row)');
    var q = query.toLowerCase().trim();
    var visible = 0;
    rows.forEach(function(row) {
        var first = row.cells[1] ? row.cells[1].textContent.toLowerCase() : '';
        var last = row.cells[2] ? row.cells[2].textContent.toLowerCase() : '';
        var match = first.includes(q) || last.includes(q);
        row.style.display = match ? '' : 'none';
        if (match) visible++;
    });
    document.getElementById('no-results').style.display = visible === 0 && q !== '' ? 'block' : 'none';
}

import { initFuzzySearch } from '../fuzzy-search.js'


document.addEventListener('DOMContentLoaded', () => {
    initFuzzySearch({
        inputId:       'search-account',
        suggestionsId: 'search-suggestions',
        tbodyId:       'accounts-tbody',
        keys:           ['firstname', 'lastname'],
    })
})
