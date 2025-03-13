<?php

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(function () {
                    Route::middleware('auth:sanctum')
                        ->group([
                            base_path('routes/api/users.php'),
                            base_path('routes/api/files.php'),
                            base_path('routes/api/comments.php'),
                            base_path('routes/api/notifications.php'),
                            base_path('routes/api/activities.php'),
                        ]);
                });
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (NotFoundHttpException $exception) {
            return response()->json(['message' => 'Запись не найдена'], 404);
        });
        $exceptions->render(function (AuthenticationException $exception) {
            if (Request::is('api/login')) {
                return response()->json(['message' => 'Не правильные данные для авторизации'], 401);
            } elseif (Request::is('api/*')) {
                return response()->json(['message' => 'Не авторизован'], 401);
            }
            return $exception;
        });
        $exceptions->render(function (AccessDeniedHttpException $exception) {
            return response()->json(['message' => 'Доступ запрещен'], 401);
        });
    })->create();
