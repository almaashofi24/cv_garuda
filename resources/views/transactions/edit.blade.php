<x-app-layout>
    <x-slot name="header">
        Ubah Transaksi {{ $transaction->nomor_bukti }}
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
        $selectedAccount = $transaction->account;
        $initialGolongan = $selectedAccount ? $selectedAccount->golongan : ($transaction->tipe === 'pemasukan' ? '4. Pendapatan' : '5. Beban');
    @endphp

    <div class="max-w-4xl mx-auto space-y-6" x-data="{
        tipe: '{{ $transaction->tipe }}',
        golongan: '{{ old('golongan', $initialGolongan) }}',
        accountId: '{{ old('account_id', $transaction->account_id) }}',
        jumlah: '{{ old('jumlah', $transaction->jumlah) }}',
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
        },

        get formattedRupiah() {
            if (!this.jumlah || isNaN(this.jumlah)) return 'Rp 0';
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(this.jumlah);
        }
    }">
        <!-- Header Card -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold uppercase {{ $transaction->tipe === 'pemasukan' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                        {{ $transaction->tipe }}
                    </span>
                    <span class="text-xs text-slate-500 font-mono font-bold">{{ $transaction->nomor_bukti }}</span>
                </div>
                <h2 class="text-xl font-black text-slate-900">
                    Edit Data Transaksi (7 Golongan Akun)
                </h2>
            </div>
            <a href="{{ route('transaksi.index') }}" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition">
                &larr; Kembali
            </a>
        </div>

        <!-- Form Card -->
        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-sm">
            <form method="POST" action="{{ route('transaksi.update', $transaction) }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <input type="hidden" name="tipe" value="{{ $transaction->tipe }}">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Nomor Bukti (Read-Only) -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Nomor Bukti
                        </label>
                        <input type="text" value="{{ $transaction->nomor_bukti }}" readonly class="w-full text-sm font-bold bg-slate-100 text-slate-600 rounded-xl border-slate-300 cursor-not-allowed">
                    </div>

                    <!-- Tanggal Transaksi -->
                    <div>
                        <label for="tanggal" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Tanggal Transaksi <span class="text-rose-500">*</span>
                        </label>
                        <input type="date" id="tanggal" name="tanggal" value="{{ old('tanggal', $transaction->tanggal->format('Y-m-d')) }}" required class="w-full text-sm rounded-xl border-slate-300 focus:border-amber-500 focus:ring-amber-500">
                    </div>
                </div>

                <!-- Keterangan Transaksi -->
                <div>
                    <label for="deskripsi" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Keterangan <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" id="deskripsi" name="deskripsi" value="{{ old('deskripsi', $transaction->deskripsi) }}" required class="w-full text-sm rounded-xl border-slate-300 focus:border-amber-500 focus:ring-amber-500">
                </div>

                <!-- Golongan Akun & Nama Akun -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 p-4 rounded-xl bg-slate-50 border border-slate-200">
                    <div>
                        <label class="block text-xs font-bold text-slate-800 uppercase tracking-wider mb-2">
                            Golongan Akun <span class="text-rose-500">*</span>
                        </label>
                        <select x-model="golongan" @change="onGolonganChange()" class="w-full text-sm font-semibold rounded-xl border-slate-300 focus:border-amber-500 bg-white">
                            <option value="5. Beban">5. Beban</option>
                            <option value="4. Pendapatan">4. Pendapatan</option>
                            <option value="1. Harta">1. Harta</option>
                            <option value="2. Utang">2. Utang</option>
                            <option value="3. Modal">3. Modal</option>
                            <option value="6. Prive">6. Prive</option>
                            <option value="7. Ikhtisar Laba Rugi">7. Ikhtisar Laba Rugi</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-800 uppercase tracking-wider mb-2">
                            Nama Akun <span class="text-rose-500">*</span>
                        </label>
                        <select name="account_id" x-model="accountId" required class="w-full text-sm font-bold text-slate-900 rounded-xl border-slate-300 focus:border-amber-500 bg-white">
                            <template x-for="acc in filteredAccounts" :key="acc.id">
                                <option :value="acc.id" x-text="acc.kode + ' - ' + acc.nama" :selected="acc.id == accountId"></option>
                            </template>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Nominal / Jumlah -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label for="jumlah" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                Nominal <span class="text-rose-500">*</span>
                            </label>
                            <span class="text-xs font-bold text-amber-600" x-text="formattedRupiah"></span>
                        </div>
                        <div class="relative">
                            <span class="absolute left-3 top-2.5 text-xs font-bold text-slate-400">Rp</span>
                            <input type="number" id="jumlah" name="jumlah" step="any" min="1" x-model="jumlah" required class="w-full pl-10 text-sm font-bold text-slate-900 rounded-xl border-slate-300 focus:border-amber-500 focus:ring-amber-500">
                        </div>
                    </div>

                    <!-- Metode Pembayaran -->
                    <div>
                        <label for="metode_pembayaran" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Metode Pembayaran
                        </label>
                        <select id="metode_pembayaran" name="metode_pembayaran" class="w-full text-sm rounded-xl border-slate-300 focus:border-amber-500 focus:ring-amber-500">
                            <option value="transfer" {{ old('metode_pembayaran', $transaction->metode_pembayaran) === 'transfer' ? 'selected' : '' }}>Transfer Bank / Rekening</option>
                            <option value="tunai" {{ old('metode_pembayaran', $transaction->metode_pembayaran) === 'tunai' ? 'selected' : '' }}>Tunai / Kas Langsung</option>
                        </select>
                    </div>
                </div>

                <!-- Pihak Terkait & Bukti -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label for="pihak_terkait" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Pihak Terkait (Vendor / Pelanggan / Karyawan)
                        </label>
                        <input type="text" id="pihak_terkait" name="pihak_terkait" value="{{ old('pihak_terkait', $transaction->pihak_terkait) }}" class="w-full text-sm rounded-xl border-slate-300 focus:border-amber-500">
                    </div>

                    <div>
                        <label for="lampiran" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Ganti Bukti / Nota (Opsional)
                        </label>
                        <input type="file" id="lampiran" name="lampiran" accept="image/*,.pdf" class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200">
                        @if($transaction->lampiran)
                            <p class="text-[11px] text-emerald-600 mt-1 font-semibold">Terdapat lampiran bukti tersimpan.</p>
                        @endif
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-200 flex items-center justify-end gap-3">
                    <a href="{{ route('transaksi.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-300 text-slate-700 hover:bg-slate-50 font-bold text-xs">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-black text-xs sm:text-sm shadow-md transition">
                        Perbarui Transaksi
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
