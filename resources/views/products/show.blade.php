<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2 text-sm text-gray-500">
            <a href="{{ route('home') }}" class="hover:text-indigo-600 transition">Beranda</a>
            <span>/</span>
            <a href="{{ route('products.index') }}" class="hover:text-indigo-600 transition">Katalog</a>
            <span>/</span>
            <span class="text-gray-800 font-medium truncate max-w-xs">{{ $product->title }}</span>
        </div>
    </x-slot>

    <div class="py-10 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Alert Notifikasi Biasa (Fallback) --}}
            @if (session('success'))
                <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm font-medium">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm font-medium">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Grid Konten Utama: 2 Kolom Seimbang -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                <!-- KOLOM KIRI (7/12): Foto & Deskripsi Lengkap -->
                <div class="lg:col-span-7 space-y-6">
                    <!-- Foto Barang -->
                    <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-200/80">
                        <div class="aspect-square w-full rounded-xl overflow-hidden bg-gray-100 flex items-center justify-center">
                            @if ($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" 
                                     alt="{{ $product->title }}" 
                                     class="w-full h-full object-cover">
                            @else
                                <div class="text-gray-400 text-sm flex flex-col items-center gap-2">
                                    <span class="text-3xl">📷</span>
                                    <span>Tidak ada foto barang</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Kotak Deskripsi -->
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200/80">
                        <h2 class="text-base font-bold text-gray-900 border-b border-gray-100 pb-3">Deskripsi Produk</h2>
                        <div class="mt-4 text-sm text-gray-700 leading-relaxed whitespace-pre-line">
                            {{ $product->description }}
                        </div>
                    </div>
                </div>

                <!-- KOLOM KANAN (5/12): Harga, Info Singkat & Kartu Penjual (Sticky) -->
                <div class="lg:col-span-5 space-y-6 sticky top-24">
                    
                    <!-- Kotak Info Harga & Judul -->
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200/80">
                        
                        <!-- Baris Kategori, Status & Indikator Tayangan (Views) -->
                        <div class="flex items-center justify-between gap-2 mb-3">
                            <div class="flex items-center gap-2">
                                <span class="py-1 px-3 bg-indigo-50 text-indigo-700 text-xs font-semibold rounded-full uppercase tracking-wider">
                                    {{ $product->category->name }}
                                </span>

                                <!-- Indikator Total Tayangan -->
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-gray-100 text-gray-600 font-medium rounded-full text-[11px]" title="Total dilihat">
                                    <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <span>{{ number_format($product->views ?? 0, 0, ',', '.') }}</span>
                                </span>
                            </div>
                            
                            @if ($product->status === 'available')
                                <span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 text-xs rounded-full font-semibold border border-emerald-200">
                                    ● Tersedia
                                </span>
                            @else
                                <span class="px-2.5 py-1 bg-gray-100 text-gray-600 text-xs rounded-full font-semibold border border-gray-200">
                                    ● Terjual
                                </span>
                            @endif
                        </div>

                        <h1 class="text-2xl font-extrabold text-gray-900 leading-snug">{{ $product->title }}</h1>
                        <p class="text-3xl font-extrabold text-indigo-600 mt-3">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </p>

                        <!-- Spesifikasi Ringkas -->
                        <div class="grid grid-cols-2 gap-3 mt-6 pt-5 border-t border-gray-100 text-xs">
                            <div class="bg-gray-50 p-3 rounded-xl">
                                <span class="text-gray-400 block font-medium">Kondisi</span>
                                <span class="font-bold text-gray-800 capitalize mt-0.5 block">
                                    {{ str_replace('_', ' ', $product->condition) }}
                                </span>
                            </div>
                            <div class="bg-gray-50 p-3 rounded-xl">
                                <span class="text-gray-400 block font-medium">Tanggal Tayang</span>
                                <span class="font-bold text-gray-800 mt-0.5 block">
                                    {{ $product->created_at->format('d M Y') }}
                                </span>
                            </div>
                        </div>

                        <!-- Aksi Pemilik (Jika yang melihat adalah penjual itu sendiri) -->
                        @if (Auth::id() === $product->user_id)
                            <div class="mt-6 pt-5 border-t border-gray-100 space-y-2">
                                <form action="{{ route('products.toggleStatus', $product) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="w-full py-2.5 px-4 {{ $product->status === 'available' ? 'bg-amber-500 hover:bg-amber-600' : 'bg-emerald-600 hover:bg-emerald-700' }} text-white font-semibold rounded-xl text-xs transition shadow-sm">
                                        {{ $product->status === 'available' ? 'Tandai Sebagai Terjual' : 'Aktifkan Kembali Barang' }}
                                    </button>
                                </form>
                                <div class="flex gap-2">
                                    <a href="{{ route('products.edit', $product) }}" class="flex-1 text-center py-2 bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-xl text-xs font-semibold transition">
                                        Edit Data
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Kotak Informasi Penjual, Chat & Wishlist -->
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200/80">
                        <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block mb-4">Informasi Penjual</span>
                        
                        <div class="flex items-center gap-4">
                            @if ($product->user->avatar)
                                <img src="{{ asset('storage/' . $product->user->avatar) }}" 
                                     alt="{{ $product->user->name }}" 
                                     class="w-14 h-14 rounded-2xl object-cover ring-2 ring-gray-100 shrink-0">
                            @else
                                <div class="w-14 h-14 rounded-2xl bg-indigo-600 text-white font-bold text-xl flex items-center justify-center shrink-0 shadow-sm">
                                    {{ strtoupper(substr($product->user->name, 0, 1)) }}
                                </div>
                            @endif

                            <div class="min-w-0 flex-1">
                                <h3 class="font-bold text-gray-900 text-base truncate">{{ $product->user->name }}</h3>
                                
                                <p class="text-xs text-indigo-600 font-medium flex items-center gap-1 mt-0.5 truncate">
                                    <span>🎓</span> {{ $product->user->faculty ?? 'Mahasiswa Kampus' }}
                                </p>

                                <div class="flex items-center gap-1.5 text-xs text-amber-500 font-bold mt-1">
                                    <span>★</span>
                                    <span class="text-gray-800">{{ $product->user->averageRating() }}</span>
                                    <span class="text-gray-400 font-normal">({{ $product->user->receivedReviews->count() }} ulasan)</span>
                                </div>
                            </div>
                        </div>

                        <!-- Tombol Hubungi WhatsApp -->
                        <div class="mt-6">
                            @php
                                $cleanWa = preg_replace('/[^0-9]/', '', $product->user->whatsapp_number ?? '');

                                if (str_starts_with($cleanWa, '0')) {
                                    $cleanWa = '62' . substr($cleanWa, 1);
                                }

                                $waMessage = "Halo Kak {$product->user->name}, saya melihat barang \"{$product->title}\" seharga Rp " . number_format($product->price, 0, ',', '.') . " di PasarKampus.\n\nApakah barangnya masih tersedia dan bisa COD di area kampus?\n" . url()->current();

                                $waLink = "https://wa.me/{$cleanWa}?text=" . rawurlencode($waMessage);
                            @endphp

                            @if ($product->user->whatsapp_number)
                                <a href="{{ $waLink }}" 
                                   target="_blank" 
                                   rel="noopener noreferrer"
                                   class="w-full py-3 px-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-2xl text-xs sm:text-sm flex items-center justify-center gap-2 shadow-sm transition hover:shadow duration-150">
                                    <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                                        <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.669-.699c.969.54 1.771.82 2.791.82 3.181 0 5.768-2.586 5.768-5.766 0-3.18-2.587-5.766-5.768-5.766zm9.969 5.766c0 5.518-4.482 10-10 10-1.748 0-3.391-.453-4.832-1.246l-5.168 1.354 1.381-5.043c-.886-1.492-1.381-3.23-1.381-5.065 0-5.518 4.482-10 10-10 5.518 0 10 4.482 10 10z"/>
                                    </svg>
                                    <span>Hubungi via WhatsApp</span>
                                </a>
                            @else
                                <button disabled 
                                        class="w-full py-3 px-4 bg-gray-100 text-gray-400 font-semibold rounded-2xl text-xs sm:text-sm flex items-center justify-center gap-2 cursor-not-allowed">
                                    <span>Kontak WhatsApp Belum Tersedia</span>
                                </button>
                            @endif
                        </div>

                        <!-- Tombol Wishlist -->
                        @auth
                            @if (Auth::id() !== $product->user_id)
                                <form action="{{ route('wishlist.toggle', $product) }}" method="POST" class="mt-3">
                                    @csrf
                                    @php $isFav = $product->isFavoritedBy(Auth::user()); @endphp
                                    <button type="submit" 
                                            class="w-full py-2.5 px-4 rounded-xl text-xs font-bold transition flex items-center justify-center gap-2 border {{ $isFav ? 'bg-rose-50 border-rose-200 text-rose-600 hover:bg-rose-100' : 'bg-white border-gray-200 text-gray-700 hover:bg-gray-50' }}">
                                        <svg class="w-4 h-4 {{ $isFav ? 'fill-current text-rose-600' : 'fill-none stroke-current' }}" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                        </svg>
                                        <span>{{ $isFav ? 'Hapus dari Favorit' : 'Simpan ke Favorit' }}</span>
                                    </button>
                                </form>
                            @endif
                        @endauth

                        <!-- Tombol Laporkan Barang -->
                        @auth
                            @if (Auth::id() !== $product->user_id)
                                <div class="text-center mt-3 pt-2 border-t border-gray-100">
                                    <button type="button" 
                                            onclick="openReportModal()"
                                            class="text-xs text-gray-400 hover:text-rose-600 transition inline-flex items-center gap-1 font-medium">
                                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24">
                                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
                                        </svg>
                                        Laporkan barang ini jika melanggar
                                    </button>
                                </div>
                            @endif
                        @endauth

                    </div>

                </div>
            </div>

            <!-- BAGIAN BAWAH: Ulasan Penjual -->
            <div class="mt-12 bg-white p-6 sm:p-8 rounded-2xl shadow-sm border border-gray-200/80">
                <div class="flex items-center justify-between border-b border-gray-100 pb-4 mb-6">
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Ulasan & Reputasi Penjual</h2>
                        <p class="text-xs text-gray-500 mt-0.5">Pengalaman transaksi mahasiswa dengan {{ $product->user->name }}</p>
                    </div>
                    <div class="text-right">
                        <span class="text-2xl font-extrabold text-gray-900">{{ $product->user->averageRating() }}</span>
                        <span class="text-xs text-amber-500 font-bold">★ / 5.0</span>
                    </div>
                </div>

                <!-- Form Beri Ulasan -->
                @auth
                    @if (Auth::id() !== $product->user_id)
                        <form action="{{ route('seller.reviews.store', $product->user) }}" method="POST" class="mb-8 p-5 bg-gray-50 rounded-2xl border border-gray-200/80">
                            @csrf
                            <h3 class="text-xs font-bold text-gray-800 uppercase tracking-wider mb-3">Tulis Penilaian Transaksi</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-3">
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Rating Bintang</label>
                                    <select name="rating" class="w-full text-xs rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                                        <option value="5">⭐⭐⭐⭐⭐ (5 - Sangat Puas)</option>
                                        <option value="4">⭐⭐⭐⭐ (4 - Ramah & Sesuai)</option>
                                        <option value="3">⭐⭐⭐ (3 - Cukup Standar)</option>
                                        <option value="2">⭐⭐ (2 - Kurang Puas)</option>
                                        <option value="1">⭐ (1 - Buruk / Kecewa)</option>
                                    </select>
                                </div>
                                <div class="sm:col-span-3">
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Catatan Pengalaman COD / Kondisi Barang</label>
                                    <input type="text" name="comment" placeholder="Contoh: Penjual responsif, barang sesuai deskripsi, COD lancar di perpustakaan..." required
                                           class="w-full text-xs rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                                </div>
                            </div>
                            <div class="text-right">
                                <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-semibold shadow-sm transition">
                                    Kirim Ulasan
                                </button>
                            </div>
                        </form>
                    @endif
                @endauth

                <!-- Riwayat Komentar Ulasan -->
                <div class="space-y-4">
                    @forelse ($product->user->receivedReviews()->latest()->take(5)->get() as $review)
                        <div class="p-4 rounded-xl bg-gray-50 border border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="font-bold text-sm text-gray-800">{{ $review->reviewer->name }}</span>
                                    <span class="text-amber-500 text-xs font-bold">{{ str_repeat('★', $review->rating) }}</span>
                                </div>
                                <p class="text-xs text-gray-600 mt-1">{{ $review->comment }}</p>
                            </div>
                            <span class="text-[11px] text-gray-400 shrink-0">
                                {{ $review->created_at->diffForHumans() }}
                            </span>
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-400 text-xs">
                            Belum ada ulasan untuk penjual ini. Jadilah yang pertama bertransaksi!
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- BAGIAN BAWAH: Rekomendasi Barang Sejenis -->
            @if (isset($relatedProducts) && $relatedProducts->count() > 0)
                <div class="mt-12">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-bold text-gray-900">Barang Sejenis Lainnya</h2>
                        <a href="{{ route('products.index', ['category_id' => $product->category_id]) }}" class="text-xs font-semibold text-indigo-600 hover:underline">
                            Lihat Kategori Ini &rarr;
                        </a>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                        @foreach ($relatedProducts as $item)
                            <div class="bg-white rounded-2xl border border-gray-200/80 overflow-hidden shadow-sm hover:shadow-md transition flex flex-col">
                                <div class="aspect-square bg-gray-100 relative overflow-hidden">
                                    @if ($item->image)
                                        <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-xs text-gray-400">
                                            No Image
                                        </div>
                                    @endif
                                </div>
                                <div class="p-4 flex flex-col flex-1 justify-between">
                                    <div>
                                        <h3 class="font-semibold text-gray-900 text-sm line-clamp-2 hover:text-indigo-600">
                                            <a href="{{ route('products.show', $item) }}">{{ $item->title }}</a>
                                        </h3>
                                        <p class="text-xs text-gray-400 capitalize mt-1">{{ str_replace('_', ' ', $item->condition) }}</p>
                                    </div>
                                    <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between">
                                        <span class="text-sm font-bold text-indigo-600">
                                            Rp {{ number_format($item->price, 0, ',', '.') }}
                                        </span>
                                        <a href="{{ route('products.show', $item) }}" class="text-xs px-2.5 py-1 bg-gray-100 hover:bg-indigo-600 hover:text-white rounded-lg font-medium transition">
                                            Lihat
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </div>

    <!-- Script SweetAlert2 untuk Modal Lapor & Alert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function openReportModal() {
            Swal.fire({
                title: 'Laporkan Pelanggaran Barang',
                html: `
                    <form id="reportForm" action="{{ route('products.report', $product) }}" method="POST" class="text-left text-xs space-y-3 mt-3">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        
                        <div>
                            <label class="block font-semibold text-gray-700 mb-1">Kategori Masalah:</label>
                            <select name="reason" id="swal-reason" class="w-full text-xs rounded-xl border-gray-300 focus:border-rose-500 focus:ring-rose-500" required>
                                <option value="Barang Terlarang / Ilegal">Barang Terlarang / Melanggar Aturan Kampus</option>
                                <option value="Indikasi Penipuan / Fake Post">Indikasi Penipuan / Akun Fiktif</option>
                                <option value="Harga Tidak Masuk Akal / Spam">Spam / Harga Palsu</option>
                                <option value="Deskripsi / Foto Mengandung SARA / Asusila">Foto / Teks Tidak Pantas</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>

                        <div>
                            <label class="block font-semibold text-gray-700 mb-1">Keterangan / Bukti Tambahan:</label>
                            <textarea name="details" id="swal-details" rows="3" placeholder="Jelaskan alasan pelaporan secara rinci..." 
                                    class="w-full text-xs rounded-xl border-gray-300 focus:border-rose-500 focus:ring-rose-500"></textarea>
                        </div>
                    </form>
                `,
                showCancelButton: true,
                confirmButtonText: 'Kirim Laporan',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#6b7280',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'rounded-xl px-4 py-2 font-semibold text-xs',
                    cancelButton: 'rounded-xl px-4 py-2 font-semibold text-xs'
                },
                preConfirm: () => {
                    const form = document.getElementById('reportForm');
                    form.submit();
                }
            });
        }

        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Sukses!',
                text: "{{ session('success') }}",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                customClass: { popup: 'rounded-2xl' }
            });
        @endif

        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Perhatian',
                text: "{{ session('error') }}",
                confirmButtonColor: '#4f46e5',
                customClass: { popup: 'rounded-2xl' }
            });
        @endif
    </script>
</x-app-layout>