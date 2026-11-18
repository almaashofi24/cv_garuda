<x-app-layout>
    <x-slot name="header">
        Kelola Kategori Transaksi
    </x-slot>

    <div class="space-y-6" x-data="{
        showModal: false,
        isEdit: false,
        modalTitle: 'Tambah Kategori Baru',
        formAction: '{{ route('kategori.store') }}',
        formMethod: 'POST',
        id: '',
        nama: '',
        tipe: 'pemasukan',
        deskripsi: '',
        warna: '#2563eb',

        openCreate() {
            this.isEdit = false;
            this.modalTitle = 'Tambah Kategori Baru';
            this.formAction = '{{ route('kategori.store') }}';
            this.formMethod = 'POST';
            this.id = '';
            this.nama = '';
            this.tipe = 'pemasukan';
            this.deskripsi = '';
            this.warna = '#2563eb';
            this.showModal = true;
        },

        openEdit(cat) {
            this.isEdit = true;
            this.modalTitle = 'Ubah Kategori: ' + cat.nama;
            this.formAction = '/kategori/update/' + cat.id;
            this.formMethod = 'POST';
            this.id = cat.id;
            this.nama = cat.nama;
            this.tipe = cat.tipe;
            this.deskripsi = cat.deskripsi || '';
            this.warna = cat.warna || '#2563eb';
            this.showModal = true;
        }
    }">
        <!-- Header & Add Button -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
            <div>
                <h2 class="text-xl font-black text-slate-900">Daftar Kategori Transaksi</h2>
                <p class="text-xs text-slate-500">Kelompokkan arus kas pemasukan dan pos beban pengeluaran perusahaan</p>
            </div>
            <button @click="openCreate()" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-amber-400 font-bold text-xs sm:text-sm shadow-md transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                + Tambah Kategori
            </button>
        </div>

        <!-- Filter Tab & Search -->
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4">
            <div class="flex items-center gap-2 p-1 bg-white rounded-xl border border-slate-200 text-xs font-bold">
                <a href="{{ route('kategori.index') }}" class="px-3.5 py-1.5 rounded-lg transition {{ !request('tipe') ? 'bg-slate-900 text-amber-400' : 'text-slate-600 hover:text-slate-900' }}">
                    Semua
                </a>
                <a href="{{ route('kategori.index', ['tipe' => 'pemasukan']) }}" class="px-3.5 py-1.5 rounded-lg transition {{ request('tipe') === 'pemasukan' ? 'bg-emerald-600 text-white' : 'text-slate-600 hover:text-emerald-700' }}">
                    Pemasukan
                </a>
                <a href="{{ route('kategori.index', ['tipe' => 'pengeluaran']) }}" class="px-3.5 py-1.5 rounded-lg transition {{ request('tipe') === 'pengeluaran' ? 'bg-rose-600 text-white' : 'text-slate-600 hover:text-rose-700' }}">
                    Pengeluaran
                </a>
            </div>

            <form method="GET" action="{{ route('kategori.index') }}" class="flex items-center gap-2">
                @if (request('tipe'))
                    <input type="hidden" name="tipe" value="{{ request('tipe') }}">
                @endif
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kategori..." class="text-xs rounded-xl border-slate-200 focus:border-amber-500 focus:ring-amber-500">
                <button type="submit" class="p-2 rounded-xl bg-slate-900 text-amber-400 hover:bg-slate-800">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </button>
            </form>
        </div>

        <!-- Tabel Kategori -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50 text-slate-600 font-bold border-b border-slate-200 uppercase tracking-wider text-[11px]">
                            <th class="px-5 py-3">Nama Kategori</th>
                            <th class="px-5 py-3">Tipe</th>
                            <th class="px-5 py-3">Deskripsi</th>
                            <th class="px-5 py-3">Warna Bagan</th>
                            <th class="px-5 py-3 text-center">Jml Transaksi</th>
                            <th class="px-5 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($categories as $cat)
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="px-5 py-3 font-bold text-slate-900 whitespace-nowrap">
                                    {{ $cat->nama }}
                                </td>
                                <td class="px-5 py-3 whitespace-nowrap">
                                    <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $cat->tipe === 'pemasukan' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                        {{ $cat->tipe }}
                                    </span>
                                </td>
                                <td class="px-5 py-3 text-slate-600 max-w-sm">
                                    {{ $cat->deskripsi ?? '-' }}
                                </td>
                                <td class="px-5 py-3 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <span class="w-4 h-4 rounded-full border border-slate-300" style="background-color: {{ $cat->warna }}"></span>
                                        <span class="font-mono text-[11px] text-slate-500">{{ $cat->warna }}</span>
                                    </div>
                                </td>
                                <td class="px-5 py-3 text-center whitespace-nowrap font-bold text-slate-700">
                                    {{ $cat->transactions_count }} transaksi
                                </td>
                                <td class="px-5 py-3 text-center whitespace-nowrap">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <button @click="openEdit({{ json_encode($cat) }})" class="p-1 rounded-lg text-blue-600 hover:bg-blue-50" title="Edit Kategori">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>

                                        @if ($cat->transactions_count == 0)
                                            <form method="POST" action="{{ route('kategori.destroy', $cat) }}" onsubmit="return confirm('Hapus kategori {{ $cat->nama }}?');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1 rounded-lg text-rose-600 hover:bg-rose-50" title="Hapus">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        @else
                                            <span class="p-1 text-slate-300 cursor-not-allowed" title="Kategori sedang digunakan pada data transaksi, tidak bisa dihapus">
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
                                <td colspan="6" class="px-5 py-8 text-center text-slate-400 italic">
                                    Tidak ada data kategori yang cocok.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($categories->hasPages())
                <div class="px-5 py-4 border-t border-slate-100 bg-slate-50/50">
                    {{ $categories->links() }}
                </div>
            @endif
        </div>

        <!-- Modal Create / Edit Kategori -->
        <div 
            x-show="showModal" 
            x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/70 backdrop-blur-sm"
        >
            <div 
                @click.outside="showModal = false"
                class="bg-white rounded-2xl max-w-lg w-full p-6 sm:p-8 shadow-2xl border border-slate-100 space-y-5"
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
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Kategori <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama" x-model="nama" required placeholder="Contoh: Pembelian Material / Penjualan Jasa" class="w-full text-xs rounded-xl border-slate-300 focus:border-amber-500 focus:ring-amber-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Tipe Mutasi <span class="text-rose-500">*</span></label>
                        <select name="tipe" x-model="tipe" required class="w-full text-xs rounded-xl border-slate-300 focus:border-amber-500 focus:ring-amber-500">
                            <option value="pemasukan">Pemasukan Kas (BKM)</option>
                            <option value="pengeluaran">Pengeluaran Kas (BKK)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Deskripsi / Peruntukan</label>
                        <textarea name="deskripsi" x-model="deskripsi" rows="2" placeholder="Keterangan singkat pos transaksi ini..." class="w-full text-xs rounded-xl border-slate-300 focus:border-amber-500 focus:ring-amber-500"></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Warna Label & Grafik</label>
                        <div class="flex items-center gap-3">
                            <input type="color" name="warna" x-model="warna" class="w-10 h-10 p-0.5 rounded-xl border border-slate-300 cursor-pointer">
                            <input type="text" x-model="warna" class="text-xs rounded-xl border-slate-300 font-mono w-28">
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-4 border-t border-slate-100">
                        <button type="button" @click="showModal = false" class="px-4 py-2 rounded-xl border border-slate-200 text-slate-600 text-xs font-bold hover:bg-slate-50">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-amber-400 text-xs font-black shadow-md">
                            Simpan Kategori
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

