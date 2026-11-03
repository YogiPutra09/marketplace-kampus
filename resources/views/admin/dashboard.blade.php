<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2.5">
                    <span class="text-2xl">🛡️</span>
                    <div>
                        <h2 class="font-extrabold text-xl text-gray-900 tracking-tight">
                            {{ __('Panel Administrator PasarKampus') }}
                        </h2>
                        <p class="text-xs text-gray-500 mt-0.5">Pusat kendali moderasi barang, akun mahasiswa, dan data kategori</p>
                    </div>
                </div>
            </div>

            <!-- Tombol Aksi Header -->
            <div class="flex items-center gap-2.5">
                <!-- Tombol Kelola Mahasiswa -->
                <a href="{{ route('admin.users.index') }}" 
                class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-white hover:bg-gray-50 text-gray-700 text-xs font-semibold rounded-xl border border-gray-200 shadow-xs transition">
                    <span>👥</span>
                    <span>Kelola Mahasiswa</span>
                </a>

                <!-- Tombol Kelola Kategori -->
                <a href="{{ route('admin.categories.index') }}" 
                class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-xl shadow-xs transition">
                    <span>📁</span>
                    <span>Kelola Kategori</span>
                </a>
                
                <span class="inline-flex items-center px-3 py-1.5 bg-rose-50 border border-rose-200 text-rose-700 text-xs font-bold rounded-xl">
                    Admin Active
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- Alert Sukses --}}
            @if (session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-2xl text-sm font-medium flex items-center justify-between shadow-sm">
                    <span class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        {{ session('success') }}
                    </span>
                </div>
            @endif

            {{-- Seksi Laporan Masuk dari Mahasiswa --}}
            @if (isset($pendingReports) && $pendingReports->count() > 0)
                <div class="bg-rose-50/70 border border-rose-200 p-6 rounded-3xl shadow-sm">
                    <div class="flex items-center justify-between border-b border-rose-200/60 pb-3 mb-4">
                        <h3 class="font-bold text-sm text-rose-900 flex items-center gap-2">
                            <span>🚨</span> Laporan Pelanggaran Masuk ({{ $pendingReports->count() }})
                        </h3>
                        <span class="text-[11px] bg-rose-600 text-white font-semibold px-2.5 py-0.5 rounded-full shadow-sm">Perlu Ditinjau Segera</span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs bg-white rounded-2xl overflow-hidden border border-rose-100 shadow-sm">
                            <thead>
                                <tr class="bg-rose-100/60 text-rose-950 uppercase text-[10px] tracking-wider">
                                    <th class="py-3 px-4">Barang Dilaporkan</th>
                                    <th class="py-3 px-4">Penjual</th>
                                    <th class="py-3 px-4">Alasan & Detail</th>
                                    <th class="py-3 px-4">Pelapor</th>
                                    <th class="py-3 px-4 text-center">Tindakan Admin</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($pendingReports as $report)
                                    <tr class="hover:bg-rose-50/40 transition">
                                        <td class="py-3.5 px-4 font-semibold text-gray-800">
                                            <a href="{{ route('products.show', $report->product) }}" target="_blank" class="hover:underline text-indigo-600 block line-clamp-1">
                                                {{ $report->product->title }}
                                            </a>
                                        </td>
                                        <td class="py-3.5 px-4 text-gray-600">{{ $report->product->user->name }}</td>
                                        <td class="py-3.5 px-4">
                                            <span class="font-bold text-rose-600 block">{{ $report->reason }}</span>
                                            <span class="text-[11px] text-gray-500 italic block">{{ $report->details ?? '-' }}</span>
                                        </td>
                                        <td class="py-3.5 px-4 text-gray-600">
                                            <span class="font-medium text-gray-800">{{ $report->reporter->name }}</span>
                                            <span class="text-[10px] text-gray-400 block">{{ $report->created_at->diffForHumans() }}</span>
                                        </td>
                                        <td class="py-3.5 px-4 text-center">
                                            <div class="flex items-center justify-center gap-2">
                                                <form action="{{ route('admin.products.destroy', $report->product) }}" method="POST" onsubmit="return confirm('Takedown barang ini dan hapus permanen?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="px-3 py-1.5 bg-rose-600 hover:bg-rose-700 text-white font-semibold rounded-xl text-[10px] transition shadow-sm">
                                                        Takedown
                                                    </button>
                                                </form>

                                                <form action="{{ route('admin.reports.dismiss', $report) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-600 font-semibold rounded-xl text-[10px] transition">
                                                        Abaikan
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <!-- 4 Kartu Statistik -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                    <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block">Total Mahasiswa</span>
                    <p class="text-3xl font-black text-gray-900 mt-2">{{ $stats['total_users'] }}</p>
                    <span class="text-xs text-gray-400 mt-1 block">Akun pengguna aktif</span>
                </div>

                <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                    <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block">Barang Tersedia</span>
                    <p class="text-3xl font-black text-indigo-600 mt-2">{{ $stats['active_products'] }}</p>
                    <span class="text-xs text-gray-400 mt-1 block">Siap ditransaksikan</span>
                </div>

                <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                    <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block">Barang Terjual</span>
                    <p class="text-3xl font-black text-emerald-600 mt-2">{{ $stats['sold_products'] }}</p>
                    <span class="text-xs text-gray-400 mt-1 block">Transaksi sukses</span>
                </div>

                <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                    <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block">Total Ulasan</span>
                    <p class="text-3xl font-black text-amber-500 mt-2">{{ $stats['total_reviews'] }}</p>
                    <span class="text-xs text-gray-400 mt-1 block">Feedback antar mahasiswa</span>
                </div>
            </div>

            <!-- Bagian Moderasi Barang & Mahasiswa Baru -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                <!-- Kolom Kiri: Tabel Barang -->
                <div class="lg:col-span-8 bg-white p-6 sm:p-7 rounded-3xl border border-gray-100 shadow-sm">
                    <div class="border-b border-gray-100 pb-4 mb-5">
                        <h3 class="font-bold text-base text-gray-900">Moderasi Postingan Barang</h3>
                        <p class="text-xs text-gray-400 mt-0.5">Takedown iklan yang tidak pantas, spam, atau melanggar etika kampus.</p>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead>
                                <tr class="text-gray-400 uppercase border-b border-gray-100 text-[10px] tracking-wider">
                                    <th class="py-3 px-3">Barang</th>
                                    <th class="py-3 px-3">Penjual</th>
                                    <th class="py-3 px-3">Harga</th>
                                    <th class="py-3 px-3">Status</th>
                                    <th class="py-3 px-3 text-center">Aksi Moderasi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse ($recentProducts as $prod)
                                    <tr class="hover:bg-gray-50/70 transition">
                                        <td class="py-3.5 px-3 flex items-center gap-3">
                                            @if ($prod->image)
                                                <img src="{{ asset('storage/' . $prod->image) }}" class="w-10 h-10 rounded-xl object-cover shadow-xs border border-gray-100">
                                            @else
                                                <div class="w-10 h-10 rounded-xl bg-gray-100 text-gray-400 flex items-center justify-center text-[10px] font-medium">No Img</div>
                                            @endif
                                            <div class="min-w-0">
                                                <a href="{{ route('products.show', $prod) }}" target="_blank" class="font-bold text-gray-900 hover:text-indigo-600 block truncate max-w-xs">
                                                    {{ $prod->title }}
                                                </a>
                                                <span class="text-[11px] text-gray-400">{{ $prod->category->name }}</span>
                                            </div>
                                        </td>
                                        <td class="py-3.5 px-3 text-gray-700 font-medium">
                                            {{ $prod->user->name }}
                                        </td>
                                        <td class="py-3.5 px-3 font-extrabold text-gray-900">
                                            Rp {{ number_format($prod->price, 0, ',', '.') }}
                                        </td>
                                        <td class="py-3.5 px-3">
                                            @if ($prod->status === 'available')
                                                <span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 rounded-full font-bold text-[10px]">Tersedia</span>
                                            @else
                                                <span class="px-2.5 py-1 bg-gray-100 text-gray-600 rounded-full font-bold text-[10px]">Terjual</span>
                                            @endif
                                        </td>
                                        <td class="py-3.5 px-3 text-center">
                                            <form id="takedown-form-{{ $prod->id }}" action="{{ route('admin.products.destroy', $prod) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" 
                                                        onclick="confirmTakedown('{{ $prod->id }}', '{{ addslashes($prod->title) }}')"
                                                        class="px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 font-bold rounded-xl text-[11px] transition">
                                                    Hapus Paksa
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-8 text-center text-gray-400">Belum ada barang terdaftar.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Kolom Kanan: Mahasiswa Terbaru -->
                <div class="lg:col-span-4 bg-white p-6 sm:p-7 rounded-3xl border border-gray-100 shadow-sm">
                    <div class="border-b border-gray-100 pb-4 mb-5">
                        <h3 class="font-bold text-base text-gray-900">Mahasiswa Terbaru</h3>
                        <p class="text-xs text-gray-400 mt-0.5">Pengguna yang baru bergabung</p>
                    </div>

                    <div class="space-y-4">
                        @forelse ($recentUsers as $usr)
                            <div class="flex items-center gap-3">
                                @if ($usr->avatar)
                                    <img src="{{ asset('storage/' . $usr->avatar) }}" class="w-10 h-10 rounded-full object-cover border border-gray-100 shadow-xs">
                                @else
                                    <div class="w-10 h-10 rounded-full bg-indigo-50 text-indigo-700 flex items-center justify-center font-bold text-xs border border-indigo-100">
                                        {{ strtoupper(substr($usr->name, 0, 1)) }}
                                    </div>
                                @endif
                                <div class="min-w-0 flex-1">
                                    <h4 class="font-bold text-xs text-gray-900 truncate">{{ $usr->name }}</h4>
                                    <p class="text-[11px] text-gray-400 truncate">{{ $usr->faculty ?? 'Belum isi prodi' }}</p>
                                </div>
                                <span class="text-[10px] text-gray-400 shrink-0">
                                    {{ $usr->created_at->diffForHumans() }}
                                </span>
                            </div>
                        @empty
                            <p class="text-xs text-gray-400 py-4 text-center">Belum ada mahasiswa lain yang mendaftar.</p>
                        @endforelse
                    </div>
                </div>

            </div>

        </div>
    </div>

    <!-- Script SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmTakedown(productId, productTitle) {
            Swal.fire({
                title: 'Takedown Barang?',
                text: `Barang "${productTitle}" beserta fotonya akan dihapus permanen dari sistem.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus Sekarang!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-3xl p-6',
                    confirmButton: 'rounded-xl px-4 py-2 font-semibold text-xs',
                    cancelButton: 'rounded-xl px-4 py-2 font-semibold text-xs'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`takedown-form-${productId}`).submit();
                }
            });
        }
    </script>
</x-app-layout>