<?php

use App\Exceptions\AdminException;
use App\Http\Middleware\DecryptRequest;
use App\Http\Middleware\EncryptResponse;
use App\Http\Middleware\LogRequestResponse;
use App\Http\Middleware\PreventDuplicateRequestsMiddleware;
use App\Http\Middleware\RateLimitMiddleware;
use App\Http\Middleware\RouteMiddleware;
use App\Http\Middleware\VerifyToken;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware('admin')
                ->group(base_path('routes/admin.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->appendToGroup('admin', [
            RouteMiddleware::class,  //路由 【第一个执行】
            RateLimitMiddleware::class,  //请求频率限制
            PreventDuplicateRequestsMiddleware::class,  //重复请求
            DecryptRequest::class,  //解密请求
            VerifyToken::class,  //验证Token
            LogRequestResponse::class,  //记录日志
            EncryptResponse::class,  //最后执行加密响应
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->report(function (\Throwable $e){
//            dd($e->getMessage());
            return false;
        });
    })->create();
