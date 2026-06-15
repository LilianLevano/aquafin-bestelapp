import {initFuzzySearch} from "../fuzzy-search.js";

document.addEventListener('DOMContentLoaded', () => {
    initFuzzySearch({
        inputId:       'search-materials',
        suggestionsId: 'search-suggestions',
        tbodyId:       'materials-tbody',
        keys:           ['name'],
    })
})
