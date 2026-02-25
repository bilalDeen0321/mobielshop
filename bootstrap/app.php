<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Session\TokenMismatchException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'admin.auth' => \App\Http\Middleware\RedirectIfNotAdmin::class,
            'admin.guest' => \App\Http\Middleware\RedirectIfAdmin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->renderable(function (TokenMismatchException $e, $request) {
            if ($request->is('admin-panel/login')) {
                return redirect()->route('admin.login')
                    ->with('status', '419')
                    ->withErrors(['_token' => 'Your session expired. Please try again.']);
            }
            return redirect()->back()
                ->withInput($request->except('password', '_token'))
                ->with('status', '419')
                ->withErrors(['_token' => 'Your session expired. Please try again.']);
        });
    })->create();
