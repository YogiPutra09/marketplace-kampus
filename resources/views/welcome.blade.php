<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Marketplace Kampus') }} - Jual Beli Barang Bekas Mahasiswa</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-800">

    <!-- Navbar -->
    <header class="bg-white border-b border-gray-100 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-8">
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    <x-application-logo class="h-8 w-auto fill-current text-indigo-600" />
                    <span class="font-bold text-lg text-gray-900 tracking-tight">Pasar<span class="text-indigo-600">Kampus</span></span>
                </a>
                <nav class="hidden md:flex items-center gap-6 text-sm font-medium text-gray-600">
                    <a href="{{ route('products.index') }}" class="hover:text-indigo-600 transition">Katalog Lengkap</a>
                    @auth
                        <a href="{{ route('products.my') }}" class="hover:text-indigo-600 transition">Barang Saya</a>
                    @endauth
                </nav>
            </div>

            <div class="flex items-center gap-3">
                @auth
                    <a href="{{ route('products.create') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-medium transition shadow-sm">
                        + Jual Barang
                    </a>
                    <a href="{{ route('profile.edit') }}" class="text-sm font-medium text-gray-600 hover:text-indigo-600">
                        {{ Auth::user()->name }}
                    </a>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-medium text-gray-600 hover:text-indigo-600 px-3 py-2">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" class="text-sm font-medium px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition shadow-sm">
                        Daftar Akun
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="bg-gradient-to-b from-indigo-50 to-white py-16 sm:py-24 border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <span class="inline-block py-1 px-3 bg-indigo-100 text-indigo-700 text-xs font-semibold rounded-full uppercase tracking-wider mb-4">
                Khusus Lingkungan Kampus
            </span>
            <h1 class="text-3xl sm:text-5xl font-extrabold text-gray-900 tracking-tight leading-tight max-w-3xl mx-auto">
                Beli Hemat, Jual Cepat Bareng Teman Seperjuangan Kampus
            </h1>
            <p class="mt-4 text-base sm:text-lg text-gray-600 max-w-2xl mx-auto">
                Cari buku kuliah bekas, periferal kos, alat praktikum, hingga perlengkapan belajar tanpa repot biaya ongkir mahal. Cukup chat WhatsApp dan COD di kantin atau perpus!
            </p>

            <!-- Search Bar Hero -->
            <form action="{{ route('products.index') }}" method="GET" class="mt-8 max-w-xl mx-auto flex flex-col sm:flex-row gap-2">
                <input type="text" name="search" placeholder="Cari buku, kalkulator, monitor kos..." 
                       class="w-full px-4 py-3 rounded-xl border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                <button type="submit" class="w-full sm:w-auto px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-semibold transition shadow-sm shrink-0">
                    Cari Barang
                </button>
            </form>
        </div>
    </section>

    <!-- Kategori Cepat -->
    <section class="py-12 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Jelajahi Berdasarkan Kategori</h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3">
                @foreach ($categories as $category)
                    <a href="{{ route('products.index', ['category_id' => $category->id]) }}" 
                       class="p-4 rounded-xl border border-gray-200 hover:border-indigo-500 hover:shadow-md transition text-center group bg-white">
                        <div class="text-2xl mb-2 group-hover:scale-110 transition-transform">📦</div>
                        <span class="text-xs sm:text-sm font-medium text-gray-700 group-hover:text-indigo-600">
                            {{ $category->name }}
                        </span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Produk Terbaru -->
    <section class="py-12 bg-gray-50 border-t border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Barang Baru Masuk</h2>
                    <p class="text-sm text-gray-500">Koleksi teranyar yang siap dipinang sebelum kehabisan.</p>
                </div>
                <a href="{{ route('products.index') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-700">
                    Lihat Semua &rarr;
                </a>
            </div>

            @if ($latestProducts->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach ($latestProducts as $item)
                        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm hover:shadow-md transition flex flex-col">
                            <div class="aspect-square bg-gray-100 relative overflow-hidden">
                                @if ($item->image)
                                    <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-xs text-gray-400">
                                        No Image
                                    </div>
                                @endif
                                <span class="absolute top-2 left-2 bg-white/90 backdrop-blur-sm text-xs font-semibold px-2 py-0.5 rounded text-gray-700">
                                    {{ $item->category->name }}
                                </span>
                            </div>
                            <div class="p-4 flex flex-col flex-1 justify-between">
                                <div>
                                    <h3 class="font-semibold text-gray-900 text-sm line-clamp-2 hover:text-indigo-600">
                                        <a href="{{ route('products.show', $item) }}">
                                            {{ $item->title }}
                                        </a>
                                    </h3>
                                    <p class="text-xs text-gray-500 mt-1 capitalize">Kondisi: {{ $item->condition }}</p>
                                </div>
                                <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between">
                                    <span class="text-base font-bold text-indigo-600">
                                        Rp {{ number_format($item->price, 0, ',', '.') }}
                                    </span>
                                    <a href="{{ route('products.show', $item) }}" class="text-xs px-3 py-1.5 bg-gray-100 hover:bg-indigo-600 hover:text-white rounded-md font-medium transition">
                                        Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12 bg-white rounded-xl border border-dashed border-gray-300">
                    <p class="text-gray-500 text-sm">Belum ada barang yang dijual saat ini.</p>
                </div>
            @endif
        </div>
    </section>

    <!-- Nilai Keunggulan (Value Props) -->
    <section class="py-16 bg-white border-t border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
                <div class="p-6">
                    <div class="w-12 h-12 mx-auto bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center text-xl mb-4">💬</div>
                    <h3 class="font-bold text-gray-900 mb-2">Direct Chat WhatsApp</h3>
                    <p class="text-sm text-gray-600">Langsung negosiasi dengan penjual via pesan otomatis tanpa perantara pihak ketiga.</p>
                </div>
                <div class="p-6">
                    <div class="w-12 h-12 mx-auto bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center text-xl mb-4">🤝</div>
                    <h3 class="font-bold text-gray-900 mb-2">Bebas Ongkir (COD Kampus)</h3>
                    <p class="text-sm text-gray-600">Janji temu langsung di area fakultas, gedung rektorat, perpustakaan, atau kantin favorit.</p>
                </div>
                <div class="p-6">
                    <div class="w-12 h-12 mx-auto bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center text-xl mb-4">♻️</div>
                    <h3 class="font-bold text-gray-900 mb-2">Reuse & Re-educate</h3>
                    <p class="text-sm text-gray-600">Buku pelajaran dan modul praktikum tidak terbuang sia-sia dan bermanfaat bagi adik tingkat.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-400 py-8 border-t border-gray-800 text-center text-xs">
        <div class="max-w-7xl mx-auto px-4">
            <p>&copy; {{ date('Y') }} PasarKampus. Marketplace Barang Bekas Mahasiswa.</p>
        </div>
    </footer>

</body>
</html>