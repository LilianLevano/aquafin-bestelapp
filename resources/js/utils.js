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
