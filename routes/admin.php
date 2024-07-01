<?php

use App\Http\Controllers\Admin\IndexController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\LoginController;

/**
 * 注册管理员路由
 * 主要主处理系统后台API交互
 * @作者 Qasim
 * @日期 2023/6/27
 */
//初始化请求
Route::prefix('init')->get('/admin', [IndexController::class, 'index']);


// 正常请求
Route::prefix(config('admin.route_prefix'))->group(function () {

    //登录
    Route::post('login', [LoginController::class, 'login'])->name('admin.login');


    //API权限验证
    Route::middleware(['auth:sanctum'])->group(function () {

        // 注销登录
        Route::post('logout', [LoginController::class, 'logout']);
    //    // 仪表盘路由
    //    Route::get('dashboard', [DashboardController::class, 'index'])->middleware('auth:admin');
    //    // 用户管理路由
    //    Route::middleware('auth:admin')->group(function () {
    //        Route::get('users', [UserController::class, 'index']);
    //        Route::get('users/{id}', [UserController::class, 'show']);
    //        Route::post('users', [UserController::class, 'store']);
    //        Route::put('users/{id}', [UserController::class, 'update']);
    //        Route::delete('users/{id}', [UserController::class, 'destroy']);
    //    });
    });

});




