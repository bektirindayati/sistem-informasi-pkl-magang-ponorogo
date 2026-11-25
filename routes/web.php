<?php

use App\Http\Controllers\Admin\DashboardAdminController;
use App\Http\Controllers\Admin\DokumentasiAdminController;
use App\Http\Controllers\Admin\InstansiAdminController;
use App\Http\Controllers\Admin\PendaftarAdminController;
use App\Http\Controllers\Admin\SuratBalasanAdminController;
use App\Http\Controllers\Admin\VerifikasiAdminController;
use App\Http\Controllers\Admin\PenggunaController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Publik\BerandaController;
use App\Http\Controllers\Publik\ChatbotController;
use App\Http\Controllers\Publik\InstansiPublikController;
use App\Http\Controllers\Publik\DokumentasiPublikController;
use App\Http\Controllers\SuperAdmin\DinasSuperAdminController;
use App\Http\Controllers\SuperAdmin\PenggunaSuperAdminController;
use App\Http\Controllers\User\DashboardUserController;
use App\Http\Controllers\User\PendaftaranUserController;
use App\Http\Controllers\User\ProfilUserController;
use App\Http\Controllers\User\SuratBalasanUserController;
use App\Http\Controllers\Admin\SertifikatAdminController;
use App\Http\Controllers\User\SertifikatUserController;
use App\Http\Controllers\Admin\SuratSelesaiAdminController;
use App\Http\Controllers\User\SuratSelesaiUserController;
use App\Http\Controllers\Admin\TemplateSuratController;
use Illuminate\Support\Facades\Route;

// Parameter id wajib angka, supaya segmen seperti "status" atau "riwayat" tidak pernah ditangkap sebagai {id}.
Route::pattern('id', '[0-9]+');
Route::pattern('pendaftaran', '[0-9]+');
Route::pattern('dinasId', '[0-9]+');
Route::pattern('template', '[0-9]+');

/*
|--------------------------------------------------------------------------
| HALAMAN PUBLIK
|--------------------------------------------------------------------------
*/
Route::get('/', BerandaController::class)->name('home');
Route::view('/informasi', 'user.pendaftaran.informasi')->name('informasi');
Route::view('/login', 'auth.login')->name('login');

Route::get('/instansi', InstansiPublikController::class)->name('instansi.index');

Route::get('/dokumentasi', [DokumentasiPublikController::class, 'index'])->name('dokumentasi.index');
Route::get('/dokumentasi/{dokumentasi}', [DokumentasiPublikController::class, 'show'])
    ->name('dokumentasi.show');

Route::post('/chatbot/ask', [ChatbotController::class, 'ask'])
    ->middleware('throttle:20,1')
    ->name('chatbot.ask');

/*
|--------------------------------------------------------------------------
| AUTENTIKASI
|--------------------------------------------------------------------------
*/
Route::controller(GoogleAuthController::class)->prefix('auth/google')->group(function () {
    Route::get('/', 'redirectToGoogle')->name('auth.google');
    Route::get('/callback', 'handleGoogleCallback')->name('auth.google.callback');
});

Route::post('/logout', LogoutController::class)->name('logout');

