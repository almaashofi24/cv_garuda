<x-app-layout>
    <x-slot name="header">
        Dashboard
    </x-slot>

    <div class="space-y-6">
        <!-- Banner Selamat Datang & Profil Perusahaan -->
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-slate-900 via-blue-950 to-slate-900 p-6 sm:p-8 text-white shadow-xl border border-slate-800">
            <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                <div class="space-y-2">
                    <h2 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                        {{ $company['nama_perusahaan'] ?? 'CV. GARUDA RAJA PERKASA DUABELAS' }}
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-300 max-w-2xl leading-relaxed">
                        {{ $company['alamat_perusahaan'] ?? 'Jl. Trans Kalimantan Ruko Keling Kumang No.2, Simpang Ampar, Kec. Tayan Hilir, Kabupaten Sanggau, Kalimantan Barat 78564' }}
                    </p>
                </div>
                <div class="flex flex-wrap items-center gap-3 shrink-0">
                    <a href="{{ route('transaksi.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold text-xs sm:text-sm shadow-lg shadow-amber-500/20 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        + Input Transaksi
                    </a>
                    <a href="{{ route('buku-besar.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-white border border-slate-700 font-bold text-xs sm:text-sm transition">
                        <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                        Buku Besar
                    </a>
                    <a href="{{ route('neraca.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-amber-400 border border-slate-700 font-bold text-xs sm:text-sm transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/>
                        </svg>
                        Neraca
                    </a>
                </div>
            </div>
            <!-- Decorative Background Graphic -->
            <div class="absolute -right-12 -bottom-12 w-64 h-64 bg-amber-500/5 rounded-full blur-3xl pointer-events-none"></div>
        </div>

        <!-- 4 Kartu Metrik Ringkasan (Total Kas, Total Piutang, Total Utang, Laba Bulan Ini) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
            <!-- 1. Total Kas -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Kas</span>
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="text-xl sm:text-2xl font-black text-emerald-700">
                        Rp {{ number_format($totalKas, 0, ',', '.') }}
                    </div>
                    <div class="mt-2 flex items-center gap-1.5 text-xs text-slate-500 font-medium">
                        <span class="px-1.5 py-0.5 rounded bg-emerald-100 text-emerald-800 font-bold">110 Kas</span>
                        <span>Saldo likuid kas & bank</span>
                    </div>
                </div>
            </div>

            <!-- 2. Total Piutang -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Piutang</span>
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="text-xl sm:text-2xl font-black text-blue-700">
                        Rp {{ number_format($totalPiutang, 0, ',', '.') }}
                    </div>
                    <div class="mt-2 flex items-center gap-1.5 text-xs text-slate-500 font-medium">
                        <span class="px-1.5 py-0.5 rounded bg-blue-100 text-blue-800 font-bold">120 Piutang</span>
                        <span>Tagihan termin & pelanggan</span>
                    </div>
                </div>
            </div>

            <!-- 3. Total Utang -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Utang</span>
                    <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center font-bold">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="text-xl sm:text-2xl font-black text-rose-700">
                        Rp {{ number_format($totalUtang, 0, ',', '.') }}
                    </div>
                    <div class="mt-2 flex items-center gap-1.5 text-xs text-slate-500 font-medium">
                        <span class="px-1.5 py-0.5 rounded bg-rose-100 text-rose-800 font-bold">2. Utang</span>
                        <span>Kewajiban usaha & vendor</span>
                    </div>
                </div>
            </div>

            <!-- 4. Laba Bulan Ini -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Laba Bulan Ini</span>
                    <div class="w-10 h-10 rounded-xl {{ $labaBulanIni >= 0 ? 'bg-amber-50 text-amber-600' : 'bg-rose-50 text-rose-600' }} flex items-center justify-center font-bold">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="text-xl sm:text-2xl font-black {{ $labaBulanIni >= 0 ? 'text-amber-600' : 'text-rose-600' }}">
                        Rp {{ number_format($labaBulanIni, 0, ',', '.') }}
                    </div>
                    <div class="mt-2 flex items-center gap-1.5 text-xs {{ $labaBulanIni >= 0 ? 'text-emerald-600' : 'text-rose-600' }} font-bold">
                        <span>{{ $labaBulanIni >= 0 ? 'Surplus Operasional' : 'Defisit Operasional' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2 Kolom Grafik: Garis (Tren Pengeluaran vs Pemasukan 12 Bulan) & Donat (Pengeluaran per Akun Beban) -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Grafik Garis: Tren Pengeluaran & Pemasukan 12 Bulan Terakhir -->
            <div class="lg:col-span-2 bg-white rounded-2xl p-5 sm:p-6 border border-slate-200/80 shadow-sm">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2 pb-4 border-b border-slate-100">
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Grafik Pengeluaran & Pemasukan (12 Bulan)</h3>
                        <p class="text-xs text-slate-500">Tren mutasi kas dan pembebanan biaya perusahaan</p>
                    </div>
                    <div class="flex items-center gap-4 text-xs font-bold">
                        <div class="flex items-center gap-1.5">
                            <span class="w-3 h-3 rounded-full bg-rose-500"></span>
                            <span>Pengeluaran</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                            <span>Pemasukan</span>
                        </div>
                    </div>
                </div>
                <div class="mt-4 h-72 sm:h-80">
                    <canvas id="lineChart12Months"></canvas>
                </div>
            </div>

            <!-- Grafik Donat: Pengeluaran per Akun Beban (5. Beban) -->
            <div class="bg-white rounded-2xl p-5 sm:p-6 border border-slate-200/80 shadow-sm flex flex-col">
                <div class="pb-4 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Grafik Pengeluaran per Akun</h3>
                        <p class="text-xs text-slate-500">Distribusi beban operasional (5. Beban)</p>
                    </div>
                    <a href="{{ route('pengeluaran.index') }}" class="text-[11px] font-bold text-rose-600 hover:text-rose-700">Detail &rarr;</a>
                </div>
                <div class="mt-4 flex-1 flex items-center justify-center relative min-h-[220px]">
                    @if(count($donutData) > 0)
                        <canvas id="doughnutChartCategories"></canvas>
                    @else
                        <p class="text-xs text-slate-400 italic">Belum ada data pengeluaran.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Tabel 10 Transaksi Terbaru -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="px-5 sm:px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-bold text-slate-900">10 Transaksi Terbaru</h3>
                    <p class="text-xs text-slate-500">Pencatatan mutasi kas dan buku besar terkini</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('transaksi.create') }}" class="text-xs font-bold text-amber-600 hover:text-amber-700 flex items-center gap-1">
                        + Input Baru
                    </a>
                    <span class="text-slate-300">|</span>
                    <a href="{{ route('transaksi.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-800 flex items-center gap-1">
                        Lihat Semua Transaksi
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50/80 text-slate-600 font-bold border-b border-slate-200 uppercase tracking-wider text-[11px]">
                            <th class="px-5 py-3">Bukti / Tanggal</th>
                            <th class="px-5 py-3">Keterangan</th>
                            <th class="px-5 py-3">Akun & Golongan</th>
                            <th class="px-5 py-3">Jenis</th>
                            <th class="px-5 py-3 text-right">Nominal</th>
                            <th class="px-5 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($recentTransactions as $tx)
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="px-5 py-3 whitespace-nowrap">
                                    <div class="font-bold text-slate-900">{{ $tx->nomor_bukti }}</div>
                                    <div class="text-[11px] text-slate-500">{{ $tx->formatted_tanggal }}</div>
                                </td>
                                <td class="px-5 py-3 max-w-xs">
                                    <div class="font-bold text-slate-800 truncate">{{ $tx->deskripsi }}</div>
                                    <div class="text-[11px] text-slate-500 truncate">{{ $tx->pihak_terkait ?: '-' }}</div>
                                </td>
                                <td class="px-5 py-3 whitespace-nowrap">
                                    @if($tx->account)
                                        <div class="font-bold text-slate-900">{{ $tx->account->kode_nama }}</div>
                                        <div class="text-[11px] text-slate-500">{{ $tx->account->golongan }}</div>
                                    @else
                                        <div class="font-bold text-slate-700">{{ $tx->category ? $tx->category->nama : '-' }}</div>
                                        <div class="text-[11px] text-slate-400">Kategori Umum</div>
                                    @endif
                                </td>
                                <td class="px-5 py-3 whitespace-nowrap">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold {{ $tx->tipe === 'pemasukan' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $tx->tipe === 'pemasukan' ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                                        {{ ucfirst($tx->tipe) }}
                                    </span>
                                </td>
                                <td class="px-5 py-3 whitespace-nowrap text-right font-black {{ $tx->tipe === 'pemasukan' ? 'text-emerald-600' : 'text-slate-900' }}">
                                    {{ $tx->formatted_jumlah }}
                                </td>
                                <td class="px-5 py-3 whitespace-nowrap text-center">
                                    <a href="{{ route('transaksi.show', $tx) }}" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-[11px] font-bold transition">
                                        <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-10 text-center text-slate-400">
                                    Belum ada catatan transaksi.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Chart.js Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. Line Chart: 12 Bulan Terakhir (Pengeluaran vs Pemasukan)
            const ctxLine = document.getElementById('lineChart12Months');
            if (ctxLine) {
                new Chart(ctxLine, {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($chartLabels) !!},
                        datasets: [
                            {
                                label: 'Pengeluaran',
                                data: {!! json_encode($chartExpense) !!},
                                borderColor: '#ef4444',
                                backgroundColor: 'rgba(239, 68, 68, 0.08)',
                                borderWidth: 2.5,
                                tension: 0.35,
                                fill: true,
                                pointRadius: 3,
                                pointHoverRadius: 6,
                            },
                            {
                                label: 'Pemasukan',
                                data: {!! json_encode($chartIncome) !!},
                                borderColor: '#10b981',
                                backgroundColor: 'rgba(16, 185, 129, 0.08)',
                                borderWidth: 2.5,
                                tension: 0.35,
                                fill: true,
                                pointRadius: 3,
                                pointHoverRadius: 6,
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: {
                            intersect: false,
                            mode: 'index',
                        },
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.dataset.label || '';
                                        if (label) {
                                            label += ': ';
                                        }
                                        if (context.parsed.y !== null) {
                                            label += 'Rp ' + new Intl.NumberFormat('id-ID').format(context.parsed.y);
                                        }
                                        return label;
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        if (value >= 1000000) {
                                            return 'Rp ' + (value / 1000000) + ' Jt';
                                        }
                                        return 'Rp ' + value;
                                    },
                                    font: { size: 10 }
                                },
                                grid: { color: '#f1f5f9' }
                            },
                            x: {
                                ticks: { font: { size: 10 } },
                                grid: { display: false }
                            }
                        }
                    }
                });
            }

            // 2. Doughnut Chart: Pengeluaran per Akun Beban
            const ctxDonut = document.getElementById('doughnutChartCategories');
            if (ctxDonut) {
                new Chart(ctxDonut, {
                    type: 'doughnut',
                    data: {
                        labels: {!! json_encode($donutLabels) !!},
                        datasets: [{
                            data: {!! json_encode($donutData) !!},
                            backgroundColor: {!! json_encode($donutColors) !!},
                            borderWidth: 2,
                            borderColor: '#ffffff',
                            hoverOffset: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    boxWidth: 12,
                                    font: { size: 11 },
                                    padding: 10
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const label = context.label || '';
                                        const value = context.parsed || 0;
                                        return label + ': Rp ' + new Intl.NumberFormat('id-ID').format(value);
                                    }
                                }
                            }
                        },
                        cutout: '68%'
                    }
                });
            }
        });
    </script>
</x-app-layout>
