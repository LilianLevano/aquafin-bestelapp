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

document.getElementById('name').addEventListener('blur', function() {
    var ok = this.value.trim().length >= 2;
    setValidity(this, ok, 'Role name must be at least 2 characters.');
});

document.getElementById('value').addEventListener('blur', function() {
    if (!this.value.trim()) return;
    var ok = /^[a-z0-9_\-]{2,}$/.test(this.value.trim());
    setValidity(this, ok, 'Role value must be lowercase letters, numbers, - or _ only.');
});

document.getElementById('create-form').addEventListener('submit', function(e) {
    var valid = true;
    this.querySelectorAll('[required]').forEach(function(input) {
        if (!input.value.trim()) {
            input.classList.add('is-invalid');
            valid = false;
        }
    });
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
