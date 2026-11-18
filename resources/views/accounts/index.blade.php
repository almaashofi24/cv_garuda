<x-app-layout>
    <x-slot name="header">
        Data Akun (7 Golongan Akuntansi)
    </x-slot>

    <div class="space-y-6" x-data="{
        showModalCreate: false,
        showModalEdit: false,
        editAction: '',
        editData: {
            kode: '',
            golongan: '',
            nama: '',
            saldo_normal: 'Debit',
            saldo_awal: 0,
            deskripsi: ''
        },
        openEdit(acc) {
            this.editData = { ...acc };
            this.editAction = '/akun/update/' + acc.id;
            this.showModalEdit = true;
        }
    }">
        <!-- Header & Action Card -->
        <div class="bg-white p-5 sm:p-6 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl sm:text-2xl font-black text-slate-900">Master 7 Golongan Akun</h2>
                <p class="text-xs text-slate-500 mt-0.5">Daftar bagan akun akuntansi (Chart of Accounts) standar CV. Garuda Raja Perkasa Duabelas.</p>
            </div>
            <button @click="showModalCreate = true" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold text-xs sm:text-sm shadow-md shadow-amber-500/20 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                + Tambah Akun Baru
            </button>
        </div>

        <!-- Tab 7 Golongan Akuntansi -->
        <div class="bg-white p-2 rounded-2xl border border-slate-200/80 shadow-sm overflow-x-auto">
            <div class="flex items-center gap-1 min-w-max text-xs font-bold">
                <a href="{{ route('akun.index') }}" class="px-3.5 py-2 rounded-xl transition {{ !request('golongan') ? 'bg-slate-900 text-amber-400 font-black' : 'text-slate-600 hover:bg-slate-100' }}">
                    Semua Akun ({{ count($accounts) }})
                </a>
                @foreach ($golonganList as $golNama => $golKode)
                    <a href="{{ route('akun.index', ['golongan' => $golNama]) }}" class="px-3 py-2 rounded-xl transition {{ request('golongan') === $golNama ? 'bg-slate-900 text-amber-400 font-black' : 'text-slate-600 hover:bg-slate-100' }}">
                        {{ $golNama }}
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Tabel Daftar Akun -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="p-4 sm:p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <form method="GET" action="{{ route('akun.index') }}" class="flex items-center gap-2 max-w-md w-full">
                    @if(request('golongan'))
                        <input type="hidden" name="golongan" value="{{ request('golongan') }}">
                    @endif
                    <div class="relative flex-1">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode atau nama akun..." class="w-full text-xs rounded-xl border-slate-300 focus:border-amber-500 focus:ring-amber-500 pl-8">
                        <svg class="w-4 h-4 text-slate-400 absolute left-2.5 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <button type="submit" class="px-3 py-2 rounded-xl bg-slate-900 text-amber-400 font-bold text-xs">Cari</button>
                    @if(request('search'))
                        <a href="{{ route('akun.index', request()->only('golongan')) }}" class="px-2.5 py-2 text-xs text-slate-500 hover:text-slate-700">Clear</a>
                    @endif
                </form>

                <div class="text-xs text-slate-500">
                    Menampilkan <strong class="text-slate-900">{{ count($accounts) }} akun</strong> akuntansi.
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50 text-slate-600 font-bold border-b border-slate-200 uppercase tracking-wider text-[11px]">
                            <th class="px-5 py-3 w-24">Kode</th>
                            <th class="px-5 py-3 w-40">Golongan</th>
                            <th class="px-5 py-3">Nama Akun di Web</th>
                            <th class="px-5 py-3 w-28 text-center">Jenis Saldo</th>
                            <th class="px-5 py-3 text-right w-36">Saldo Awal</th>
                            <th class="px-5 py-3 text-right w-40">Saldo Terkini</th>
                            <th class="px-5 py-3 text-center w-28">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($accounts as $acc)
                            <tr class="hover:bg-slate-50/70 transition">
                                <td class="px-5 py-3 whitespace-nowrap">
                                    <span class="px-2 py-0.5 rounded-md bg-slate-900 text-amber-400 font-mono font-bold text-xs">
                                        {{ $acc->kode }}
                                    </span>
                                </td>
                                <td class="px-5 py-3 whitespace-nowrap text-slate-600 font-semibold">
                                    {{ $acc->golongan }}
                                </td>
                                <td class="px-5 py-3">
                                    <div class="font-bold text-slate-900 text-sm">{{ $acc->nama }}</div>
                                    @if($acc->deskripsi)
                                        <div class="text-[11px] text-slate-400 truncate max-w-xs">{{ $acc->deskripsi }}</div>
                                    @endif
                                </td>
                                <td class="px-5 py-3 whitespace-nowrap text-center">
                                    <span class="inline-block px-2.5 py-0.5 rounded-full font-bold text-[11px] {{ $acc->saldo_normal === 'Debit' ? 'bg-emerald-100 text-emerald-800' : ($acc->saldo_normal === 'Kredit' ? 'bg-blue-100 text-blue-800' : 'bg-slate-200 text-slate-700') }}">
                                        {{ $acc->saldo_normal }}
                                    </span>
                                </td>
                                <td class="px-5 py-3 whitespace-nowrap text-right font-medium text-slate-600">
                                    {{ $acc->formatted_saldo_awal }}
                                </td>
                                <td class="px-5 py-3 whitespace-nowrap text-right font-black text-slate-900">
                                    {{ $acc->formatted_saldo }}
                                </td>
                                <td class="px-5 py-3 whitespace-nowrap text-center">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <!-- Tombol Edit Akun -->
                                        <button 
                                            type="button" 
                                            @click="openEdit({{ Js::from($acc) }})" 
                                            class="p-1.5 rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-700 transition" 
                                            title="Ubah Akun"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>

                                        <!-- Tombol Hapus Akun -->
                                        @if(!$acc->is_locked)
                                            <form method="POST" action="{{ route('akun.destroy', $acc) }}" onsubmit="return confirm('Hapus akun {{ $acc->kode }} - {{ $acc->nama }}?');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1.5 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 transition" title="Hapus Akun">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        @else
                                            <span class="p-1.5 text-slate-300" title="Akun inti sistem dilindungi">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                                </svg>
                                            </span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-8 text-center text-slate-400 italic">
                                    Tidak ada data akun yang ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal Tambah Akun Baru -->
        <div x-show="showModalCreate" x-cloak class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div @click="showModalCreate = false" class="fixed inset-0 transition-opacity bg-slate-950/75 backdrop-blur-sm"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

                <div class="inline-block w-full max-w-lg p-6 my-8 text-left align-middle transition-all transform bg-white rounded-2xl shadow-xl sm:my-8 relative">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                        <h3 class="text-lg font-black text-slate-900">+ Tambah Akun Baru</h3>
                        <button @click="showModalCreate = false" class="text-slate-400 hover:text-slate-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <form method="POST" action="{{ route('akun.store') }}" class="mt-4 space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Golongan Akun <span class="text-rose-500">*</span></label>
                            <select name="golongan" required class="w-full text-xs font-semibold rounded-xl border-slate-300 focus:border-amber-500">
                                <option value="1. Harta">1. Harta (Kode 1-100 | Saldo Normal: Debit)</option>
                                <option value="2. Utang">2. Utang (Kode 2-100 | Saldo Normal: Kredit)</option>
                                <option value="3. Modal">3. Modal (Kode 3-100 | Saldo Normal: Kredit)</option>
                                <option value="4. Pendapatan">4. Pendapatan (Kode 4-100 | Saldo Normal: Kredit)</option>
                                <option value="5. Beban" selected>5. Beban (Kode 5-100 | Saldo Normal: Debit)</option>
                                <option value="6. Prive">6. Prive (Kode 6-100 | Saldo Normal: Debit)</option>
                                <option value="7. Ikhtisar Laba Rugi">7. Ikhtisar Laba Rugi (Kode 7-100 | Saldo Normal: Penutup)</option>
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Kode Akun <span class="text-rose-500">*</span></label>
                                <input type="text" name="kode" required placeholder="Contoh: 560" class="w-full text-xs font-mono font-bold rounded-xl border-slate-300 focus:border-amber-500">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Jenis Saldo Normal <span class="text-rose-500">*</span></label>
                                <select name="saldo_normal" required class="w-full text-xs font-semibold rounded-xl border-slate-300 focus:border-amber-500">
                                    <option value="Debit">Debit</option>
                                    <option value="Kredit">Kredit</option>
                                    <option value="Penutup">Penutup</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Akun <span class="text-rose-500">*</span></label>
                            <input type="text" name="nama" required placeholder="Contoh: Beban Pemeliharaan Armada" class="w-full text-xs rounded-xl border-slate-300 focus:border-amber-500">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Saldo Awal (Rp)</label>
                            <input type="number" name="saldo_awal" value="0" min="0" step="any" class="w-full text-xs font-bold rounded-xl border-slate-300 focus:border-amber-500">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Deskripsi / Catatan</label>
                            <input type="text" name="deskripsi" placeholder="Keterangan fungsi akun ini..." class="w-full text-xs rounded-xl border-slate-300 focus:border-amber-500">
                        </div>

                        <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-2">
                            <button type="button" @click="showModalCreate = false" class="px-4 py-2 rounded-xl border border-slate-300 text-slate-700 text-xs font-bold">
                                Batal
                            </button>
                            <button type="submit" class="px-5 py-2 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 text-xs font-black shadow-sm">
                                Simpan Akun
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Edit Akun -->
        <div x-show="showModalEdit" x-cloak class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div @click="showModalEdit = false" class="fixed inset-0 transition-opacity bg-slate-950/75 backdrop-blur-sm"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

                <div class="inline-block w-full max-w-lg p-6 my-8 text-left align-middle transition-all transform bg-white rounded-2xl shadow-xl sm:my-8 relative">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                        <div>
                            <h3 class="text-lg font-black text-slate-900">Ubah Akun</h3>
                            <p class="text-[11px] text-slate-500">Kode: <strong class="font-mono text-slate-800" x-text="editData.kode"></strong> &bull; Golongan: <span x-text="editData.golongan"></span></p>
                        </div>
                        <button @click="showModalEdit = false" class="text-slate-400 hover:text-slate-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <form method="POST" :action="editAction" class="mt-4 space-y-4">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Akun <span class="text-rose-500">*</span></label>
                            <input type="text" name="nama" x-model="editData.nama" required class="w-full text-xs font-bold rounded-xl border-slate-300 focus:border-amber-500">
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Jenis Saldo Normal <span class="text-rose-500">*</span></label>
                                <select name="saldo_normal" x-model="editData.saldo_normal" required class="w-full text-xs font-semibold rounded-xl border-slate-300 focus:border-amber-500">
                                    <option value="Debit">Debit</option>
                                    <option value="Kredit">Kredit</option>
                                    <option value="Penutup">Penutup</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Saldo Awal (Rp)</label>
                                <input type="number" name="saldo_awal" x-model="editData.saldo_awal" min="0" step="any" class="w-full text-xs font-bold rounded-xl border-slate-300 focus:border-amber-500">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Deskripsi / Catatan</label>
                            <input type="text" name="deskripsi" x-model="editData.deskripsi" class="w-full text-xs rounded-xl border-slate-300 focus:border-amber-500">
                        </div>

                        <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-2">
                            <button type="button" @click="showModalEdit = false" class="px-4 py-2 rounded-xl border border-slate-300 text-slate-700 text-xs font-bold">
                                Batal
                            </button>
                            <button type="submit" class="px-5 py-2 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 text-xs font-black shadow-sm">
                                Perbarui Akun
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
