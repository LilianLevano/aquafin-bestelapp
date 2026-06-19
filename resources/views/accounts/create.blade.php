@extends('layouts.app')
@section('title', 'New Account')

@section('content')
    <div class="card" style="max-width:640px;margin:0 auto;">
        <div class="tabs">
            <a href="{{ route('admin.accounts.index') }}" class="tab">Overview</a>
        </div>

        <h1 class="h1">New Account</h1>

        <form x-data="{ sent: false }" @submit.prevent="sent = true; $el.submit()" id="create-form" method="POST" action="{{ route('admin.accounts.store') }}" class="form">
            @csrf

            <fieldset :disabled="sent">
                <div class="grid-2">
                    <div class="field">
                        <label for="first_name">First Name</label>
                        <input id="first_name" name="first_name" value="{{ old('first_name') }}" required
                            class="{{ $errors->has('first_name') ? 'is-invalid' : '' }}">
                        @error('first_name') <p class="error">{{ $message }}</p> @enderror
                    </div>
                    <div class="field">
                        <label for="last_name">Last Name</label>
                        <input id="last_name" name="last_name" value="{{ old('last_name') }}" required
                            class="{{ $errors->has('last_name') ? 'is-invalid' : '' }}">
                        @error('last_name') <p class="error">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="field">
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required
                        class="{{ $errors->has('email') ? 'is-invalid' : '' }}">
                    @error('email') <p class="error">{{ $message }}</p> @enderror
                </div>

                <div class="field">
                    <label for="phone_number">Telefoonnummer</label>
                    <input id="phone_number" type="tel" name="phone_number"
                        value="{{ old('phone_number') }}" required

                        class="{{ $errors->has('phone_number') ? 'is-invalid' : '' }}">
                    @error('phone_number') <p class="error">{{ $message }}</p> @enderror
                </div>

                <div class="field">
                    <label for="password">Password</label>
                    <div class="input-group">
                        <input id="password" type="password" name="password" required
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
                        <input id="password_confirmation" type="password" name="password_confirmation" required
                            class="{{ $errors->has('password_confirmation') ? 'is-invalid' : '' }}">
                        <button type="button" class="btn-toggle-pw" onclick="togglePw('password_confirmation', this)">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                            <span>Show</span>
                        </button>
                    </div>
                </div>

                <div class="field">
                    <label for="role_id">Role</label>
                    <select id="role_id" name="role_id" required
                            class="{{ $errors->has('role_id') ? 'is-invalid' : '' }}">
                        <option value="">— Select role —</option>
                        @foreach($roles as $r)
                            <option value="{{ $r->id }}" @selected(old('role_id') == $r->id)>{{ $r->name }}</option>
                        @endforeach
                    </select>
                    @error('role_id') <p class="error">{{ $message }}</p> @enderror
                </div>

                <div class="field">
                    <label for="site_id">Locatie: </label>
                    <select id="site_id" name="site_id" required
                            class="{{ $errors->has('site_id') ? 'is-invalid' : '' }}">
                        <option value="">— Select role —</option>
                        @foreach($sites as $s)
                            <option value="{{ $s->id }}" @selected(old('site_id') == $s->id)>{{ $s->description }}</option>
                        @endforeach
                    </select>
                    @error('site_id') <p class="error">{{ $message }}</p> @enderror
                </div>

                <div class="row-end">
                    <a href="{{ route('admin.accounts.index') }}" class="btn btn-outline">Cancel</a>
                    <button id="submit-btn" type="submit" class="btn btn-primary">Create User</button>
                </div>
            </fieldset>
        </form>
    </div>
@endsection

@push('scripts')
    @vite('resources/js/accounts/account-create.js')
@endpush
