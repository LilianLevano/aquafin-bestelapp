window.toggleHulp = function(show) {
    document.getElementById('section-login').style.display = show ? 'none' : 'block';
    document.getElementById('section-hulp').style.display = show ? 'block' : 'none';
};

function isValidEmail(value) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
}

function isValidName(value) {
    return value.trim().length >= 2 && /^[A-Za-zÀ-öø-ÿ\s\-']+$/.test(value.trim());
}

function validateField(input) {
    var val = input.value.trim();
    var empty = val === '';
    var invalid = false;

    if (!empty) {
        if (input.type === 'email') invalid = !isValidEmail(val);
        else if (input.name === 'first_name' || input.name === 'last_name') invalid = !isValidName(val);
    }

    if (empty || invalid) {
        input.classList.add('is-invalid');
        return false;
    }
    input.classList.remove('is-invalid');
    return true;
}

function showToast(message, type) {
    var toast = document.getElementById('toast');
    if (!toast) return;
    toast.textContent = message;
    toast.className = 'toast toast-' + type + ' toast-show';
    clearTimeout(toast._timer);
    toast._timer = setTimeout(function () {
        toast.classList.remove('toast-show');
    }, 4000);
}

// LOGIN FORM — traditionele POST, client-side validatie + disable tijdens verzenden
var loginForm = document.querySelector('#section-login .form');
if (loginForm) {
    loginForm.querySelectorAll('input').forEach(function (input) {
        input.addEventListener('input', function () {
            if (this.value.trim() !== '') this.classList.remove('is-invalid');
        });
    });

    loginForm.addEventListener('submit', function (e) {
        var valid = true;
        this.querySelectorAll('input[required]').forEach(function (input) {
            if (!validateField(input)) valid = false;
        });
        if (!valid) { e.preventDefault(); return; }

        var btn = this.querySelector('button[type="submit"]');
        if (btn) {
            btn.disabled = true;
            btn.dataset.original = btn.textContent;
            btn.textContent = btn.textContent + '…';
        }
    });
}

// HULP FORM — fetch/JSON, client-side validatie, toast
var hulpForm = document.getElementById('form-hulp');
if (hulpForm) {
    hulpForm.querySelectorAll('input, textarea, select').forEach(function (el) {
        el.addEventListener('input', function () {
            if (this.value.trim() !== '') this.classList.remove('is-invalid');
        });
        el.addEventListener('change', function () {
            if (this.value.trim() !== '') this.classList.remove('is-invalid');
        });
    });

    hulpForm.addEventListener('submit', function (e) {
        e.preventDefault();

        var valid = true;
        hulpForm.querySelectorAll('input[required], textarea[required], select[required]').forEach(function (input) {
            if (!validateField(input)) valid = false;
        });
        if (!valid) return;

        var btn = hulpForm.querySelector('button[type="submit"]');
        var allFields = hulpForm.querySelectorAll('input, textarea, select');

        btn.disabled = true;
        btn.dataset.original = btn.textContent;
        btn.textContent = btn.textContent + '…';
        allFields.forEach(function (el) { el.disabled = true; });

        var formData = new FormData(hulpForm);

        fetch(hulpForm.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
            },
            body: formData,
        })
        .then(function (res) {
            return res.json().then(function (data) {
                return { ok: res.ok, data: data };
            });
        })
        .then(function (result) {
            if (result.ok && result.data.status === 'success') {
                showToast(result.data.message, 'success');
                hulpForm.reset();
                setTimeout(function () { toggleHulp(false); }, 1800);
            } else {
                showToast(result.data.message || 'Er ging iets mis met het verzoeken voor hulp...', 'error');
            }
        })
        .catch(function () {
            showToast('Er ging iets mis met het verzoeken voor hulp...', 'error');
        })
        .finally(function () {
            btn.disabled = false;
            btn.textContent = btn.dataset.original;
            allFields.forEach(function (el) { el.disabled = false; });
        });
    });
}

// Her inschakelen bij browser back-knop
window.addEventListener('pageshow', function (e) {
    if (e.persisted) {
        document.querySelectorAll('button[type="submit"]').forEach(function (btn) {
            btn.disabled = false;
            if (btn.dataset.original) btn.textContent = btn.dataset.original;
        });
        document.querySelectorAll('input, textarea, select').forEach(function (el) {
            el.disabled = false;
        });
    }
});
