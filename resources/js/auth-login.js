import { findIn } from "./utilities.js";

// Get the localized placeholder template
const placeholder = findIn(document, "input[name='_generic-placeholder']");
const placeholderText = placeholder ? placeholder.value : "Het ___ veld moet gevuld worden.";

setupEventListeners(document.querySelector("main"));

/**
 * Handles all event bindings for login and help-request sections.
 *
 * @param {HTMLElement} main
 */
function setupEventListeners(main) {
    const loginSection = findIn(main, "#section-login");
    const helpRequestSection = findIn(main, "#section-help-request");

    if (!loginSection || !helpRequestSection) return;

    // Login section elements
    const toggleHelpRequestOn = findIn(loginSection, "#toggle-help-request-on");
    const buttonShowPassword = findIn(loginSection, "#show-password");
    const pathSvg = findIn(loginSection, "#show-password #path-svg");
    const inputPassword = findIn(loginSection, "#password");

    // Help section elements
    const toggleHelpRequestOff = findIn(helpRequestSection, "#toggle-help-request-off");

    [loginSection, helpRequestSection].forEach(section => {
        section.addEventListener("click", event => {
            const target = event.target;

            // Toggle visibility of sections
            if ([toggleHelpRequestOn, toggleHelpRequestOff].includes(target)) {
                loginSection.classList.toggle("visually-hidden");
                helpRequestSection.classList.toggle("visually-hidden");
            }

            // Password show/hide
            if (target === buttonShowPassword) {
                if (!inputPassword || !pathSvg) return;
                if (inputPassword.type === "password") {
                    inputPassword.type = "text";
                    // Use visible SVG path
                    pathSvg.setAttribute(
                        "d",
                        "M602.83-377.17q50.5-50.5 50.5-122.83t-50.5-122.83q-50.5-50.5-122.83-50.5t-122.83 50.5q-50.5 50.5-50.5 122.83t50.5 122.83q50.5 50.5 122.83 50.5t122.83-50.5ZM401.5-421.5q-32.17-32.17-32.17-78.5t32.17-78.5q32.17-32.17 78.5-32.17t78.5 32.17q32.17 32.17 32.17 78.5t-32.17 78.5q-32.17 32.17-78.5 32.17t-78.5-32.17Zm-186.17 139Q96.67-365 40-500q56.67-135 175.33-217.5Q334-800 480-800t264.67 82.5Q863.33-635 920-500q-56.67 135-175.33 217.5Q626-200 480-200t-264.67-82.5ZM480-500Zm217.5 169.83q99.17-63.5 151.17-169.83-52-106.33-151.17-169.83-99.17-63.5-217.5-63.5t-217.5 63.5Q163.33-606.33 110.67-500q52.66 106.33 151.83 169.83 99.17 63.5 217.5 63.5t217.5-63.5Z"
                    );
                } else {
                    inputPassword.type = "password";
                    // Use hidden SVG path
                    pathSvg.setAttribute(
                        "d",
                        "m634-422-48.67-48.67q20.34-63-27-108-47.33-45-107.66-26.66L402-654q17-10 36.83-14.67 19.84-4.66 41.17-4.66 72.33 0 122.83 50.5T653.33-500q0 21.33-5 41.5T634-422Zm128.67 128-46-45.33Q762-373 796.17-414.17q34.16-41.16 52.5-85.83-50-107.67-147.84-170.5-97.83-62.83-214.16-62.83-37.67 0-76.34 6.66Q371.67-720 346-710l-51.33-52q37-16.33 87.66-27.17Q433-800 483.33-800q145.67 0 264 82.17Q865.67-635.67 920-500q-25 62.33-64.83 114.5-39.84 52.17-92.5 91.5ZM808-61.33 640-226.67q-35 13-76.17 19.84Q522.67-200 480-200q-147.67 0-266.33-82.17Q95-364.33 40-500q20.33-52.33 54.67-100.5 34.33-48.17 82-90.17L56-812l46.67-47.33 750 750-44.67 48ZM222.67-644q-34.34 26.67-65.34 66.33-31 39.67-46.66 77.67 50.66 107.67 150.16 170.5t224.5 62.83q28.67 0 56.34-3.5 27.66-3.5 45-9.83L532-335.33q-11 4.33-25 6.5-14 2.16-27 2.16-71.67 0-122.5-50.16Q306.67-427 306.67-500q0-13.67 2.16-27 2.17-13.33 6.5-25l-92.66-92Zm309.66 125.67Zm-127.66 63.66Z"
                    );
                }
            }
        });

        // Bubble validation for required fields on focus out (add error)
        section.addEventListener("focusout", event => {
            const input = event.target;
            if (
                input instanceof HTMLInputElement &&
                input.hasAttribute("required")
            ) {
                handleRequiredBlur(input);
            }
        });

        // When focusing into a field, always reset error display
        section.addEventListener("focusin", event => {
            const input = event.target;
            if (
                input instanceof HTMLInputElement &&
                input.hasAttribute("required")
            ) {
                resetRequiredError(input);
            }
        });
    });
}

/**
 * Sets error styling & dynamic placeholder for required but empty fields on blur.
 *
 * @param {HTMLInputElement} input
 */
function handleRequiredBlur(input) {
    if (input.value.trim() === "") {
        input.classList.add("error");
        input.setAttribute(
            "placeholder",
            placeholderText.replace("___", input.dataset.translation ?? input.getAttribute("name"))
        );
    } else {
        input.classList.remove("error");
        input.setAttribute("placeholder", "");
    }
}

/**
 * Clears error styling and placeholder on required field focus.
 *
 * @param {HTMLInputElement} input
 */
function resetRequiredError(input) {
    input.classList.remove("error");
    input.setAttribute("placeholder", "");
}
