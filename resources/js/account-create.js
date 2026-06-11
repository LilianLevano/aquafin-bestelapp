function togglePw(id, btn) {
    var input = document.getElementById(id);
    input.type = input.type === 'password' ? 'text' : 'password';
    btn.textContent = input.type === 'password' ? 'Show' : 'Hide';
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

function showToast(message, type) {
    var el = document.createElement('div');
    el.className = 'alert ' + (type === 'success' ? 'alert-success' : 'alert-error');
    el.textContent = message;
    var card = document.querySelector('.card');
    card.insertBefore(el, card.firstChild);
    setTimeout(function () { el.remove(); }, 4000);
}

document.getElementById('email').addEventListener('blur', function () {
    var ok = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.value.trim());
    setValidity(this, ok, 'Ongeldig emailadres.');
});

document.getElementById('first_name').addEventListener('blur', function () {
    var ok = /^[A-Za-zÀ-ÿ\s\-']{2,}$/.test(this.value.trim());
    setValidity(this, ok, 'Min. 2 tekens, alleen letters.');
});

document.getElementById('last_name').addEventListener('blur', function () {
    var ok = /^[A-Za-zÀ-ÿ\s\-']{2,}$/.test(this.value.trim());
    setValidity(this, ok, 'Min. 2 tekens, alleen letters.');
});

document.getElementById('password').addEventListener('blur', function () {
    var v = this.value;
    var ok = v.length >= 8 && /[A-Z]/.test(v) && /[a-z]/.test(v) && /[0-9]/.test(v);
    setValidity(this, ok, 'Min. 8 tekens, 1 hoofdletter, 1 kleine letter, 1 cijfer.');
});

document.getElementById('password_confirmation').addEventListener('blur', function () {
    var ok = this.value === document.getElementById('password').value;
    setValidity(this, ok, 'Wachtwoorden komen niet overeen.');
});

document.getElementById('create-form').addEventListener('submit', function (e) {
    e.preventDefault();

    var valid = true;
    this.querySelectorAll('[required]').forEach(function (input) {
        if (!input.value.trim()) {
            input.classList.add('is-invalid');
            valid = false;
        }
    });
    var pw = document.getElementById('password').value;
    var pwc = document.getElementById('password_confirmation').value;
    if (pw !== pwc) {
        document.getElementById('password_confirmation').classList.add('is-invalid');
        valid = false;
    }
    if (!valid) return;

    var btn = document.getElementById('submit-btn');
    var form = this;
    var originalText = btn.textContent;
    btn.disabled = true;
    btn.textContent = originalText + '…';

    fetch(form.action, {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: new FormData(form),
    })
        .then(function (response) {
            return response.json().then(function (json) {
                return { status: response.status, body: json };
            });
        })
        .then(function (result) {
            btn.disabled = false;
            btn.textContent = originalText;

            if (result.status === 200 || result.status === 201) {
                showToast('Gebruiker aangemaakt.', 'success');
                form.reset();
            } else if (result.status === 422) {
                var errors = result.body.errors || {};
                Object.keys(errors).forEach(function (field) {
                    var input = document.getElementById(field);
                    if (input) setValidity(input, false, errors[field][0]);
                });
            } else {
                showToast('Er ging iets mis met het aanmaken...', 'error');
            }
        })
        .catch(function () {
            btn.disabled = false;
            btn.textContent = originalText;
            showToast('Er ging iets mis met het versturen...', 'error');
        });
});
