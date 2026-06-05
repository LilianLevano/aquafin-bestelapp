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
                    <td>{{ $a->voornaam }}</td>
                    <td>{{ $a->achternaam }}</td>
                    <td>{{ $a->mail }}</td>
                    <td><a href="{{ route('admin.accounts.edit', $a) }}" class="link">Reset</a></td>
                    <td>{{ $a->role->name ?? '—' }}</td>
                    <td class="right">
                        <button type="button" class="link"
                            data-account="{{ json_encode(['id' => $a->id, 'mail' => $a->mail, 'voornaam' => $a->voornaam, 'achternaam' => $a->achternaam, 'role_id' => $a->role_id]) }}"
                            onclick="openEdit(this)">Edit</button>
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

    {{-- INLINE EDIT FORM --}}
    <div id="section-edit" style="display:none">
        <div class="row-between mb">
            <h1 class="h1">Edit User</h1>
            <button type="button" class="btn btn-outline btn-sm" onclick="showTable()">← Back</button>
        </div>

        <form id="inline-edit-form" method="POST" action="" class="form" style="max-width:520px">
            @csrf
            <input type="hidden" name="_method" value="PUT">

            <div class="field">
                <label for="edit-mail">Email</label>
                <input id="edit-mail" type="email" name="mail" required>
            </div>

            <div class="grid-2">
                <div class="field">
                    <label for="edit-voornaam">First Name</label>
                    <input id="edit-voornaam" name="voornaam" required>
                </div>
                <div class="field">
                    <label for="edit-achternaam">Last Name</label>
                    <input id="edit-achternaam" name="achternaam" required>
                </div>
            </div>

            <div class="field">
                <label for="edit-role">Role</label>
                <select id="edit-role" name="role_id" required>
                    @foreach($accounts->pluck('role')->filter()->unique('id') as $r)
                        <option value="{{ $r->id }}">{{ $r->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="field">
                <label for="edit-password">New Password <span class="muted">(optional)</span></label>
                <div class="input-group">
                    <input id="edit-password" type="password" name="password">
                    <button type="button" class="btn-toggle-pw" onclick="togglePw('edit-password', this)">Show</button>
                </div>
            </div>

            <div class="field">
                <label for="edit-password-confirm">Confirm Password</label>
                <div class="input-group">
                    <input id="edit-password-confirm" type="password" name="password_confirmation">
                    <button type="button" class="btn-toggle-pw" onclick="togglePw('edit-password-confirm', this)">Show</button>
                </div>
            </div>

            <div class="row-end">
                <button type="button" class="btn btn-outline" onclick="showTable()">Cancel</button>
                <button id="edit-submit-btn" type="submit" class="btn btn-primary">Edit User</button>
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
    var a = JSON.parse(btn.dataset.account);
    document.getElementById('inline-edit-form').action = '{{ url("admin/accounts") }}/' + a.id;
    document.getElementById('edit-mail').value = a.mail;
    document.getElementById('edit-mail').dataset.original = a.mail;
    document.getElementById('edit-voornaam').value = a.voornaam;
    document.getElementById('edit-voornaam').dataset.original = a.voornaam;
    document.getElementById('edit-achternaam').value = a.achternaam;
    document.getElementById('edit-achternaam').dataset.original = a.achternaam;
    document.getElementById('edit-role').value = a.role_id;
    document.getElementById('edit-role').dataset.original = a.role_id;
    document.getElementById('edit-password').value = '';
    document.getElementById('edit-password-confirm').value = '';
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
