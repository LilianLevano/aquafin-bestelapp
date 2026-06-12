@extends('layouts.app')
@section('title', 'Edit Account')

@section('content')
    <div class="card" style="max-width:560px;margin:0 auto;">
        <h1 class="h1">Edit Account</h1>

        <form id="edit-form" method="POST" action="{{ route('admin.accounts.update', $account) }}" class="form">
            @csrf @method('PUT')

            <div class="field">
                <label for="email">Mail</label>
                <input id="email" type="email" name="email"
                    value="{{ old('email', $account->email) }}" required
                    data-original="{{ $account->email }}"
                    class="{{ $errors->has('email') ? 'is-invalid' : '' }}">
                @error('email') <p class="error">{{ $message }}</p> @enderror
            </div>

            <div class="field">
                <label for="phone_number">Telefoonnummer</label>
                <input id="phone_number" type="tel" name="phone_number"
                       value="{{ old('phone_number', $account->phone_number) }}" required
                       data-original="{{ $account->phone_number }}"
                       class="{{ $errors->has('phone_number') ? 'is-invalid' : '' }}">
                @error('phone_number') <p class="error">{{ $message }}</p> @enderror
            </div>

            <div class="grid-2">
                <div class="field">
                    <label for="first_name">First Name</label>
                    <input id="first_name" name="first_name"
                        value="{{ old('first_name', $account->first_name) }}" required
                        data-original="{{ $account->first_name }}"
                        class="{{ $errors->has('first_name') ? 'is-invalid' : '' }}">
                    @error('first_name') <p class="error">{{ $message }}</p> @enderror
                </div>
                <div class="field">
                    <label for="last_name">Last Name</label>
                    <input id="last_name" name="last_name"
                        value="{{ old('last_name', $account->last_name) }}" required
                        data-original="{{ $account->last_name }}"
                        class="{{ $errors->has('last_name') ? 'is-invalid' : '' }}">
                    @error('last_name') <p class="error">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="field">
                <label for="role_id">Rol</label>
                <select id="role_id" name="role_id" required
                        data-original="{{ $account->role_id }}"
                        class="{{ $errors->has('role_id') ? 'is-invalid' : '' }}">
                    @foreach($roles as $r)
                        <option value="{{ $r->id }}" @selected(old('role_id', $account->role_id) == $r->id)>{{ $r->name }}</option>
                    @endforeach
                </select>
                @error('role_id') <p class="error">{{ $message }}</p> @enderror
            </div>

            <div class="field">
                <label for="site_id">Locatie: </label>
                <select id="site_id" name="site_id" required
                        class="{{ $errors->has('site_id') ? 'is-invalid' : '' }}">
                    <option value="">— Select locatie —</option>
                    @foreach($sites as $s)
                        <option value="{{ $s->id }}" @selected(old('site_id', $account->site_id) == $s->id)>{{ $s->description }}</option>
                    @endforeach
                </select>
                @error('site_id') <p class="error">{{ $message }}</p> @enderror
            </div>

            <div class="field">
                <label for="password">New Password <span class="muted">(optional)</span></label>
                <div class="input-group">
                    <input id="password" type="password" name="password"
                        class="{{ $errors->has('password') ? 'is-invalid' : '' }}">
                    <button type="button" class="btn-toggle-pw" onclick="togglePw('password', this)">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                        <span>Show</span>
                    </button>
                </div>
                @error('password') <p class="error">{{ $message }}</p> @enderror
            </div>

            <div class="field">
                <label for="password_confirmation">Confirm Password</label>
                <div class="input-group">
                    <input id="password_confirmation" type="password" name="password_confirmation">
                    <button type="button" class="btn-toggle-pw" onclick="togglePw('password_confirmation', this)">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                        <span>Show</span>
                    </button>
                </div>
                @error('password_confirmation') <p class="error">{{ $message }}</p> @enderror
            </div>

            <div class="row-end">
                <a href="{{ route('admin.accounts.index') }}" class="btn btn-outline">Cancel</a>
                <button id="submit-btn" type="submit" class="btn btn-primary">Edit User</button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    @vite('resources/js/account-edit.js')
@endpush
