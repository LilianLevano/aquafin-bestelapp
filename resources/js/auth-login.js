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

const showPassWordButton = document.getElementById('show-password')
let pathSvg = document.getElementById('path-svg')
const passwordInput = document.getElementById('password')
showPassWordButton.addEventListener('click', ()=>{



    if(passwordInput.type === "password"){
        passwordInput.type = "text"
        pathSvg.setAttribute('d', 'm634-422-48.67-48.67q20.34-63-27-108-47.33-45-107.66-26.66L402-654q17-10 36.83-14.67 19.84-4.66 41.17-4.66 72.33 0 122.83 50.5T653.33-500q0 21.33-5 41.5T634-422Zm128.67 128-46-45.33Q762-373 796.17-414.17q34.16-41.16 52.5-85.83-50-107.67-147.84-170.5-97.83-62.83-214.16-62.83-37.67 0-76.34 6.66Q371.67-720 346-710l-51.33-52q37-16.33 87.66-27.17Q433-800 483.33-800q145.67 0 264 82.17Q865.67-635.67 920-500q-25 62.33-64.83 114.5-39.84 52.17-92.5 91.5ZM808-61.33 640-226.67q-35 13-76.17 19.84Q522.67-200 480-200q-147.67 0-266.33-82.17Q95-364.33 40-500q20.33-52.33 54.67-100.5 34.33-48.17 82-90.17L56-812l46.67-47.33 750 750-44.67 48ZM222.67-644q-34.34 26.67-65.34 66.33-31 39.67-46.66 77.67 50.66 107.67 150.16 170.5t224.5 62.83q28.67 0 56.34-3.5 27.66-3.5 45-9.83L532-335.33q-11 4.33-25 6.5-14 2.16-27 2.16-71.67 0-122.5-50.16Q306.67-427 306.67-500q0-13.67 2.16-27 2.17-13.33 6.5-25l-92.66-92Zm309.66 125.67Zm-127.66 63.66Z')
    }else{
        passwordInput.type = "password"
        pathSvg.setAttribute('d', "M602.83-377.17q50.5-50.5 50.5-122.83t-50.5-122.83q-50.5-50.5-122.83-50.5t-122.83 50.5q-50.5 50.5-50.5 122.83t50.5 122.83q50.5 50.5 122.83 50.5t122.83-50.5ZM401.5-421.5q-32.17-32.17-32.17-78.5t32.17-78.5q32.17-32.17 78.5-32.17t78.5 32.17q32.17 32.17 32.17 78.5t-32.17 78.5q-32.17 32.17-78.5 32.17t-78.5-32.17Zm-186.17 139Q96.67-365 40-500q56.67-135 175.33-217.5Q334-800 480-800t264.67 82.5Q863.33-635 920-500q-56.67 135-175.33 217.5Q626-200 480-200t-264.67-82.5ZM480-500Zm217.5 169.83q99.17-63.5 151.17-169.83-52-106.33-151.17-169.83-99.17-63.5-217.5-63.5t-217.5 63.5Q163.33-606.33 110.67-500q52.66 106.33 151.83 169.83 99.17 63.5 217.5 63.5t217.5-63.5Z")
    }


})

passwordInput.addEventListener('click', () =>{
    const textError = document.getElementById('check-input-password')

    passwordInput.addEventListener('blur', () => {
        if (passwordInput.value.trim() === '') {
            passwordInput.style.borderColor = 'red';
            textError.style.display = 'block';
        } else {
            passwordInput.style.borderColor = 'black';
            textError.style.display = 'none';
        }
    });

})

const emailInput = document.getElementById('email')

emailInput.addEventListener('click', () =>{
    const textError = document.getElementById('check-input-email')

    emailInput.addEventListener('blur', () => {
        if (emailInput.value.trim() === '') {
            emailInput.style.borderColor = 'red';
            textError.style.display = 'block';
        } else {
            emailInput.style.borderColor = 'black';
            textError.style.display = 'none';
        }
    });

})

