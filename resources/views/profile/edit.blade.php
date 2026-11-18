<x-app-layout>
    <x-slot name="header">
        Profil Pengguna
    </x-slot>

    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Header Card -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <h2 class="text-xl font-black text-slate-900">Pengaturan Profil & Akun</h2>
                <p class="text-xs text-slate-500">Kelola informasi identitas akun pengguna Anda dalam sistem.</p>
            </div>
            <a href="{{ route('password.change') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-amber-400 text-xs font-bold transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                </svg>
                Menu Khusus Ubah Kata Sandi &rarr;
            </a>
        </div>

        <div class="p-6 sm:p-8 bg-white border border-slate-200 shadow-sm rounded-2xl">
            <div class="max-w-xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="p-6 sm:p-8 bg-white border border-slate-200 shadow-sm rounded-2xl">
            <div class="max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>
    </div>
</x-app-layout>
