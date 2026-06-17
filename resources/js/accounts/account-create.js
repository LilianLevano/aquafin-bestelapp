window.togglePw = function(inputId, btn) {
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
function setValidity(input, valid, message) {
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

document.getElementById('email').addEventListener('blur', function() {
    var ok = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.value.trim());
    setValidity(this, ok, 'Ongeldige email address.');
});

document.getElementById('first_name').addEventListener('blur', function() {
    var ok = /^[A-Za-zÀ-ÿ\s\-']{2,}$/.test(this.value.trim());
    setValidity(this, ok, 'Min. 2 tekens, enkel letters.');
});

document.getElementById('last_name').addEventListener('blur', function() {
    var ok = /^[A-Za-zÀ-ÿ\s\-']{2,}$/.test(this.value.trim());
    setValidity(this, ok, 'Min. 2 tekens, enkel letters.');
});

document.getElementById('phone_number').addEventListener('blur', function() {
    var ok = /^(\+32|0)[0-9]{8,9}$/.test(this.value.trim());
    setValidity(this, ok, 'Ongeldig telefoonnummer. (Verwachte formaat: (+32|0)xxxxxxxxx (Belgisch telefoonnummer)) ');
});

document.getElementById('password').addEventListener('blur', function() {
    var v = this.value;
    var ok = v.length >= 8;
    setValidity(this, ok, 'Min. 8 tekens');
});

document.getElementById('password_confirmation').addEventListener('blur', function() {
    var ok = this.value === document.getElementById('password').value;
    setValidity(this, ok, 'Wachtwoorden komen niet overeen.');
});

document.getElementById('create-form').addEventListener('submit', function(e) {
    var valid = true;
    this.querySelectorAll('[required]').forEach(function(input) {
        if (!input.value.trim()) {
            input.classList.add('is-invalid');
            valid = false;
        }
    });
    var pw = document.getElementById('password').value;ç
    var pwc = document.getElementById('password_confirmation').value;
    if (pw !== pwc) {
        document.getElementById('password_confirmation').classList.add('is-invalid');
        valid = false;
    }
    if (!valid) { e.preventDefault(); return; }
    var btn = document.getElementById('submit-btn');
    btn.disabled = true;
    btn.dataset.original = btn.textContent;
    btn.textContent = btn.textContent + '…';
});

window.addEventListener('pageshow', function(e) {
    if (e.persisted) {
        var btn = document.getElementById('submit-btn');
        btn.disabled = false;
        if (btn.dataset.original) btn.textContent = btn.dataset.original;
    }
});
