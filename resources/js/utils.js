import {initFuzzySearch} from "./fuzzy-search.js";

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

export function checkMaxLength(inputId, errorId, maxLength) {
    const input = document.getElementById(inputId);
    const message = document.getElementById(errorId);

    if (!input || !message) return;

    input.addEventListener('input', () => {
        message.style.display = input.value.length >= maxLength ? 'block' : 'none';
    });
}

export function checkMinLength(inputId, errorId, minLength) {
    const input = document.getElementById(inputId);
    const message = document.getElementById(errorId);

    if (!input || !message) return;

    input.addEventListener('blur', () => {
        message.style.display = input.value.length < minLength ? 'block' : 'none';
    });
}

export function checkEmailFormat(inputId, errorId) {
    const input = document.getElementById(inputId);
    const message = document.getElementById(errorId);

    if (!input || !message) return;

    input.addEventListener('blur', () => {
        const valid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(input.value);
        message.style.display = valid ? 'none' : 'block';
    });
}

export function checkChanged(input) {
    if (input.value !== input.dataset.original) {
        input.style.borderColor = '#f59e0b'; // orange
        input.style.backgroundColor = '#fffbeb';
    } else {
        input.style.borderColor = '';
        input.style.backgroundColor = '';
    }
}

export function addCheckChange(checkChanged){
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('[data-original]').forEach(input => {
            input.addEventListener('input', () => checkChanged(input));
        });
    });
}

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

export function checkEmail(inputId, setValidity){
    document.getElementById(inputId).addEventListener('blur', function() {
        var ok = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.value.trim());
        setValidity(this, ok, 'Invalid email address.');
    });

}

export function checkPhoneNumber(inputId, setValidity){
    document.getElementById(inputId).addEventListener('blur', function() {
        var ok = /^(\+32|0)[0-9]{8,9}$/.test(this.value.trim());
        setValidity(this, ok, 'Ongeldig telefoonnummer.');
    });
}

export function checkName(inputId, setValidity){
    document.getElementById(inputId).addEventListener('blur', function() {
        var ok = /^[A-Za-zÀ-ÿ\s\-']{2,}$/.test(this.value.trim());
        setValidity(this, ok, 'Min. 2 characters, letters only.');
    });
}

export function checkPassword(inputId, setValidity){
    document.getElementById(inputId).addEventListener('blur', function() {
        if (!this.value) return;
        var v = this.value;
        var ok = v.length >= 8 && /[A-Z]/.test(v) && /[a-z]/.test(v) && /[0-9]/.test(v);
        setValidity(this, ok, 'Min. 8 characters, 1 uppercase, 1 lowercase, 1 number.');
    });
}

export function checkPasswordMatch(inputId, inputToMatchId, setValidity){
    document.getElementById(inputId).addEventListener('blur', function() {
        var pw = document.getElementById(inputToMatchId).value;
        if (!pw) return;
        setValidity(this, this.value === pw, 'Passwords do not match.');
    });
}

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
