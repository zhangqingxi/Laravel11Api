<?php

namespace App\Http\Middleware;

use App\Constants\CommonStatusCodes;
use App\Exceptions\CustomException;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Psr\SimpleCache\InvalidArgumentException;

/**
 * 请求频率限制中间件
 * @Auther Qasim
 * @date 2023/6/30
 */
class RateLimitMiddleware
{
    /**
     * 处理传入请求
     * @param Request $request 请求对象
     * @param Closure $next 下一个中间件
     * @return mixed
     * @throws CustomException
     * @throws InvalidArgumentException
     */
    public function handle(Request $request, Closure $next): mixed
    {
        //获取路由类型
        $routeName = $request->attributes->get('route_name');

        // 基于IP地址限制请求次数
        $key = 'rate_limit:' . $request->ip();

        // 请求次数
        $maxAttempts = config($routeName . '.request.max_requests');

        //时间间隔
        $decaySeconds = config($routeName . '.request.time_limit') * 60; //转换为秒

        if (cache_store($routeName)->has($key)) {

            $attempts = cache_store($routeName)->get($key);

            if ($attempts >= $maxAttempts) {

                throw new CustomException(message('too_many_requests'), CommonStatusCodes::TOO_MANY_REQUESTS);
            }

            cache_store($routeName)->increment($key);
        } else {

            cache_store($routeName)->put($key, 1, $decaySeconds);
        }

        return $next($request);
    }
}
