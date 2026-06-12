<x-layouts.site-layout>

    <div style="padding: 40px; text-align: left;">

        <h1>Besteklijst</h1>

        <!-- Knoppen -->
        <div style="margin-bottom: 20px; display: flex; gap: 10px;">
            <button onclick="herlaadt()" class="btn-primary">Herlaadt</button>
            <input type="text" id="zoekInput" placeholder="Zoek op naam of categorie..." 
                   onkeyup="filterTabel()"
                   style="padding: 10px; border: 1px solid #d1d5db; border-radius: 8px; width: 300px;">
        </div>

        <!-- Tabel -->
        <div id="tabelContainer">
            <table class="manager-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Materiaal</th>
                        <th>Categorie</th>
                        <th>Acties</th>
                    </tr>
                </thead>
                <tbody id="tabelBody">
                    <!-- Data-binding: rijen komen hier -->
                    <tr><td>1</td><td>Veiligheidshelm</td><td>Watermateriaal</td><td><button class="btn-edit" onclick="bewerk(1, 'Veiligheidshelm', 'Watermateriaal')">Bewerk</button></td></tr>
                    <tr><td>2</td><td>Veiligheidsvest</td><td>Installatie</td><td><button class="btn-edit" onclick="bewerk(2, 'Veiligheidsvest', 'Installatie')">Bewerk</button></td></tr>
                    <tr><td>3</td><td>Werkhandschoenen</td><td>Elektriciteit</td><td><button class="btn-edit" onclick="bewerk(3, 'Werkhandschoenen', 'Elektriciteit')">Bewerk</button></td></tr>
                </tbody>
            </table>
            <p id="geenMateriaal" class="empty-message">Geen materialen om te tonen.</p>
        </div>

        <!-- Bewerkformulier (verborgen) -->
        <div id="bewerkContainer" style="display:none;">
            <h2>Materiaal bewerken</h2>
            <div class="form-card">
                <label>Naam</label>
                <input type="text" id="bewerkNaam" class="text-field" style="width:100%;">

                <label>Categorie</label>
                <select id="bewerkCategorie" class="text-field" style="width:100%;">
                    <option>Watermateriaal</option>
                    <option>Installatie</option>
                    <option>Elektriciteit</option>
                </select>

                <div style="margin-top: 20px; display:flex; gap:10px;">
                    <button class="btn-primary" onclick="opslaanBewerk()">Materiaal bewerken</button>
                    <button class="btn-outline" onclick="annuleer()" style="padding:10px 18px; border-radius:8px; border:1px solid #ccc; cursor:pointer;">Annuleer</button>
                </div>
            </div>
        </div>

    </div>

    <script>
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

            document.getElementById('geenMateriaal').style.display = zichtbaar === 0 ? 'block' : 'none';
        }
    </script>

</x-layouts.site-layout>