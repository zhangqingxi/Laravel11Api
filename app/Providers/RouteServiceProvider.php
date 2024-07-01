<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            // 你的路由配置
        });
    }

    protected function configureRateLimiting()
    {
        RateLimiter::for('global', function (Request $request) {

            return Limit::perMinute(60)->by($request->ip());
        });

        RateLimiter::for('admin', function (Request $request) {

            return Limit::perMinute(30)->by(optional($request->user())->id ?: $request->ip());
        });
    }
}
