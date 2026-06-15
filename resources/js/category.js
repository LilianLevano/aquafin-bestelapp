import {checkMaxLength, checkMinLength, checkEmailFormat, validateOnBlur} from './utils.js';

checkMinLength('name','check-input-name', 2 )

import { initFuzzySearch } from './fuzzy-search.js'


document.addEventListener('DOMContentLoaded', () => {
    initFuzzySearch({
        inputId:       'search-categories',
        suggestionsId: 'search-suggestions',
        tbodyId:       'categories-tbody',
        keys:           ['name'],
    })
})
