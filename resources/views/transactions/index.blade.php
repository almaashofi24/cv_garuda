<x-app-layout>
    <x-slot name="header">
        Semua Transaksi Keuangan
    </x-slot>

    <div class="space-y-6">
        <!-- Header & Quick Actions -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
            <div>
                <h2 class="text-xl font-black text-slate-900">Buku Kas & Transaksi</h2>
                <p class="text-xs text-slate-500">Seluruh mutasi penerimaan dan pengeluaran kas perusahaan</p>
            </div>
            @role('Admin|Staf Keuangan')
            <div class="flex items-center gap-2">
                <a href="{{ route('transaksi.create', ['tipe' => 'pemasukan']) }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    + Pemasukan (BKM)
                </a>
                <a href="{{ route('transaksi.create', ['tipe' => 'pengeluaran']) }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                    </svg>
                    + Pengeluaran (BKK)
                </a>
            </div>
            @endrole
        </div>

        <!-- Filter Box -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm" x-data="{ expanded: false }">
            <form method="GET" action="{{ route('transaksi.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                    <!-- Search Input -->
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Cari Transaksi</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Nomor bukti, uraian, pihak..." class="w-full text-xs rounded-xl border-slate-200 focus:border-amber-500 focus:ring-amber-500">
                    </div>

                    <!-- Jenis Transaksi -->
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Jenis Mutasi</label>
                        <select name="tipe" class="w-full text-xs rounded-xl border-slate-200 focus:border-amber-500 focus:ring-amber-500">
                            <option value="">Semua (Pemasukan & Pengeluaran)</option>
                            <option value="pemasukan" {{ request('tipe') === 'pemasukan' ? 'selected' : '' }}>Pemasukan Kas (BKM)</option>
                            <option value="pengeluaran" {{ request('tipe') === 'pengeluaran' ? 'selected' : '' }}>Pengeluaran Kas (BKK)</option>
                        </select>
                    </div>

                    <!-- Kategori -->
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Kategori</label>
                        <select name="category_id" class="w-full text-xs rounded-xl border-slate-200 focus:border-amber-500 focus:ring-amber-500">
                            <option value="">Semua Kategori</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                    [{{ ucfirst($cat->tipe) }}] {{ $cat->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Metode Pembayaran -->
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Metode Bayar</label>
                        <select name="metode_pembayaran" class="w-full text-xs rounded-xl border-slate-200 focus:border-amber-500 focus:ring-amber-500">
                            <option value="">Semua Metode</option>
                            <option value="tunai" {{ request('metode_pembayaran') === 'tunai' ? 'selected' : '' }}>Tunai / Kas Kecil</option>
                            <option value="transfer" {{ request('metode_pembayaran') === 'transfer' ? 'selected' : '' }}>Transfer Bank</option>
                        </select>
                    </div>
                </div>

                <!-- Rentang Tanggal Filter -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-2 border-t border-slate-100 items-end">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Dari Tanggal</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full text-xs rounded-xl border-slate-200 focus:border-amber-500 focus:ring-amber-500">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Sampai Tanggal</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full text-xs rounded-xl border-slate-200 focus:border-amber-500 focus:ring-amber-500">
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="submit" class="flex-1 py-2 px-4 rounded-xl bg-slate-900 hover:bg-slate-800 text-amber-400 font-bold text-xs shadow-sm transition">
                            Terapkan Filter
                        </button>
                        <a href="{{ route('transaksi.index') }}" class="py-2 px-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition" title="Reset Filter">
                            Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Tabel Transaksi -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50/80 text-slate-600 font-bold border-b border-slate-200 uppercase tracking-wider text-[11px]">
                            <th class="px-5 py-3">Bukti & Tanggal</th>
                            <th class="px-5 py-3">Mutasi</th>
                            <th class="px-5 py-3">Akun 7 Golongan</th>
                            <th class="px-5 py-3">Pihak Terkait</th>
                            <th class="px-5 py-3">Deskripsi / Uraian</th>
                            <th class="px-5 py-3">Metode</th>
                            <th class="px-5 py-3 text-right">Nominal</th>
                            <th class="px-5 py-3 text-center">Lampiran</th>
                            <th class="px-5 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($transactions as $tx)
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="px-5 py-3 whitespace-nowrap">
                                    <div class="font-bold text-slate-900">{{ $tx->nomor_bukti }}</div>
                                    <div class="text-[11px] text-slate-500">{{ $tx->formatted_tanggal }}</div>
                                </td>
                                <td class="px-5 py-3 whitespace-nowrap">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase {{ $tx->tipe === 'pemasukan' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                        {{ $tx->tipe }}
                                    </span>
                                </td>
                                <td class="px-5 py-3 whitespace-nowrap">
                                    <div class="font-bold text-slate-800">
                                        {{ $tx->account ? $tx->account->kode_nama : ($tx->category->nama ?? '-') }}
                                    </div>
                                    <div class="text-[10px] text-slate-500 font-medium">
                                        {{ $tx->account ? $tx->account->golongan : 'Kategori' }}
                                    </div>
                                </td>
                                <td class="px-5 py-3 max-w-[150px] truncate font-medium text-slate-700">
                                    {{ $tx->pihak_terkait }}
                                </td>
                                <td class="px-5 py-3 max-w-xs truncate text-slate-600">
                                    {{ $tx->deskripsi }}
                                </td>
                                <td class="px-5 py-3 whitespace-nowrap">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $tx->metode_pembayaran === 'tunai' ? 'bg-amber-100 text-amber-800' : 'bg-blue-100 text-blue-800' }}">
                                        {{ $tx->metode_pembayaran }}
                                    </span>
                                </td>
                                <td class="px-5 py-3 text-right whitespace-nowrap font-black {{ $tx->tipe === 'pemasukan' ? 'text-emerald-600' : 'text-rose-600' }}">
                                    {{ $tx->tipe === 'pemasukan' ? '+' : '-' }} {{ $tx->formatted_jumlah }}
                                </td>
                                <td class="px-5 py-3 text-center whitespace-nowrap">
                                    @if ($tx->lampiran)
                                        <a href="{{ Storage::url($tx->lampiran) }}" target="_blank" class="inline-flex items-center gap-1 text-[11px] text-blue-600 hover:text-blue-800 font-semibold" title="Buka Bukti">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                                            </svg>
                                            Bukti
                                        </a>
                                    @else
                                        <span class="text-slate-400 text-[10px]">-</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3 text-center whitespace-nowrap">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <a href="{{ route('transaksi.show', $tx) }}" class="p-1 rounded-lg text-slate-600 hover:bg-slate-100 hover:text-slate-900" title="Lihat Voucher">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>

                                        @role('Admin|Staf Keuangan')
                                        <a href="{{ route('transaksi.edit', $tx) }}" class="p-1 rounded-lg text-blue-600 hover:bg-blue-50" title="Ubah Data">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>
                                        @endrole

                                        @role('Admin')
                                        <form method="POST" action="{{ route('transaksi.destroy', $tx) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi {{ $tx->nomor_bukti }}? Data akan dipindahkan ke tempat sampah (Soft Delete).');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1 rounded-lg text-rose-600 hover:bg-rose-50" title="Hapus">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                        @endrole
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-5 py-8 text-center text-slate-400 italic">
                                    Tidak ada data transaksi yang sesuai filter.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginasi -->
            @if ($transactions->hasPages())
                <div class="px-5 py-4 border-t border-slate-100 bg-slate-50/50">
                    {{ $transactions->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

