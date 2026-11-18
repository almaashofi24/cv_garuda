<x-app-layout>
    <x-slot name="header">
        Input Transaksi Keuangan
    </x-slot>

    @php
        $accountsData = $accounts->map(function($a) {
            return [
                'id' => $a->id,
                'kode' => $a->kode,
                'nama' => $a->nama,
                'golongan' => $a->golongan,
                'saldo_normal' => $a->saldo_normal,
            ];
        });
    @endphp

    <div class="max-w-4xl mx-auto space-y-6" x-data="{
        tipe: '{{ old('tipe', $tipe) }}',
        golongan: '{{ old('golongan', '5. Beban') }}',
        accountId: '{{ old('account_id', '') }}',
        jumlah: '{{ old('jumlah', '') }}',
        allAccounts: {{ Js::from($accountsData) }},

        get filteredAccounts() {
            if (!this.golongan) return this.allAccounts;
            return this.allAccounts.filter(a => a.golongan === this.golongan);
        },

        onGolonganChange() {
            const list = this.filteredAccounts;
            if (list.length > 0) {
                this.accountId = list[0].id;
            } else {
                this.accountId = '';
            }

            // Otomatis tentukan Jenis Transaksi (Pemasukan / Pengeluaran)
            if (this.golongan.includes('Beban') || this.golongan.includes('Prive')) {
                this.tipe = 'pengeluaran';
            } else if (this.golongan.includes('Pendapatan')) {
                this.tipe = 'pemasukan';
            }
        },

        get formattedRupiah() {
            if (!this.jumlah || isNaN(this.jumlah)) return 'Rp 0';
            return 'Rp ' . new Intl.NumberFormat('id-ID').format(this.jumlah);
        },

        init() {
            if (!this.accountId) {
                this.onGolonganChange();
            }
        }
    }">
        <!-- Header Card -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-amber-50 text-amber-700 text-xs font-bold border border-amber-200 mb-2">
                    <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                    Sistem 7 Golongan Akuntansi CV. Garuda
                </div>
                <h2 class="text-xl sm:text-2xl font-black text-slate-900">
                    Form Tambah Transaksi
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">
                    Otomatis masuk ke <span class="font-bold text-slate-700">Jurnal</span> &rarr; <span class="font-bold text-slate-700">Buku Besar</span> &rarr; <span class="font-bold text-slate-700">Laporan Keuangan</span>.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('buku-besar.index') }}" class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition">
                    Lihat Buku Besar
                </a>
                <a href="{{ route('dashboard') }}" class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition">
                    &larr; Dashboard
                </a>
            </div>
        </div>

        <!-- Form Card Sesuai Spesifikasi Format Form Input Transaksi -->
        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-sm">
            <form method="POST" action="{{ route('transaksi.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- 1. Tanggal: [ ____ ] -->
                <div>
                    <label for="tanggal" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Tanggal <span class="text-rose-500">*</span>
                    </label>
                    <input 
                        type="date" 
                        id="tanggal" 
                        name="tanggal" 
                        value="{{ old('tanggal', $tanggalDefault) }}" 
                        required 
                        class="w-full text-sm rounded-xl border-slate-300 focus:border-amber-500 focus:ring-amber-500 @error('tanggal') border-rose-500 @enderror"
                    >
                    @error('tanggal')
                        <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- 2. Keterangan: [ ____ ] Contoh: Bayar gaji karyawan -->
                <div>
                    <label for="deskripsi" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Keterangan <span class="text-rose-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="deskripsi" 
                        name="deskripsi" 
                        value="{{ old('deskripsi') }}" 
                        required 
                        placeholder="Contoh: Bayar gaji karyawan" 
                        class="w-full text-sm rounded-xl border-slate-300 focus:border-amber-500 focus:ring-amber-500 @error('deskripsi') border-rose-500 @enderror"
                    >
                    <p class="text-[11px] text-slate-400 mt-1">Uraian ringkas keterangan transaksi yang akan tercatat pada jurnal dan buku besar.</p>
                    @error('deskripsi')
                        <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- 3. Golongan Akun & Nama Akun -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 p-4 rounded-xl bg-slate-50 border border-slate-200">
                    <!-- Golongan Akun: [ Pilih: Beban / Pendapatan / Harta / Utang / Modal / Prive / Ikhtisar ] -->
                    <div>
                        <label for="golongan_select" class="block text-xs font-bold text-slate-800 uppercase tracking-wider mb-2">
                            Golongan Akun <span class="text-rose-500">*</span>
                        </label>
                        <select 
                            id="golongan_select" 
                            name="golongan"
                            x-model="golongan" 
                            @change="onGolonganChange()"
                            class="w-full text-sm font-semibold rounded-xl border-slate-300 focus:border-amber-500 focus:ring-amber-500 bg-white"
                        >
                            <option value="5. Beban">5. Beban (Biaya Gaji, Sewa, Listrik, BBM Transportasi, dll.)</option>
                            <option value="4. Pendapatan">4. Pendapatan (Pendapatan Jasa, Pendapatan Lain-lain)</option>
                            <option value="1. Harta">1. Harta (Kas, Piutang Usaha, Perlengkapan, Peralatan)</option>
                            <option value="2. Utang">2. Utang (Utang Usaha, Utang Bank, Utang Gaji)</option>
                            <option value="3. Modal">3. Modal (Modal Pemilik, Modal Tambahan)</option>
                            <option value="6. Prive">6. Prive (Prive Pemilik)</option>
                            <option value="7. Ikhtisar Laba Rugi">7. Ikhtisar Laba Rugi (Penutup)</option>
                        </select>
                        <p class="text-[11px] text-slate-500 mt-1">Pilih salah satu dari 7 golongan akuntansi.</p>
                    </div>

                    <!-- Nama Akun: [ Pilih: Beban Gaji, dll. (terfilter otomatis) ] -->
                    <div>
                        <label for="account_id" class="block text-xs font-bold text-slate-800 uppercase tracking-wider mb-2">
                            Nama Akun <span class="text-rose-500">*</span>
                        </label>
                        <select 
                            id="account_id" 
                            name="account_id" 
                            x-model="accountId"
                            required 
                            class="w-full text-sm font-bold text-slate-900 rounded-xl border-slate-300 focus:border-amber-500 focus:ring-amber-500 bg-white @error('account_id') border-rose-500 @enderror"
                        >
                            <template x-for="acc in filteredAccounts" :key="acc.id">
                                <option :value="acc.id" x-text="acc.kode + ' - ' + acc.nama + ' (' + acc.saldo_normal + ')'"></option>
                            </template>
                        </select>
                        <p class="text-[11px] text-slate-500 mt-1">Nama akun spesifik yang didebit / dikredit.</p>
                        @error('account_id')
                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- 4. Jenis: [ Pemasukan / Pengeluaran ] -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Jenis Transaksi Kas <span class="text-rose-500">*</span>
                    </label>
                    <div class="grid grid-cols-2 gap-4">
                        <label 
                            :class="tipe === 'pengeluaran' ? 'border-rose-500 bg-rose-50 text-rose-900 ring-2 ring-rose-500/20' : 'border-slate-200 hover:bg-slate-50 text-slate-700'"
                            class="relative flex items-center gap-3 p-4 rounded-xl border cursor-pointer transition"
                        >
                            <input type="radio" name="tipe" value="pengeluaran" x-model="tipe" class="text-rose-600 focus:ring-rose-500">
                            <div>
                                <span class="block text-sm font-bold">Pengeluaran (Kas Keluar / BKK)</span>
                                <span class="block text-[11px] text-slate-500">Kredit Kas &rarr; Beban/Harta/Utang Bertambah</span>
                            </div>
                        </label>

                        <label 
                            :class="tipe === 'pemasukan' ? 'border-emerald-500 bg-emerald-50 text-emerald-900 ring-2 ring-emerald-500/20' : 'border-slate-200 hover:bg-slate-50 text-slate-700'"
                            class="relative flex items-center gap-3 p-4 rounded-xl border cursor-pointer transition"
                        >
                            <input type="radio" name="tipe" value="pemasukan" x-model="tipe" class="text-emerald-600 focus:ring-emerald-500">
                            <div>
                                <span class="block text-sm font-bold">Pemasukan (Kas Masuk / BKM)</span>
                                <span class="block text-[11px] text-slate-500">Debit Kas &rarr; Pendapatan/Modal Bertambah</span>
                            </div>
                        </label>
                    </div>
                    @error('tipe')
                        <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- 5. Nominal: [ Rp ____ ] -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label for="jumlah" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                            Nominal <span class="text-rose-500">*</span>
                        </label>
                        <span class="text-xs font-black px-3 py-1 rounded-lg bg-amber-100 text-amber-900" x-text="formattedRupiah"></span>
                    </div>
                    <div class="relative">
                        <span class="absolute left-4 top-3 text-slate-400 font-bold text-sm">Rp</span>
                        <input 
                            type="number" 
                            id="jumlah" 
                            name="jumlah" 
                            x-model="jumlah" 
                            value="{{ old('jumlah') }}" 
                            min="1" 
                            step="any" 
                            required 
                            placeholder="0" 
                            class="w-full text-base font-black pl-12 pr-4 py-2.5 rounded-xl border-slate-300 focus:border-amber-500 focus:ring-amber-500 @error('jumlah') border-rose-500 @enderror"
                        >
                    </div>
                    @error('jumlah')
                        <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Opsi Tambahan (Metode Pembayaran & Pihak Terkait) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-2 border-t border-slate-100">
                    <div>
                        <label for="metode_pembayaran" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Metode Pembayaran
                        </label>
                        <select id="metode_pembayaran" name="metode_pembayaran" class="w-full text-sm rounded-xl border-slate-300 focus:border-amber-500 focus:ring-amber-500">
                            <option value="transfer" {{ old('metode_pembayaran', 'transfer') === 'transfer' ? 'selected' : '' }}>Transfer Bank / Rekening</option>
                            <option value="tunai" {{ old('metode_pembayaran') === 'tunai' ? 'selected' : '' }}>Tunai / Kas Langsung</option>
                        </select>
                    </div>

                    <div>
                        <label for="pihak_terkait" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Pihak Terkait (Vendor / Pelanggan / Karyawan)
                        </label>
                        <input 
                            type="text" 
                            id="pihak_terkait" 
                            name="pihak_terkait" 
                            value="{{ old('pihak_terkait') }}" 
                            placeholder="Contoh: Karyawan Operasional / PLN / Toko Bangunan" 
                            class="w-full text-sm rounded-xl border-slate-300 focus:border-amber-500 focus:ring-amber-500"
                        >
                    </div>
                </div>

                <!-- 6. Bukti / Nota: [ Upload Foto ] -->
                <div class="pt-2 border-t border-slate-100">
                    <label for="lampiran" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Bukti / Nota [ Upload Foto ]
                    </label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-slate-300 border-dashed rounded-xl hover:border-amber-500 transition">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-10 w-10 text-slate-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-xs text-slate-600 justify-center">
                                <label for="lampiran" class="relative cursor-pointer bg-white rounded-md font-bold text-amber-600 hover:text-amber-500 focus-within:outline-none">
                                    <span>Pilih file foto/nota</span>
                                    <input id="lampiran" name="lampiran" type="file" accept="image/*,.pdf" class="sr-only">
                                </label>
                                <p class="pl-1 text-slate-500">atau seret ke sini</p>
                            </div>
                            <p class="text-[11px] text-slate-400">JPG, PNG, atau PDF (Maksimal 2 MB)</p>
                        </div>
                    </div>
                    @error('lampiran')
                        <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- 7. Tombol: [ Simpan ] -->
                <div class="pt-4 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="text-xs text-slate-500 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>Sistem otomatis mencatat jurnal berpasangan dan memperbarui buku besar.</span>
                    </div>

                    <div class="flex items-center gap-3 w-full sm:w-auto">
                        <a href="{{ route('dashboard') }}" class="w-1/2 sm:w-auto px-5 py-2.5 rounded-xl border border-slate-300 text-slate-700 hover:bg-slate-50 font-bold text-xs text-center transition">
                            Batal
                        </a>
                        <button type="submit" class="w-1/2 sm:w-auto px-6 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-black text-xs sm:text-sm shadow-lg shadow-amber-500/20 transition">
                            Simpan Transaksi
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
