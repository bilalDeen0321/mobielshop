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
        // When Laravel's exception renderer dist files are missing (e.g. on production deploy), show a simple error page instead of crashing
        $rendererStyles = base_path('vendor/laravel/framework/src/Illuminate/Foundation/resources/exceptions/renderer/dist/styles.css');
        $exceptions->renderable(function (\Throwable $e, $request) use ($rendererStyles) {
            if (! is_file($rendererStyles) && ! $request->expectsJson()) {
                $status = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500;
                return response()->view('errors.500', [], $status);
            }
            return null;
        });

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
