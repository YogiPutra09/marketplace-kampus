<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'PasarKampus') }} - Jual Beli Barang Bekas Mahasiswa</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-slate-50 text-slate-800 selection:bg-indigo-600 selection:text-white">

    <!-- Top Accent Bar Pelangi Halus -->
    <div class="h-1.5 w-full bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500"></div>

    <!-- Navbar -->
    <header class="bg-white/90 backdrop-blur-md border-b border-indigo-100/70 sticky top-0 z-50 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-8">
                <!-- Logo & Brand -->
                <a href="{{ route('home') }}" class="flex items-center gap-2.5 group">
                    <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-indigo-600 via-violet-600 to-pink-500 flex items-center justify-center text-white shadow-md shadow-indigo-500/25 group-hover:scale-105 group-hover:rotate-1 transition-all duration-200">
                        <span class="text-base font-black tracking-wider">PK</span>
                    </div>
                    <span class="font-extrabold text-xl tracking-tight bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 bg-clip-text text-transparent">
                        Pasar<span class="text-slate-900">Kampus</span>
                    </span>
                </a>

                <!-- Desktop Menu -->
                <nav class="hidden md:flex items-center gap-2 text-xs font-bold">
                    <a href="{{ route('products.index') }}" class="px-3.5 py-2 rounded-xl text-slate-600 hover:text-indigo-600 hover:bg-indigo-50/70 transition">
                        Katalog Lengkap
                    </a>
                    @auth
                        <a href="{{ route('products.my') }}" class="px-3.5 py-2 rounded-xl text-slate-600 hover:text-indigo-600 hover:bg-indigo-50/70 transition">
                            Barang Saya
                        </a>
                        <a href="{{ route('wishlist.index') }}" class="px-3.5 py-2 rounded-xl text-rose-600 hover:bg-rose-50 transition flex items-center gap-1.5 border border-transparent hover:border-rose-100">
                            <span class="text-rose-500">❤️</span>
                            <span>Favorit</span>
                        </a>
                    @endauth
                </nav>
            </div>

            <!-- Right Controls -->
            <div class="flex items-center gap-3">
                @auth
                    <a href="{{ route('products.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 hover:from-indigo-700 hover:to-pink-700 text-white rounded-xl text-xs font-bold shadow-md shadow-indigo-500/20 hover:shadow-lg transition duration-200">
                        <span>+</span>
                        <span>Jual Barang</span>
                    </a>
                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 p-1.5 pe-3 border border-indigo-100 rounded-2xl bg-white hover:border-indigo-300 hover:shadow-xs transition">
                        @if (Auth::user()->avatar)
                            <img src="{{ asset('storage/' . Auth::user()->avatar) }}" class="w-7 h-7 rounded-xl object-cover ring-2 ring-indigo-200">
                        @else
                            <div class="w-7 h-7 rounded-xl bg-gradient-to-tr from-indigo-500 to-pink-500 text-white flex items-center justify-center text-xs font-bold shadow-xs">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                        @endif
                        <span class="text-xs font-bold text-slate-700 max-w-[100px] truncate">{{ Auth::user()->name }}</span>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="text-xs font-bold text-slate-700 hover:text-indigo-600 px-3.5 py-2 rounded-xl hover:bg-slate-100/70 transition">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" class="px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white text-xs font-bold rounded-xl shadow-md shadow-indigo-500/20 transition hover:shadow">
                        Daftar Akun
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Hero Section dengan Ambient Warna Dinamis -->
    <section class="relative overflow-hidden bg-gradient-to-b from-indigo-50/70 via-purple-50/40 to-slate-50 py-16 sm:py-24 border-b border-indigo-100/50">
        <!-- Lingkaran Cahaya Multi-Warna di Latar Belakang -->
        <div class="absolute -top-32 left-1/4 w-[450px] h-[450px] bg-gradient-to-tr from-indigo-400/20 to-pink-400/20 blur-3xl rounded-full pointer-events-none"></div>
        <div class="absolute top-1/2 right-1/4 w-[350px] h-[350px] bg-gradient-to-br from-cyan-400/15 to-purple-400/20 blur-3xl rounded-full pointer-events-none"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <span class="inline-flex items-center gap-1.5 py-1.5 px-4 bg-gradient-to-r from-indigo-50 to-pink-50 text-indigo-700 text-xs font-bold rounded-full uppercase tracking-wider mb-5 border border-indigo-200/60 shadow-xs">
                <span>🎓</span> Khusus Lingkungan Kampus
            </span>
            <h1 class="text-3xl sm:text-5xl font-extrabold text-slate-900 tracking-tight leading-tight max-w-3xl mx-auto">
                Beli Hemat, Jual Cepat Bareng <span class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 bg-clip-text text-transparent">Teman Seperjuangan</span>
            </h1>
            <p class="mt-4 text-sm sm:text-base text-slate-600 max-w-2xl mx-auto leading-relaxed">
                Cari buku kuliah bekas, periferal kos, alat praktikum, hingga perlengkapan belajar tanpa repot biaya ongkir mahal. Cukup chat WhatsApp dan janjian COD di kantin atau perpus!
            </p>

            <!-- Search Bar Floating dengan Border Aksen Gradasi -->
            <div class="mt-8 max-w-xl mx-auto p-1 rounded-2xl bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 shadow-xl shadow-indigo-500/10">
                <form action="{{ route('products.index') }}" method="GET" class="flex flex-col sm:flex-row gap-1 bg-white p-1.5 rounded-[14px]">
                    <input type="text" name="search" placeholder="Cari kalkulus, kalkulator Casio, helm, periferal..." 
                           class="w-full px-4 py-3 rounded-xl border-none focus:ring-0 text-sm text-slate-800 placeholder:text-slate-400">
                    <button type="submit" class="w-full sm:w-auto px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white rounded-xl text-xs font-bold transition shadow-sm shrink-0">
                        Cari Barang
                    </button>
                </form>
            </div>
        </div>
    </section>

    <!-- Kategori Cepat dengan Nuansa Warna-Warni Pastel -->
    <section class="py-14 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-7">
                <div>
                    <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">Jelajahi Berdasarkan Kategori</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Temukan barang kebutuhan sesuai klaster perkuliahan.</p>
                </div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3.5">
                @php
                    $colors = [
                        ['bg' => 'bg-indigo-50 text-indigo-600 group-hover:bg-indigo-600', 'border' => 'hover:border-indigo-400'],
                        ['bg' => 'bg-rose-50 text-rose-600 group-hover:bg-rose-600', 'border' => 'hover:border-rose-400'],
                        ['bg' => 'bg-amber-50 text-amber-600 group-hover:bg-amber-600', 'border' => 'hover:border-amber-400'],
                        ['bg' => 'bg-emerald-50 text-emerald-600 group-hover:bg-emerald-600', 'border' => 'hover:border-emerald-400'],
                        ['bg' => 'bg-cyan-50 text-cyan-600 group-hover:bg-cyan-600', 'border' => 'hover:border-cyan-400'],
                        ['bg' => 'bg-purple-50 text-purple-600 group-hover:bg-purple-600', 'border' => 'hover:border-purple-400'],
                    ];
                @endphp

                @foreach ($categories as $index => $category)
                    @php $palette = $colors[$index % count($colors)]; @endphp
                    <a href="{{ route('products.index', ['category_id' => $category->id]) }}" 
                       class="p-4 rounded-2xl border border-slate-200/90 {{ $palette['border'] }} hover:shadow-md transition text-center group bg-white flex flex-col items-center justify-center">
                        <div class="w-12 h-12 rounded-2xl {{ $palette['bg'] }} flex items-center justify-center text-xl mb-2.5 group-hover:scale-110 group-hover:text-white transition duration-200 shadow-xs">
                            📦
                        </div>
                        <span class="text-xs font-bold text-slate-700 group-hover:text-slate-900 transition">
                            {{ $category->name }}
                        </span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Produk Terbaru -->
    <section class="py-14 bg-gradient-to-b from-slate-50 to-indigo-50/20 border-t border-slate-200/60">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">Barang Baru Masuk 🔥</h2>
                    <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Koleksi teranyar yang siap dipinang sebelum kehabisan.</p>
                </div>
                <a href="{{ route('products.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-700 flex items-center gap-1 group">
                    <span>Lihat Semua</span>
                    <span class="group-hover:translate-x-0.5 transition-transform">&rarr;</span>
                </a>
            </div>

            @if ($latestProducts->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach ($latestProducts as $item)
                        <div class="bg-white rounded-2xl border border-slate-200/80 overflow-hidden shadow-xs hover:shadow-lg hover:border-indigo-200 transition duration-300 flex flex-col justify-between group">
                            <div>
                                <div class="aspect-square bg-slate-100 relative overflow-hidden">
                                    @if ($item->image)
                                        <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-xs text-slate-400">
                                            No Image
                                        </div>
                                    @endif
                                    <span class="absolute top-2.5 left-2.5 bg-white/95 backdrop-blur-md text-[10px] uppercase tracking-wider font-extrabold px-2.5 py-1 rounded-lg text-indigo-700 shadow-xs border border-indigo-100">
                                        {{ $item->category->name }}
                                    </span>
                                </div>

                                <div class="p-4 pb-0">
                                    <h3 class="font-bold text-slate-900 text-sm line-clamp-2 hover:text-indigo-600 transition">
                                        <a href="{{ route('products.show', $item) }}">
                                            {{ $item->title }}
                                        </a>
                                    </h3>
                                    <div class="flex items-center gap-2 mt-1.5">
                                        <span class="text-[10px] px-2 py-0.5 bg-slate-100 text-slate-600 font-semibold rounded-md capitalize">
                                            {{ str_replace('_', ' ', $item->condition) }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="p-4 pt-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-base font-extrabold text-indigo-600">
                                        Rp {{ number_format($item->price, 0, ',', '.') }}
                                    </span>
                                    <a href="{{ route('products.show', $item) }}" class="text-xs px-3.5 py-1.5 bg-indigo-50 text-indigo-700 hover:bg-indigo-600 hover:text-white rounded-xl font-bold transition shadow-2xs">
                                        Detail
                                    </a>
                                </div>

                                <!-- Baris Penjual & Indikator Tayangan -->
                                <div class="mt-3 pt-2.5 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-400">
                                    <span class="truncate max-w-[130px] font-medium text-slate-500">{{ $item->user->name }}</span>
                                    <span class="flex items-center gap-1 shrink-0 font-medium" title="Total dilihat">
                                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        {{ number_format($item->views ?? 0, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12 bg-white rounded-3xl border border-dashed border-slate-300">
                    <p class="text-slate-500 text-sm">Belum ada barang yang dijual saat ini.</p>
                </div>
            @endif
        </div>
    </section>

    <!-- Kartu Nilai Keunggulan Berwarna-Warni -->
    <section class="py-16 bg-white border-t border-slate-200/60">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
                <!-- Direct WhatsApp Card -->
                <div class="p-7 rounded-3xl bg-gradient-to-b from-indigo-50/60 to-white border border-indigo-100 shadow-xs hover:shadow-md transition">
                    <div class="w-14 h-14 mx-auto bg-indigo-600 text-white rounded-2xl flex items-center justify-center text-2xl mb-4 shadow-md shadow-indigo-600/20">💬</div>
                    <h3 class="font-extrabold text-slate-900 text-base mb-2">Direct Chat WhatsApp</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">Langsung negosiasi dengan penjual melalui pesan otomatis yang rapi tanpa potongan biaya aplikasi.</p>
                </div>

                <!-- COD Kampus Card -->
                <div class="p-7 rounded-3xl bg-gradient-to-b from-emerald-50/60 to-white border border-emerald-100 shadow-xs hover:shadow-md transition">
                    <div class="w-14 h-14 mx-auto bg-emerald-600 text-white rounded-2xl flex items-center justify-center text-2xl mb-4 shadow-md shadow-emerald-600/20">🤝</div>
                    <h3 class="font-extrabold text-slate-900 text-base mb-2">Bebas Ongkir (COD Kampus)</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">Janji temu langsung di area fakultas, gedung rektorat, perpustakaan, atau kantin favoritmu.</p>
                </div>

                <!-- Reuse Card -->
                <div class="p-7 rounded-3xl bg-gradient-to-b from-amber-50/60 to-white border border-amber-100 shadow-xs hover:shadow-md transition">
                    <div class="w-14 h-14 mx-auto bg-amber-500 text-white rounded-2xl flex items-center justify-center text-2xl mb-4 shadow-md shadow-amber-500/20">♻️</div>
                    <h3 class="font-extrabold text-slate-900 text-base mb-2">Reuse & Re-educate</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">Buku kuliah dan modul praktikum tidak terbuang sia-sia dan kembali berguna bagi adik tingkat.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer dengan Aksen Garis Gradasi -->
    <footer class="bg-slate-950 text-slate-400 py-12 border-t border-slate-800 text-center text-xs">
        <div class="max-w-7xl mx-auto px-4 space-y-3">
            <div class="flex items-center justify-center gap-2">
                <div class="w-6 h-6 rounded-lg bg-gradient-to-tr from-indigo-500 to-pink-500 flex items-center justify-center text-white text-[10px] font-black">PK</div>
                <span class="font-bold text-white text-sm">PasarKampus</span>
            </div>
            <p class="text-slate-400">Platform Jual Beli Barang Bekas Mahasiswa &bull; Mudah, Aman, Tanpa Ongkir</p>
            <p class="text-slate-500">&copy; {{ date('Y') }} PasarKampus. Seluruh hak cipta dilindungi.</p>
        </div>
    </footer>

</body>
</html>