<x-app-layout>
    <x-slot name="header">
        Kelola Pengguna Sistem
    </x-slot>

    <div class="space-y-6" x-data="{
        showModal: false,
        isEdit: false,
        modalTitle: 'Tambah Pengguna Baru',
        formAction: '{{ route('pengguna.store') }}',
        id: '',
        name: '',
        email: '',
        role: 'Staf Keuangan',

        openCreate() {
            this.isEdit = false;
            this.modalTitle = 'Tambah Pengguna Baru';
            this.formAction = '{{ route('pengguna.store') }}';
            this.id = '';
            this.name = '';
            this.email = '';
            this.role = 'Staf Keuangan';
            this.showModal = true;
        },

        openEdit(user, userRole) {
            this.isEdit = true;
            this.modalTitle = 'Ubah Pengguna: ' + user.name;
            this.formAction = '/pengguna/update/' + user.id;
            this.id = user.id;
            this.name = user.name;
            this.email = user.email;
            this.role = userRole || 'Staf Keuangan';
            this.showModal = true;
        }
    }">
        <!-- Header & Action -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
            <div>
                <h2 class="text-xl font-black text-slate-900">Manajemen Pengguna & Hak Akses</h2>
                <p class="text-xs text-slate-500">Kelola akun staf, pimpinan, dan atur peran izin akses ke modul sistem</p>
            </div>
            <button @click="openCreate()" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-amber-400 font-bold text-xs sm:text-sm shadow-md transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                + Tambah Pengguna
            </button>
        </div>

        <!-- Filter & Search -->
        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
            <form method="GET" action="{{ route('pengguna.index') }}" class="flex flex-wrap items-center gap-3 flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email pengguna..." class="text-xs rounded-xl border-slate-200 focus:border-amber-500 flex-1 min-w-[200px]">

                <select name="role" class="text-xs rounded-xl border-slate-200 focus:border-amber-500">
                    <option value="">Semua Peran</option>
                    @foreach ($roles as $r)
                        <option value="{{ $r->name }}" {{ request('role') === $r->name ? 'selected' : '' }}>
                            {{ $r->name }}
                        </option>
                    @endforeach
                </select>

                <button type="submit" class="px-4 py-2 rounded-xl bg-slate-900 text-amber-400 font-bold text-xs hover:bg-slate-800">
                    Filter
                </button>
                <a href="{{ route('pengguna.index') }}" class="px-3 py-2 rounded-xl bg-slate-100 text-slate-600 font-bold text-xs hover:bg-slate-200">
                    Reset
                </a>
            </form>
        </div>

        <!-- Tabel Pengguna -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50 text-slate-600 font-bold border-b border-slate-200 uppercase tracking-wider text-[11px]">
                            <th class="px-5 py-3">Nama Pengguna</th>
                            <th class="px-5 py-3">Email Akun</th>
                            <th class="px-5 py-3">Peran (Role)</th>
                            <th class="px-5 py-3">Terdaftar Sejak</th>
                            <th class="px-5 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($users as $u)
                            @php
                                $userRole = $u->roles->pluck('name')->first() ?? '-';
                            @endphp
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="px-5 py-3 whitespace-nowrap">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-lg bg-slate-900 text-amber-400 flex items-center justify-center font-bold text-xs shrink-0">
                                            {{ strtoupper(substr($u->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-slate-900">{{ $u->name }}</div>
                                            @if ($u->id === auth()->id())
                                                <span class="text-[10px] text-emerald-600 font-bold">(Akun Anda Saat Ini)</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-3 whitespace-nowrap text-slate-600">
                                    {{ $u->email }}
                                </td>
                                <td class="px-5 py-3 whitespace-nowrap">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase 
                                        {{ $userRole === 'Admin' ? 'bg-amber-100 text-amber-800' : ($userRole === 'Staf Keuangan' ? 'bg-blue-100 text-blue-800' : 'bg-slate-100 text-slate-700') }}">
                                        {{ $userRole }}
                                    </span>
                                </td>
                                <td class="px-5 py-3 whitespace-nowrap text-slate-500">
                                    {{ $u->created_at ? $u->created_at->format('d/m/Y H:i') : '-' }} WIB
                                </td>
                                <td class="px-5 py-3 text-center whitespace-nowrap">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <button @click="openEdit({{ json_encode($u) }}, '{{ $userRole }}')" class="p-1 rounded-lg text-blue-600 hover:bg-blue-50" title="Edit Pengguna">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>

                                        @if ($u->id !== auth()->id())
                                            <form method="POST" action="{{ route('pengguna.destroy', $u) }}" onsubmit="return confirm('Hapus pengguna {{ $u->name }}?');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1 rounded-lg text-rose-600 hover:bg-rose-50" title="Hapus">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-8 text-center text-slate-400 italic">
                                    Tidak ada pengguna yang cocok.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($users->hasPages())
                <div class="px-5 py-4 border-t border-slate-100 bg-slate-50/50">
                    {{ $users->links() }}
                </div>
            @endif
        </div>

        <!-- Modal Tambah / Ubah Pengguna -->
        <div 
            x-show="showModal" 
            x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/70 backdrop-blur-sm"
        >
            <div 
                @click.outside="showModal = false"
                class="bg-white rounded-2xl max-w-md w-full p-6 sm:p-8 shadow-2xl border border-slate-100 space-y-5"
            >
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h3 class="text-base font-black text-slate-900" x-text="modalTitle"></h3>
                    <button @click="showModal = false" class="text-slate-400 hover:text-slate-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form :action="formAction" method="POST" class="space-y-4">
                    @csrf
                    <template x-if="isEdit">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Lengkap <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" x-model="name" required placeholder="Contoh: Bpk. Bambang Irawan" class="w-full text-xs rounded-xl border-slate-300 focus:border-amber-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Alamat Email <span class="text-rose-500">*</span></label>
                        <input type="email" name="email" x-model="email" required placeholder="user@cvgaruda.com" class="w-full text-xs rounded-xl border-slate-300 focus:border-amber-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Hak Akses (Peran) <span class="text-rose-500">*</span></label>
                        <select name="role" x-model="role" required class="w-full text-xs rounded-xl border-slate-300 focus:border-amber-500">
                            <option value="Admin">Admin / Pemilik (Akses Penuh)</option>
                            <option value="Staf Keuangan">Staf Keuangan (Input, Ubah & Laporan)</option>
                            <option value="Viewer">Viewer (Hanya Lihat Dashboard & Laporan)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">
                            Kata Sandi <span x-show="!isEdit" class="text-rose-500">*</span>
                            <span x-show="isEdit" class="text-[10px] text-slate-400 font-normal">(Kosongkan jika tidak diubah)</span>
                        </label>
                        <input type="password" name="password" :required="!isEdit" placeholder="Minimal 8 karakter" class="w-full text-xs rounded-xl border-slate-300 focus:border-amber-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Konfirmasi Kata Sandi</label>
                        <input type="password" name="password_confirmation" placeholder="Ketik ulang kata sandi" class="w-full text-xs rounded-xl border-slate-300 focus:border-amber-500">
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-4 border-t border-slate-100">
                        <button type="button" @click="showModal = false" class="px-4 py-2 rounded-xl border border-slate-200 text-slate-600 text-xs font-bold hover:bg-slate-50">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-amber-400 text-xs font-black shadow-md">
                            Simpan Pengguna
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

