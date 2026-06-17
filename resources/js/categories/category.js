import {checkMaxLength, checkMinLength, checkEmailFormat, validateOnBlur} from '../utils.js';

checkMinLength('name','check-input-name', 2 )

import { initFuzzySearch } from '../fuzzy-search.js'


document.addEventListener('DOMContentLoaded', () => {
    initFuzzySearch({
        inputId:       'search-categories',
        suggestionsId: 'search-suggestions',
        tbodyId:       'categories-tbody',
        keys:           ['name'],
    })
})

function checkChanged(input) {
    if (input.value !== input.dataset.original) {
        input.style.borderColor = '#f59e0b'; // orange
        input.style.backgroundColor = '#fffbeb';
    } else {
        input.style.borderColor = '';
        input.style.backgroundColor = '';
    }
}

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-original]').forEach(input => {
        input.addEventListener('input', () => checkChanged(input));
    });
});
