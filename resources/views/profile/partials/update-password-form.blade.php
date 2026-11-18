<section>
    <header>
        <h2 class="text-base font-extrabold text-slate-900">
            Perbarui Kata Sandi Akun
        </h2>

        <p class="mt-1 text-xs text-slate-500">
            Pastikan akun Anda menggunakan kata sandi yang aman dan tidak mudah ditebak untuk melindungi data keuangan perusahaan.
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-4">
        @csrf
        @method('put')

        <div>
            <label for="update_password_current_password" class="block text-xs font-bold text-slate-700 uppercase mb-1">
                Kata Sandi Saat Ini <span class="text-rose-500">*</span>
            </label>
            <input id="update_password_current_password" name="current_password" type="password" required autocomplete="current-password" placeholder="Masukkan kata sandi lama Anda" class="w-full text-xs rounded-xl border-slate-300 focus:border-amber-500 focus:ring-amber-500" />
            <x-input-error :messages="$errors->get('current_password')" class="mt-1" />
        </div>

        <div>
            <label for="update_password_password" class="block text-xs font-bold text-slate-700 uppercase mb-1">
                Kata Sandi Baru <span class="text-rose-500">*</span>
            </label>
            <input id="update_password_password" name="password" type="password" required autocomplete="new-password" placeholder="Minimal 8 karakter" class="w-full text-xs rounded-xl border-slate-300 focus:border-amber-500 focus:ring-amber-500" />
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <div>
            <label for="update_password_password_confirmation" class="block text-xs font-bold text-slate-700 uppercase mb-1">
                Konfirmasi Kata Sandi Baru <span class="text-rose-500">*</span>
            </label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password" placeholder="Ketik ulang kata sandi baru" class="w-full text-xs rounded-xl border-slate-300 focus:border-amber-500 focus:ring-amber-500" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button type="submit" class="px-5 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-amber-400 font-extrabold text-xs shadow-md transition">
                Simpan Kata Sandi
            </button>
        </div>
    </form>
</section>
