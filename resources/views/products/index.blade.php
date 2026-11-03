<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Katalog Barang Bekas Kampus') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Panel Filter & Pencarian Lanjutan -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 mb-8">
                <form action="{{ route('products.index') }}" method="GET" class="space-y-4">
                    
                    <!-- Baris 1: Kata Kunci & Kategori -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Cari Barang</label>
                            <input type="text" 
                                   name="search" 
                                   value="{{ request('search') }}" 
                                   placeholder="Contoh: Kalkulus, Keyboard mechanical, Helm..." 
                                   class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Kategori</label>
                            <select name="category_id" class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                                <option value="">Semua Kategori</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Baris 2: Kondisi, Rentang Harga & Sorting -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Kondisi</label>
                            <select name="condition" class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                                <option value="">Semua Kondisi</option>
                                <option value="like_new" {{ request('condition') == 'like_new' ? 'selected' : '' }}>Seperti Baru (Like New)</option>
                                <option value="good" {{ request('condition') == 'good' ? 'selected' : '' }}>Bagus (Good)</option>
                                <option value="fair" {{ request('condition') == 'fair' ? 'selected' : '' }}>Layak Pakai (Fair)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Harga Minimum (Rp)</label>
                            <input type="number" 
                                   name="min_price" 
                                   value="{{ request('min_price') }}" 
                                   placeholder="0" 
                                   min="0"
                                   class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Harga Maksimum (Rp)</label>
                            <input type="number" 
                                   name="max_price" 
                                   value="{{ request('max_price') }}" 
                                   placeholder="Contoh: 200000" 
                                   min="0"
                                   class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Urutkan</label>
                            <select name="sort" class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru</option>
                                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Harga: Terendah</option>
                                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Harga: Tertinggi</option>
                                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Terlama</option>
                            </select>
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="flex items-center justify-end gap-3 pt-2">
                        @if(request()->hasAny(['search', 'category_id', 'condition', 'min_price', 'max_price', 'sort']))
                            <a href="{{ route('products.index') }}" class="text-xs text-gray-500 hover:text-red-600 font-medium transition">
                                Reset Filter
                            </a>
                        @endif
                        <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-semibold shadow-sm transition">
                            Terapkan Filter
                        </button>
                    </div>

                </form>
            </div>

            <!-- Grid Daftar Barang -->
            @if ($products->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach ($products as $product)
                        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm hover:shadow-md transition flex flex-col">
                            <div class="aspect-square bg-gray-100 relative overflow-hidden">
                                @if ($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->title }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-xs text-gray-400">
                                        No Image
                                    </div>
                                @endif
                                <span class="absolute top-2 left-2 bg-white/90 backdrop-blur-sm text-xs font-semibold px-2 py-0.5 rounded text-gray-700">
                                    {{ $product->category->name }}
                                </span>
                            </div>
                            <div class="p-4 flex flex-col flex-1 justify-between">
                                <div>
                                    <h3 class="font-semibold text-gray-900 text-sm line-clamp-2 hover:text-indigo-600">
                                        <a href="{{ route('products.show', $product) }}">
                                            {{ $product->title }}
                                        </a>
                                    </h3>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="text-xs text-gray-400 capitalize">Kondisi: {{ str_replace('_', ' ', $product->condition) }}</span>
                                    </div>
                                </div>
                                <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between">
                                    <span class="text-base font-bold text-indigo-600">
                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                    </span>
                                    <a href="{{ route('products.show', $product) }}" class="text-xs px-3 py-1.5 bg-gray-100 hover:bg-indigo-600 hover:text-white rounded-md font-medium transition">
                                        Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $products->links() }}
                </div>
            @else
                <div class="bg-white p-12 text-center rounded-xl border border-dashed border-gray-300">
                    <p class="text-gray-500 text-sm">Tidak ada barang yang cocok dengan kriteria pencarian dan filter ini.</p>
                    <a href="{{ route('products.index') }}" class="mt-3 inline-block text-xs font-semibold text-indigo-600 hover:underline">
                        Bersihkan Semua Filter
                    </a>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>