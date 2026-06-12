<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <div class="field">
                <label for="first_name">First Name</label>
                <input id="first_name" name="first_name"
                       value="{{ old('first_name', $user->first_name) }}" required
                       data-original="{{ $user->first_name }}"
                       class="{{ $errors->has('first_name') ? 'is-invalid' : '' }}">
                @error('first_name') <p class="error">{{ $message }}</p> @enderror
            </div>

        </div>

        <div>
            <div class="field">
                <label for="last_name">Last Name</label>
                <input id="last_name" name="last_name"
                       value="{{ old('last_name', $user->last_name) }}" required
                       data-original="{{ $user->last_name }}"
                       class="{{ $errors->has('last_name') ? 'is-invalid' : '' }}">
                @error('last_name') <p class="error">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <div class="field">
                <label for="email">Mail</label>
                <input id="email" type="email" name="email"
                       value="{{ old('email', $user->email) }}" required
                       data-original="{{ $user->email }}"
                       class="{{ $errors->has('email') ? 'is-invalid' : '' }}">
                @error('email') <p class="error">{{ $message }}</p> @enderror
            </div>

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>


        <div class="field">
            <label for="phone_number">Telefoonnummer</label>
            <input id="phone_number" type="tel" name="phone_number"
                   value="{{ old('phone_number', $user->phone_number) }}" required
                   data-original="{{ $user->phone_number }}"
                   class="{{ $errors->has('phone_number') ? 'is-invalid' : '' }}">
            @error('phone_number') <p class="error">{{ $message }}</p> @enderror
        </div>

        <div class="flex items-center gap-4">

            <button type="submit" class="btn-primary">Save</button>


            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved') }}.</p>
            @endif
        </div>
    </form>
</section>
