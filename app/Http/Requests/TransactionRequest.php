<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'tanggal' => ['required', 'date'],
            'tipe' => ['required', 'in:pemasukan,pengeluaran'],
            'account_id' => ['nullable', 'exists:accounts,id'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'deskripsi' => ['required', 'string', 'max:1000'],
            'jumlah' => ['required', 'numeric', 'min:1', 'max:999999999999.99'],
            'metode_pembayaran' => ['nullable', 'in:tunai,transfer'],
            'pihak_terkait' => ['nullable', 'string', 'max:150'],
            'lampiran' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ];
    }

    /**
     * Custom validation messages in Indonesian.
     */
    public function messages(): array
    {
        return [
            'tanggal.required' => 'Tanggal transaksi wajib diisi.',
            'tanggal.date' => 'Format tanggal tidak valid.',
            'tipe.required' => 'Jenis transaksi wajib dipilih.',
            'tipe.in' => 'Jenis transaksi harus berupa pemasukan atau pengeluaran.',
            'account_id.exists' => 'Akun yang dipilih tidak ditemukan dalam sistem.',
            'category_id.exists' => 'Kategori yang dipilih tidak valid.',
            'deskripsi.required' => 'Keterangan transaksi wajib diisi.',
            'deskripsi.max' => 'Keterangan tidak boleh melebihi 1000 karakter.',
            'jumlah.required' => 'Nominal transaksi wajib diisi.',
            'jumlah.numeric' => 'Nominal transaksi harus berupa angka yang valid.',
            'jumlah.min' => 'Nominal transaksi minimal adalah Rp 1.',
            'jumlah.max' => 'Nominal transaksi melebihi batas maksimum sistem.',
            'metode_pembayaran.in' => 'Metode pembayaran harus berupa tunai atau transfer.',
            'lampiran.file' => 'Lampiran harus berupa file yang valid.',
            'lampiran.mimes' => 'Format lampiran yang diizinkan hanya JPG, JPEG, PNG, atau PDF.',
            'lampiran.max' => 'Ukuran lampiran bukti transaksi maksimal adalah 2 MB (2048 KB).',
        ];
    }
}
