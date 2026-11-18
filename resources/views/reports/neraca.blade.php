<x-app-layout>
    <x-slot name="header">
        Laporan Neraca (Harta = Utang + Modal)
    </x-slot>

    <div class="space-y-6">
        <!-- Header & Filter Form -->
        <div class="bg-white p-5 sm:p-6 rounded-2xl border border-slate-200/80 shadow-sm space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h2 class="text-xl sm:text-2xl font-black text-slate-900">Neraca Keuangan (Balance Sheet)</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Persamaan Dasar Akuntansi: <strong class="text-slate-800">Harta (Aktiva) = Utang + Modal (Pasiva)</strong></p>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <a href="{{ route('neraca.export.pdf', request()->query()) }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-rose-600 hover:bg-rose-500 text-white font-bold text-xs shadow-sm transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Cetak PDF
                    </a>
                    <a href="{{ route('neraca.export.excel', request()->query()) }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow-sm transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Download Excel
                    </a>
                </div>
            </div>

            <!-- Filter Tanggal Neraca -->
            <form method="GET" action="{{ route('neraca.index') }}" class="pt-4 border-t border-slate-100 flex flex-wrap items-end gap-3">
                <div class="w-full sm:w-64">
                    <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Posisi Neraca Per Tanggal</label>
                    <input type="date" name="as_of_date" value="{{ $asOfDate }}" class="w-full text-xs rounded-xl border-slate-300 focus:border-amber-500 focus:ring-amber-500">
                </div>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-amber-400 font-bold text-xs shadow-sm transition">
                    Tampilkan Neraca
                </button>
            </form>
        </div>

        <!-- Banner Keseimbangan Neraca (Balance Indicator) -->
        <div class="p-4 sm:p-5 rounded-2xl border {{ $isBalanced ? 'bg-emerald-50 border-emerald-200 text-emerald-900' : 'bg-rose-50 border-rose-200 text-rose-900' }} shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl {{ $isBalanced ? 'bg-emerald-500 text-white' : 'bg-rose-500 text-white' }} flex items-center justify-center font-bold shrink-0">
                        @if($isBalanced)
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        @else
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        @endif
                    </div>
                    <div>
                        <div class="text-xs font-bold uppercase tracking-wider">
                            Status Neraca: {{ $isBalanced ? 'SEIMBANG / BALANCE' : 'TIDAK SEIMBANG' }}
                        </div>
                        <div class="text-sm sm:text-base font-black mt-0.5">
                            Total Harta: Rp {{ number_format($totalHarta, 0, ',', '.') }} = Total Utang + Modal: Rp {{ number_format($totalPasiva, 0, ',', '.') }}
                        </div>
                    </div>
                </div>

                @if(!$isBalanced)
                    <div class="text-xs text-rose-700 font-bold">
                        Selisih: Rp {{ number_format($selisih, 0, ',', '.') }}
                    </div>
                @else
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-200 text-emerald-800 text-xs font-black">
                        Validasi SAK 100% Cocok
                    </span>
                @endif
            </div>
        </div>

        <!-- Dua Kolom Neraca: Harta (Aktiva) vs Utang & Modal (Pasiva) -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Kolom Kiri: 1. HARTA / AKTIVA -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden flex flex-col justify-between">
                <div>
                    <div class="p-5 bg-slate-900 text-white border-b border-slate-800 flex items-center justify-between">
                        <div>
                            <span class="text-[10px] font-bold tracking-wider uppercase text-amber-400">Aktiva</span>
                            <h3 class="text-lg font-black">1. HARTA</h3>
                        </div>
                        <span class="text-xs px-2.5 py-1 rounded-full bg-slate-800 text-slate-300 font-bold">Debit</span>
                    </div>

                    <table class="w-full text-xs">
                        <thead>
                            <tr class="bg-slate-50 text-slate-500 font-bold border-b border-slate-200 text-[11px] uppercase">
                                <th class="px-5 py-3">Kode & Nama Akun</th>
                                <th class="px-5 py-3 text-right">Saldo (Rp)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($hartaAccounts as $item)
                                <tr class="hover:bg-slate-50/70 transition">
                                    <td class="px-5 py-3 text-slate-800 font-medium">
                                        <span class="font-mono font-bold text-slate-500 mr-1.5">{{ $item['kode'] }}</span>
                                        <span class="font-bold text-slate-900">{{ $item['nama'] }}</span>
                                    </td>
                                    <td class="px-5 py-3 text-right font-black text-slate-900 whitespace-nowrap">
                                        Rp {{ number_format($item['saldo'], 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="px-5 py-4 text-center text-slate-400 italic">Belum ada akun harta.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-5 bg-slate-100 border-t-2 border-slate-300 flex items-center justify-between">
                    <span class="font-black text-xs sm:text-sm text-slate-900 uppercase">TOTAL HARTA (AKTIVA)</span>
                    <span class="font-black text-base sm:text-lg text-emerald-700 whitespace-nowrap">
                        Rp {{ number_format($totalHarta, 0, ',', '.') }}
                    </span>
                </div>
            </div>

            <!-- Kolom Kanan: 2. UTANG & 3. MODAL (PASIVA) -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden flex flex-col justify-between">
                <div>
                    <!-- Bagian Utang -->
                    <div class="p-5 bg-slate-900 text-white border-b border-slate-800 flex items-center justify-between">
                        <div>
                            <span class="text-[10px] font-bold tracking-wider uppercase text-amber-400">Pasiva (Kewajiban)</span>
                            <h3 class="text-lg font-black">2. UTANG / KEWAJIBAN</h3>
                        </div>
                        <span class="text-xs px-2.5 py-1 rounded-full bg-slate-800 text-slate-300 font-bold">Kredit</span>
                    </div>

                    <table class="w-full text-xs">
                        <thead>
                            <tr class="bg-slate-50 text-slate-500 font-bold border-b border-slate-200 text-[11px] uppercase">
                                <th class="px-5 py-3">Kode & Nama Akun</th>
                                <th class="px-5 py-3 text-right">Saldo (Rp)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($utangAccounts as $item)
                                <tr class="hover:bg-slate-50/70 transition">
                                    <td class="px-5 py-3 text-slate-800 font-medium">
                                        <span class="font-mono font-bold text-slate-500 mr-1.5">{{ $item['kode'] }}</span>
                                        <span class="font-bold text-slate-900">{{ $item['nama'] }}</span>
                                    </td>
                                    <td class="px-5 py-3 text-right font-black text-slate-900 whitespace-nowrap">
                                        Rp {{ number_format($item['saldo'], 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="px-5 py-3 text-center text-slate-400 italic">Tidak ada saldo utang.</td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr class="bg-slate-50/80 font-bold text-slate-800 border-t border-slate-200">
                                <td class="px-5 py-2.5 uppercase text-[11px]">Subtotal Utang</td>
                                <td class="px-5 py-2.5 text-right font-black text-slate-900 whitespace-nowrap">
                                    Rp {{ number_format($totalUtang, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>

                    <!-- Bagian Modal & Ekuitas -->
                    <div class="p-4 bg-slate-800 text-white border-t-2 border-slate-700 flex items-center justify-between">
                        <div>
                            <span class="text-[10px] font-bold tracking-wider uppercase text-amber-400">Ekuitas</span>
                            <h4 class="text-sm font-black">3. MODAL & 6. PRIVE</h4>
                        </div>
                        <span class="text-xs px-2.5 py-0.5 rounded-full bg-slate-900 text-slate-300 font-bold">Kredit</span>
                    </div>

                    <table class="w-full text-xs">
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($modalAccounts as $item)
                                <tr class="hover:bg-slate-50/70 transition">
                                    <td class="px-5 py-3 text-slate-800 font-medium">
                                        <span class="font-mono font-bold text-slate-500 mr-1.5">{{ $item['kode'] }}</span>
                                        <span class="font-bold text-slate-900">{{ $item['nama'] }}</span>
                                    </td>
                                    <td class="px-5 py-3 text-right font-black text-slate-900 whitespace-nowrap">
                                        Rp {{ number_format($item['saldo'], 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach

                            @foreach ($priveAccounts as $item)
                                <tr class="hover:bg-slate-50/70 transition bg-rose-50/30">
                                    <td class="px-5 py-3 text-rose-800 font-medium">
                                        <span class="font-mono font-bold text-rose-600 mr-1.5">{{ $item['kode'] }}</span>
                                        <span class="font-bold">{{ $item['nama'] }}</span>
                                        <span class="text-[10px] text-rose-500 ml-1">(Pengurang Modal)</span>
                                    </td>
                                    <td class="px-5 py-3 text-right font-black text-rose-700 whitespace-nowrap">
                                        - Rp {{ number_format($item['saldo'], 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach

                            <!-- Laba Bersih Berjalan -->
                            <tr class="hover:bg-slate-50/70 transition bg-amber-50/30">
                                <td class="px-5 py-3 text-slate-900 font-bold">
                                    <span class="text-amber-600 mr-1.5">&bull;</span>
                                    <span>Laba Bersih Periode Berjalan</span>
                                    <span class="text-[10px] text-slate-500 font-normal ml-1">(dari Laba Rugi)</span>
                                </td>
                                <td class="px-5 py-3 text-right font-black {{ $labaBersihBerjalan >= 0 ? 'text-emerald-700' : 'text-rose-700' }} whitespace-nowrap">
                                    Rp {{ number_format($labaBersihBerjalan, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr class="bg-slate-50/80 font-bold text-slate-800 border-t border-slate-200">
                                <td class="px-5 py-2.5 uppercase text-[11px]">Subtotal Modal / Ekuitas</td>
                                <td class="px-5 py-2.5 text-right font-black text-slate-900 whitespace-nowrap">
                                    Rp {{ number_format($totalModal, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="p-5 bg-slate-100 border-t-2 border-slate-300 flex items-center justify-between">
                    <span class="font-black text-xs sm:text-sm text-slate-900 uppercase">TOTAL KEWAJIBAN & MODAL (PASIVA)</span>
                    <span class="font-black text-base sm:text-lg text-blue-700 whitespace-nowrap">
                        Rp {{ number_format($totalPasiva, 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
