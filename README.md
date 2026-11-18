# SISTEM PENCATATAN & LAPORAN KEUANGAN
## CV. GARUDA RAJA PERKASA DUABELAS

Aplikasi web modern pencatatan kas (pemasukan dan pengeluaran), pembukuan arus kas, analisis kinerja keuangan 12 bulan, laporan laba rugi, serta ekspor resmi berkas PDF (ber-kop surat & tanda tangan pimpinan) dan lembar kerja Excel.

---

### IDENTITAS PERUSAHAAN
* **Nama Perusahaan**: **CV. GARUDA RAJA PERKASA DUABELAS**
* **Alamat Kantor**: Jl. Trans Kalimantan Ruko Keling Kumang No.2, Simpang Ampar, Kec. Tayan Hilir, Kabupaten Sanggau, Kalimantan Barat 78564
* **Telepon**: +62 852-4500-1212
* **Email**: kontak@garudaraja.co.id
* **Pimpinan**: H. Sudirman, S.E. (Direktur Utama)
* **Staf Keuangan**: Siti Aminah, A.Md. (Staf Keuangan & Administrasi)

Identitas ini secara konsisten ditampilkan di **Sidebar Navigasi, Header Sistem, Halaman Login, Bukti Voucher Kas, serta Kop Surat Resmi Seluruh Laporan Cetak & Berkas PDF**.

---

### TECH STACK
* **Backend**: PHP 8.2+ dengan **Laravel 11**
* **Database**: MySQL / MariaDB (Database Migration, Seeder lengkap, & Eloquent ORM)
* **Frontend**: Blade + Tailwind CSS + Alpine.js
* **Visualisasi Grafik**: Chart.js (Grafik garis 12 bulan & donat per kategori)
* **Autentikasi**: Laravel Breeze
* **Manajemen Hak Akses**: Spatie Laravel Permission (Role: *Admin*, *Staf Keuangan*, *Viewer*)
* **Ekspor Dokumen PDF**: `barryvdh/laravel-dompdf` (Kop surat resmi & kolom tanda tangan)
* **Ekspor Dokumen Excel**: `maatwebsite/excel` (Format rekapitulasi rapi dengan formula total)
* **Lokalisasi**: Bahasa Indonesia, format mata uang Rupiah (`Rp 1.250.000`), tanggal `dd/mm/yyyy`, zona waktu `Asia/Pontianak` (WIB).

---

### ROLE & HAK AKSES PENGGUNA

Aplikasi ini dikonfigurasi secara eksklusif untuk **Administrator / Pimpinan Perusahaan**:

| Role | Keterangan & Hak Akses | Akun Login |
|---|---|---|
| **Admin / Pemilik** | Akses penuh seluruh sistem: Kategori, Profil Perusahaan, Input/Edit/Hapus Transaksi (Soft Delete), Log Aktivitas, dan Semua Laporan. | `admin@cvgaruda.com`<br>Password: `password` |

> **Catatan Keamanan**: Akses sistem dibatasi secara ketat hanya untuk pengguna berstatus Admin. Akun non-admin tidak diizinkan masuk ke aplikasi. Fitur pendaftaran akun umum (`/register`) dan menu Kelola Pengguna dinonaktifkan demi privasi dan integritas data keuangan perusahaan.

---

### FITUR UTAMA SISTEM

1. **Dashboard Eksekutif Keuangan**
   * 4 Kartu Ringkasan Metrik: Total Pemasukan Bulan Ini, Total Pengeluaran Bulan Ini, Laba/Surplus Bersih Bulan Ini, dan Saldo Kas Keseluruhan (All-time).
   * Grafik Garis Interaktif: Tren perbandingan Pemasukan vs Pengeluaran selama 12 bulan terakhir.
   * Grafik Donat Interaktif: Distribusi beban pengeluaran berdasarkan kategori.
   * Tabel 10 Transaksi Terkini dengan tombol detail voucher kas.

2. **Transaksi Pemasukan Kas (Bukti Kas Masuk / BKM)**
   * Nomor bukti otomatis urut per tanggal (Contoh: `BKM-20261029-0001`).
   * Tanggal transaksi, kategori pemasukan, sumber/pelanggan, metode bayar (Tunai / Transfer Bank).
   * Input nominal dengan preview format Rupiah live realtime.
   * Upload lampiran bukti transfer / kuitansi (JPG, PNG, PDF maks 2 MB).

3. **Transaksi Pengeluaran Kas (Bukti Kas Keluar / BKK)**
   * Nomor bukti otomatis urut per tanggal (Contoh: `BKK-20261029-0001`).
   * Tanggal transaksi, kategori pengeluaran, penerima/vendor, metode bayar (Tunai / Transfer Bank).
   * Upload nota belanja / faktur tagihan (JPG, PNG, PDF).

4. **Semua Transaksi & Pencarian Lanjutan**
   * Tampilan terpadu seluruh mutasi kas.
   * Filter multikriteria: Pencarian teks, jenis transaksi, kategori, metode pembayaran, dan rentang tanggal kustom.
   * Pengurutan (*sorting*) berdasarkan kolom tanggal, nomor bukti, maupun nominal.
   * Paginasi dinamis.
   * Cetak Voucher Kas langsung dari peramban dengan layout standar akuntansi.

