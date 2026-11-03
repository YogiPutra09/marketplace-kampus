<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Jual Barang Bekas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <!-- Judul Barang -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700">Nama Barang</label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}" required
                            placeholder="Contoh: Buku Kalkulus Edisi 8 / Mouse Gaming Logitech"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('title')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kategori -->
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700">Kategori</label>
                        <select name="category_id" id="category_id" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Harga -->
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700">Harga (Rp)</label>
                        <input type="number" name="price" id="price" value="{{ old('price') }}" required min="0" step="1000"
                            placeholder="Contoh: 50000"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('price')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kondisi Barang -->
                    <div>
                        <label for="condition" class="block text-sm font-medium text-gray-700">Kondisi Barang</label>
                        <select name="condition" id="condition" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="like_new" {{ old('condition') == 'like_new' ? 'selected' : '' }}>Seperti Baru (Like New)</option>
                            <option value="good" {{ old('condition', 'good') == 'good' ? 'selected' : '' }}>Bagus / Layak Pakai (Good)</option>
                            <option value="fair" {{ old('condition') == 'fair' ? 'selected' : '' }}>Ada Minus / Pemakaian Wajar (Fair)</option>
                        </select>
                        @error('condition')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Foto Barang -->
                    <div>
                        <label for="image" class="block text-sm font-medium text-gray-700">Foto Barang (Maks. 2MB)</label>
                        <input type="file" name="image" id="image" accept="image/*"
                            class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        @error('image')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Deskripsi Barang -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi Lengkap & Minus</label>
                        <textarea name="description" id="description" rows="4" required
                            placeholder="Jelaskan kondisi fisik, kelengkapan, alasan dijual, atau minus jika ada..."
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tombol Simpan -->
                    <div class="flex items-center justify-end gap-3 pt-4 border-t">
                        <a href="{{ route('products.index') }}" class="text-sm text-gray-600 hover:underline">Batal</a>
                        <button type="submit" class="px-5 py-2.5 bg-indigo-600 text-white font-medium text-sm rounded-lg hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-300">
                            Terbitkan Barang
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</x-app-layout>