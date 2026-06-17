@use(App\Models\Role)
<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <a href="{{ route('/') }}" class="flex items-center">
                    <img class="logo" src="{{ asset('images/Logo_Aquafin.png') }}" alt="Aquafin logo" title="Aquafin logo">
                </a>
                <div class="space-x-6 sm:flex sm:ml-10" id="main-nav-bar">
                    @if(auth()->check())
                        {{-- Show navigation links based on user role --}}
                        <x-nav-link :href="route('/')" :active="request()->routeIs('/')">
                            {{ __('Dashboard') }}
                        </x-nav-link>

                        @php
                            $roleName = auth()->user()->role->name ?? null;
                        @endphp

                        @if($roleName === Role::ADMIN)

                            <x-nav-link :href="route('admin.accounts.index')" :active="request()->routeIs('technieker')">
                                 Accounts
                            </x-nav-link>

                            <x-nav-link :href="route('admin.roles.index')" :active="request()->routeIs('technieker')">
                             Rollen
                            </x-nav-link>

                            <x-nav-link :href="route('admin.materials.index')" :active="request()->routeIs('technieker')">
                                 Materialen
                            </x-nav-link>

                            <x-nav-link :href="route('admin.categories.index')" :active="request()->routeIs('technieker')">
                                Categorieën
                            </x-nav-link>

                            <x-nav-link href="{{route('admin.help-requests.index', 'all')}}"  :active="request()->routeIs('technieker')">
                                Hulpaanvraag
                            </x-nav-link>
                        @elseif($roleName === Role::TECHNIEKER)


                            <x-nav-link :href="route('orders.index')" :active="request()->routeIs('technieker.bestellen')">
                                Bestellingen
                            </x-nav-link>

                            <x-nav-link :href="route('orders.create')" :active="request()->routeIs('technieker.bestellen')">
                                Bestellen
                            </x-nav-link>



                        @endif
                    @endif
                </div>
            </div>

            @if (auth()->check())
                <!-- Settings Dropdown -->
                <div class="hidden sm:flex sm:items-center ml-6">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition">
                                <div>{{ Auth::user()->first_name }}</div>
                                <div class="ml-1">
                                    <svg class="fill-current h-4 w-4" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                 Profile
                            </x-dropdown-link>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                   Log Out
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            @endif

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex border-0 items-center justify-center p-2 rounded-md   focus:outline-none transition">
                    <svg xmlns="http://www.w3.org/2000/svg" height="40px" viewBox="0 -960 960 960" width="40px" fill="#000000"><path d="M120-693.33V-760h720v66.67H120ZM120-200v-66.67h720V-200H120Zm0-246.67v-66.66h720v66.66H120Z"/></svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation -->
    <div :class="{'block': open, 'hidden': !open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1" id="mobile-nav-bar">
            @if(auth()->check())
                <x-responsive-nav-link :href="route('/')" :active="request()->routeIs('/')">
                 Dashboard
                </x-responsive-nav-link>
                @php
                    $roleName = auth()->user()->role->name ?? null;
                @endphp

                @if($roleName === Role::ADMIN)

                    <x-responsive-nav-link :href="route('admin.accounts.index')" :active="request()->routeIs('technieker')">
                       Accounts
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('admin.roles.index')" :active="request()->routeIs('technieker')">
                        Rollen
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('admin.materials.index')" :active="request()->routeIs('technieker')">
                      Materialen
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('admin.categories.index')" :active="request()->routeIs('technieker')">
                        Categorieën
                    </x-responsive-nav-link>

                    <x-responsive-nav-link href="#">
                        Hulpaanvraag
                    </x-responsive-nav-link>
                @elseif($roleName === Role::TECHNIEKER)


                    <x-responsive-nav-link :href="route('orders.index')" :active="request()->routeIs('technieker.bestellen')">
                       Bestellingen
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('orders.create')" :active="request()->routeIs('technieker.bestellen')">
                         Bestellen
                    </x-responsive-nav-link>

                @endif
            @endif
        </div>

        @if (auth()->check())
            <!-- Responsive Settings Options -->
            <div class="pt-4 pb-1 border-t border-gray-200">
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->first_name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>
                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        @endif
    </div>
</nav>

@push('scripts')
    @vite('resources/js/navigation.js')
@endpush
