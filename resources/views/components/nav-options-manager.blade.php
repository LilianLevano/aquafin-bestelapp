

@php
    // Default to empty string if type param is not set
    $type = $type ?? '';
@endphp

@if ($type === 'responsive')
    <x-responsive-nav-link :href="route('manager.orders.index')" :active="request()->routeIs('manager.orders.index')">
        {{ __('Bestellingen') }}
    </x-responsive-nav-link>
@else
    <x-nav-link :href="route('manager.orders.index')" :active="request()->routeIs('manager.orders.index')">
        {{ __('Bestellingen') }}
    </x-nav-link>
@endif
