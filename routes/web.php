<?php

use App\Http\Controllers\Admin\DashboardAdminController;
use App\Http\Controllers\Admin\DokumentasiAdminController;
use App\Http\Controllers\Admin\InstansiAdminController;
use App\Http\Controllers\Admin\PendaftarAdminController;
use App\Http\Controllers\Admin\SuratBalasanAdminController;
use App\Http\Controllers\Admin\VerifikasiAdminController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Publik\BerandaController;
use App\Http\Controllers\Publik\ChatbotController;
use App\Http\Controllers\Publik\DokumentasiPublikController;
use App\Http\Controllers\SuperAdmin\DinasSuperAdminController;
use App\Http\Controllers\SuperAdmin\PenggunaSuperAdminController;
use App\Http\Controllers\User\DashboardUserController;
use App\Http\Controllers\User\PendaftaranUserController;
use App\Http\Controllers\User\ProfilUserController;
use App\Http\Controllers\User\SuratBalasanUserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| HALAMAN PUBLIK
|--------------------------------------------------------------------------
*/
Route::get('/', BerandaController::class)->name('home');
Route::view('/informasi', 'user.pendaftaran.informasi')->name('informasi');
Route::view('/login', 'auth.login')->name('login');

Route::get('/dokumentasi/{dokumentasi}', [DokumentasiPublikController::class, 'show'])
    ->name('dokumentasi.show');

Route::post('/chatbot/ask', [ChatbotController::class, 'ask'])
    ->name('chatbot.ask')
    ->middleware('throttle:20,1');

/*
|--------------------------------------------------------------------------
| AUTENTIKASI
|--------------------------------------------------------------------------
*/
Route::get('/auth/google', [GoogleAuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback']);
Route::post('/logout', LogoutController::class)->name('logout');

/*
|--------------------------------------------------------------------------
| USER / PENDAFTAR
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    Route::get('/user/dashboard', DashboardUserController::class)->name('user.dashboard');

    Route::controller(ProfilUserController::class)->group(function () {
        Route::get('/user/profil', 'index')->name('user.profil');
        Route::put('/user/profil', 'update')->name('user.profil.update');
    });

    Route::view('/dinas/kominfo', 'user.dinas.kominfo')->name('user.dinas.kominfo');

    Route::get('/user/pendaftaran/{id}/surat-balasan', SuratBalasanUserController::class)
        ->name('user.pendaftaran.surat');

    Route::prefix('user/pendaftaran')
        ->name('user.pendaftaran.')
        ->controller(PendaftaranUserController::class)
        ->group(function () {
            Route::get('/daftar', 'create')->name('create');
            Route::post('/daftar', 'store')->name('store');

            Route::post('/draft', 'saveDraft')->name('draft');

            Route::get('/status', 'status')->name('status');
            Route::get('/riwayat', 'riwayat')->name('riwayat');
            Route::get('/bidang/{dinasId}', 'getBidang')->name('bidang');

            Route::get('/{id}/edit', 'edit')->name('edit');
            Route::put('/{id}', 'update')->name('update');
            Route::delete('/{id}', 'destroy')->name('destroy');
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

        // Verifikasi berkas + cetak surat balasan (surat relevan setelah diterima)
        Route::middleware('permission:verifikasi-pendaftar')->group(function () {
            Route::prefix('pendaftaran/{id}/surat')
                ->name('surat.')
                ->controller(SuratBalasanAdminController::class)
                ->group(function () {
                    Route::get('/', 'show')->name('show');
                    Route::put('/', 'update')->name('update');
                    Route::get('/pratinjau', 'pratinjau')->name('pratinjau');
                    Route::post('/terbitkan', 'terbitkan')->name('terbitkan');
                    Route::post('/tarik', 'tarik')->name('tarik');
                });

            // Kompatibilitas: view lama yang masih memakai route ini diarahkan ke halaman surat.
            // Hapus setelah semua blade memakai admin.surat.show.
            Route::get('/pendaftaran/{id}/cetak-surat', [SuratBalasanAdminController::class, 'show'])
                ->name('pendaftar.download');

            Route::prefix('verifikasi')
                ->name('verifikasi.')
                ->controller(VerifikasiAdminController::class)
                ->group(function () {
                    Route::get('/', 'index')->name('index');
                    Route::put('/{id}', 'update')->name('update');
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

        // ---- Khusus super admin (permission hanya dimiliki role super_admin) ----

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
                Route::put('/{id}', 'update')->name('update');
            });
    });