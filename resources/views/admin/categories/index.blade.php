<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-xl text-gray-800 leading-tight flex items-center gap-2">
                <span>📁</span> {{ __('Manajemen Kategori Barang') }}
            </h2>
            <a href="{{ route('admin.dashboard') }}" class="text-xs font-semibold px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition">
                &larr; Kembali ke Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Pesan Notifikasi --}}
            @if (session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm font-medium">
                    ✓ {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm font-medium">
                    ⚠ {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                <!-- Form Tambah Kategori (Kolom Kiri 4/12) -->
                <div class="lg:col-span-4 bg-white p-6 rounded-2xl border border-gray-200/80 shadow-sm">
                    <h3 class="font-bold text-base text-gray-900 border-b border-gray-100 pb-3">Tambah Kategori Baru</h3>
                    
                    <form action="{{ route('admin.categories.store') }}" method="POST" class="mt-4 space-y-4">
                        @csrf
                        <div>
                            <x-input-label for="name" :value="__('Nama Kategori')" class="text-xs uppercase font-semibold text-gray-600" />
                            <x-text-input id="name" name="name" type="text" class="w-full mt-1 rounded-xl text-sm border-gray-300" placeholder="Contoh: Alat Tulis & Modul" required />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <button type="submit" class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl text-xs transition shadow-sm">
                            + Tambah Kategori
                        </button>
                    </form>
                </div>

                <!-- Tabel Daftar Kategori (Kolom Kanan 8/12) -->
                <div class="lg:col-span-8 bg-white p-6 rounded-2xl border border-gray-200/80 shadow-sm">
                    <div class="flex items-center justify-between border-b border-gray-100 pb-3 mb-4">
                        <h3 class="font-bold text-base text-gray-900">Daftar Kategori Saat Ini</h3>
                        <span class="text-xs text-gray-400 font-medium">Total: {{ $categories->count() }} Kategori</span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead>
                                <tr class="text-gray-400 uppercase border-b border-gray-100">
                                    <th class="py-3 px-3">Nama Kategori</th>
                                    <th class="py-3 px-3">Slug (URL)</th>
                                    <th class="py-3 px-3 text-center">Jumlah Barang</th>
                                    <th class="py-3 px-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse ($categories as $cat)
                                    <tr class="hover:bg-gray-50/80">
                                        <td class="py-3 px-3 font-bold text-gray-900">
                                            {{ $cat->name }}
                                        </td>
                                        <td class="py-3 px-3 text-gray-500 font-mono text-[11px]">
                                            {{ $cat->slug }}
                                        </td>
                                        <td class="py-3 px-3 text-center">
                                            <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold {{ $cat->products_count > 0 ? 'bg-indigo-50 text-indigo-700' : 'bg-gray-100 text-gray-500' }}">
                                                {{ $cat->products_count }} barang
                                            </span>
                                        </td>
                                        <td class="py-3 px-3 text-center">
                                            <div class="flex items-center justify-center gap-2">
                                                <!-- Tombol Edit Cepat (SweetAlert2 Popup Prompt) -->
                                                <button type="button" 
                                                        onclick="editCategory('{{ $cat->id }}', '{{ addslashes($cat->name) }}')"
                                                        class="px-2 py-1 bg-amber-50 hover:bg-amber-100 text-amber-700 rounded-lg text-[11px] font-semibold transition">
                                                    Edit
                                                </button>

                                                <!-- Form Update Tersembunyi -->
                                                <form id="edit-form-{{ $cat->id }}" action="{{ route('admin.categories.update', $cat) }}" method="POST" class="hidden">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="name" id="edit-input-{{ $cat->id }}">
                                                </form>

                                                <!-- Tombol Hapus -->
                                                <form id="delete-cat-{{ $cat->id }}" action="{{ route('admin.categories.destroy', $cat) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" 
                                                            onclick="confirmDeleteCategory('{{ $cat->id }}', '{{ addslashes($cat->name) }}', {{ $cat->products_count }})"
                                                            class="px-2 py-1 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg text-[11px] font-semibold transition">
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-6 text-center text-gray-400">Belum ada kategori yang terdaftar.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

        </div>
    </div>

    <!-- SweetAlert2 Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Modal Edit Kategori
        function editCategory(id, currentName) {
            Swal.fire({
                title: 'Ubah Nama Kategori',
                input: 'text',
                inputValue: currentName,
                showCancelButton: true,
                confirmButtonText: 'Simpan',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#4f46e5',
                inputValidator: (value) => {
                    if (!value || value.trim() === '') {
                        return 'Nama kategori tidak boleh kosong!';
                    }
                },
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'rounded-xl px-4 py-2 font-semibold text-xs',
                    cancelButton: 'rounded-xl px-4 py-2 font-semibold text-xs'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById(`edit-form-${id}`);
                    const input = document.getElementById(`edit-input-${id}`);
                    input.value = result.value.trim();
                    form.submit();
                }
            });
        }

        // Dialog Konfirmasi Hapus Kategori
        function confirmDeleteCategory(id, name, count) {
            if (count > 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Tidak Bisa Menghapus',
                    text: `Kategori "${name}" masih memiliki ${count} barang aktif. Pindahkan atau hapus barangnya terlebih dahulu!`,
                    confirmButtonColor: '#4f46e5',
                    customClass: { popup: 'rounded-2xl' }
                });
                return;
            }

            Swal.fire({
                title: 'Hapus Kategori?',
                text: `Kategori "${name}" akan dihapus permanen.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'rounded-xl px-4 py-2 font-semibold text-xs',
                    cancelButton: 'rounded-xl px-4 py-2 font-semibold text-xs'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`delete-cat-${id}`).submit();
                }
            });
        }
    </script>
</x-app-layout>