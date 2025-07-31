<?php

use Illuminate\Http\Request;
use Illuminate\Foundation\Application;
use App\Http\Middleware\CheckPermission;
use App\Http\Middleware\LocationMiddleware;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\Ensure2FAIsVerifiedMiddleware;
use App\Http\Middleware\EmailHasBeenVerifiedMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->redirectUsersTo(function (Request $request) {
            if ($request->user() && $request->user()->role === 'admin') {
                return route('admin.dashboard');
            }
            return route('dashboard');
        });
        $middleware->append(LocationMiddleware::class);
        $middleware->alias([
            'email_verified' => EmailHasBeenVerifiedMiddleware::class,
            'two_factor' => Ensure2FAIsVerifiedMiddleware::class,
            'permission' => CheckPermission::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
