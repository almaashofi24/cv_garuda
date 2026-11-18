<x-app-layout>
    <x-slot name="header">
        Laporan Arus Kas & Rekapitulasi
    </x-slot>

    <div class="space-y-6" x-data="{
        jenisPeriode: '{{ $filters['jenisPeriode'] }}',
    }">
        <!-- Header & Action Card -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-black text-slate-900">Rekapitulasi Arus Kas</h2>
                <p class="text-xs text-slate-500">Periode: <strong class="text-slate-800">{{ $periodeJudul }}</strong></p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <!-- Tombol Ekspor PDF -->
                <a href="{{ route('laporan.export.pdf', request()->query()) }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Ekspor PDF
                </a>

                <!-- Tombol Ekspor Excel -->
                <a href="{{ route('laporan.export.excel', request()->query()) }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Ekspor Excel
                </a>

                <!-- Link ke Laba Rugi -->
                <a href="{{ route('laporan.laba-rugi', request()->query()) }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-amber-400 text-xs font-bold shadow-sm transition">
                    Lihat Laba Rugi &rarr;
                </a>
            </div>
        </div>

        <!-- Filter Box Periode & Parameter -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
            <form method="GET" action="{{ route('laporan.index') }}" class="space-y-4">
                <!-- Pilihan Jenis Periode (Harian, Bulanan, Tahunan, Custom) -->
                <div>
                    <label class="block text-[11px] font-bold text-slate-600 uppercase mb-2">Pilihan Mode Periode</label>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 text-xs font-bold">
                        <label :class="jenisPeriode === 'hari_ini' ? 'bg-slate-900 text-amber-400 border-slate-900' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-slate-100'" class="flex items-center justify-center p-2.5 rounded-xl border cursor-pointer transition">
                            <input type="radio" name="jenis_periode" value="hari_ini" x-model="jenisPeriode" class="sr-only">
                            <span>Hari Ini</span>
                        </label>
                        <label :class="jenisPeriode === 'bulan_ini' ? 'bg-slate-900 text-amber-400 border-slate-900' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-slate-100'" class="flex items-center justify-center p-2.5 rounded-xl border cursor-pointer transition">
                            <input type="radio" name="jenis_periode" value="bulan_ini" x-model="jenisPeriode" class="sr-only">
                            <span>Bulanan</span>
                        </label>
                        <label :class="jenisPeriode === 'tahun_ini' ? 'bg-slate-900 text-amber-400 border-slate-900' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-slate-100'" class="flex items-center justify-center p-2.5 rounded-xl border cursor-pointer transition">
                            <input type="radio" name="jenis_periode" value="tahun_ini" x-model="jenisPeriode" class="sr-only">
                            <span>Tahunan</span>
                        </label>
                        <label :class="jenisPeriode === 'custom' ? 'bg-slate-900 text-amber-400 border-slate-900' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-slate-100'" class="flex items-center justify-center p-2.5 rounded-xl border cursor-pointer transition">
                            <input type="radio" name="jenis_periode" value="custom" x-model="jenisPeriode" class="sr-only">
                            <span>Rentang Kustom</span>
                        </label>
                    </div>
                </div>

                <!-- Input Dinamis Tergantung Pilihan Periode -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 pt-2 border-t border-slate-100">
                    <!-- Jika Bulanan: Pilih Bulan & Tahun -->
                    <template x-if="jenisPeriode === 'bulan_ini'">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Bulan</label>
                            <select name="bulan" class="w-full text-xs rounded-xl border-slate-200 focus:border-amber-500">
                                @for ($m = 1; $m <= 12; $m++)
                                    <option value="{{ $m }}" {{ $filters['bulan'] == $m ? 'selected' : '' }}>
                                        {{ \Carbon\Carbon::create(2026, $m, 1)->translatedFormat('F') }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                    </template>

                    <template x-if="jenisPeriode === 'bulan_ini' || jenisPeriode === 'tahun_ini'">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Tahun</label>
                            <select name="tahun" class="w-full text-xs rounded-xl border-slate-200 focus:border-amber-500">
                                @for ($y = date('Y'); $y >= date('Y') - 5; $y--)
                                    <option value="{{ $y }}" {{ $filters['tahun'] == $y ? 'selected' : '' }}>
                                        {{ $y }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                    </template>

                    <!-- Jika Custom: Input Tanggal Mulai & Selesai -->
                    <template x-if="jenisPeriode === 'custom'">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Dari Tanggal</label>
                            <input type="date" name="start_date" value="{{ $filters['startDate'] }}" class="w-full text-xs rounded-xl border-slate-200 focus:border-amber-500">
                        </div>
                    </template>
                    <template x-if="jenisPeriode === 'custom'">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Sampai Tanggal</label>
                            <input type="date" name="end_date" value="{{ $filters['endDate'] }}" class="w-full text-xs rounded-xl border-slate-200 focus:border-amber-500">
                        </div>
                    </template>

                    <!-- Filter Kategori -->
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Kategori</label>
                        <select name="category_id" class="w-full text-xs rounded-xl border-slate-200 focus:border-amber-500">
                            <option value="">Semua Kategori</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}" {{ $filters['category_id'] == $cat->id ? 'selected' : '' }}>
                                    [{{ ucfirst($cat->tipe) }}] {{ $cat->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filter Metode Bayar -->
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Metode Bayar</label>
                        <select name="metode_pembayaran" class="w-full text-xs rounded-xl border-slate-200 focus:border-amber-500">
                            <option value="">Semua Metode</option>
                            <option value="tunai" {{ $filters['metode_pembayaran'] === 'tunai' ? 'selected' : '' }}>Tunai</option>
                            <option value="transfer" {{ $filters['metode_pembayaran'] === 'transfer' ? 'selected' : '' }}>Transfer Bank</option>
                        </select>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
                    <button type="submit" class="px-5 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-amber-400 font-bold text-xs shadow-sm transition">
                        Tampilkan Laporan
                    </button>
                </div>
            </form>
        </div>

        <!-- 3 Kartu Ringkasan Hasil Laporan -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">
                <span class="text-xs font-bold text-slate-500 uppercase">Total Pemasukan Kas</span>
                <div class="text-xl sm:text-2xl font-black text-emerald-600 mt-2">
                    Rp {{ number_format($totalPemasukan, 0, ',', '.') }}
                </div>
                <p class="text-[11px] text-slate-400 mt-1">Periode {{ $periodeJudul }}</p>
            </div>

            <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">
                <span class="text-xs font-bold text-slate-500 uppercase">Total Pengeluaran Kas</span>
                <div class="text-xl sm:text-2xl font-black text-rose-600 mt-2">
                    Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}
                </div>
                <p class="text-[11px] text-slate-400 mt-1">Periode {{ $periodeJudul }}</p>
            </div>

            <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">
                <span class="text-xs font-bold text-slate-500 uppercase">Saldo / Surplus Bersih</span>
                <div class="text-xl sm:text-2xl font-black {{ $saldoBersih >= 0 ? 'text-blue-700' : 'text-rose-700' }} mt-2">
                    Rp {{ number_format($saldoBersih, 0, ',', '.') }}
                </div>
                <p class="text-[11px] font-bold {{ $saldoBersih >= 0 ? 'text-emerald-600' : 'text-rose-600' }} mt-1">
                    {{ $saldoBersih >= 0 ? 'Surplus Kas' : 'Defisit Kas' }}
                </p>
            </div>
        </div>

        <!-- Tabel Rincian Transaksi Laporan -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-sm font-bold text-slate-900">Rincian Transaksi Terbuku ({{ count($transactions) }} data)</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50 text-slate-600 font-bold border-b border-slate-200 uppercase tracking-wider text-[11px]">
                            <th class="px-4 py-3 text-center">No.</th>
                            <th class="px-4 py-3">Bukti / Tgl</th>
                            <th class="px-4 py-3">Kategori</th>
                            <th class="px-4 py-3">Pihak Terkait</th>
                            <th class="px-4 py-3">Uraian Transaksi</th>
                            <th class="px-4 py-3">Metode</th>
                            <th class="px-4 py-3 text-right">Pemasukan (Rp)</th>
                            <th class="px-4 py-3 text-right">Pengeluaran (Rp)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($transactions as $idx => $tx)
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="px-4 py-3 text-center text-slate-400 font-medium">{{ $idx + 1 }}</td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="font-bold text-slate-900">{{ $tx->nomor_bukti }}</div>
                                    <div class="text-[11px] text-slate-500">{{ $tx->formatted_tanggal }}</div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap font-medium text-slate-700">
                                    {{ $tx->category ? $tx->category->nama : '-' }}
                                </td>
                                <td class="px-4 py-3 max-w-[140px] truncate font-bold text-slate-800">
                                    {{ $tx->pihak_terkait }}
                                </td>
                                <td class="px-4 py-3 max-w-xs truncate text-slate-600">
                                    {{ $tx->deskripsi }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $tx->metode_pembayaran === 'tunai' ? 'bg-amber-100 text-amber-800' : 'bg-blue-100 text-blue-800' }}">
                                        {{ $tx->metode_pembayaran }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right whitespace-nowrap font-black text-emerald-600">
                                    {{ $tx->tipe === 'pemasukan' ? number_format($tx->jumlah, 0, ',', '.') : '-' }}
                                </td>
                                <td class="px-4 py-3 text-right whitespace-nowrap font-black text-rose-600">
                                    {{ $tx->tipe === 'pengeluaran' ? number_format($tx->jumlah, 0, ',', '.') : '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-8 text-center text-slate-400 italic">
                                    Tidak ada data transaksi pada periode yang dipilih.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="bg-slate-100/80 font-black text-slate-900 text-xs border-t-2 border-slate-300">
                        <tr>
                            <td colspan="6" class="px-4 py-3 text-right uppercase">Total:</td>
                            <td class="px-4 py-3 text-right text-emerald-700">
                                Rp {{ number_format($totalPemasukan, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3 text-right text-rose-700">
                                Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}
                            </td>
                        </tr>
                        <tr class="bg-slate-200/60">
                            <td colspan="6" class="px-4 py-3 text-right uppercase">Saldo Bersih (Pemasukan - Pengeluaran):</td>
                            <td colspan="2" class="px-4 py-3 text-right text-sm {{ $saldoBersih >= 0 ? 'text-blue-800' : 'text-rose-800' }}">
                                Rp {{ number_format($saldoBersih, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>

