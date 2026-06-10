function toggleHulp(show) {
    document.getElementById('section-login').style.display = show ? 'none' : 'block';
    document.getElementById('section-hulp').style.display = show ? 'block' : 'none';
}

document.querySelectorAll('.form').forEach(function(form) {
    form.addEventListener('submit', function() {
        var btn = this.querySelector('button[type="submit"]');
        if (btn) {
            btn.disabled = true;
            btn.dataset.original = btn.textContent;
            btn.textContent = btn.textContent + '…';
        }
    });
});

window.addEventListener('pageshow', function(e) {
    if (e.persisted) {
        document.querySelectorAll('button[type="submit"]').forEach(function(btn) {
            btn.disabled = false;
            if (btn.dataset.original) btn.textContent = btn.dataset.original;
        });
    }
});
