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
                        <button type="button" class="link"><a href="{{route('admin.rollen.edit', $r->id)}}">Edit</a> </button>
                        <form method="POST" action="{{ route('admin.rollen.destroy', $r) }}" style="display:inline"
                              onsubmit="return confirm('Delete this role?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="link link-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr id="empty-row"><td colspan="4" class="muted center">No roles found.</td></tr>
                @endforelse
            </tbody>
        </table>
        <p id="no-results" class="muted center" style="display:none;padding:16px;">No results found.</p>
    </div>



</div>

<script>
function showTable() {
    document.getElementById('section-table').style.display = 'block';
    document.getElementById('section-edit').style.display = 'none';
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
