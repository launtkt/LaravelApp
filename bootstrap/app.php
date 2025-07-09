<?php

use App\Http\Middleware\RoleMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Log;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->appendToGroup('api', [
        EnsureFrontendRequestsAreStateful::class,
    ]);
        $middleware->alias([
            'role' => RoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {

        // 401 - Chưa đăng nhập hoặc token không hợp lệ
        $exceptions->render(function (AuthenticationException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Phiên đăng nhập đã hết hạn hoặc token không hợp lệ.',
                ], 401, [], JSON_PRETTY_PRINT);
            }
        });

        // 403 - Không có quyền
        $exceptions->render(function (AuthorizationException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Bạn không có quyền thực hiện hành động này.',
                ], 403, [], JSON_PRETTY_PRINT);
            }
        });

        // 404 - Không tìm thấy model
        $exceptions->render(function (ModelNotFoundException|NotFoundHttpException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Không tìm thấy tài nguyên yêu cầu.',
                ], 404, [], JSON_PRETTY_PRINT);
            }
        });

        // 422 - Dữ liệu không hợp lệ (validation)
        $exceptions->render(function (ValidationException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Dữ liệu không hợp lệ',
                    'errors' => $e->errors(),
                ], 422, [], JSON_PRETTY_PRINT);
            }
        });

        // 500 - Lỗi không xác định
        $exceptions->render(function (\Exception $e, $request) {
            if ($request->expectsJson()) {
                Log::error("Hệ thống lỗi: " . $e->getMessage());
                return response()->json([
                    'message' => 'Đã xảy ra lỗi hệ thống. Vui lòng thử lại sau.',
                ], 500, [], JSON_PRETTY_PRINT);
            }
        });
    })->create();