/*
|--------------------------------------------------------------------------
| USER / PENDAFTAR
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // URL tetap /dinas/kominfo (tanpa prefix user) agar link lama tidak putus.
    Route::view('/dinas/kominfo', 'user.dinas.kominfo')->name('user.dinas.kominfo');

    Route::prefix('user')->name('user.')->group(function () {

        Route::get('/dashboard', DashboardUserController::class)->name('dashboard');

        Route::controller(ProfilUserController::class)->group(function () {
            Route::get('/profil', 'index')->name('profil');
            Route::put('/profil', 'update')->name('profil.update');
        });

        Route::prefix('pendaftaran')->name('pendaftaran.')->group(function () {

            Route::controller(PendaftaranUserController::class)->group(function () {
                // Form, draft, dan kirim
                Route::get('/daftar', 'create')->name('create');
                Route::post('/daftar', 'store')->name('store');
                Route::post('/draft', 'saveDraft')->name('draft');

                // Status & riwayat
                Route::get('/status', 'status')->name('status');
                Route::get('/riwayat', 'riwayat')->name('riwayat');

                // Detail pengajuan pengguna
                Route::get('/{id}', 'show')->name('show');

                // Data untuk form (dipanggil JavaScript)
                Route::get('/bidang/{dinasId}', 'getBidang')->name('bidang');

                // Ubah & batal
                Route::get('/{id}/edit', 'edit')->name('edit');
                Route::put('/{id}', 'update')->name('update');
                Route::delete('/{id}', 'destroy')->name('destroy');
            });

            // Unduh surat balasan yang sudah terbit
            Route::get('/{pendaftaran}/surat-balasan', SuratBalasanUserController::class)->name('surat');
            Route::get('/{pendaftaran}/surat-selesai', SuratSelesaiUserController::class)->name('surat-selesai');
            Route::get('/{pendaftaran}/sertifikat', SertifikatUserController::class)->name('sertifikat');
        });
    });
});

/*
|--------------------------------------------------------------------------
| ADMIN (admin dinas & super admin) -- akses diatur lewat permission
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard admin
        Route::get('/dashboard', DashboardAdminController::class)
            ->middleware('permission:lihat-dashboard')
            ->name('dashboard');

        // Data pendaftar
        Route::middleware('permission:lihat-pendaftar')
            ->prefix('pendaftar')
            ->name('pendaftar.')
            ->controller(PendaftarAdminController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/{id}', 'show')->name('show');
            });



// Verifikasi berkas & surat
Route::middleware('permission:verifikasi-pendaftar')->group(function () {

    // Verifikasi pendaftar
    Route::prefix('verifikasi')
        ->name('verifikasi.')
        ->controller(VerifikasiAdminController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::put('/{id}', 'update')->name('update');
        });

    // Surat balasan
    Route::prefix('pendaftaran/{id}/surat')
        ->name('surat.')
        ->controller(SuratBalasanAdminController::class)
        ->group(function () {
            Route::get('/', 'show')->name('show');
            Route::get('/draf', 'draf')->name('draf');
            Route::post('/status', 'status')->name('status');
            Route::post('/email', 'email')->name('email');
        });

    // Sertifikat
    Route::prefix('pendaftaran/{id}/sertifikat')
        ->name('sertifikat.')
        ->controller(SertifikatAdminController::class)
        ->group(function () {
            Route::get('/', 'show')->name('show');
            Route::get('/draf', 'draf')->name('draf');
            Route::post('/status', 'status')->name('status');
            Route::post('/email', 'email')->name('email');
        });

    // Surat selesai magang
    Route::prefix('pendaftaran/{id}/surat-selesai')
        ->name('surat-selesai.')
        ->controller(SuratSelesaiAdminController::class)
        ->group(function () {
            Route::get('/', 'show')->name('show');
            Route::get('/draf', 'draf')->name('draf');
            Route::post('/status', 'status')->name('status');
            Route::post('/email', 'email')->name('email');
        });
});

        // Bidang instansi
        Route::middleware('permission:kelola-instansi')
            ->prefix('instansi')
            ->name('instansi.')
            ->controller(InstansiAdminController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/', 'store')->name('store');
                Route::delete('/{id}', 'destroy')->name('destroy');
            });

        // Dokumentasi kegiatan
        Route::middleware('permission:kelola-dokumentasi')
            ->prefix('dokumentasi')
            ->name('dokumentasi.')
            ->controller(DokumentasiAdminController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/', 'store')->name('store');
                Route::get('/{id}/edit', 'edit')->name('edit');
                Route::put('/{id}', 'update')->name('update');
                Route::delete('/{id}', 'destroy')->name('destroy');
            });

        // Template surat: super admin = bawaan, admin dinas = template dinasnya
Route::middleware('permission:kelola-template-surat')
    ->prefix('template-surat')
    ->name('template.')
    ->controller(TemplateSuratController::class)
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/sistem/{jenis}', 'sistem')->name('sistem');
        Route::post('/{jenis}', 'unggah')->name('unggah')->where('jenis', '[a-z_]+');
        Route::get('/{template}/unduh', 'unduh')->name('unduh');
        Route::get('/{template}/pratinjau', 'pratinjau')->name('pratinjau');
        Route::post('/{template}/pratinjau', 'buatPratinjau')->name('pratinjau.buat');
        Route::post('/{template}/pratinjau/sekarang', 'buatPratinjauSekarang')->name('pratinjau.sekarang');
        Route::get('/{template}/contoh', 'contoh')->name('contoh');
        Route::post('/{template}/aktifkan', 'aktifkan')->name('aktifkan');
        Route::delete('/{template}', 'hapus')->name('hapus');
    });
        // Khusus super admin (permission hanya dimiliki role super_admin)

        Route::middleware('permission:kelola-dinas')
            ->prefix('dinas')
            ->name('dinas.')
            ->controller(DinasSuperAdminController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/', 'store')->name('store');
                Route::get('/{id}/edit', 'edit')->name('edit');
                Route::put('/{id}', 'update')->name('update');
                Route::delete('/{id}', 'destroy')->name('destroy');
            });

      Route::middleware('permission:kelola-admin')
    ->prefix('pengguna')
    ->name('pengguna.')
    ->controller(PenggunaSuperAdminController::class)
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/user', 'user')->name('user');
        Route::put('/{id}', 'update')->name('update');

        Route::patch('/{user}/status-akun', [
            PenggunaController::class,
            'updateStatusAkun'
        ])->name('status-akun');
    });
    });
