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