5. **Pengelolaan Kategori Transaksi**
   * CRUD kategori pemasukan dan pengeluaran (Penjualan Material, Jasa Konstruksi, Sewa Alat Berat, Gaji Karyawan, BBM Armada, Pembelian Material, Listrik/Air, Servis Armada, dll).
   * Dilengkapi pemilih warna (*color picker*) untuk visualisasi grafik.
   * Proteksi integritas: Kategori yang sudah memiliki transaksi tidak dapat dihapus.

6. **Laporan & Ekspor Berkas**
   * Mode Laporan Fleksibel: **Harian**, **Bulanan**, **Tahunan**, dan **Rentang Tanggal Kustom**.
   * **Laporan Arus Kas**: Rekapitulasi mutasi kas, saldo awal/akhir, dan perincian per transaksi.
   * **Laporan Laba Rugi Sederhana**: Pengelompokan Pendapatan Operasional vs Beban Operasional beserta Laba/Rugi Bersih periode berjalan.
   * **Ekspor PDF Resmi**: Menghasilkan dokumen siap cetak A4 dengan Kop Surat CV. Garuda Raja Perkasa Duabelas serta kolom tanda tangan Direktur Utama & Bendahara/Staf Keuangan.
   * **Ekspor Excel**: File `.xlsx` rapi dengan judul instansi, heading tebal, dan format angka akuntansi.

7. **Keamanan, Ubah Kata Sandi & Audit Trail (Log Aktivitas)**
   * **Fitur Ubah Kata Sandi (`/ubah-password`)**: Menu khusus untuk seluruh pengguna (Admin, Staf Keuangan, Viewer) untuk mengganti kata sandi dengan verifikasi kata sandi saat ini, tombol intip sandi (show/hide), dan indikator kecocokan real-time.
   * Proteksi CSRF pada setiap formulir.
   * Validasi Form Request dengan pesan berbahasa Indonesia yang jelas.
   * Otorisasi role via middleware Spatie (`role:Admin`, `role:Admin|Staf Keuangan`).
   * **Soft Delete**: Menghapus transaksi tidak menghilangkannya secara permanen dari database (`deleted_at`).
   * **Log Aktivitas**: Merekam setiap penambahan, perubahan, penghapusan data, perubahan kata sandi, kegiatan login/logout, hingga waktu dan alamat IP pelaksana.

---

### STRUKTUR DATABASE

* `users`: Akun pengguna, email, dan kata sandi terenkripsi.
* `roles` & `permissions`: Struktur tabel Spatie Permission untuk manajemen wewenang.
* `categories`: Master kategori (`id`, `nama`, `tipe`: enum pemasukan/pengeluaran, `deskripsi`, `warna`, `timestamps`).
* `transactions`: Data mutasi keuangan (`id`, `nomor_bukti`, `tanggal`, `tipe`, `category_id`, `deskripsi`, `jumlah` *DECIMAL(15,2)*, `metode_pembayaran`, `pihak_terkait`, `lampiran`, `user_id`, `timestamps`, `deleted_at`).
  * Index pada `tanggal`, `tipe`, `metode_pembayaran`, serta foreign keys ke `categories` dan `users`.
* `activity_logs`: Riwayat audit trail (`id`, `user_id`, `aksi`, `model`, `detail`, `ip_address`, `user_agent`, `timestamps`).
* `company_settings`: Konfigurasi profil instansi dan pejabat penandatangan laporan.

---

### PANDUAN INSTALASI & MENJALANKAN APLIKASI

#### 1. Persyaratan Sistem
* PHP 8.2 atau lebih baru (ekstensi aktif: `pdo_mysql`, `gd`, `zip`, `mbstring`, `intl`)
* MySQL / MariaDB 10.4+
* Composer 2.x
* Node.js 18+ & NPM

#### 2. Konfigurasi Lingkungan (.env)
File `.env` sudah dikonfigurasi dengan pengaturan database MySQL:
```env
APP_NAME="CV. GARUDA RAJA PERKASA DUABELAS"
APP_ENV=local
APP_DEBUG=true
APP_TIMEZONE=Asia/Pontianak
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cv_garuda
DB_USERNAME=root
DB_PASSWORD=
DB_COLLATION=utf8mb4_unicode_ci
```

#### 3. Instalasi Dependensi & Setup Database
Bila mengkloning atau menjalankan ulang pada server baru:
```bash
# 1. Pastikan server MySQL berjalan (misalnya via XAMPP / Laragon)
# 2. Buat database: CREATE DATABASE cv_garuda CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

# 3. Jalankan migration dan seeder data awal:
php artisan migrate:fresh --seed

# 4. Hubungkan penyimpanan storage untuk lampiran:
php artisan storage:link

# 5. Kompilasi aset tampilan frontend:
npm run build
```

#### 4. Menjalankan Server Pengembangan
```bash
php artisan serve
```
Buka peramban di [http://localhost:8000](http://localhost:8000) dan masuk menggunakan salah satu akun demo di atas.

---

### PENGUJIAN OTOMATIS (AUTOMATED TESTS)
Seluruh modul telah divalidasi dengan pengujian otomatis PHPUnit:
```bash
php artisan test --filter=FinanceAppTest
```
Semua 11 skenario pengujian utama (Login, Dashboard, Transaksi Kas Masuk/Keluar, Soft Delete, Otorisasi Hak Akses, Generasi PDF, dan Ekspor Excel) berstatus **100% PASS**.

---
&copy; 2026 **CV. GARUDA RAJA PERKASA DUABELAS** &bull; Simpang Ampar, Tayan Hilir, Kab. Sanggau, Kalimantan Barat.
