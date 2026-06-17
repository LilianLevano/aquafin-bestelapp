
let huidigId = null;

function bewerk(id, naam, categorie) {
    huidigId = id;
    document.getElementById('tabelContainer').style.display = 'none';
    document.getElementById('bewerkContainer').style.display = 'block';
    document.getElementById('bewerkNaam').value = naam;
    document.getElementById('bewerkCategorie').value = categorie;
}

function annuleer() {
    document.getElementById('tabelContainer').style.display = 'block';
    document.getElementById('bewerkContainer').style.display = 'none';
}

function opslaanBewerk() {
    alert('Materiaal ' + huidigId + ' bijgewerkt!');
    annuleer();
}

function herlaadt() {
    location.reload();
}

function filterTabel() {
    const zoekwoord = document.getElementById('zoekInput').value.toLowerCase();
    const rijen = document.querySelectorAll('#tabelBody tr');
    let zichtbaar = 0;

    rijen.forEach(function(rij) {
        const tekst = rij.innerText.toLowerCase();
        if (tekst.includes(zoekwoord)) {
            rij.style.display = '';
            zichtbaar++;
        } else {
            rij.style.display = 'none';
        }
    });

    document.getElementById('geenMaterial').style.display = zichtbaar === 0 ? 'block' : 'none';
}
