<section>
    <header class="border-b border-gray-100 pb-4">
        <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">
            <span>👤</span> {{ __('Informasi Profil') }}
        </h2>
        <p class="mt-1 text-sm text-gray-500">
            {{ __('Perbarui data diri, nomor kontak transaksi WhatsApp, dan identitas fakultas kampusmu.') }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <!-- Bagian Unggah Avatar (Live Preview) -->
        <div class="bg-gray-50 p-5 rounded-2xl border border-gray-200/80 flex flex-col sm:flex-row items-center gap-6">
            <div class="relative group shrink-0">
                @if ($user->avatar)
                    <img id="avatar-preview" 
                         src="{{ asset('storage/' . $user->avatar) }}" 
                         alt="{{ $user->name }}" 
                         class="w-20 h-20 rounded-2xl object-cover ring-4 ring-white shadow-md">
                @else
                    <div id="avatar-fallback" class="w-20 h-20 rounded-2xl bg-indigo-600 text-white flex items-center justify-center font-bold text-2xl shadow-md ring-4 ring-white">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <img id="avatar-preview" src="#" alt="Preview" class="w-20 h-20 rounded-2xl object-cover ring-4 ring-white shadow-md hidden">
                @endif
                <div class="absolute -bottom-1 -right-1 bg-white p-1 rounded-full shadow border text-xs">📷</div>
            </div>

            <div class="flex-1 text-center sm:text-left">
                <label for="avatar" class="cursor-pointer inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-xl text-xs font-semibold text-gray-700 shadow-sm hover:bg-gray-50 hover:border-indigo-400 transition">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                    </svg>
                    Pilih Foto Baru
                </label>
                <input id="avatar" name="avatar" type="file" accept="image/*" class="hidden" onchange="previewAvatar(event)">
                <p class="text-xs text-gray-400 mt-2">Format: JPG, PNG, atau WEBP. Maksimal ukuran 1MB.</p>
                <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
            </div>
        </div>

        <!-- Grid 2 Kolom untuk Input Data -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <!-- Nama Lengkap -->
            <div>
                <x-input-label for="name" :value="__('Nama Lengkap')" class="text-xs uppercase tracking-wider font-semibold text-gray-600" />
                <div class="relative mt-1">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">📛</span>
                    <x-text-input id="name" name="name" type="text" class="w-full pl-10 rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm shadow-sm" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                </div>
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            <!-- Email -->
            <div>
                <x-input-label for="email" :value="__('Alamat Email')" class="text-xs uppercase tracking-wider font-semibold text-gray-600" />
                <div class="relative mt-1">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">✉️</span>
                    <x-text-input id="email" name="email" type="email" class="w-full pl-10 rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm shadow-sm" :value="old('email', $user->email)" required autocomplete="username" />
                </div>
                <x-input-error class="mt-2" :messages="$errors->get('email')" />
            </div>

            <!-- Nomor WhatsApp -->
            <div>
                <x-input-label for="whatsapp_number" :value="__('Nomor WhatsApp')" class="text-xs uppercase tracking-wider font-semibold text-gray-600" />
                <div class="relative mt-1">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-emerald-600">💬</span>
                    <x-text-input id="whatsapp_number" name="whatsapp_number" type="text" class="w-full pl-10 rounded-xl border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 text-sm shadow-sm font-medium" :value="old('whatsapp_number', $user->whatsapp_number)" required placeholder="6281234567890" />
                </div>
                <p class="text-[11px] text-gray-400 mt-1">Gunakan format angka langsung diawali kode negara 62.</p>
                <x-input-error class="mt-2" :messages="$errors->get('whatsapp_number')" />
            </div>

            <!-- Fakultas / Prodi -->
            <div>
                <x-input-label for="faculty" :value="__('Fakultas / Program Studi')" class="text-xs uppercase tracking-wider font-semibold text-gray-600" />
                <div class="relative mt-1">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-indigo-500">🎓</span>
                    <x-text-input id="faculty" name="faculty" type="text" class="w-full pl-10 rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm shadow-sm" :value="old('faculty', $user->faculty)" placeholder="Contoh: S1 Informatika" />
                </div>
                <p class="text-[11px] text-gray-400 mt-1">Ditampilkan di kartu penjual agar pembeli tahu asal fakultasmu.</p>
                <x-input-error class="mt-2" :messages="$errors->get('faculty')" />
            </div>
        </div>

        <!-- Tombol Aksi Simpan -->
        <div class="flex items-center justify-between pt-4 border-t border-gray-100">
            <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl text-sm transition shadow-sm hover:shadow flex items-center gap-2">
                <span>💾</span> Simpan Perubahan
            </button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" 
                   x-show="show" 
                   x-transition 
                   x-init="setTimeout(() => show = false, 3000)" 
                   class="text-xs font-semibold text-emerald-600 bg-emerald-50 px-3 py-1.5 rounded-lg border border-emerald-200">
                    ✓ Profil berhasil diperbarui!
                </p>
            @endif
        </div>
    </form>

    <!-- Script Live Preview Foto Profil -->
    <script>
        function previewAvatar(event) {
            const input = event.target;
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('avatar-preview');
                    const fallback = document.getElementById('avatar-fallback');
                    
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    if (fallback) fallback.classList.add('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</section>