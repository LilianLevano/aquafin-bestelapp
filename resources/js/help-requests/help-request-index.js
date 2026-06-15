function filterStatus(status, btn) {
    document.querySelectorAll('.status-tabs .tab').forEach(function(t) {
        t.classList.remove('active');
    });

    btn.classList.add('active');

    var cards = document.querySelectorAll('.aanvraag-card');
    var visible = 0;

    cards.forEach(function(card) {
        if (status === 'alle' || card.dataset.status === status) {
            card.style.display = 'block';
            visible++;
        } else {
            card.style.display = 'none';
        }
    });

    document.getElementById('no-results').style.display = visible === 0 ? 'block' : 'none';
}
