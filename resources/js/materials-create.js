// Taak 1 : Validatie naam (onblur)
function validateNaam() {
    const naamInput = document.getElementById('materiaalNaam');
    const errorElement = document.getElementById('naamError');

    if (naamInput.value.length < 3) {
        naamInput.style.border = '2px solid red';
        errorElement.style.display = 'block';
        return false;
    } else {
        naamInput.style.border = '';
        errorElement.style.display = 'none';
        return true;
    }
}

//  Taak 2 : Validatie categorie (onblur)
function validateCategorie() {
    const categorieInput = document.getElementById('materiaalCategorie');
    const errorElement = document.getElementById('categorieError');

    if (categorieInput.value === '') {
        categorieInput.style.border = '2px solid red';
        errorElement.style.display = 'block';
        return false;
    } else {
        categorieInput.style.border = '';
        errorElement.style.display = 'none';
        return true;
    }
}

//  Taak 3 : Formulier uitschakelen + daarna her inschakelen
function disableForm(event) {
    event.preventDefault();

    const form = event.target;
    const button = document.getElementById('submitBtn');

    // Valideer eerst alles
    const naamOk = validateNaam();
    const categorieOk = validateCategorie();
    if (!naamOk || !categorieOk) return;

    // Uitschakelen tijdens verzenden
    button.disabled = true;
    button.innerText = 'Bezig...';
    form.querySelectorAll('input, select').forEach(field => field.disabled = true);

    // Simulatie van verzenden (vervang dit door je echte fetch/axios call)
    setTimeout(function() {
        //  Taak 3 : Her inschakelen na afloop (succes of fout)
        button.disabled = false;
        button.innerText = 'Aanmaken';
        form.querySelectorAll('input, select').forEach(field => field.disabled = false);

        alert('Materiaal succesvol aangemaakt!'); // vervang door jouw logica
    }, 2000);
}
