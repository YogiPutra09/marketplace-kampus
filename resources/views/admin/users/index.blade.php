<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-2.5">
                <span class="text-2xl">👥</span>
                <div>
                    <h2 class="font-extrabold text-xl text-gray-900 tracking-tight">
                        {{ __('Manajemen Akun Mahasiswa') }}
                    </h2>
                    <p class="text-xs text-gray-500 mt-0.5">Pantau data kontak, jumlah barang jualan, dan status blokir akun mahasiswa.</p>
                </div>
            </div>

            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold rounded-xl transition">
                &larr; Kembali ke Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Pesan Notifikasi --}}
            @if (session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-2xl text-sm font-medium flex items-center gap-2 shadow-sm">
                    ✓ {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-2xl text-sm font-medium flex items-center gap-2 shadow-sm">
                    ⚠ {{ session('error') }}
                </div>
            @endif

            <!-- Kontainer Tabel & Form Pencarian -->
            <div class="bg-white p-6 sm:p-7 rounded-3xl border border-gray-100 shadow-sm space-y-5">
                
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-gray-100 pb-5">
                    <div>
                        <h3 class="font-bold text-base text-gray-900">Daftar Mahasiswa Terdaftar</h3>
                        <p class="text-xs text-gray-400">Total: {{ $users->total() }} Mahasiswa</p>
                    </div>

                    <form action="{{ route('admin.users.index') }}" method="GET" class="flex items-center gap-2">
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}" 
                               placeholder="Cari nama, prodi, WA, email..." 
                               class="text-xs rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 w-64 shadow-xs">
                        <button type="submit" class="px-3.5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-semibold transition shadow-xs">
                            Cari
                        </button>
                        @if (request('search'))
                            <a href="{{ route('admin.users.index') }}" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl text-xs font-semibold transition">
                                Reset
                            </a>
                        @endif
                    </form>
                </div>

                <!-- Tabel Data Mahasiswa -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="text-gray-400 uppercase border-b border-gray-100 text-[10px] tracking-wider">
                                <th class="py-3 px-3">Mahasiswa</th>
                                <th class="py-3 px-3">Fakultas / Prodi</th>
                                <th class="py-3 px-3">WhatsApp</th>
                                <th class="py-3 px-3 text-center">Jualan Aktif</th>
                                <th class="py-3 px-3 text-center">Status Akun</th>
                                <th class="py-3 px-3 text-center">Aksi Moderasi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($users as $u)
                                <tr class="hover:bg-gray-50/70 transition">
                                    <td class="py-3.5 px-3 flex items-center gap-3">
                                        @if ($u->avatar)
                                            <img src="{{ asset('storage/' . $u->avatar) }}" class="w-9 h-9 rounded-full object-cover border border-gray-100 shadow-xs">
                                        @else
                                            <div class="w-9 h-9 rounded-full bg-indigo-50 text-indigo-700 flex items-center justify-center font-bold text-xs border border-indigo-100">
                                                {{ strtoupper(substr($u->name, 0, 1)) }}
                                            </div>
                                        @endif
                                        <div>
                                            <span class="font-bold text-gray-900 block">{{ $u->name }}</span>
                                            <span class="text-[11px] text-gray-400">{{ $u->email }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3.5 px-3 text-gray-600">
                                        {{ $u->faculty ?? 'Belum diisi' }}
                                    </td>
                                    <td class="py-3.5 px-3">
                                        @if ($u->whatsapp_number)
                                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $u->whatsapp_number) }}" target="_blank" class="text-emerald-600 hover:underline font-mono text-[11px] font-semibold flex items-center gap-1">
                                                <span>💬</span> {{ $u->whatsapp_number }}
                                            </a>
                                        @else
                                            <span class="text-gray-400 italic">Belum ada</span>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-3 text-center">
                                        <span class="px-2.5 py-0.5 rounded-full font-bold text-[10px] {{ $u->products_count > 0 ? 'bg-indigo-50 text-indigo-700' : 'bg-gray-100 text-gray-500' }}">
                                            {{ $u->products_count }} barang
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-3 text-center">
                                        @if ($u->is_banned)
                                            <span class="px-2.5 py-1 bg-rose-50 text-rose-700 rounded-full font-bold text-[10px] border border-rose-200">
                                                🚫 Diblokir
                                            </span>
                                        @else
                                            <span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 rounded-full font-bold text-[10px]">
                                                Aktif
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-3 text-center">
                                        <form action="{{ route('admin.users.toggleBan', $u) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            @if ($u->is_banned)
                                                <button type="submit" 
                                                        onclick="return confirm('Aktifkan kembali akun {{ $u->name }}?')" 
                                                        class="px-3 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-bold rounded-xl text-[11px] transition">
                                                    Buka Blokir
                                                </button>
                                            @else
                                                <button type="submit" 
                                                        onclick="return confirm('Peringatan: Blokir akun {{ $u->name }}? Mahasiswa ini tidak akan bisa login!')" 
                                                        class="px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 font-bold rounded-xl text-[11px] transition">
                                                    Blokir Akun
                                                </button>
                                            @endif
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-8 text-center text-gray-400">Tidak ada mahasiswa yang cocok dengan pencarian.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Paginasi Halaman -->
                <div class="pt-4 border-t border-gray-100">
                    {{ $users->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>