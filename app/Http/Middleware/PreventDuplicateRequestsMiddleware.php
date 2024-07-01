<?php

namespace App\Http\Middleware;

use App\Constants\CommonStatusCodes;
use App\Exceptions\CustomException;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

/**
 * 防止重复请求中间件
 * @Auther Qasim
 * @date 2023/6/30
 */
class PreventDuplicateRequestsMiddleware
{
    /**
     * 处理传入请求
     * @param Request $request 请求对象
     * @param Closure $next 下一个中间件
     * @return mixed
     * @throws CustomException
     */
    public function handle(Request $request, Closure $next): mixed
    {

        //获取路由类型
        $routeName = $request->attributes->get('route_name');

        // 通过请求参数构建唯一 ID
        $requestId = sha1(json_encode($request->all()));

        // 检查请求 ID 是否存在
        if (Cache::has($requestId)) {

            $request->attributes->set('request_duplicate', true);

            throw new CustomException(message('request_duplicate'), CommonStatusCodes::REQUEST_DUPLICATE);
        }

        // 将请求 ID 存储到 Cache 中，并设置过期时间
        Cache::put($requestId, true, now()->addSeconds(config($routeName . '.request.duplicate_time')));

        $request->attributes->set('request_duplicate', false);

        return $next($request);
    }
}
