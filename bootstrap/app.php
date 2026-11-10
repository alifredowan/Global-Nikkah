<?php

use App\Http\Middleware\AddSecurityHeaders;
use App\Http\Middleware\EnsureHasAdminPermission;
use App\Http\Middleware\EnsureUserIsModerator;
use App\Http\Middleware\EnsureUserIsSuperAdmin;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->append(AddSecurityHeaders::class);

        $middleware->validateCsrfTokens(except: [
            'webhook/*',
            'api/*',
        ]);

        $middleware->alias([
            'moderator' => EnsureUserIsModerator::class,
            'super_admin' => EnsureUserIsSuperAdmin::class,
            'permission' => EnsureHasAdminPermission::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );

        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json([
                    'message' => 'The requested resource was not found.',
                ], 404);
            }
        });

        $exceptions->render(function (AccessDeniedHttpException|AuthorizationException $e, Request $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json([
                    'message' => $e->getMessage() ?: 'This action is unauthorized.',
                ], 403);
            }
        });

        $exceptions->render(function (TooManyRequestsHttpException|ThrottleRequestsException $e, Request $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json([
                    'message' => 'Too many requests. Please slow down and try again later.',
                ], 429);
            }
        });

        $exceptions->render(function (Throwable $e, Request $request) {
            if (($request->is('api/*') || $request->expectsJson()) && ! ($e instanceof ValidationException) && ! ($e instanceof AuthenticationException)) {
                Log::error('API Unhandled Exception', [
                    'url' => $request->fullUrl(),
                    'method' => $request->method(),
                    'ip' => $request->ip(),
                    'user_id' => $request->user()?->id,
                    'exception' => get_class($e),
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ]);

                if (! config('app.debug')) {
                    return response()->json([
                        'message' => 'An unexpected server error occurred. Please try again later.',
                    ], 500);
                }
            }
        });
    })->create();
