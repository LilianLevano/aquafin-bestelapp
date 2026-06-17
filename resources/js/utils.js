import {initFuzzySearch} from "./fuzzy-search.js";

/**
 * Function to check on empty input onblur.
 * Must use an error message in HTML Page with none display as default.
 *
 *
 * @param inputId
 * @param errorId
 */
export function validateOnBlur(inputId, errorId) {
    const input = document.getElementById(inputId);
    const error = document.getElementById(errorId);

    if (!input || !error) return;

    input.addEventListener('blur', () => {
        if (input.value.trim() === '') {
            input.style.borderColor = 'red';
            error.style.display = 'block';
        } else {
            input.style.borderColor = 'black';
            error.style.display = 'none';
        }
    });
}

/**
 * Check on max length when typing in an input.
 * Requires an error message with none display as default in the HTML page.
 *
 * @param inputId
 * @param errorId
 * @param maxLength
 */
export function checkMaxLength(inputId, errorId, maxLength) {
    const input = document.getElementById(inputId);
    const message = document.getElementById(errorId);

    if (!input || !message) return;

    input.addEventListener('input', () => {
        message.style.display = input.value.length >= maxLength ? 'block' : 'none';
    });
}

/**
 * Check on min. length when input is onblur.
 * Requires an error message with none display as default in the HTML page.
 *
 * @param inputId
 * @param errorId
 * @param minLength
 */
export function checkMinLength(inputId, errorId, minLength) {
    const input = document.getElementById(inputId);
    const message = document.getElementById(errorId);

    if (!input || !message) return;

    input.addEventListener('blur', () => {
        message.style.display = input.value.length < minLength ? 'block' : 'none';
    });
}

/**
 * Check an email input.
 * Checks on format (<x>@<y>.<z>)
 * Requires an error message with none display as default in the HTML page.
 *
 * @param inputId
 * @param errorId
 */
export function checkEmailFormat(inputId, errorId) {
    const input = document.getElementById(inputId);
    const message = document.getElementById(errorId);

    if (!input || !message) return;

    input.addEventListener('blur', () => {
        const valid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(input.value);
        message.style.display = valid ? 'none' : 'block';
    });
}

/**
 * Checks on changes when typing in an input.
 *
 * @param input
 */
export function checkChanged(input) {
    if (input.value !== input.dataset.original) {
        input.style.borderColor = '#f59e0b'; // orange
        input.style.backgroundColor = '#fffbeb';
    } else {
        input.style.borderColor = '';
        input.style.backgroundColor = '';
    }
}

/**
 * Function to apply the "checkChanged" function to all inputs on a page.
 * The inputs must have a "data-original" attribute in order to be picked up.
 *
 * @param checkChanged Uses the function checkChanged in js/utils.js
 */
export function addCheckChange(checkChanged){
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('[data-original]').forEach(input => {
            input.addEventListener('input', () => checkChanged(input));
        });
    });
}

/**
 * Function to add an error field under the chosen field with custom message.
 *
 *
 * @param input
 * @param valid
 * @param message
 */
export function setValidity(input, valid, message) {
    var field = input.closest('.field');
    var existing = field.querySelector('.error-js');
    if (valid) {
        input.classList.remove('is-invalid');
        if (existing) existing.remove();
    } else {
        input.classList.add('is-invalid');
        if (!existing) {
            var p = document.createElement('p');
            p.className = 'error error-js';
            p.textContent = message;
            field.appendChild(p);
        }
    }
}

/**
 * Function to check the format of an email input onblur.
 * Uses setValidity function.
 * @param inputId
 * @param setValidity
 */
export function checkEmail(inputId, setValidity){
    document.getElementById(inputId).addEventListener('blur', function() {
        var ok = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.value.trim());
        setValidity(this, ok, 'Invalid email address.');
    });

}

/**
 * Function to check the format of a phone number input onblur.
 * Uses setValidity function.
 * @param inputId
 * @param setValidity
 */
export function checkPhoneNumber(inputId, setValidity){
    document.getElementById(inputId).addEventListener('blur', function() {
        var ok = /^(\+32|0)[0-9]{8,9}$/.test(this.value.trim());
        setValidity(this, ok, 'Ongeldig telefoonnummer.');
    });
}

/**
 * Function to check the format of a simple text input onblur.
 * It checks if the input is not empty.
 * Uses setValidity function.
 * @param inputId
 * @param setValidity
 */
export function checkName(inputId, setValidity){
    document.getElementById(inputId).addEventListener('blur', function() {
        var ok = /^[A-Za-zÀ-ÿ\s\-']{2,}$/.test(this.value.trim());
        setValidity(this, ok, 'Min. 2 characters, letters only.');
    });
}

/**
 * Function to check the format of a password input onblur.
 * Uses setValidity function.
 * @param inputId
 * @param setValidity
 */
export function checkPassword(inputId, setValidity){
    document.getElementById(inputId).addEventListener('blur', function() {
        if (!this.value) return;
        var v = this.value;
        var ok = v.length >= 8 && /[A-Z]/.test(v) && /[a-z]/.test(v) && /[0-9]/.test(v);
        setValidity(this, ok, 'Min. 8 characters, 1 uppercase, 1 lowercase, 1 number.');
    });
}

/**
 * Function to check if the confirmation password input matches the password input onblur.
 * Uses setValidity function.
 * @param inputId
 * @param inputToMatchId
 * @param setValidity
 */
export function checkPasswordMatch(inputId, inputToMatchId, setValidity){
    document.getElementById(inputId).addEventListener('blur', function() {
        var pw = document.getElementById(inputToMatchId).value;
        if (!pw) return;
        setValidity(this, this.value === pw, 'Passwords do not match.');
    });
}

/**
 * Function to hide or show the value in a password input.
 * Onclick, it changes the type of the input.
 * It changes the SVG icon to match the type.
 * @param inputId
 * @param btn
 */
export function togglePassword(inputId, btn){
    const input = document.getElementById(inputId);
    const label = btn.querySelector('span');
    const icon = btn.querySelector('svg');

    if (input.type === 'password') {
        input.type = 'text';
        label.textContent = 'Hide';
        icon.innerHTML = `
            <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/>
            <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/>
            <line x1="1" y1="1" x2="23" y2="23"/>
        `;
    } else {
        input.type = 'password';
        label.textContent = 'Show';
        icon.innerHTML = `
            <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
            <circle cx="12" cy="12" r="3"/>
        `;
    }
}

/**
 * Function to easily access the initiation of the "initFuzzySearch" function to create a fuzzy search bar.
 *
 * @param inputId
 * @param suggestionsId
 * @param tbodyId
 * @param keys
 * @param containerId
 * @param itemSelector
 */
export function addFuzzySearch(inputId, suggestionsId, tbodyId, keys, containerId = null, itemSelector = null){
    document.addEventListener('DOMContentLoaded', () => {
        initFuzzySearch({
            inputId:       inputId,
            suggestionsId: suggestionsId,
            containerId:   containerId,
            itemSelector:  itemSelector,
            tbodyId:       tbodyId,
            keys:          keys,
        })
    })
}
