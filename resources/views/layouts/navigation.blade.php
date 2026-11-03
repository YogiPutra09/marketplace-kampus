<nav x-data="{ open: false }" class="bg-white/95 backdrop-blur-md border-b border-indigo-100/70 sticky top-0 z-50 shadow-xs transition-all">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <!-- Logo & Brand -->
                <div class="shrink-0 flex items-center gap-2">
                    <a href="{{ route('home') }}" class="flex items-center gap-2.5 group">
                        <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-indigo-600 via-indigo-500 to-violet-500 flex items-center justify-center text-white shadow-md shadow-indigo-500/20 group-hover:scale-105 transition-transform duration-200">
                            <span class="text-base font-black tracking-wider">PK</span>
                        </div>
                        <span class="font-extrabold text-lg tracking-tight bg-gradient-to-r from-indigo-700 via-indigo-900 to-gray-900 bg-clip-text text-transparent">
                            Pasar<span class="text-indigo-600">Kampus</span>
                        </span>
                    </a>
                </div>

                <!-- Navigation Links (Desktop) -->
                <div class="hidden space-x-2 sm:-my-px sm:ms-8 sm:flex items-center">
                    <!-- Katalog Lengkap -->
                    <a href="{{ route('products.index') }}" 
                       class="px-3.5 py-2 rounded-xl text-xs font-semibold transition-all duration-150 {{ request()->routeIs('products.*') && !request()->routeIs('products.my') ? 'bg-indigo-50 text-indigo-700 font-bold shadow-xs' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-50' }}">
                        <span>Katalog Lengkap</span>
                    </a>

                    @auth
                        <!-- Barang Saya -->
                        <a href="{{ route('products.my') }}" 
                           class="px-3.5 py-2 rounded-xl text-xs font-semibold transition-all duration-150 {{ request()->routeIs('products.my') ? 'bg-indigo-50 text-indigo-700 font-bold shadow-xs' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-50' }}">
                            <span>Barang Saya</span>
                        </a>

                        <!-- Favorit Saya -->
                        <a href="{{ route('wishlist.index') }}" 
                           class="px-3.5 py-2 rounded-xl text-xs font-semibold transition-all duration-150 flex items-center gap-1.5 {{ request()->routeIs('wishlist.*') ? 'bg-rose-50 text-rose-600 font-bold shadow-xs border border-rose-100' : 'text-gray-600 hover:text-rose-600 hover:bg-rose-50/50' }}">
                            <span class="text-rose-500">❤️</span>
                            <span>Favorit</span>
                        </a>
                    @endauth
                </div>
            </div>

            <!-- Settings Dropdown (Auth) / Login & Register (Guest) -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 gap-3">
                @auth
                    <!-- Tombol Jual Cepat di Navbar -->
                    <a href="{{ route('products.create') }}" 
                       class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-700 hover:to-violet-700 text-white rounded-xl text-xs font-bold shadow-sm shadow-indigo-500/20 hover:shadow-md transition-all duration-200">
                        <span>+</span>
                        <span>Jual Barang</span>
                    </a>

                    <x-dropdown align="right" width="56">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center gap-2.5 p-1.5 pe-3 border border-gray-200/80 rounded-2xl bg-white hover:border-indigo-300 hover:shadow-xs focus:outline-none transition-all duration-150">
                                @if (Auth::user()->avatar)
                                    <img src="{{ asset('storage/' . Auth::user()->avatar) }}" class="w-8 h-8 rounded-xl object-cover ring-2 ring-indigo-100">
                                @else
                                    <div class="w-8 h-8 rounded-xl bg-gradient-to-tr from-indigo-500 to-violet-500 text-white flex items-center justify-center text-xs font-bold shadow-xs">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </div>
                                @endif

                                <div class="text-left">
                                    <div class="text-xs font-bold text-gray-800 leading-tight">{{ Str::limit(Auth::user()->name, 12) }}</div>
                                    <div class="text-[10px] text-indigo-600 font-medium leading-none mt-0.5">Mahasiswa</div>
                                </div>

                                <svg class="fill-current h-3.5 w-3.5 text-gray-400 ms-0.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <!-- Info Pengguna di Dropdown -->
                            <div class="px-4 py-2.5 border-b border-gray-100 bg-gradient-to-br from-indigo-50/50 to-transparent">
                                <p class="text-xs font-bold text-gray-800 truncate">{{ Auth::user()->name }}</p>
                                <p class="text-[11px] text-gray-400 truncate">{{ Auth::user()->email }}</p>
                            </div>

                            @if (Auth::user()->isAdmin())
                                <x-dropdown-link :href="route('admin.dashboard')" class="text-rose-600 hover:bg-rose-50 font-bold flex items-center gap-2">
                                    <span>🛡️</span> {{ __('Panel Administrator') }}
                                </x-dropdown-link>
                                <div class="border-t border-gray-100"></div>
                            @endif

                            <x-dropdown-link :href="route('wishlist.index')" class="flex items-center gap-2 text-gray-700 hover:text-rose-600">
                                <span>❤️</span> {{ __('Barang Favorit') }}
                            </x-dropdown-link>

                            <x-dropdown-link :href="route('profile.edit')" class="flex items-center gap-2">
                                <span>⚙️</span> {{ __('Pengaturan Akun') }}
                            </x-dropdown-link>

                            <div class="border-t border-gray-100"></div>

                            <!-- Logout -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                        class="text-rose-600 hover:bg-rose-50 flex items-center gap-2"
                                        onclick="event.preventDefault(); this.closest('form').submit();">
                                    <span>🚪</span> {{ __('Keluar') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <div class="flex items-center gap-2">
                        <a href="{{ route('login') }}" class="px-4 py-2 text-xs font-bold text-gray-700 hover:text-indigo-600 rounded-xl hover:bg-gray-100/70 transition">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="px-4 py-2 bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-700 hover:to-violet-700 text-white text-xs font-bold rounded-xl shadow-sm shadow-indigo-500/20 transition hover:shadow">
                            Daftar Akun
                        </a>
                    </div>
                @endauth
            </div>

            <!-- Hamburger Button (Mobile) -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-xl text-gray-500 hover:text-indigo-600 hover:bg-indigo-50/70 focus:outline-none transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu (Mobile) -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden border-t border-indigo-50 bg-white/95 backdrop-blur-md">
        <div class="pt-3 pb-3 space-y-1.5 px-3">
            <x-responsive-nav-link :href="route('products.index')" :active="request()->routeIs('products.*') && !request()->routeIs('products.my')" class="rounded-xl">
                {{ __('Katalog Lengkap') }}
            </x-responsive-nav-link>

            @auth
                <x-responsive-nav-link :href="route('products.my')" :active="request()->routeIs('products.my')" class="rounded-xl">
                    {{ __('Barang Saya') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('wishlist.index')" :active="request()->routeIs('wishlist.*')" class="rounded-xl text-rose-600">
                    {{ __('Favorit ❤️') }}
                </x-responsive-nav-link>

                @if (Auth::user()->isAdmin())
                    <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')" class="text-rose-600 font-bold bg-rose-50/70 rounded-xl">
                        🛡️ {{ __('Panel Admin') }}
                    </x-responsive-nav-link>
                @endif

                <div class="pt-2">
                    <a href="{{ route('products.create') }}" class="w-full text-center block py-2.5 px-4 bg-gradient-to-r from-indigo-600 to-violet-600 text-white rounded-xl text-xs font-bold shadow-sm">
                        + Jual Barang
                    </a>
                </div>
            @endauth
        </div>

        <!-- Responsive Auth Links -->
        <div class="pt-4 pb-3 border-t border-gray-100 bg-gray-50/60">
            @auth
                <div class="px-4 flex items-center gap-3">
                    @if (Auth::user()->avatar)
                        <img src="{{ asset('storage/' . Auth::user()->avatar) }}" class="w-10 h-10 rounded-xl object-cover ring-2 ring-indigo-200">
                    @else
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-500 to-violet-500 text-white flex items-center justify-center font-bold text-sm shadow-xs">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                    @endif
                    <div class="min-w-0">
                        <div class="font-bold text-sm text-gray-800 truncate">{{ Auth::user()->name }}</div>
                        <div class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</div>
                    </div>
                </div>

                <div class="mt-3 space-y-1 px-3">
                    <x-responsive-nav-link :href="route('profile.edit')" class="rounded-xl">
                        {{ __('Pengaturan Profil') }}
                    </x-responsive-nav-link>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')"
                                class="text-rose-600 rounded-xl"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                            {{ __('Keluar') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            @else
                <div class="space-y-2 px-4 py-2">
                    <a href="{{ route('login') }}" class="block w-full text-center py-2 text-xs font-bold text-gray-700 bg-white border border-gray-200 rounded-xl">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" class="block w-full text-center py-2 text-xs font-bold text-white bg-indigo-600 rounded-xl shadow-sm">
                        Daftar Akun
                    </a>
                </div>
            @endauth
        </div>
    </div>
</nav>