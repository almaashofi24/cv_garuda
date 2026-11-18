<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\ActivityLog;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    /**
     * Tampilkan daftar kategori.
     */
    public function index(Request $request)
    {
        $query = Category::withCount('transactions');

        if ($request->filled('tipe') && in_array($request->tipe, ['pemasukan', 'pengeluaran'])) {
            $query->where('tipe', $request->tipe);
        }

        if ($request->filled('search')) {
            $query->where('nama', 'like', "%{$request->search}%");
        }

        $categories = $query->orderBy('tipe')->orderBy('nama')->paginate(15)->withQueryString();

        return view('categories.index', compact('categories'));
    }

    /**
     * Simpan kategori baru.
     */
    public function store(CategoryRequest $request)
    {
        $category = Category::create($request->validated());

        ActivityLog::record(
            'tambah',
            'Kategori',
            "Menambahkan kategori {$category->tipe} baru: '{$category->nama}'",
            Auth::id()
        );

        return redirect()->route('kategori.index')->with('success', "Kategori '{$category->nama}' berhasil ditambahkan.");
    }

    /**
     * Perbarui data kategori.
     */
    public function update(CategoryRequest $request, Category $category)
    {
        $oldName = $category->nama;
        $category->update($request->validated());

        ActivityLog::record(
            'ubah',
            'Kategori',
            "Mengubah kategori '{$oldName}' menjadi '{$category->nama}'",
            Auth::id()
        );

        return redirect()->route('kategori.index')->with('success', "Kategori '{$category->nama}' berhasil diperbarui.");
    }

    /**
     * Hapus kategori (dicegah bila sudah memiliki transaksi).
     */
    public function destroy(Category $category)
    {
        if ($category->transactions()->exists()) {
            return back()->with('error', "Kategori '{$category->nama}' tidak dapat dihapus karena masih digunakan pada data transaksi.");
        }

        $nama = $category->nama;
        $category->delete();

        ActivityLog::record(
            'hapus',
            'Kategori',
            "Menghapus kategori '{$nama}'",
            Auth::id()
        );

        return redirect()->route('kategori.index')->with('success', "Kategori '{$nama}' berhasil dihapus.");
    }
}

