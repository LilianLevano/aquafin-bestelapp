@extends('layouts.admin')
@section('title', 'Accounts')

@section('content')
<div class="card">

    <div class="tabs">
        <button type="button" class="tab tab-active">Huidig</button>
        <a href="{{ route('admin.accounts.create') }}" class="tab">Nieuw</a>
    </div>

    {{-- TABEL --}}
    <div id="section-table">
        <div class="row-between mb">
            <h1 class="h1">Accounts</h1>
            <button type="button" class="btn btn-outline btn-sm" onclick="location.reload()">↺ Herlaadt</button>
        </div>

        @if(session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Voornaam</th>
                    <th>Familienaam</th>
                    <th>Email</th>
                    <th>Wachtwoord reset</th>
                    <th>Rol</th>
                    <th class="right">Acties</th>
                </tr>
            </thead>
            <tbody>
                @forelse($accounts as $a)
                <tr>
                    <td>{{ $a->id }}</td>
                    <td>{{ $a->voornaam }}</td>
                    <td>{{ $a->achternaam }}</td>
                    <td>{{ $a->mail }}</td>
                    <td>
                        <a href="{{ route('admin.accounts.edit', $a) }}" class="link">Reset</a>
                    </td>
                    <td>{{ $a->role->name ?? '—' }}</td>
                    <td class="right">
                        <button type="button" class="link"
                            data-account="{{ json_encode(['id' => $a->id, 'mail' => $a->mail, 'voornaam' => $a->voornaam, 'achternaam' => $a->achternaam, 'role_id' => $a->role_id]) }}"
                            onclick="openEdit(this)">Bewerk</button>
                        <form method="POST" action="{{ route('admin.accounts.destroy', $a) }}" style="display:inline"
                              onsubmit="return confirm('Account verwijderen?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="link link-danger">Verwijder</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="muted center">Geen gebruikers om te tonen.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- INLINE EDIT FORMULIER --}}
    <div id="section-edit" style="display:none">
        <div class="row-between mb">
            <h1 class="h1">Gebruiker bewerken</h1>
            <button type="button" class="btn btn-outline btn-sm" onclick="showTable()">← Terug</button>
        </div>

        <form id="inline-edit-form" method="POST" action="" class="form" style="max-width:520px">
            @csrf
            <input type="hidden" name="_method" value="PUT">

            <div class="field">
                <label for="edit-mail">Mail</label>
                <input id="edit-mail" type="email" name="mail" required>
            </div>

            <div class="grid-2">
                <div class="field">
                    <label for="edit-voornaam">Voornaam</label>
                    <input id="edit-voornaam" name="voornaam" required>
                </div>
                <div class="field">
                    <label for="edit-achternaam">Familienaam</label>
                    <input id="edit-achternaam" name="achternaam" required>
                </div>
            </div>

            <div class="field">
                <label for="edit-role">Rol</label>
                <select id="edit-role" name="role_id" required>
                    @foreach($accounts->pluck('role')->filter()->unique('id') as $r)
                        <option value="{{ $r->id }}">{{ $r->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="field">
                <label for="edit-password">Nieuw wachtwoord <span class="muted">(optioneel)</span></label>
                <div class="input-group">
                    <input id="edit-password" type="password" name="password">
                    <button type="button" class="btn-toggle-pw" onclick="togglePw('edit-password', this)">Toon</button>
                </div>
            </div>

            <div class="field">
                <label for="edit-password-confirm">Bevestig wachtwoord</label>
                <div class="input-group">
                    <input id="edit-password-confirm" type="password" name="password_confirmation">
                    <button type="button" class="btn-toggle-pw" onclick="togglePw('edit-password-confirm', this)">Toon</button>
                </div>
            </div>

            <div class="row-end">
                <button type="button" class="btn btn-outline" onclick="showTable()">Annuleren</button>
                <button type="submit" class="btn btn-primary">Gebruiker bewerken</button>
            </div>
        </form>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>id</th><th>Voornaam</th><th>Achternaam</th>
                <th>Rol</th><th>Mail</th><th class="right">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($accounts as $a)
            <tr>
                <td>{{ $a->id }}</td>
                <td>{{ $a->first_name }}</td>
                <td>{{ $a->last_name }}</td>
                <td>{{ $a->role->name ?? '—' }}</td>
                <td>{{ $a->email }}</td>
                <td class="right">
                    <a href="{{ route('admin.accounts.edit', $a) }}" class="link">edit</a>
                    <form method="POST" action="{{ route('admin.accounts.destroy', $a) }}" style="display:inline"
                          onsubmit="return confirm('Account verwijderen?');">
                        @csrf @method('DELETE')
                        <button type="submit" class="link link-danger">delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="muted center">Geen accounts.</td></tr>
            @endforelse
        </tbody>
    </table>
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
    document.getElementById('edit-voornaam').value = a.voornaam;
    document.getElementById('edit-achternaam').value = a.achternaam;
    document.getElementById('edit-role').value = a.role_id;
    document.getElementById('edit-password').value = '';
    document.getElementById('edit-password-confirm').value = '';
    document.getElementById('section-table').style.display = 'none';
    document.getElementById('section-edit').style.display = 'block';
}

function togglePw(id, btn) {
    var input = document.getElementById(id);
    input.type = input.type === 'password' ? 'text' : 'password';
    btn.textContent = input.type === 'password' ? 'Toon' : 'Verberg';
}
</script>
@endsection
