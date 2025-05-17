<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\Cors;
use Illuminate\Filesystem\FilesystemServiceProvider;
use Laravel\Sanctum\SanctumServiceProvider;

return Application::configure(basePath: dirname(__DIR__))
    ->withProviders([
        FilesystemServiceProvider::class, // ✅ Tambahkan ini
        SanctumServiceProvider::class,    // ✅ Pastikan ini juga ada
    ])
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Exclude API routes from CSRF
        $middleware->append([
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        ]);
        $middleware->validateCsrfTokens(except: [
            'api/*', // Exclude all API routes from CSRF verification
            'sanctum/csrf-cookie',
        ]);

        // Konfigurasi grup middleware api TANPA ThrottleRequests
        $middleware->group('api', [
            Cors::class,
            // HAPUS ThrottleRequests DARI SINI
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
