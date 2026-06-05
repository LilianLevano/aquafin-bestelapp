@extends('layouts.admin')
@section('title', 'Accounts')

@section('content')
<div class="card">

    <div class="tabs">
        <button type="button" class="tab tab-active">Current</button>
        <a href="{{ route('admin.accounts.create') }}" class="tab">New</a>
    </div>

    {{-- TABLE --}}
    <div id="section-table">
        <div class="row-between mb">
            <h1 class="h1">Accounts</h1>
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

        <table class="table" id="accounts-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Password Reset</th>
                    <th>Role</th>
                    <th class="right">Actions</th>
                </tr>
            </thead>
            <tbody id="accounts-tbody">
                @forelse($accounts as $a)
                <tr>
                    <td>{{ $a->id }}</td>
                    <td>{{ $a->first_name }}</td>
                    <td>{{ $a->last_name }}</td>
                    <td>{{ $a->email }}</td>
                    <td><a href="{{ route('admin.accounts.edit', $a) }}" class="link">Reset</a></td>
                    <td>{{ $a->role->name ?? '—' }}</td>
                    <td class="right">

                        <a href="{{route('admin.accounts.edit', $a->id)}}" class="link">Edit</a>


                        <form method="POST" action="{{ route('admin.accounts.destroy', $a) }}" style="display:inline"
                              onsubmit="return confirm('Delete this account?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="link link-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr id="empty-row"><td colspan="7" class="muted center">No users to display.</td></tr>
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

document.getElementById('edit-mail').addEventListener('blur', function() {
    var ok = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.value.trim());
    setValidity(this, ok, 'Invalid email address.');
});

document.getElementById('edit-voornaam').addEventListener('blur', function() {
    var ok = /^[A-Za-zÀ-ÿ\s\-']{2,}$/.test(this.value.trim());
    setValidity(this, ok, 'Min. 2 characters, letters only.');
});

document.getElementById('edit-achternaam').addEventListener('blur', function() {
    var ok = /^[A-Za-zÀ-ÿ\s\-']{2,}$/.test(this.value.trim());
    setValidity(this, ok, 'Min. 2 characters, letters only.');
});

document.getElementById('edit-password').addEventListener('blur', function() {
    if (!this.value) return;
    var v = this.value;
    var ok = v.length >= 8 && /[A-Z]/.test(v) && /[a-z]/.test(v) && /[0-9]/.test(v);
    setValidity(this, ok, 'Min. 8 characters, 1 uppercase, 1 lowercase, 1 number.');
});

document.getElementById('edit-password-confirm').addEventListener('blur', function() {
    var pw = document.getElementById('edit-password').value;
    if (!pw) return;
    setValidity(this, this.value === pw, 'Passwords do not match.');
});

document.querySelectorAll('#edit-mail, #edit-voornaam, #edit-achternaam, #edit-role').forEach(function(input) {
    input.addEventListener('input', function() {
        if (this.dataset.original !== undefined) {
            this.classList.toggle('is-modified', this.value !== this.dataset.original);
        }
    });
});

document.getElementById('inline-edit-form').addEventListener('submit', function(e) {
    var valid = true;
    this.querySelectorAll('[required]').forEach(function(input) {
        if (!input.value.trim()) {
            input.classList.add('is-invalid');
            valid = false;
        }
    });
    var pw = document.getElementById('edit-password').value;
    var pwc = document.getElementById('edit-password-confirm').value;
    if (pw && pw !== pwc) {
        document.getElementById('edit-password-confirm').classList.add('is-invalid');
        valid = false;
    }
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
    var rows = document.querySelectorAll('#accounts-tbody tr:not(#empty-row)');
    var q = query.toLowerCase().trim();
    var visible = 0;
    rows.forEach(function(row) {
        var first = row.cells[1] ? row.cells[1].textContent.toLowerCase() : '';
        var last = row.cells[2] ? row.cells[2].textContent.toLowerCase() : '';
        var match = first.includes(q) || last.includes(q);
        row.style.display = match ? '' : 'none';
        if (match) visible++;
    });
    document.getElementById('no-results').style.display = visible === 0 && q !== '' ? 'block' : 'none';
}
</script>
@endsection
