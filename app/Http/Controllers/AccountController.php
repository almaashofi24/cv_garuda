<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    /**
     * Daftar 7 Golongan Akun (Chart of Accounts).
     */
    public function index(Request $request)
    {
        $golonganList = [
            '1. Harta' => '1-100',
            '2. Utang' => '2-100',
            '3. Modal' => '3-100',
            '4. Pendapatan' => '4-100',
            '5. Beban' => '5-100',
            '6. Prive' => '6-100',
            '7. Ikhtisar Laba Rugi' => '7-100',
        ];

        $query = Account::withCount('journalEntries');

        // Filter tab Golongan
        if ($request->filled('golongan') && array_key_exists($request->golongan, $golonganList)) {
            $query->where('golongan', $request->golongan);
        }

        // Pencarian nama atau kode akun
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode', 'like', "%{$search}%")
                  ->orWhere('nama', 'like', "%{$search}%")
                  ->orWhere('golongan', 'like', "%{$search}%");
            });
        }

        $accounts = $query->orderBy('kode')->get();

        // Hitung total saldo per golongan
        $accountsGrouped = $accounts->groupBy('golongan');

        return view('accounts.index', compact('accounts', 'accountsGrouped', 'golonganList'));
    }

    /**
     * Simpan akun baru ke 7 golongan.
     */
    public function store(Request $request)
    {
        $golonganMap = [
            '1. Harta' => '1-100',
            '2. Utang' => '2-100',
            '3. Modal' => '3-100',
            '4. Pendapatan' => '4-100',
            '5. Beban' => '5-100',
            '6. Prive' => '6-100',
            '7. Ikhtisar Laba Rugi' => '7-100',
        ];

        $validated = $request->validate([
            'kode' => ['required', 'string', 'max:20', 'unique:accounts,kode'],
            'golongan' => ['required', 'in:' . implode(',', array_keys($golonganMap))],
            'nama' => ['required', 'string', 'max:150'],
            'saldo_normal' => ['required', 'in:Debit,Kredit,Penutup'],
            'saldo_awal' => ['nullable', 'numeric', 'min:0'],
            'deskripsi' => ['nullable', 'string', 'max:255'],
        ], [
            'kode.required' => 'Kode akun wajib diisi.',
            'kode.unique' => 'Kode akun ini sudah terdaftar. Gunakan kode lain.',
            'golongan.required' => 'Golongan akun wajib dipilih.',
            'nama.required' => 'Nama akun wajib diisi.',
            'saldo_normal.required' => 'Jenis saldo normal (Debit/Kredit) wajib dipilih.',
        ]);

        $golonganKode = $golonganMap[$validated['golongan']] ?? '1-100';

        $account = Account::create([
            'kode' => $validated['kode'],
            'golongan_kode' => $golonganKode,
            'golongan' => $validated['golongan'],
            'nama' => $validated['nama'],
            'saldo_normal' => $validated['saldo_normal'],
            'saldo_awal' => $validated['saldo_awal'] ?? 0,
            'deskripsi' => $validated['deskripsi'] ?? null,
            'is_locked' => false,
        ]);

        ActivityLog::record(
            'tambah',
            'Akun',
            "Menambahkan akun akuntansi {$account->kode} - {$account->nama} ({$account->golongan})",
            Auth::id() ?: 1
        );

        return redirect()->route('akun.index', ['golongan' => $account->golongan])
            ->with('success', "Akun {$account->kode} - {$account->nama} berhasil ditambahkan ke Golongan {$account->golongan}.");
    }

    /**
     * Perbarui akun.
     */
    public function update(Request $request, Account $account)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:150'],
            'saldo_normal' => ['required', 'in:Debit,Kredit,Penutup'],
            'saldo_awal' => ['nullable', 'numeric', 'min:0'],
            'deskripsi' => ['nullable', 'string', 'max:255'],
        ], [
            'nama.required' => 'Nama akun wajib diisi.',
            'saldo_normal.required' => 'Jenis saldo normal wajib dipilih.',
        ]);

        $account->update([
            'nama' => $validated['nama'],
            'saldo_normal' => $validated['saldo_normal'],
            'saldo_awal' => $validated['saldo_awal'] ?? $account->saldo_awal,
            'deskripsi' => $validated['deskripsi'] ?? $account->deskripsi,
        ]);

        ActivityLog::record(
            'edit',
            'Akun',
            "Memperbarui akun {$account->kode} - {$account->nama}",
            Auth::id() ?: 1
        );

        return redirect()->route('akun.index', ['golongan' => $account->golongan])
            ->with('success', "Data akun {$account->kode} - {$account->nama} berhasil diperbarui.");
    }

    /**
     * Hapus akun.
     */
    public function destroy(Account $account)
    {
        if ($account->is_locked) {
            return redirect()->back()->with('error', "Akun inti {$account->kode} - {$account->nama} dilindungi sistem dan tidak dapat dihapus.");
        }

        if ($account->journalEntries()->exists() || $account->transactions()->exists()) {
            return redirect()->back()->with('error', "Akun {$account->kode} - {$account->nama} tidak dapat dihapus karena telah memiliki riwayat transaksi/jurnal.");
        }

        $nama = "{$account->kode} - {$account->nama}";
        $golongan = $account->golongan;
        $account->delete();

        ActivityLog::record(
            'hapus',
            'Akun',
            "Menghapus akun akuntansi {$nama}",
            Auth::id() ?: 1
        );

        return redirect()->route('akun.index', ['golongan' => $golongan])
            ->with('success', "Akun {$nama} berhasil dihapus.");
    }
}
