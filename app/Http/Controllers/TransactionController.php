<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionRequest;
use App\Models\Account;
use App\Models\ActivityLog;
use App\Models\Category;
use App\Models\CompanySetting;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TransactionController extends Controller
{
    /**
     * Tampilkan semua transaksi (gabungan pemasukan dan pengeluaran).
     */
    public function index(Request $request)
    {
        $query = Transaction::with(['category', 'account', 'user']);

        // Filter tipe
        if ($request->filled('tipe') && in_array($request->tipe, ['pemasukan', 'pengeluaran'])) {
            $query->where('tipe', $request->tipe);
        }

        // Filter akun 7 golongan
        if ($request->filled('account_id')) {
            $query->where('account_id', $request->account_id);
        }

        // Filter golongan akun
        if ($request->filled('golongan')) {
            $query->whereHas('account', function ($q) use ($request) {
                $q->where('golongan', $request->golongan);
            });
        }

        // Filter kategori
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter metode pembayaran
        if ($request->filled('metode_pembayaran')) {
            $query->where('metode_pembayaran', $request->metode_pembayaran);
        }

        // Filter rentang tanggal
        if ($request->filled('start_date')) {
            $query->whereDate('tanggal', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('tanggal', '<=', $request->end_date);
        }

        // Pencarian deskripsi, nomor bukti, atau pihak terkait
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor_bukti', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%")
                  ->orWhere('pihak_terkait', 'like', "%{$search}%");
            });
        }

        // Pengurutan
        $sortField = $request->get('sort', 'tanggal');
        $sortDirection = $request->get('direction', 'desc');
        if (in_array($sortField, ['tanggal', 'nomor_bukti', 'jumlah', 'tipe'])) {
            $query->orderBy($sortField, $sortDirection === 'asc' ? 'asc' : 'desc');
        } else {
            $query->latest('tanggal')->latest('id');
        }

        $perPage = (int) $request->get('per_page', 15);
        $transactions = $query->paginate($perPage)->withQueryString();

        $accounts = Account::orderBy('kode')->get();
        $categories = Category::orderBy('tipe')->orderBy('nama')->get();
        $totalPemasukan = (clone $query)->where('tipe', 'pemasukan')->sum('jumlah');
        $totalPengeluaran = (clone $query)->where('tipe', 'pengeluaran')->sum('jumlah');

        return view('transactions.index', compact(
            'transactions',
            'accounts',
            'categories',
            'totalPemasukan',
            'totalPengeluaran'
        ));
    }

    /**
     * Tampilkan daftar transaksi pemasukan.
     */
    public function pemasukan(Request $request)
    {
        $request->merge(['tipe' => 'pemasukan']);
        $query = Transaction::with(['category', 'account', 'user'])->where('tipe', 'pemasukan');

        if ($request->filled('account_id')) {
            $query->where('account_id', $request->account_id);
        }
        if ($request->filled('metode_pembayaran')) {
            $query->where('metode_pembayaran', $request->metode_pembayaran);
        }
        if ($request->filled('start_date')) {
            $query->whereDate('tanggal', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('tanggal', '<=', $request->end_date);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor_bukti', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%")
                  ->orWhere('pihak_terkait', 'like', "%{$search}%");
            });
        }

        $sortField = $request->get('sort', 'tanggal');
        $sortDirection = $request->get('direction', 'desc');
        if (in_array($sortField, ['tanggal', 'nomor_bukti', 'jumlah'])) {
            $query->orderBy($sortField, $sortDirection === 'asc' ? 'asc' : 'desc');
        } else {
            $query->latest('tanggal')->latest('id');
        }

        $perPage = (int) $request->get('per_page', 15);
        $transactions = $query->paginate($perPage)->withQueryString();
        $accounts = Account::whereIn('golongan_kode', ['4-100', '1-100', '3-100'])->orderBy('kode')->get();
        $categories = Category::pemasukan()->orderBy('nama')->get();
        $totalNominal = (clone $query)->sum('jumlah');

        return view('transactions.pemasukan', compact('transactions', 'accounts', 'categories', 'totalNominal'));
    }

    /**
     * Tampilkan daftar transaksi pengeluaran.
     */
    public function pengeluaran(Request $request)
    {
        $request->merge(['tipe' => 'pengeluaran']);
        $query = Transaction::with(['category', 'account', 'user'])->where('tipe', 'pengeluaran');

        if ($request->filled('account_id')) {
            $query->where('account_id', $request->account_id);
        }
        if ($request->filled('metode_pembayaran')) {
            $query->where('metode_pembayaran', $request->metode_pembayaran);
        }
        if ($request->filled('start_date')) {
            $query->whereDate('tanggal', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('tanggal', '<=', $request->end_date);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor_bukti', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%")
                  ->orWhere('pihak_terkait', 'like', "%{$search}%");
            });
        }

        $sortField = $request->get('sort', 'tanggal');
        $sortDirection = $request->get('direction', 'desc');
        if (in_array($sortField, ['tanggal', 'nomor_bukti', 'jumlah'])) {
            $query->orderBy($sortField, $sortDirection === 'asc' ? 'asc' : 'desc');
        } else {
            $query->latest('tanggal')->latest('id');
        }

        $perPage = (int) $request->get('per_page', 15);
        $transactions = $query->paginate($perPage)->withQueryString();
        $accounts = Account::whereIn('golongan_kode', ['5-100', '1-100', '2-100', '6-100'])->orderBy('kode')->get();
        $categories = Category::pengeluaran()->orderBy('nama')->get();
        $totalNominal = (clone $query)->sum('jumlah');

        return view('transactions.pengeluaran', compact('transactions', 'accounts', 'categories', 'totalNominal'));
    }

    /**
     * Tampilkan form pembuatan transaksi baru (Format 7 Golongan Akuntansi).
     */
    public function create(Request $request)
    {
        $tipe = $request->get('tipe', 'pengeluaran');
        if (!in_array($tipe, ['pemasukan', 'pengeluaran'])) {
            $tipe = 'pengeluaran';
        }

        $tanggalDefault = Carbon::now()->format('Y-m-d');
        $nomorBuktiOtomatis = Transaction::generateNomorBukti($tipe, $tanggalDefault);

        // Ambil semua akun 7 golongan terkelompok
        $accounts = Account::orderBy('kode')->get();
        $accountsByGolongan = $accounts->groupBy('golongan');

        $categories = Category::where('tipe', $tipe)->orderBy('nama')->get();

        return view('transactions.create', compact(
            'tipe',
            'tanggalDefault',
            'nomorBuktiOtomatis',
            'accounts',
            'accountsByGolongan',
            'categories'
        ));
    }

    /**
     * Simpan transaksi baru ke database dan posting otomatis ke jurnal berpasangan.
     */
    public function store(TransactionRequest $request)
    {
        $validated = $request->validated();

        $tipe = $validated['tipe'];
        $nomorBukti = Transaction::generateNomorBukti($tipe, $validated['tanggal']);

        // Pastikan category_id valid
        $categoryId = $validated['category_id'] ?? null;
        if (!$categoryId) {
            $cat = Category::where('tipe', $tipe)->first();
            $categoryId = $cat ? $cat->id : 1;
        }

        // Tentukan account_id jika belum diisi
        $accountId = $validated['account_id'] ?? null;
        if (!$accountId) {
            if ($tipe === 'pemasukan') {
                $acc = Account::where('kode', '410')->first() ?: Account::pendapatan()->first();
            } else {
                $acc = Account::where('kode', '510')->first() ?: Account::beban()->first();
            }
            $accountId = $acc?->id;
        }

        // Upload lampiran bukti/nota jika ada
        $lampiranPath = null;
        if ($request->hasFile('lampiran')) {
            $file = $request->file('lampiran');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $lampiranPath = $file->storeAs('lampiran', $filename, 'public');
        }

        $transaction = Transaction::create([
            'nomor_bukti' => $nomorBukti,
            'tanggal' => $validated['tanggal'],
            'tipe' => $tipe,
            'category_id' => $categoryId,
            'account_id' => $accountId,
            'deskripsi' => $validated['deskripsi'],
            'jumlah' => $validated['jumlah'],
            'metode_pembayaran' => $validated['metode_pembayaran'] ?? 'transfer',
            'pihak_terkait' => $validated['pihak_terkait'] ?: '-',
            'lampiran' => $lampiranPath,
            'user_id' => Auth::id() ?: 1,
        ]);

        // Catat Log Aktivitas
        $tipeCapital = ucfirst($transaction->tipe);
        $namaAkun = $transaction->account ? " [{$transaction->account->kode_nama}]" : '';
        ActivityLog::record(
            'tambah',
            'Transaksi',
            "Menambahkan {$tipeCapital} nomor bukti {$transaction->nomor_bukti}{$namaAkun} sebesar {$transaction->formatted_jumlah} ({$transaction->deskripsi})",
            Auth::id() ?: 1
        );

        $redirectRoute = $transaction->tipe === 'pemasukan' ? 'transaksi.pemasukan' : 'transaksi.pengeluaran';
        return redirect()->route($redirectRoute)->with('success', "Transaksi {$transaction->tipe} {$transaction->nomor_bukti} berhasil disimpan dan dicatat ke Jurnal & Buku Besar.");
    }

    /**
     * Tampilkan detail transaksi.
     */
    public function show(Transaction $transaction)
    {
        $transaction->load(['category', 'account', 'journalEntries.account', 'user']);
        $company = CompanySetting::getAllKeyed();

        return view('transactions.show', compact('transaction', 'company'));
    }

    /**
     * Tampilkan form ubah transaksi.
     */
    public function edit(Transaction $transaction)
    {
        $accounts = Account::orderBy('kode')->get();
        $accountsByGolongan = $accounts->groupBy('golongan');
        $categories = Category::where('tipe', $transaction->tipe)->orderBy('nama')->get();

        return view('transactions.edit', compact('transaction', 'accounts', 'accountsByGolongan', 'categories'));
    }

    /**
     * Perbarui data transaksi dan sinkronkan jurnal otomatis.
     */
    public function update(TransactionRequest $request, Transaction $transaction)
    {
        $validated = $request->validated();

        $lampiranPath = $transaction->lampiran;
        if ($request->hasFile('lampiran')) {
            if ($transaction->lampiran && Storage::disk('public')->exists($transaction->lampiran)) {
                Storage::disk('public')->delete($transaction->lampiran);
            }
            $file = $request->file('lampiran');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $lampiranPath = $file->storeAs('lampiran', $filename, 'public');
        }

        $categoryId = $validated['category_id'] ?? $transaction->category_id;
        $accountId = $validated['account_id'] ?? $transaction->account_id;

        $transaction->update([
            'tanggal' => $validated['tanggal'],
            'tipe' => $validated['tipe'],
            'category_id' => $categoryId,
            'account_id' => $accountId,
            'deskripsi' => $validated['deskripsi'],
            'jumlah' => $validated['jumlah'],
            'metode_pembayaran' => $validated['metode_pembayaran'] ?? $transaction->metode_pembayaran,
            'pihak_terkait' => $validated['pihak_terkait'] ?: ($transaction->pihak_terkait ?: '-'),
            'lampiran' => $lampiranPath,
        ]);

        ActivityLog::record(
            'edit',
            'Transaksi',
            "Memperbarui transaksi {$transaction->nomor_bukti} menjadi {$transaction->formatted_jumlah} ({$transaction->deskripsi})",
            Auth::id() ?: 1
        );

        $redirectRoute = $transaction->tipe === 'pemasukan' ? 'transaksi.pemasukan' : 'transaksi.pengeluaran';
        return redirect()->route($redirectRoute)->with('success', "Transaksi {$transaction->nomor_bukti} berhasil diperbarui.");
    }

    /**
     * Hapus transaksi (Soft Delete).
     */
    public function destroy(Transaction $transaction)
    {
        $nomorBukti = $transaction->nomor_bukti;
        $jumlah = $transaction->formatted_jumlah;

        $transaction->delete();

        ActivityLog::record(
            'hapus',
            'Transaksi',
            "Menghapus (soft delete) transaksi {$nomorBukti} sebesar {$jumlah}",
            Auth::id() ?: 1
        );

        return redirect()->back()->with('success', "Transaksi {$nomorBukti} berhasil dipindahkan ke tempat sampah.");
    }
}
