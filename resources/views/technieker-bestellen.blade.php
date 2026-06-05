<x-layouts.site-layout>

    <h1>Bestelling plaatsen</h1>

    <section class="filter-zone">

                <button class="btn-primary">Plaats</button>

        <div class="filter-item">
            <label>Filter</label>
            <select>
                <option>Alle categorieën</option>
                <option>Pompen</option>
                <option>Buizen</option>
            </select>
        </div>

        <div class="filter-item">
            <label>Quantity</label>
            <input type="number" min="1" value="1">
        </div>

        <div class="filter-item">
            <label>Seizoen</label>
            <select>
                <option>Winter</option>
                <option>Herfst</option>
                <option>Zomer</option>
                <option>Lente</option>
            </select>
        </div>

        <button class="btn-primary">Zoeken</button>

    </section>

    <table class="manager-table">

        <thead>
            <tr>
                <th>ID</th>
                <th>Materiaal</th>
                <th>Categorie</th>
                <th>Quantity</th>
                <th>Actie</th>
            </tr>
        </thead>

        <tbody>
            <tr>
                <td>1</td>
                <td>Pomp</td>
                <td>Watermateriaal</td>
                <td><input type="number" value="1"></td>
                <td><input type="checkbox"></td>
            </tr>

            <tr>
                <td>2</td>
                <td>Buis</td>
                <td>Installatie</td>
                <td><input type="number" value="1"></td>
                <td><input type="checkbox"></td>
            </tr>
        </tbody>

    </table>

    <div class="center-button">
        <button class="btn-primary">Toon alles</button>
    </div>

</x-layouts.site-layout>