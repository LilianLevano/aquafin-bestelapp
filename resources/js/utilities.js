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
 * Retrieves data from a given API endpoint with local caching.
 * If valid cached data (identified by `key` and `duration`) is present, returns it immediately.
 * Otherwise, performs a GET request to the provided `url`, processes the JSON response, validates the response's success, caches the result, and then returns the data.
 * If the API returns an error, throws with a detailed message and error information.
 *
 * @async
 * @param {string} key - The unique key for storing/retrieving cached data in localStorage.
 * @param {number} duration - Cache validity duration in milliseconds.
 * @param {string} url - The API endpoint to fetch data from.
 * @returns {Promise<any>} The API response data (from cache or server).
 * @throws When the request fails or the API responds with an error.
 */
export async function fetchWithCache(key, duration, url) {
    const cached = await loadFromCache(key, duration);

    if (cached) {
        return cached;
    }

    try {
        const response = await fetch(url, {
            headers: { 'Accept': 'application/json' },
            credentials: 'same-origin'
        });
        const json = await response.json();
        console.log(json.message);

        if (!response.ok || !json.success) {
            throw new Error(`${json.message}: ${Array.isArray(json.errors) ? json.errors.join(', ') : json.errors?.toString()}`);
        }

        saveToCache(key, json.data);
        return json.data;
    } catch (error) {
        throw error;
    }
}

/**
 * Fetches data from a given API endpoint, bypassing cache entirely.
 * Performs a GET request to the provided `url`, processes the JSON response, validates success, caches the result, and returns the data.
 * If the API returns an error, throws with a detailed message.
 *
 * @async
 * @param {string} key - The unique key for storing/retrieving cached data in localStorage.
 * @param {string} url - The API endpoint to fetch data from.
 * @returns {Promise<any>} The API response data from the server.
 * @throws When the request fails or the API responds with an error.
 */
export async function fetchWithoutCache(key, url) {
    try {
        const response = await fetch(url, {
            headers: { 'Accept': 'application/json' },
            credentials: 'same-origin'
        });
        const json = await response.json();
        console.log(json.message);

        if (!response.ok || !json.success) {
            throw new Error(`${json.message}: ${Array.isArray(json.errors) ? json.errors.join(', ') : json.errors?.toString()}`);
        }

        saveToCache(key, json.data);
        return json.data;
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

/**
 * Adds a blur event to the provided input that validates non-empty input value.
 * Shows or hides error message accordingly.
 *
 * @param {string} inputId - The input field's id.
 * @param {string} errorId - Element id displaying the error message.
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
 * Adds input event to check for maximum length. Displays error if max length reached or exceeded.
 *
 * @param {string} inputId - Input field's id.
 * @param {string} errorId - Element id to show/hide error message.
 * @param {number} maxLength - Maximum allowed input length.
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
 * Adds blur event to check for a minimum input length.
 * Shows error message if length is under the minimum.
 *
 * @param {string} inputId - Input field id.
 * @param {string} errorId - Error message element id.
 * @param {number} minLength - Required minimum length.
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
 * Adds a blur event to validate email format. Shows/hides error on invalid/valid format.
 *
 * @param {string} inputId - Input field id.
 * @param {string} errorId - Error message element id.
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
