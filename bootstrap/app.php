<?php

use \App\Http\Middleware\AdminMiddleware;
use \App\Http\Middleware\ManagerMiddleware;
use \App\Http\Middleware\MagazijnierMiddleware;
use \App\Http\Middleware\TechniekerMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Auth\Middleware\RedirectIfAuthenticated;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            // Re-define defaults
            'auth' => Authenticate::class,
            'guest' => RedirectIfAuthenticated::class,

            // Custom aliases
            'admin' => AdminMiddleware::class,
            'manager' => ManagerMiddleware::class,
            'magazijnier' => MagazijnierMiddleware::class,
            'technieker' => TechniekerMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );
    })->create();
