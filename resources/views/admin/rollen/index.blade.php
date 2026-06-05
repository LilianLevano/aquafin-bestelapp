@extends('layouts.admin')
@section('title', 'Roles')

@section('content')
<div class="card">

    <div class="tabs">
        <button type="button" class="tab tab-active">Current</button>
        <a href="{{ route('admin.rollen.create') }}" class="tab">New</a>
    </div>

    {{-- TABLE --}}
    <div id="section-table">
        <div class="row-between mb">
            <h1 class="h1">Roles</h1>
            <button type="button" class="btn btn-outline btn-sm" onclick="location.reload()">↺ Refresh</button>
        </div>

        @if(session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        <div class="mb">
            <input id="search-input" type="text" placeholder="Search by name..."
                   oninput="filterTable(this.value)"
                   style="padding:8px 12px;border:1px solid var(--border);border-radius:8px;font:inherit;width:100%;max-width:300px;">
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th class="right">Actions</th>
                </tr>
            </thead>
            <tbody id="roles-tbody">
                @forelse($roles as $r)
                <tr>
                    <td>{{ $r->id }}</td>
                    <td>{{ $r->name }}</td>
                    <td class="right">
                        <button type="button" class="link"
                            data-role="{{ json_encode(['id' => $r->id, 'name' => $r->name]) }}"
                            onclick="openEdit(this)">Edit</button>
                        <form method="POST" action="{{ route('admin.rollen.destroy', $r) }}" style="display:inline"
                              onsubmit="return confirm('Delete this role?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="link link-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr id="empty-row"><td colspan="3" class="muted center">No roles found.</td></tr>
                @endforelse
            </tbody>
        </table>
        <p id="no-results" class="muted center" style="display:none;padding:16px;">No results found.</p>
    </div>

    {{-- INLINE EDIT FORM --}}
    <div id="section-edit" style="display:none">
        <div class="row-between mb">
            <h1 class="h1">Edit Role</h1>
            <button type="button" class="btn btn-outline btn-sm" onclick="showTable()">← Back</button>
        </div>

        <form id="inline-edit-form" method="POST" action="" class="form" style="max-width:480px">
            @csrf
            <input type="hidden" name="_method" value="PUT">

            <div class="field">
                <label for="edit-name">Role Name</label>
                <input id="edit-name" name="name" required>
            </div>

            <div class="row-end">
                <button type="button" class="btn btn-outline" onclick="showTable()">Cancel</button>
                <button id="edit-submit-btn" type="submit" class="btn btn-primary">Save Role</button>
            </div>
        </form>
    </div>

</div>

<script>
function showTable() {
    document.getElementById('section-table').style.display = 'block';
    document.getElementById('section-edit').style.display = 'none';
}

function openEdit(btn) {
    var r = JSON.parse(btn.dataset.role);
    document.getElementById('inline-edit-form').action = '{{ url("admin/rollen") }}/' + r.id;
    document.getElementById('edit-name').value = r.name;
    document.getElementById('edit-name').dataset.original = r.name;
    document.querySelectorAll('#inline-edit-form .is-invalid, #inline-edit-form .is-modified').forEach(function(el) {
        el.classList.remove('is-invalid', 'is-modified');
    });
    document.querySelectorAll('#inline-edit-form .error-js').forEach(function(el) { el.remove(); });
    document.getElementById('section-table').style.display = 'none';
    document.getElementById('section-edit').style.display = 'block';
}

function togglePw(id, btn) {
    var input = document.getElementById(id);
    input.type = input.type === 'password' ? 'text' : 'password';
    btn.textContent = input.type === 'password' ? 'Show' : 'Hide';
}

function setValidity(input, valid, message) {
    var field = input.closest('.field');
    var existing = field.querySelector('.error-js');
    if (valid) {
        input.classList.remove('is-invalid');
        if (existing) existing.remove();
    } else {
        input.classList.add('is-invalid');
        if (!existing) {
            var p = document.createElement('p');
            p.className = 'error error-js';
            p.textContent = message;
            field.appendChild(p);
        }
    }
}

document.getElementById('edit-name').addEventListener('blur', function() {
    var ok = this.value.trim().length >= 2;
    setValidity(this, ok, 'Role name must be at least 2 characters.');
});

document.getElementById('edit-name').addEventListener('input', function() {
    if (this.dataset.original !== undefined) {
        this.classList.toggle('is-modified', this.value !== this.dataset.original);
    }
});

document.getElementById('inline-edit-form').addEventListener('submit', function(e) {
    var valid = true;
    this.querySelectorAll('[required]').forEach(function(input) {
        if (!input.value.trim()) {
            input.classList.add('is-invalid');
            valid = false;
        }
    });
    if (!valid) { e.preventDefault(); return; }
    var btn = document.getElementById('edit-submit-btn');
    btn.disabled = true;
    btn.dataset.original = btn.textContent;
    btn.textContent = btn.textContent + '…';
});

window.addEventListener('pageshow', function(e) {
    if (e.persisted) {
        var btn = document.getElementById('edit-submit-btn');
        if (btn) { btn.disabled = false; if (btn.dataset.original) btn.textContent = btn.dataset.original; }
    }
});

function filterTable(query) {
    var rows = document.querySelectorAll('#roles-tbody tr:not(#empty-row)');
    var q = query.toLowerCase().trim();
    var visible = 0;
    rows.forEach(function(row) {
        var name = row.cells[1] ? row.cells[1].textContent.toLowerCase() : '';
        var match = name.includes(q);
        row.style.display = match ? '' : 'none';
        if (match) visible++;
    });
    document.getElementById('no-results').style.display = visible === 0 && q !== '' ? 'block' : 'none';
}
</script>
@endsection
