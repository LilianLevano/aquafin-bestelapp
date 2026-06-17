/**
 * Helper to safely fetch a single element within a parent.
 * Returns the found element, or null if not found or parent is falsy.
 *
 * @param {ParentNode | null | undefined} parent - The container to search within.
 * @param {string} selector - The CSS selector to match.
 * @returns {Element | null}
 */
export function findIn(parent, selector) {
    if (!parent || typeof parent.querySelector !== "function") return null;
    return parent.querySelector(selector);
}

/**
 * Helper to fetch all elements matching a selector within a parent.
 * Returns a NodeList if found, or empty array if parent is falsy.
 *
 * @param {ParentNode | null | undefined} parent - The container to search within.
 * @param {string} selector - The CSS selector to match.
 * @returns {NodeListOf<Element> | []}
 */
export function findAllIn(parent, selector) {
    if (!parent || typeof parent.querySelectorAll !== "function") return [];
    return parent.querySelectorAll(selector);
}

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
