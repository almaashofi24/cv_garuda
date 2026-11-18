<?php

namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use Tests\TestCase;

class FinanceAppTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        if (User::where('email', 'admin@cvgaruda.com')->doesntExist()) {
            $this->seed();
        }
    }

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('CV. GARUDA RAJA PERKASA DUABELAS');
        $response->assertSee('Portal Masuk Khusus Administrator');
    }

    public function test_login_screen_does_not_contain_demo_shortcuts(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertDontSee('Klik Cepat Akun Demo');
        $response->assertDontSee('Login Instan');
    }

    public function test_admin_can_login_and_access_dashboard(): void
    {
        $admin = User::where('email', 'admin@cvgaruda.com')->first();
        $this->assertNotNull($admin);

        $loginResponse = $this->post('/login', [
            'email' => 'admin@cvgaruda.com',
            'password' => 'password',
        ]);
        $loginResponse->assertRedirect(route('dashboard', absolute: false));
        $this->assertAuthenticatedAs($admin);

        $response = $this->actingAs($admin)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Dashboard Keuangan');
        $response->assertSee('CV. GARUDA RAJA PERKASA');
    }

    public function test_non_admin_is_prevented_from_logging_in(): void
    {
        $staf = User::firstOrCreate(
            ['email' => 'keuangan@cvgaruda.com'],
            [
                'name' => 'Siti Aminah, A.Md. (Staf Keuangan)',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
            ]
        );
        $staf->syncRoles(['Staf Keuangan']);

        // Pengguna selain Admin ditolak masuk
        $response = $this->from('/login')->post('/login', [
            'email' => 'keuangan@cvgaruda.com',
            'password' => 'password',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHas('error');
        $this->assertGuest();
    }

    public function test_non_admin_cannot_access_dashboard(): void
    {
        $staf = User::firstOrCreate(
            ['email' => 'keuangan@cvgaruda.com'],
            [
                'name' => 'Siti Aminah, A.Md. (Staf Keuangan)',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
            ]
        );
        $staf->syncRoles(['Staf Keuangan']);

        $response = $this->actingAs($staf)->get('/dashboard');
        $response->assertStatus(403);
    }

    public function test_transactions_can_be_created_by_admin(): void
    {
        $admin = User::where('email', 'admin@cvgaruda.com')->first();
        $cat = Category::where('tipe', 'pemasukan')->first();

        $response = $this->actingAs($admin)->post('/transaksi/simpan', [
            'tanggal' => now()->format('Y-m-d'),
            'tipe' => 'pemasukan',
            'category_id' => $cat->id,
            'deskripsi' => 'Testing transaksi pemasukan baru oleh admin',
            'jumlah' => 15000000,
            'metode_pembayaran' => 'transfer',
            'pihak_terkait' => 'PT. Pelanggan Uji Coba',
        ]);

        $response->assertRedirect(route('transaksi.pemasukan'));
        $this->assertDatabaseHas('transactions', [
            'deskripsi' => 'Testing transaksi pemasukan baru oleh admin',
            'tipe' => 'pemasukan',
            'jumlah' => 15000000,
        ]);
    }

    public function test_soft_delete_preserves_transaction_record(): void
    {
        $admin = User::where('email', 'admin@cvgaruda.com')->first();
        $tx = Transaction::latest('id')->first();

        $response = $this->actingAs($admin)->delete("/transaksi/hapus/{$tx->id}");
        $response->assertStatus(302);

        $this->assertSoftDeleted('transactions', [
            'id' => $tx->id,
        ]);
    }

    public function test_reports_can_be_viewed_by_admin(): void
    {
        $admin = User::where('email', 'admin@cvgaruda.com')->first();

        // 1. Arus Kas
        $response1 = $this->actingAs($admin)->get('/laporan');
        $response1->assertStatus(200);
        $response1->assertSee('Rekapitulasi Arus Kas');

        // 2. Laba Rugi
        $response2 = $this->actingAs($admin)->get('/laporan/laba-rugi');
        $response2->assertStatus(200);
        $response2->assertSee('Laporan Laba Rugi');
    }

    public function test_pdf_report_generates_successfully(): void
    {
        $admin = User::where('email', 'admin@cvgaruda.com')->first();

        $response = $this->actingAs($admin)->get('/laporan/ekspor/pdf');
        $response->assertStatus(200);
        $this->assertEquals('application/pdf', $response->headers->get('content-type'));
    }

    public function test_laba_rugi_pdf_report_generates_successfully(): void
    {
        $admin = User::where('email', 'admin@cvgaruda.com')->first();

        $response = $this->actingAs($admin)->get('/laporan/laba-rugi/ekspor/pdf');
        $response->assertStatus(200);
        $this->assertEquals('application/pdf', $response->headers->get('content-type'));
    }

    public function test_excel_reports_generate_successfully(): void
    {
        $admin = User::where('email', 'admin@cvgaruda.com')->first();

        // 1. Excel Arus Kas
        $resExcel1 = $this->actingAs($admin)->get('/laporan/ekspor/excel');
        $resExcel1->assertStatus(200);

        // 2. Excel Laba Rugi
        $resExcel2 = $this->actingAs($admin)->get('/laporan/laba-rugi/ekspor/excel');
        $resExcel2->assertStatus(200);
    }

    public function test_activity_log_recorded(): void
    {
        $admin = User::where('email', 'admin@cvgaruda.com')->first();

        $this->actingAs($admin)->get('/log-aktivitas')->assertStatus(200);
        $this->assertTrue(ActivityLog::count() > 0);
    }

    public function test_change_password_screen_can_be_rendered(): void
    {
        $admin = User::where('email', 'admin@cvgaruda.com')->first();

        $response = $this->actingAs($admin)->get('/ubah-password');
        $response->assertStatus(200);
        $response->assertSee('Ubah Kata Sandi Akun');
        $response->assertSee('Kata Sandi Saat Ini');
    }

    public function test_admin_can_change_password_with_valid_current_password(): void
    {
        $admin = User::where('email', 'admin@cvgaruda.com')->first();

        $response = $this->actingAs($admin)->put('/password', [
            'current_password' => 'password',
            'password' => 'PasswordBaru123!',
            'password_confirmation' => 'PasswordBaru123!',
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('success');

        // Kembalikan ke password semula
        $admin->fresh()->update(['password' => \Illuminate\Support\Facades\Hash::make('password')]);
    }
}
