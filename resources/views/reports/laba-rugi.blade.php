<x-app-layout>
    <x-slot name="header">
        Laporan Laba Rugi (Pendapatan - Beban = Laba)
    </x-slot>

    <div class="space-y-6" x-data="{
        jenisPeriode: '{{ $filters['jenisPeriode'] }}'
    }">
        <!-- Header & Action Card -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl sm:text-2xl font-black text-slate-900">Laporan Laba Rugi</h2>
                <p class="text-xs text-slate-500 mt-0.5">Periode Pembukuan: <strong class="text-slate-800">{{ $periodeJudul }}</strong></p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <!-- Tombol Ekspor PDF Laba Rugi -->
                <a href="{{ route('laporan.laba-rugi.export.pdf', request()->query()) }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Cetak PDF
                </a>

                <!-- Tombol Ekspor Excel Laba Rugi -->
                <a href="{{ route('laporan.laba-rugi.export.excel', request()->query()) }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Download Excel
                </a>
            </div>
        </div>

        <!-- Filter Box Periode -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
            <form method="GET" action="{{ route('laporan.laba-rugi') }}" class="space-y-4">
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

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-2 border-t border-slate-100 items-end">
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

                    <template x-if="jenisPeriode === 'custom'">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Dari Tanggal</label>
                            <input type="date" name="start_date" value="{{ $filters['startDate'] }}" class="w-full text-xs rounded-xl border-slate-200">
                        </div>
                    </template>

                    <template x-if="jenisPeriode === 'custom'">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Sampai Tanggal</label>
                            <input type="date" name="end_date" value="{{ $filters['endDate'] }}" class="w-full text-xs rounded-xl border-slate-200">
                        </div>
                    </template>

                    <div>
                        <button type="submit" class="w-full py-2 px-4 rounded-xl bg-slate-900 hover:bg-slate-800 text-amber-400 font-bold text-xs shadow-sm transition">
                            Tampilkan Laba Rugi
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Format Standar Laporan Laba Rugi CV GARUDA DUABELAS -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <!-- Kop Dokumen Laporan -->
            <div class="p-6 border-b border-slate-200 text-center bg-slate-50/70">
                <h3 class="text-xl font-black text-slate-900 tracking-wider">
                    {{ strtoupper($company['nama_perusahaan'] ?? 'CV. GARUDA RAJA PERKASA DUABELAS') }}
                </h3>
                <h4 class="text-sm font-black text-slate-800 uppercase mt-1">Laporan Laba Rugi</h4>
                <p class="text-xs font-semibold text-slate-600 mt-0.5">Periode: {{ $periodeJudul }}</p>
                <p class="text-[11px] text-slate-400 mt-1">{{ $company['alamat_perusahaan'] ?? '' }}</p>
            </div>

            <div class="p-6 sm:p-10 space-y-8 max-w-4xl mx-auto">
                <!-- 1. PENDAPATAN -->
                <div>
                    <div class="flex items-center justify-between pb-2 border-b-2 border-slate-800 mb-2">
                        <h4 class="text-sm font-black text-slate-900 uppercase tracking-wider">
                            Pendapatan:
                        </h4>
                        <span class="text-xs font-bold text-slate-400 uppercase">Nominal</span>
                    </div>

                    <table class="w-full text-xs sm:text-sm">
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($pemasukanList as $item)
                                <tr class="hover:bg-slate-50/50">
                                    <td class="py-2.5 pl-6 text-slate-800 font-medium">
                                        {{ $item->nama }}
                                        <span class="text-[10px] text-slate-400 ml-2">({{ $item->total_transaksi }} transaksi)</span>
                                    </td>
                                    <td class="py-2.5 pr-4 text-right font-bold text-slate-900 whitespace-nowrap">
                                        Rp {{ number_format($item->total_nominal, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="py-3 pl-6 text-slate-400 italic">Tidak ada catatan pendapatan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr class="font-black text-slate-900 border-t-2 border-slate-300 bg-slate-50/60">
                                <td class="py-3 pl-6 uppercase text-xs sm:text-sm font-black">
                                    Total Pendapatan
                                </td>
                                <td class="py-3 pr-4 text-right text-sm sm:text-base text-emerald-700 font-black whitespace-nowrap">
                                    Rp {{ number_format($totalPemasukan, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- 2. BEBAN / PENGELUARAN -->
                <div>
                    <div class="flex items-center justify-between pb-2 border-b-2 border-slate-800 mb-2">
                        <h4 class="text-sm font-black text-slate-900 uppercase tracking-wider">
                            Beban / Pengeluaran:
                        </h4>
                        <span class="text-xs font-bold text-slate-400 uppercase">Nominal</span>
                    </div>

                    <table class="w-full text-xs sm:text-sm">
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($pengeluaranList as $item)
                                <tr class="hover:bg-slate-50/50">
                                    <td class="py-2.5 pl-6 text-slate-800 font-medium">
                                        {{ $item->nama }}
                                        <span class="text-[10px] text-slate-400 ml-2">({{ $item->total_transaksi }} transaksi)</span>
                                    </td>
                                    <td class="py-2.5 pr-4 text-right font-bold text-slate-900 whitespace-nowrap">
                                        Rp {{ number_format($item->total_nominal, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="py-3 pl-6 text-slate-400 italic">Tidak ada catatan beban pengeluaran.</td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr class="font-black text-slate-900 border-t-2 border-slate-300 bg-slate-50/60">
                                <td class="py-3 pl-6 uppercase text-xs sm:text-sm font-black">
                                    Total Beban
                                </td>
                                <td class="py-3 pr-4 text-right text-sm sm:text-base text-rose-700 font-black whitespace-nowrap">
                                    Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- 3. LABA BERSIH -->
                <div class="p-5 sm:p-6 rounded-2xl {{ $labaBersih >= 0 ? 'bg-gradient-to-r from-slate-900 via-blue-950 to-slate-900 text-white' : 'bg-gradient-to-r from-rose-900 to-red-950 text-white' }} shadow-lg border border-slate-800">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <div>
                            <span class="text-[11px] font-bold uppercase tracking-wider {{ $labaBersih >= 0 ? 'text-amber-400' : 'text-rose-300' }}">
                                Hasil Perhitungan Laba Rugi (Pendapatan - Beban)
                            </span>
                            <h3 class="text-xl sm:text-2xl font-black mt-1">
                                {{ $labaBersih >= 0 ? 'Laba Bersih' : 'Rugi Bersih' }}
                            </h3>
                        </div>
                        <div class="text-left sm:text-right shrink-0">
                            <span class="text-2xl sm:text-3xl font-black {{ $labaBersih >= 0 ? 'text-emerald-400' : 'text-rose-200' }}">
                                Rp {{ number_format($labaBersih, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Kolom Tanda Tangan -->
                <div class="grid grid-cols-2 gap-8 text-center text-xs pt-8 border-t border-slate-200">
                    <div>
                        <p class="text-slate-500 font-medium">Mengetahui,</p>
                        <p class="font-bold text-slate-700">{{ $company['pimpinan_jabatan'] ?? 'Direktur Utama' }}</p>
                        <div class="h-20"></div>
                        <p class="font-extrabold text-slate-900 underline">{{ $company['pimpinan_nama'] ?? 'H. Sudirman, S.E.' }}</p>
                    </div>
                    <div>
                        <p class="text-slate-500 font-medium">{{ $company['kota_laporan'] ?? 'Sanggau' }}, {{ date('d/m/Y') }}</p>
                        <p class="font-bold text-slate-700">{{ $company['staf_keuangan_jabatan'] ?? 'Staf Keuangan' }}</p>
                        <div class="h-20"></div>
                        <p class="font-extrabold text-slate-900 underline">{{ $company['staf_keuangan_nama'] ?? 'Siti Aminah, A.Md.' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
