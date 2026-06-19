@extends('layouts.app')

@section('content')
    <h1>Bestelling plaatsen</h1>

    @if(session('status'))
        <div class="alert alert-success mb-4">{{ session('status') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger mb-4">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- FILTERS --}}
    <div class="card mb-4">
        <div class="card-body d-flex flex-wrap gap-3 align-items-end">
            <div>
                <label class="form-label small fw-semibold mb-1">Filter</label>
                <select id="filter-categorie" class="form-select form-select-sm">
                    <option value="">Alle categorieën</option>
                    @foreach($materials->pluck('category.name')->unique()->filter() as $cat)
                        <option value="{{ $cat }}">{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label small fw-semibold mb-1">Zoeken</label>
                <input type="text" id="filter-zoek"
                       placeholder="Zoek materiaal..."
                       class="form-control form-control-sm">
            </div>
        </div>
    </div>

    <div class="d-flex gap-4 align-items-start flex-wrap" id="bestel-layout">
        {{-- LEFT: FORM --}}
        <div style="flex:1;min-width:0;">
            <form action="{{ route('technieker.orders.store') }}" method="POST" id="bestel-form">
                @csrf

                {{-- ORDER INFO --}}
                <div class="card mb-4">
                    <div class="card-body d-flex flex-wrap gap-4 align-items-end">
                        <div>
                            <label for="delivery_date" class="form-label small fw-semibold mb-1">
                                Leverdatum <span class="text-danger">*</span>
                            </label>
                            <input type="date"
                                   id="delivery_date"
                                   name="delivery_date"
                                   class="form-control form-control-sm @error('delivery_date') is-invalid @enderror"
                                   value="{{ old('delivery_date') }}"
                                   required>
                            @error('delivery_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label for="site_id" class="form-label small fw-semibold mb-1">
                                Locatie <span class="text-danger">*</span>
                            </label>
                            <select name="site_id" id="site_id"
                                    class="form-select form-select-sm @error('site_id') is-invalid @enderror">
                                <option value="">Kies een locatie</option>
                                <option value="1" @selected(old('site_id') == '1')>Limburg</option>
                                <option value="2" @selected(old('site_id') == '2')>Oost-Vlaanderen</option>
                                <option value="3" @selected(old('site_id') == '3')>West-Vlaanderen</option>
                                <option value="4" @selected(old('site_id') == '4')>Antwerpen</option>
                            </select>
                            @error('site_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="ms-auto">
                            <button type="submit" class="btn btn-primary">
                                Bestelling plaatsen
                            </button>
                        </div>
                    </div>
                </div>

                <p id="alert-data" class="alert alert-error" style="display: none;">Er zijn geen voorspellingen gevonden voor deze dag. Neem een dag tussen vandaag en 14 dagen later.</p>

                <div class="card" id="priority-list" style="display: none;" >
                    <div class="card">
                        <div class="card-header fw-semibold">Prioritaire materialen</div>
                        <div class="card-header fw-semibold">Selecteer materialen</div>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0" id="materials-table">
                                <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Materiaal</th>
                                    <th class="category-material">Categorie</th>
                                    <th class="category-material">Type</th>
                                    <th>Hoeveelheid</th>
                                    <th>Selecteer</th>
                                </tr>
                                </thead>
                                <tbody id="priority-list-tbody">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- MATERIALS TABLE --}}
                <div class="card">
                    <div class="card-header fw-semibold">Selecteer materialen</div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="materials-table">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Materiaal</th>
                                    <th class="category-material">Categorie</th>
                                    <th class="category-material">Type</th>
                                    <th>Hoeveelheid</th>
                                    <th>Selecteer</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($materials as $material)
                                    <tr        data-id="{{ $material->id }}"
                                               data-naam="{{ strtolower($material->name) }}"
                                               data-categorie="{{ $material->category->name ?? '' }}">
                                        <td class="text-muted font-monospace small">{{ $material->id }}</td>
                                        <td class="fw-medium"><a href="{{route('technieker.materials.show', $material->id)}}">{{ $material->name }}</a> </td>
                                        <td class="category-material">
                                            <span class="badge bg-primary bg-opacity-10 text-primary ">
                                                {{ $material->category->name ?? '—' }}
                                            </span>
                                        </td>
                                        <td class="category-material">
                                            <span class="badge bg-primary bg-opacity-10 text-primary ">
                                                {{ $material->type }}
                                            </span>
                                        </td>
                                        <td>
                                            <input type="number"
                                                   value="0" min="0"
                                                   name="quantity[{{ $material->id }}]"
                                                   class="form-control form-control-sm quantity-input"
                                                   data-id="{{ $material->id }}"
                                                   data-naam="{{ $material->name }}">
                                        </td>
                                        <td >
                                            <input type="checkbox"
                                                   name="materials[]"
                                                   value="{{ $material->id }}"
                                                   class="form-check-input material-checkbox"
                                                   data-id="{{ $material->id }}"
                                                   data-naam="{{ $material->name }}">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
        </div>

        {{-- RIGHT: BASKET --}}
        <div class="basket-panel" id="basket-panel"
             style="width:300px;flex-shrink:0;position:sticky;top:1rem;display:none;">
            <div class="card shadow-sm">
                <div class="card-header fw-semibold d-flex justify-content-between align-items-center"
                     style="background:#1a5fa8;color:white;">
                    <span>🛒 Winkelmandje</span>
                    <span id="basket-count"
                          class="badge bg-white text-primary rounded-pill">0</span>
                </div>

                <div id="basket-empty"
                     class="card-body text-center text-muted fst-italic small py-4">
                    Nog geen items geselecteerd.
                </div>

                <ul id="basket-list"
                    class="list-group list-group-flush"
                    style="display:none;max-height:400px;overflow-y:auto;">
                </ul>

                <div id="basket-footer"
                     class="card-footer d-grid"
                     style="display:none!important;">
                    <button type="submit" form="bestel-form" class="btn btn-primary btn-sm">
                        ✓ Bestelling plaatsen
                    </button>
                </div>
            </div>
        </div>
    </div>


        {{-- RIGHT: BASKET --}}
        <div class="basket-panel" id="basket-panel"
             style="width:300px;flex-shrink:0;position:sticky;top:1rem;display:none;">
            <div class="card shadow-sm">
                <div class="card-header fw-semibold d-flex justify-content-between align-items-center"
                     style="background:#1a5fa8;color:white;">
                    <span>🛒 Winkelmandje</span>
                    <span id="basket-count"
                          class="badge bg-white text-primary rounded-pill">0</span>
                </div>

                <div id="basket-empty"
                     class="card-body text-center text-muted fst-italic small py-4">
                    Nog geen items geselecteerd.
                </div>

                <ul id="basket-list"
                    class="list-group list-group-flush"
                    style="display:none;max-height:400px;overflow-y:auto;">
                </ul>

                <div id="basket-footer"
                     class="card-footer d-grid"
                     style="display:none!important;">
                    <button type="submit" form="bestel-form" class="btn btn-primary btn-sm">
                        ✓ Bestelling plaatsen
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @vite('resources/js/orders/orders-create.js')
    <script>
        const materials = @json($materials);
        const basket = JSON.parse(sessionStorage.getItem('basket') ?? '{}');

        function updateBasket(id, naam, qty) {
            if (qty > 0) {
                basket[id] = { naam, qty };
            } else {
                delete basket[id];
            }
            sessionStorage.setItem('basket', JSON.stringify(basket));
            renderBasket();
        }

        function renderBasket() {
            const keys   = Object.keys(basket);
            const panel  = document.getElementById('basket-panel');
            const list   = document.getElementById('basket-list');
            const empty  = document.getElementById('basket-empty');
            const footer = document.getElementById('basket-footer');
            const count  = document.getElementById('basket-count');

            count.textContent = keys.length;
            panel.style.display = keys.length > 0 ? '' : 'none';

            if (keys.length === 0) {
                empty.style.display  = '';
                list.style.display   = 'none';
                footer.style.cssText = 'display:none!important;';
                return;
            }

            empty.style.display  = 'none';
            list.style.display   = '';
            footer.style.cssText = '';

            list.innerHTML = '';
            keys.forEach(id => {
                const item = basket[id];
                const li   = document.createElement('li');
                li.className = 'list-group-item d-flex justify-content-between align-items-center py-2 px-3';
                li.innerHTML = `
                    <div>
                        <div class="fw-medium small">${esc(item.naam)}</div>
                        <div class="text-muted" style="font-size:0.75rem;">Aantal: ${item.qty}</div>
                    </div>
                    <button type="button"
                            class="btn btn-sm btn-outline-danger py-0 px-2"
                            onclick="removeItem(${id})">✕</button>
                `;
                list.appendChild(li);
            });
        }

        function removeItem(id) {
            const qtyInput = document.querySelector(`.quantity-input[data-id="${id}"]`);
            const checkbox = document.querySelector(`.material-checkbox[data-id="${id}"]`);
            if (qtyInput) qtyInput.value = 0;
            if (checkbox) checkbox.checked = false;
            delete basket[id];
            sessionStorage.setItem('basket', JSON.stringify(basket));
            renderBasket();
        }

        function esc(str) {
            return String(str)
                .replace(/&/g,'&amp;')
                .replace(/</g,'&lt;')
                .replace(/>/g,'&gt;');
        }

        // Quantity input → update basket + auto-check
        document.querySelectorAll('.quantity-input').forEach(input => {
            input.addEventListener('input', function () {
                const id   = this.dataset.id;
                const naam = this.dataset.naam;
                const qty  = parseInt(this.value) || 0;
                const cb   = document.querySelector(`.material-checkbox[data-id="${id}"]`);
                cb.checked = qty > 0;
                updateBasket(id, naam, qty);
            });
        });

        // Checkbox → sync basket
        document.querySelectorAll('.material-checkbox').forEach(cb => {
            cb.addEventListener('change', function () {
                const id       = this.dataset.id;
                const naam     = this.dataset.naam;
                const qtyInput = document.querySelector(`.quantity-input[data-id="${id}"]`);
                const qty      = parseInt(qtyInput.value) || 0;

                if (!this.checked) {
                    qtyInput.value = 0;
                    delete basket[id];
                    renderBasket();
                } else if (qty === 0) {
                    qtyInput.value = 1;
                    updateBasket(id, naam, 1);
                }
            });
        });

        // Filter
        document.getElementById('filter-categorie').addEventListener('change', filterTable);
        document.getElementById('filter-zoek').addEventListener('input', filterTable);

        function filterTable() {
            const cat  = document.getElementById('filter-categorie').value.toLowerCase();
            const zoek = document.getElementById('filter-zoek').value.toLowerCase();
            document.querySelectorAll('#materials-table tbody tr').forEach(tr => {
                const matchCat  = !cat  || tr.dataset.categorie.toLowerCase() === cat;
                const matchZoek = !zoek || tr.dataset.naam.includes(zoek);
                tr.style.display = (matchCat && matchZoek) ? '' : 'none';
            });
        }

        Object.entries(basket).forEach(([id, item]) => {
            const qtyInput = document.querySelector(`.quantity-input[data-id="${id}"]`);
            const checkbox = document.querySelector(`.material-checkbox[data-id="${id}"]`);
            if (qtyInput) qtyInput.value = item.qty;
            if (checkbox) checkbox.checked = true;
        });
        renderBasket();

        document.getElementById('bestel-form').addEventListener('submit', () => {
            sessionStorage.removeItem('basket');
        });

        document.addEventListener('change', function (e) {
            if (!e.target.classList.contains('material-checkbox')) return;

            const cb = e.target;
            const id = cb.dataset.id;
            const naam = cb.dataset.naam;

            const qtyInput = document.querySelector(`.quantity-input[data-id="${id}"]`);
            const qty = qtyInput ? parseInt(qtyInput.value) || 0 : 0;

            if (!cb.checked) {
                if (qtyInput) qtyInput.value = 0;
                delete basket[id];
                renderBasket();
            } else if (qty === 0) {
                if (qtyInput) qtyInput.value = 1;
                updateBasket(id, naam, 1);
            }
        });

        document.addEventListener('input', (e) => {
            if (!e.target.classList.contains('quantity-input')) return;

            const id = e.target.dataset.id;
            const naam = e.target.dataset.naam;
            const qty = parseInt(e.target.value) || 0;

            updateBasket(id, naam, qty);
        });

        document.getElementById('delivery_date').addEventListener('change', function () {
            resetPriorityList();
        });

        function resetPriorityList() {

            const tbody = document.getElementById('priority-list-tbody');
            if (tbody) tbody.innerHTML = '';


            document.querySelectorAll('#materials-table tbody tr').forEach(tr => {
                tr.style.display = '';
            });


            const box = document.getElementById('priority-list');
            if (box) box.style.display = 'none';
        }
    </script>
@endpush
