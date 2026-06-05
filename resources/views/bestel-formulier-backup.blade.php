<x-layouts.app>

    <h2 class="mb-4">📦 Materialen Bestellen</h2>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <input type="text" id="zoekbalk" class="form-control w-50" placeholder="🔍 Zoek materiaal...">
        <button id="plaatsBtn" class="btn btn-primary" disabled>📦 Bestelling plaatsen</button>
    </div>

    <div class="row">

        <div class="col-12 mb-3">
            <div class="card shadow">
                <div class="card-header bg-primary text-white fw-bold">
                    ⭐ Prioritaire materialen &nbsp;
                    <select id="periodeSelect" class="form-select form-select-sm w-auto d-inline ms-3">
                        <option value="winter">❄️ Winter</option>
                        <option value="lente">🌸 Lente</option>
                        <option value="zomer">☀️ Zomer</option>
                        <option value="herfst">🍂 Herfst</option>
                    </select>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0" id="materialenTabel">
                        <thead class="table-light">
                            <tr>
                                <th></th>
                                <th>Naam</th>
                                <th>Categorie</th>
                                <th>Actie</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><input type="checkbox" class="form-check-input"></td>
                                <td>Veiligheidshelm</td>
                                <td>Bescherming</td>
                                <td><button class="btn btn-sm btn-primary voeg-toe" data-naam="Veiligheidshelm">+ Voeg toe</button></td>
                            </tr>
                            <tr>
                                <td><input type="checkbox" class="form-check-input"></td>
                                <td>Veiligheidsvest</td>
                                <td>Bescherming</td>
                                <td><button class="btn btn-sm btn-primary voeg-toe" data-naam="Veiligheidsvest">+ Voeg toe</button></td>
                            </tr>
                            <tr>
                                <td><input type="checkbox" class="form-check-input"></td>
                                <td>Werkhandschoenen</td>
                                <td>Bescherming</td>
                                <td><button class="btn btn-sm btn-primary voeg-toe" data-naam="Werkhandschoenen">+ Voeg toe</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-12 mb-3">
            <button class="btn btn-outline-primary" id="toonAllesBtn">👁️ Toon alle materialen</button>
        </div>

        <div class="col-12 mb-3" id="alleMaterialen" style="display:none;">
            <div class="card shadow">
                <div class="card-header bg-secondary text-white fw-bold">📋 Alle materialen</div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>