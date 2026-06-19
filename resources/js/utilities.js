/**
 * Retrieves the first element matching a selector within a given parent node.
 * Returns the found element, or null if not found, or if parent is null/undefined.
 *
 * @param {ParentNode} parent - The parent node to search within.
 * @param {string} selector - CSS selector query.
 * @returns {Element | null} The found element or null.
 */
export function findIn(parent, selector) {
    if (!parent || typeof parent.querySelector !== "function") return null;
    return parent.querySelector(selector);
}

/**
 * Retrieves all elements matching a selector within a given parent node.
 * Returns a NodeList if found, or an empty array if parent is null/undefined.
 *
 * @param {ParentNode} parent - The parent node to search within.
 * @param {string} selector - CSS selector query.
 * @returns {NodeListOf<Element> | []} NodeList of elements, or empty array.
 */
export function findAllIn(parent, selector) {
    if (!parent || typeof parent.querySelectorAll !== "function") return [];
    return parent.querySelectorAll(selector);
}

/**
 * Handles AJAX POST submission for forms, prevents default submission,
 * sends form data via fetch, and gives user feedback based on server response.
 *
 * @param {Event} event - The form submit event.
 * @param {HTMLFormElement} form - The form being submitted.
 * @returns {Promise<void>}
 */
export async function postForm(event, form) {
    event.preventDefault();
    const body = new FormData(form);

    try {
        const response = await fetch(form.action, {
            method: form.method,
            headers: {
                'Accept': 'application/json'
            },
            body: body,
        });

        const data = await response.json();
        if (response.ok) {
            alert(data.message ?? 'Request submitted!');
            form.reset();
        } else {
            alert(data.message ?? 'Error occurred while submitting your request.');
        }
    } catch (error) {
        alert('Er is een fout opgetreden. Probeer later opnieuw.');
    }
}

/**
 * Sends a generic GET request to an API endpoint and returns the parsed JSON response.
 * Throws if the response is not OK, allowing caller to handle API errors.
 *
 * @param {string} url - The URL to make the GET request to.
 * @returns {Promise<any>} The response data if successful.
 * @throws {any} The response data if an error occurred.
 */
export async function getForm(url) {
    try {
        const response = await fetch(url, {
            headers: { 'Accept': 'application/json' },
            credentials: 'same-origin'
        });

        const data = await response.json();

        if (!response.ok) {
            throw data;
        }

        return data;
    } catch (error) {
        throw error;
    }
}

/**
 * Wraps an object in a Proxy to watch for property changes. Triggers `onChange`
 * callback when any property is modified to a new value.
 *
 * @param {Object} obj - The object to watch for changes.
 * @param {Function} onChange - Callback function called with (prop, value, oldValue).
 * @returns {Proxy} Proxy for the original object.
 */
export function createWatchedObject(obj, onChange) {
    return new Proxy(obj, {
        set(target, prop, value) {
            const oldValue = target[prop];
            target[prop] = value;

            if (oldValue !== value) {
                onChange(prop, value, oldValue);
            }
            return true;
        }
    });
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

/**
 * Saves data to browser localStorage as JSON under the given `key`.
 * Includes a timestamp to enable cache invalidation.
 *
 * @param {string} key - The localStorage key
 * @param {any} data - The data to cache
 */
export function saveToCache(key, data) {
    try {
        localStorage.setItem(
            key,
            JSON.stringify({
                timestamp: Date.now(),
                data
            })
        );
    } catch (e) {
        // Most common: storage quota exceeded or JSON stringify error
        // Consider logging or handling error as needed
        console.error("Failed to save to cache:", e);
    }
}

/**
 * Loads and returns cached data from localStorage if present and not stale.
 * Checks cache age against `duration` (in minutes).
 * If not found or stale, returns null.
 *
 * @param {string} key - The localStorage key
 * @param {number} duration - Maximum cache age in minutes
 * @returns {any | null} The cached data, or null if unavailable or expired
 */
export function loadFromCache(key, duration) {
    const raw = localStorage.getItem(key);
    if (!raw) return null;

    let payload;
    try {
        payload = JSON.parse(raw);
    } catch (e) {
        // Corrupted or non-JSON data, remove and return null
        localStorage.removeItem(key);
        return null;
    }

    if (
        typeof payload !== "object" ||
        typeof payload.timestamp !== "number" ||
        !("data" in payload)
    ) {
        // Invalid structure
        localStorage.removeItem(key);
        return null;
    }

    if (Date.now() - payload.timestamp > duration) {
        localStorage.removeItem(key);
        return null;
    }
    return payload.data;
}
