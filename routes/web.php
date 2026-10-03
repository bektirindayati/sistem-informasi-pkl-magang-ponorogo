<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DokumentasiController;
use App\Http\Controllers\DokumentasiPublikController;
use App\Http\Controllers\HomeController;

Route::get('/dokumentasi/{dokumentasi}', [DokumentasiPublikController::class, 'show'])
       ->name('dokumentasi.show');

/*HALAMAN PUBLIK*/

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/informasi', function () {
    return view('user.pendaftaran.informasi');
})->name('informasi');

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/chatbot/ask', [ChatbotController::class, 'ask'])
    ->name('chatbot.ask')
    ->middleware('throttle:20,1');

/*GOOGLE AUTHENTICATION*/

Route::get('/auth/google', [GoogleAuthController::class, 'redirectToGoogle'])
    ->name('auth.google');

Route::get('/auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback']);


/*
|--------------------------------------------------------------------------
| LOGOUT
|--------------------------------------------------------------------------
*/

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('home');
})->name('logout');

/*
|--------------------------------------------------------------------------
| USER / PENDAFTAR
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    Route::get('/user/dashboard', [UserDashboardController::class, 'index'])
        ->name('user.dashboard');

    Route::get('/user/profil', [ProfileController::class, 'index'])
        ->name('user.profil');

    Route::put('/user/profil', [ProfileController::class, 'update'])
        ->name('user.profil.update');

    Route::get('/dinas/kominfo', function () {
        return view('user.dinas.kominfo');
    })->name('user.dinas.kominfo');


    // ==============================
    // PENDAFTARAN USER
    // ==============================
    Route::prefix('user/pendaftaran')
        ->name('user.pendaftaran.')
        ->group(function () {

            Route::get('/daftar', [PendaftaranController::class, 'create'])
                ->name('create');

            Route::post('/daftar', [PendaftaranController::class, 'store'])
                ->name('store');

            Route::post('/draft', [PendaftaranController::class, 'saveDraft'])
                ->name('draft');

            Route::delete('/draft/{id}', [PendaftaranController::class, 'destroyDraft'])
                ->name('draft.destroy');

            Route::get('/status', [PendaftaranController::class, 'status'])
                ->name('status');

            Route::get('/riwayat', [PendaftaranController::class, 'riwayat'])
                ->name('riwayat');

            Route::get('/bidang/{dinasId}', [PendaftaranController::class, 'getBidang'])
                ->name('bidang');

            Route::get('/{id}/edit', [PendaftaranController::class, 'edit'])
                ->name('edit');

            Route::put('/{id}', [PendaftaranController::class, 'update'])
                ->name('update');

            Route::delete('/{id}', [PendaftaranController::class, 'destroy'])
                ->name('destroy');
        });
});

/*admin permission*/
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::middleware(['permission:lihat-dashboard'])->group(function () {
            Route::get('/dashboard', [AdminController::class, 'dashboard'])
                ->name('dashboard');
        });

        Route::middleware(['permission:lihat-pendaftar'])
            ->prefix('pendaftar')
            ->name('pendaftar.')
            ->group(function () {
                Route::get('/', [AdminController::class, 'indexPendaftar'])
                    ->name('index');
                Route::get('/{id}', [AdminController::class, 'showPendaftar'])
                    ->name('show');
            });

        // Cetak surat balasan: bagian dari alur verifikasi (surat baru
        // relevan setelah status diterima), jadi disamakan gerbangnya
        // dengan permission verifikasi-pendaftar.
        Route::middleware(['permission:verifikasi-pendaftar'])->group(function () {
            Route::get('/pendaftaran/{id}/cetak-surat', [AdminController::class, 'cetakSuratBalasan'])
                ->name('pendaftar.download');
        });

        Route::middleware(['permission:verifikasi-pendaftar'])
            ->prefix('verifikasi')
            ->name('verifikasi.')
            ->group(function () {
                Route::get('/', [AdminController::class, 'verifikasiBerkas'])
                    ->name('index');
                Route::put('/{id}', [AdminController::class, 'updateStatus'])
                    ->name('update');
            });

        Route::middleware(['permission:kelola-instansi'])
            ->prefix('instansi')
            ->name('instansi.')
            ->group(function () {
                Route::get('/', [AdminController::class, 'kelolaInstansi'])
                    ->name('index');
                Route::post('/', [AdminController::class, 'storeInstansi'])
                    ->name('store');
                Route::delete('/{id}', [AdminController::class, 'destroyInstansi'])
                    ->name('destroy');
            });

        // Kelola Dinas -- sebelumnya middleware('super_admin'), sekarang permission:kelola-dinas (cuma role super_admin yang punya

        Route::middleware(['permission:kelola-dinas'])->prefix('dinas')
            ->name('dinas.')
            ->group(function () {
                Route::get('/', [AdminController::class, 'kelolaDinas'])
                    ->name('index');
                Route::post('/', [AdminController::class, 'storeDinas'])
                    ->name('store');
                Route::get('/{id}/edit', [AdminController::class, 'editDinas'])
                    ->name('edit');
                Route::put('/{id}', [AdminController::class, 'updateDinas'])
                    ->name('update');
                Route::delete('/{id}', [AdminController::class, 'destroyDinas'])
                    ->name('destroy');
            });

        // Kelola Admin -- sebelumnya middleware('super_admin'), sekarang
        // permission:kelola-admin.
        Route::middleware(['permission:kelola-admin'])->prefix('pengguna')
            ->name('pengguna.')
            ->group(function () {
                Route::get('/', [AdminController::class, 'kelolaPengguna'])
                    ->name('index');
                Route::put('/{id}', [AdminController::class, 'updatePengguna'])
                    ->name('update');
            });

        Route::middleware(['permission:kelola-dokumentasi'])
            ->prefix('dokumentasi')
            ->name('dokumentasi.')
            ->group(function () {
                Route::get('/', [DokumentasiController::class, 'index'])
                    ->name('index');
                Route::post('/', [DokumentasiController::class, 'store'])
                    ->name('store');
                Route::get('/{id}/edit', [DokumentasiController::class, 'edit'])
                    ->name('edit');
                Route::put('/{id}', [DokumentasiController::class, 'update'])
                    ->name('update');
                Route::delete('/{id}', [DokumentasiController::class, 'destroy'])
                    ->name('destroy');
            });
    });