<?php

use App\Http\Middleware\EnsureUserIsAdmin;
use App\Http\Middleware\EnsureUserIsSuperAdmin;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // 'admin' & 'super_admin': gerbang umum "apakah user ini staff /
        // super admin" -- tetap dipakai sebagai gerbang luar di /admin/*.
        //
        // BARU: 'permission', 'role', 'role_or_permission' -- alias
        // bawaan Spatie supaya bisa langsung dipakai di routes/web.php,
        // mis. Route::middleware(['auth', 'permission:kelola-dinas']).
        // Ini yang memungkinkan hak akses diatur granular per fitur,
        // bukan cuma per role.
        $middleware->alias([
            'admin' => EnsureUserIsAdmin::class,
            'super_admin' => EnsureUserIsSuperAdmin::class,
            'permission' => PermissionMiddleware::class,
            'role' => RoleMiddleware::class,
            'role_or_permission' => RoleOrPermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();