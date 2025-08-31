<?php

use App\Http\Middleware\SetLocale;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->appendToGroup('web', SetLocale::class);
        $middleware->alias([
            'role'                   => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission'             => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission'     => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            'redirect.authenticated' => \App\Http\Middleware\RedirectIfAuthenticatedToDashboard::class,
            'not.customer'           => \App\Http\Middleware\EnsureUserIsNotCustomer::class,
            'customer'               => \App\Http\Middleware\EnsureUserIsCustomer::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // $exceptions->renderable(function (\Throwable $e, $request) {
        //     dd(get_class($e)); // Dump the exact exception class name
        // });

        // Access denied
        $exceptions->renderable(function (AccessDeniedHttpException $e, $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized action.'], 403);
            }
        });

        // Route or model not found
        $exceptions->render(function (NotFoundHttpException | ModelNotFoundException $e, Request $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json(['message' => 'Record not found.'], 404);
            }
        });

        // Validation errors
        $exceptions->render(function (ValidationException $e, Request $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json(['message' => 'Validation failed.', 'errors' => $e->errors()], 422);
            }
        });

        // Unauthenticated
        $exceptions->render(function (AuthenticationException $e, Request $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }
        });

        // Generic fallback
        $exceptions->render(function (Throwable $e, Request $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json(['message' => 'Server error.', 'error' => config('app.debug') ? $e->getMessage() : null], 500);
            }
        });
    })->create();
