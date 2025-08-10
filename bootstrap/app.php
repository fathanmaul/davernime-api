<?php

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->renderable(function (Throwable $e, $request) {
            if ($request->is('api/*')) {
                if ($e instanceof ValidationException) {
                    return new JsonResponse([
                        'message' => 'validation fails',
                        'errors' => $e->errors(),
                    ], Response::HTTP_BAD_REQUEST);
                }

                if ($e instanceof UnauthorizedException) {
                    return new JsonResponse([
                        'message' => $e->getMessage(),
                    ], Response::HTTP_FORBIDDEN);
                }

                if ($e instanceof NotFoundHttpException) {
                    return new JsonResponse([
                        'message' => app()->isLocal() ? $e->getMessage() : 'Not found.',
                    ], Response::HTTP_NOT_FOUND);
                }

                return new JsonResponse([
                    'message' => app()->isLocal() ? $e->getMessage() : 'Internal server error',
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        });
    })->create();
