<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'nama' => ['required', 'string', 'max:100'],
            'tipe' => ['required', 'in:pemasukan,pengeluaran'],
            'deskripsi' => ['nullable', 'string', 'max:255'],
            'warna' => ['nullable', 'string', 'max:30'],
        ];
    }

    /**
     * Custom validation messages in Indonesian.
     */
    public function messages(): array
    {
        return [
            'nama.required' => 'Nama kategori wajib diisi.',
            'nama.max' => 'Nama kategori tidak boleh melebihi 100 karakter.',
            'tipe.required' => 'Tipe kategori wajib dipilih.',
            'tipe.in' => 'Tipe kategori harus berupa pemasukan atau pengeluaran.',
            'deskripsi.max' => 'Deskripsi tidak boleh melebihi 255 karakter.',
        ];
    }
}

