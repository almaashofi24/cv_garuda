<x-app-layout>
    <x-slot name="header">
        Transaksi Pengeluaran Kas (BKK)
    </x-slot>

    <div class="space-y-6">
        <!-- Header Banner & Summary -->
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 bg-gradient-to-r from-rose-900 to-red-950 p-6 rounded-2xl text-white shadow-md">
            <div>
                <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-rose-500/20 text-rose-300 text-xs font-bold border border-rose-500/30 mb-2">
                    Bukti Kas Keluar (BKK)
                </div>
                <h2 class="text-xl sm:text-2xl font-black">Pencatatan Pengeluaran Kas</h2>
                <p class="text-xs sm:text-sm text-rose-200/80">Pembayaran upah tukang & staf, BBM armada, material proyek, servis, utilitas, dan biaya lainnya</p>
            </div>
            <div class="flex flex-wrap items-center gap-4">
                <div class="bg-white/10 backdrop-blur-md px-4 py-2.5 rounded-xl border border-white/10 text-right">
                    <span class="block text-[11px] text-rose-200 uppercase font-semibold">Total Terfilter</span>
                    <span class="block text-lg sm:text-xl font-black text-rose-300">
                        Rp {{ number_format($totalNominal, 0, ',', '.') }}
                    </span>
                </div>
                @role('Admin|Staf Keuangan')
                <a href="{{ route('transaksi.create', ['tipe' => 'pengeluaran']) }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-rose-500 hover:bg-rose-400 text-slate-950 font-extrabold text-xs sm:text-sm shadow-lg transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                    </svg>
                    + Catat Pengeluaran Baru
                </a>
                @endrole
            </div>
        </div>

        <!-- Filter Box -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
            <form method="GET" action="{{ route('transaksi.pengeluaran') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 items-end">
                <div>
                    <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Cari Pengeluaran</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Nomor BKK, vendor, uraian..." class="w-full text-xs rounded-xl border-slate-200 focus:border-rose-500 focus:ring-rose-500">
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Kategori Pengeluaran</label>
                    <select name="category_id" class="w-full text-xs rounded-xl border-slate-200 focus:border-rose-500 focus:ring-rose-500">
                        <option value="">Semua Kategori</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Dari Tanggal</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full text-xs rounded-xl border-slate-200 focus:border-rose-500 focus:ring-rose-500">
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Sampai Tanggal</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full text-xs rounded-xl border-slate-200 focus:border-rose-500 focus:ring-rose-500">
                </div>

                <div class="flex items-center gap-2">
                    <button type="submit" class="flex-1 py-2 px-3 rounded-xl bg-rose-700 hover:bg-rose-800 text-white font-bold text-xs shadow-sm transition">
                        Filter
                    </button>
                    <a href="{{ route('transaksi.pengeluaran') }}" class="py-2 px-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition" title="Reset Filter">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Tabel Pengeluaran -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-rose-50/60 text-rose-950 font-bold border-b border-rose-100 uppercase tracking-wider text-[11px]">
                            <th class="px-5 py-3">No. Bukti (BKK)</th>
                            <th class="px-5 py-3">Tanggal</th>
                            <th class="px-5 py-3">Penerima / Vendor</th>
                            <th class="px-5 py-3">Kategori</th>
                            <th class="px-5 py-3">Uraian / Deskripsi</th>
                            <th class="px-5 py-3">Metode</th>
                            <th class="px-5 py-3 text-right">Jumlah (Rp)</th>
                            <th class="px-5 py-3 text-center">Nota / Bukti</th>
                            <th class="px-5 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($transactions as $tx)
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="px-5 py-3 whitespace-nowrap font-bold text-slate-900">
                                    {{ $tx->nomor_bukti }}
                                </td>
                                <td class="px-5 py-3 whitespace-nowrap text-slate-600">
                                    {{ $tx->formatted_tanggal }}
                                </td>
                                <td class="px-5 py-3 font-bold text-slate-800 max-w-[150px] truncate">
                                    {{ $tx->pihak_terkait }}
                                </td>
                                <td class="px-5 py-3 whitespace-nowrap text-rose-800 font-semibold">
                                    {{ $tx->category ? $tx->category->nama : '-' }}
                                </td>
                                <td class="px-5 py-3 max-w-xs truncate text-slate-600">
                                    {{ $tx->deskripsi }}
                                </td>
                                <td class="px-5 py-3 whitespace-nowrap">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $tx->metode_pembayaran === 'tunai' ? 'bg-amber-100 text-amber-800' : 'bg-blue-100 text-blue-800' }}">
                                        {{ $tx->metode_pembayaran }}
                                    </span>
                                </td>
                                <td class="px-5 py-3 text-right whitespace-nowrap font-black text-rose-600">
                                    - {{ $tx->formatted_jumlah }}
                                </td>
                                <td class="px-5 py-3 text-center whitespace-nowrap">
                                    @if ($tx->lampiran)
                                        <a href="{{ Storage::url($tx->lampiran) }}" target="_blank" class="inline-flex items-center gap-1 text-[11px] text-blue-600 hover:text-blue-800 font-semibold">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                                            </svg>
                                            Lihat
                                        </a>
                                    @else
                                        <span class="text-slate-400 text-[10px]">-</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3 text-center whitespace-nowrap">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <a href="{{ route('transaksi.show', $tx) }}" class="p-1 rounded-lg text-slate-600 hover:bg-slate-100 hover:text-slate-900" title="Detail Voucher">
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
                                        <form method="POST" action="{{ route('transaksi.destroy', $tx) }}" onsubmit="return confirm('Hapus pengeluaran {{ $tx->nomor_bukti }}?');" class="inline">
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
                                    Belum ada transaksi pengeluaran yang cocok.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($transactions->hasPages())
                <div class="px-5 py-4 border-t border-slate-100 bg-slate-50/50">
                    {{ $transactions->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

