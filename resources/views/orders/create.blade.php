@extends('layouts.app')
@section('title', 'Bestelling plaatsen')

@section('content')
<div style="padding: 2rem; max-width: 1100px; margin: 0 auto;">

    <h1 class="h1 mb-4">Bestelling plaatsen</h1>

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

    {{-- FILTERS (visual only, no backend yet) --}}
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

    <form action="{{ route('orders.store') }}" method="POST">
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
                        @foreach($sites as $site)
                            <option value="{{ $site->id }}"
                                @selected(old('site_id', auth()->user()->site_id) == $site->id)>
                                {{ $site->locatie }}
                            </option>
                        @endforeach
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

        {{-- MATERIALS TABLE --}}
        <div class="card">
            <div class="card-header fw-semibold">Selecteer materialen</div>
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="materials-table">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Materiaal</th>
                            <th>Categorie</th>
                            <th>Hoeveelheid</th>
                            <th>Selecteer</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($materials as $material)
                            <tr data-naam="{{ strtolower($material->name) }}"
                                data-categorie="{{ $material->category->name ?? '' }}">
                                <td class="text-muted font-monospace small">{{ $material->id }}</td>
                                <td class="fw-medium">{{ $material->name }}</td>
                                <td>
                                    <span class="badge bg-primary bg-opacity-10 text-primary">
                                        {{ $material->category->name ?? '—' }}
                                    </span>
                                </td>
                                <td style="width:140px;">
                                    <input type="number"
                                           value="0" min="0"
                                           name="quantity[{{ $material->id }}]"
                                           class="form-control form-control-sm quantity-input"
                                           data-id="{{ $material->id }}">
                                </td>
                                <td style="width:80px;">
                                    <input type="checkbox"
                                           name="materials[]"
                                           value="{{ $material->id }}"
                                           class="form-check-input material-checkbox"
                                           data-id="{{ $material->id }}">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </form>
</div>

<script>
// Auto-check checkbox when quantity > 0
document.querySelectorAll('.quantity-input').forEach(input => {
    input.addEventListener('change', function () {
        const id = this.dataset.id;
        const checkbox = document.querySelector(`.material-checkbox[data-id="${id}"]`);
        if (parseInt(this.value) > 0) {
            checkbox.checked = true;
        } else {
            checkbox.checked = false;
        }
    });
});

// Filter by category
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
</script>
@endsection