import {checkMinLength} from "./utils.js";

checkMinLength('name', 'name-error', 3)
checkMinLength('description', 'description-error', 5)

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
