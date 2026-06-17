<x-nav-link :href="route('orders.index')" :active="request()->routeIs('orders.*')">
    {{ __('Orders') }}
</x-nav-link>