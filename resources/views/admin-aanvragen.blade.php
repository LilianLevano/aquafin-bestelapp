<x-layouts.site-layout>

    <h1>Aanvragen</h1>

    <div class="status-tabs">
        <button class="tab active">Open</button>
        <button class="tab">Opgelost</button>
        <button class="tab">Alle</button>
    </div>

    <div class="aanvraag-card">

        <div class="aanvraag-header">
            <div class="aanvraag-title">
                <label>Titel</label>
                <input type="text" class="text-field" placeholder="Probleem met authenticatie">
            </div>

            <a href="/admin/antwoord" class="btn-primary">Answer</a>
        </div>

        <div class="aanvraag-description">
            <label>Description</label>
            <textarea class="description-box" rows="5" placeholder="Typ hier de beschrijving"></textarea>
        </div>

        <div class="aanvraag-footer">
            <p><strong>Posted:</strong> 03/06/2026</p>
            <p><strong>Posted on:</strong> 10:30</p>
        </div>

    </div>

</x-layouts.site-layout>