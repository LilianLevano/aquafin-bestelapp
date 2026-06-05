<x-layouts.site-layout>

    <h1>Aanvragen</h1>

    <div class="status-tabs">
        <button class="tab active" onclick="filterStatus('open', this)">Open</button>
        <button class="tab" onclick="filterStatus('opgelost', this)">Opgelost</button>
        <button class="tab" onclick="filterStatus('alle', this)">Alle</button>
    </div>

    <div id="aanvragen-list">
        @forelse($aanvragen ?? [] as $aanvraag)
        <div class="aanvraag-card" data-status="{{ $aanvraag->status ?? 'open' }}">

            <div class="aanvraag-header">
                <div class="aanvraag-title">
                    <label>Titel</label>
                    <input type="text" class="text-field" value="{{ $aanvraag->titel ?? '' }}" readonly>
                </div>
                <a href="/admin/antwoord/{{ $aanvraag->id ?? '' }}" class="btn-primary">Answer</a>
            </div>

            <div class="aanvraag-description">
                <label>Description</label>
                <textarea class="description-box" rows="5" readonly>{{ $aanvraag->descriptie ?? '' }}</textarea>
            </div>

            <div class="aanvraag-footer">
                <p><strong>Posted:</strong> {{ isset($aanvraag->created_at) ? $aanvraag->created_at->format('d/m/Y') : '—' }}</p>
                <p><strong>Time:</strong> {{ isset($aanvraag->created_at) ? $aanvraag->created_at->format('H:i') : '—' }}</p>
                <p><strong>Status:</strong> <span class="status-badge status-{{ $aanvraag->status ?? 'open' }}">{{ ucfirst($aanvraag->status ?? 'open') }}</span></p>
            </div>

        </div>
        @empty
        <p id="empty-msg" class="muted center">No requests found.</p>
        @endforelse
    </div>

    <p id="no-results" style="display:none;text-align:center;color:#64748b;padding:16px;">No requests found for this status.</p>

    <script>
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
    </script>

</x-layouts.site-layout>
