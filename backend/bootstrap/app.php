<?php

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(function () {
                    Route::group([], [
                            base_path('routes/api.php'),
                        ]);
                    Route::prefix('handbook')
                        ->middleware('auth:sanctum')
                        ->group([
                            base_path('routes/api/handbook.php'),
                        ]);
                    Route::middleware('auth:sanctum')
                        ->group([
                            base_path('routes/api/orders.php'),
                        ]);
                    Route::middleware('auth:sanctum')
                        ->group([
                            base_path('routes/api/comments.php'),
                        ]);
                    Route::middleware('auth:sanctum')
                        ->group([
                            base_path('routes/api/reports.php'),
                        ]);
                });
        }
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (NotFoundHttpException $exception) {
            return (new JsonResponse(['message' => 'Запись не найдена'], 404));
        });
        $exceptions->render(function (AuthenticationException $exception) {
            return (new JsonResponse(['message' => 'Не авторизован'], 401));
        });
        $exceptions->render(function (AccessDeniedHttpException $exception) {
            return (new JsonResponse(['message' => 'Доступ запрещен'], 401));
        });
    })->create();
