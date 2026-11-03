<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-extrabold text-xl text-gray-900 leading-tight flex items-center gap-2">
                <span>❤️</span> {{ __('Barang Favorit Saya') }}
            </h2>
            <span class="text-xs font-semibold px-3 py-1 bg-rose-50 text-rose-700 rounded-full border border-rose-200">
                {{ $wishlists->total() }} Tersimpan
            </span>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-2xl text-sm font-medium">
                    ✓ {{ session('success') }}
                </div>
            @endif

            @if ($wishlists->count() > 0)
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 sm:gap-6">
                    @foreach ($wishlists as $product)
                        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden flex flex-col justify-between group hover:shadow-md transition">
                            <div class="relative">
                                <a href="{{ route('products.show', $product) }}">
                                    @if ($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->title }}" class="w-full h-44 object-cover group-hover:scale-105 transition duration-300">
                                    @else
                                        <div class="w-full h-44 bg-gray-100 flex items-center justify-center text-xs text-gray-400">Tidak ada gambar</div>
                                    @endif
                                </a>

                                <!-- Tombol Hapus Cepat dari Wishlist -->
                                <form action="{{ route('wishlist.toggle', $product) }}" method="POST" class="absolute top-3 right-3">
                                    @csrf
                                    <button type="submit" title="Hapus dari favorit" class="p-2 bg-white/90 hover:bg-rose-50 text-rose-600 rounded-full shadow-sm transition">
                                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>

                            <div class="p-4 flex flex-col justify-between flex-1">
                                <div>
                                    <span class="text-[10px] uppercase font-bold text-indigo-600 tracking-wider block mb-1">
                                        {{ $product->category->name }}
                                    </span>
                                    <a href="{{ route('products.show', $product) }}" class="font-bold text-sm text-gray-900 hover:text-indigo-600 line-clamp-1">
                                        {{ $product->title }}
                                    </a>
                                    <p class="font-extrabold text-indigo-700 text-sm mt-1">
                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                    </p>
                                </div>

                                <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between text-[11px] text-gray-500">
                                    <span class="truncate max-w-[120px]">{{ $product->user->name }}</span>
                                    @if ($product->status === 'available')
                                        <span class="text-emerald-600 font-semibold">Tersedia</span>
                                    @else
                                        <span class="text-gray-400 font-semibold">Terjual</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="pt-4">
                    {{ $wishlists->links() }}
                </div>
            @else
                <div class="bg-white rounded-3xl p-12 text-center border border-gray-100 shadow-sm max-w-md mx-auto">
                    <span class="text-5xl block mb-3">💔</span>
                    <h3 class="font-bold text-gray-900 text-base">Belum Ada Barang Favorit</h3>
                    <p class="text-xs text-gray-400 mt-1">Simpan barang yang kamu minati saat melihat katalog agar mudah ditemukan kembali nanti.</p>
                    <a href="{{ route('products.index') }}" class="inline-block mt-5 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-semibold transition">
                        Eksplor Katalog Barang
                    </a>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>