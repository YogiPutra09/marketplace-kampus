<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Daftar Barang Jualan Saya') }}
            </h2>
            <a href="{{ route('products.create') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg transition">
                + Tambah Barang
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                @if ($products->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50 border-b text-xs uppercase text-gray-500 font-semibold">
                                    <th class="p-4">Barang</th>
                                    <th class="p-4">Kategori</th>
                                    <th class="p-4">Harga</th>
                                    <th class="p-4">Status</th>
                                    <th class="p-4 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y text-sm">
                                @foreach ($products as $product)
                                    <tr class="hover:bg-gray-50">
                                        <td class="p-4 flex items-center gap-3">
                                            @if ($product->image)
                                                <img src="{{ asset('storage/' . $product->image) }}" class="w-12 h-12 object-cover rounded-md">
                                            @else
                                                <div class="w-12 h-12 bg-gray-100 flex items-center justify-center text-xs text-gray-400 rounded-md">
                                                    No Img
                                                </div>
                                            @endif
                                            <div>
                                                <a href="{{ route('products.show', $product) }}" class="font-semibold text-gray-800 hover:text-indigo-600 block">
                                                    {{ $product->title }}
                                                </a>
                                                <span class="text-xs text-gray-400 capitalize">Kondisi: {{ $product->condition }}</span>
                                            </div>
                                        </td>
                                        <td class="p-4 text-gray-600">{{ $product->category->name }}</td>
                                        <td class="p-4 font-semibold text-gray-800">
                                            Rp {{ number_format($product->price, 0, ',', '.') }}
                                        </td>
                                        <td class="p-4">
                                            @if ($product->status === 'available')
                                                <span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full font-medium">
                                                    Tersedia
                                                </span>
                                            @else
                                                <span class="px-2 py-1 bg-gray-200 text-gray-600 text-xs rounded-full font-medium">
                                                    Terjual
                                                </span>
                                            @endif
                                        </td>
                                        <td class="p-4">
                                            <div class="flex items-center justify-center gap-2">
                                                <!-- Toggle Status -->
                                                <form id="form-toggle-{{ $product->id }}" action="{{ route('products.toggleStatus', $product) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="button" 
                                                            onclick="confirmToggle({{ $product->id }}, '{{ $product->status }}')"
                                                            class="text-xs px-2.5 py-1.5 rounded border border-gray-300 hover:bg-gray-100 text-gray-700 transition">
                                                        {{ $product->status === 'available' ? 'Set Terjual' : 'Set Tersedia' }}
                                                    </button>
                                                </form>

                                                <!-- Edit -->
                                                <a href="{{ route('products.edit', $product) }}" 
                                                   class="inline-flex items-center justify-center text-xs px-2.5 py-1.5 rounded border border-transparent bg-blue-50 text-blue-600 hover:bg-blue-100 transition leading-normal">
                                                    Edit
                                                </a>

                                                <!-- Hapus -->
                                                <form id="form-delete-{{ $product->id }}" action="{{ route('products.destroy', $product) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" 
                                                            onclick="confirmDelete({{ $product->id }})"
                                                            class="text-xs px-2.5 py-1.5 rounded bg-red-50 text-red-600 hover:bg-red-100 transition">
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="p-4 border-t">
                        {{ $products->links() }}
                    </div>
                @else
                    <div class="p-12 text-center text-gray-500">
                        <p>Kamu belum mengunggah barang jualan apapun.</p>
                        <a href="{{ route('products.create') }}" class="mt-2 inline-block text-indigo-600 hover:underline text-sm font-semibold">
                            Mulai Jual Barang Sekarang &rarr;
                        </a>
                    </div>
                @endif
            </div>

        </div>
    </div>

    <!-- Script SweetAlert2 ditempatkan di sini sebelum layout ditutup -->
    <script>
        function confirmToggle(id, status) {
            const isAvailable = (status === 'available');
            
            Swal.fire({
                title: isAvailable ? 'Tandai Sebagai Terjual?' : 'Aktifkan Kembali Barang?',
                text: isAvailable 
                    ? 'Barang ini tidak akan ditampilkan lagi di katalog publik.' 
                    : 'Barang ini akan kembali muncul di katalog publik dan siap dibeli.',
                icon: isAvailable ? 'question' : 'info',
                showCancelButton: true,
                confirmButtonColor: isAvailable ? '#f59e0b' : '#10b981',
                cancelButtonColor: '#6b7280',
                confirmButtonText: isAvailable ? 'Ya, Set Terjual' : 'Ya, Aktifkan',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('form-toggle-' + id).submit();
                }
            });
        }

        function confirmDelete(id) {
            Swal.fire({
                title: 'Hapus Barang Ini?',
                text: 'Data dan gambar barang akan dihapus permanen. Tindakan ini tidak bisa dibatalkan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus Permanen',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('form-delete-' + id).submit();
                }
            });
        }
    </script>
</x-app-layout>