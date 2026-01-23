<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Trust all proxies for HTTPS detection
        $middleware->trustProxies(at: '*');
        
        // Register custom middleware aliases
        $middleware->alias([
            'auth' => \App\Http\Middleware\Authenticate::class,
            'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
            'role' => \App\Http\Middleware\CheckRole::class,
            'active' => \App\Http\Middleware\EnsureUserIsActive::class,
            'boursier' => \App\Http\Middleware\CheckBoursier::class,
            'verified_partner' => \App\Http\Middleware\CheckVerifiedPartner::class,
            'admin.permission' => \App\Http\Middleware\CheckAdminPermission::class,
            'admin.no_mobile' => \App\Http\Middleware\RestrictMobileAdmin::class,
            'bachelier.complete' => \App\Http\Middleware\EnsureBachelierProfileComplete::class,
        ]);
        
        // Add global middleware if needed
        // $middleware->append(\App\Http\Middleware\TrackUserActivity::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
