<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('products.index')" :active="request()->routeIs('products.index')">
                        {{ __('Katalog') }}
                    </x-nav-link>

                    @auth
                        <x-nav-link :href="route('products.my')" :active="request()->routeIs('products.my')">
                            {{ __('Barang Saya') }}
                        </x-nav-link>

                        <!-- 1. TITIK DESKTOP: MENU KHUSUS ADMIN -->
                        @if (Auth::user()->isAdmin())
                            <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')" class="text-rose-600 hover:text-rose-700 font-semibold">
                                <span class="mr-1">🛡️</span> {{ __('Panel Admin') }}
                            </x-nav-link>
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Settings Dropdown (Auth) / Login & Register (Guest) -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center gap-2 px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                <!-- Tampilkan Foto Avatar jika ada -->
                                @if (Auth::user()->avatar)
                                    <img src="{{ asset('storage/' . Auth::user()->avatar) }}" class="w-7 h-7 rounded-full object-cover">
                                @else
                                    <div class="w-7 h-7 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center text-xs font-bold">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </div>
                                @endif

                                <div>{{ Auth::user()->name }}</div>

                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <!-- 2. TITIK DROPDOWN: LINK ADMIN -->
                            @if (Auth::user()->isAdmin())
                                <x-dropdown-link :href="route('admin.dashboard')" class="text-rose-600 font-semibold flex items-center gap-2">
                                    <span>🛡️</span> {{ __('Panel Admin') }}
                                </x-dropdown-link>
                                <div class="border-t border-gray-100"></div>
                            @endif

                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <div class="flex items-center gap-3">
                        <a href="{{ route('login') }}" class="text-sm font-medium text-gray-600 hover:text-indigo-600">
                            Log in
                        </a>
                        <a href="{{ route('register') }}" class="text-sm font-medium px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md transition shadow-sm">
                            Register
                        </a>
                    </div>
                @endauth
            </div>

            <!-- Hamburger (Mobile) -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu (Mobile) -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('products.index')" :active="request()->routeIs('products.index')">
                {{ __('Katalog') }}
            </x-responsive-nav-link>

            @auth
                <x-responsive-nav-link :href="route('products.my')" :active="request()->routeIs('products.my')">
                    {{ __('Barang Saya') }}
                </x-responsive-nav-link>

                <!-- 3. TITIK MOBILE: LINK MENU ADMIN -->
                @if (Auth::user()->isAdmin())
                    <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')" class="text-rose-600 font-semibold">
                        🛡️ {{ __('Panel Admin') }}
                    </x-responsive-nav-link>
                @endif
            @endauth
        </div>

        <!-- Responsive Auth Links -->
        <div class="pt-4 pb-3 border-t border-gray-200">
            @auth
                <div class="px-4 flex items-center gap-3">
                    @if (Auth::user()->avatar)
                        <img src="{{ asset('storage/' . Auth::user()->avatar) }}" class="w-10 h-10 rounded-full object-cover">
                    @else
                        <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-sm">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                    @endif
                    <div>
                        <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                        <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                    </div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            @else
                <div class="space-y-1 px-4">
                    <x-responsive-nav-link :href="route('login')">
                        {{ __('Log in') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('register')">
                        {{ __('Register') }}
                    </x-responsive-nav-link>
                </div>
            @endauth
        </div>
    </div>
</nav>