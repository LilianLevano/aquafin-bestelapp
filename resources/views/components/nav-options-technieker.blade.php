@php
    // Default to empty string if type param is not set
    $type = $type ?? '';
@endphp

@if ($type === 'responsive')
    <x-responsive-nav-link :href="route('technieker.orders.index')" :active="request()->routeIs('technieker.orders.index')">
        {{ __('Bestellingen') }}
    </x-responsive-nav-link>

    <x-responsive-nav-link :href="route('technieker.orders.create')" :active="request()->routeIs('technieker.orders.create')">
        {{ __('Bestellen') }}
    </x-responsive-nav-link>
@else
    <x-nav-link :href="route('technieker.orders.index')" :active="request()->routeIs('technieker.orders.index')">
        {{ __('Bestellingen') }}
    </x-nav-link>

    <x-nav-link :href="route('technieker.orders.create')" :active="request()->routeIs('technieker.orders.create')">
        {{ __('Bestellen') }}
    </x-nav-link>
@endif
