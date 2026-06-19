import {
    checkMinLength,
    checkChanged,
    addCheckChange,
    addFuzzySearch
} from '../utilities.js';

checkMinLength('name','check-input-name', 2 )
addCheckChange(checkChanged);
addFuzzySearch('search-categories', 'search-suggestions', 'categories-tbody', ['name'])
