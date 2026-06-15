@php
    // Default to empty string if type param is not set
    $type = $type ?? '';
@endphp

@if ($type === 'responsive')
    <x-responsive-nav-link :href="route('admin.accounts.index')" :active="request()->routeIs('admin.accounts.index')">
        {{ __('Accounts') }}
    </x-responsive-nav-link>

    <x-responsive-nav-link :href="route('admin.roles.index')" :active="request()->routeIs('admin.roles.index')">
        {{ __('Rollen') }}
    </x-responsive-nav-link>

    <x-responsive-nav-link :href="route('admin.help-requests.index')" :active="request()->routeIs('admin.help-requests.index')">
        {{ __('Hulpaanvragen') }}
    </x-responsive-nav-link>

    <x-responsive-nav-link :href="route('admin.addresses.index')" :active="request()->routeIs('admin.addresses.index')">
        {{ __('Adressen') }}
    </x-responsive-nav-link>
@else
    <x-nav-link :href="route('admin.accounts.index')" :active="request()->routeIs('admin.accounts.index')">
        {{ __('Accounts') }}
    </x-nav-link>

    <x-nav-link :href="route('admin.roles.index')" :active="request()->routeIs('admin.roles.index')">
        {{ __('Rollen') }}
    </x-nav-link>

    <x-nav-link :href="route('admin.help-requests.index')" :active="request()->routeIs('admin.help-requests.index')">
        {{ __('Hulpaanvragen') }}
    </x-nav-link>

    <x-responsive-nav-link :href="route('admin.addresses.index')" :active="request()->routeIs('admin.addresses.index')">
        {{ __('Adressen') }}
    </x-responsive-nav-link>
@endif
