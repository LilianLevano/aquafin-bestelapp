import { initFuzzySearch } from '../fuzzy-search.js'

document.addEventListener('DOMContentLoaded', () => {
    initFuzzySearch({
        inputId:       'search-requests',
        suggestionsId: 'search-suggestions',
        containerId:   'aanvragen-list',
        itemSelector:  '.aanvraag-card',
        keys:          ['title'],
    })
})
