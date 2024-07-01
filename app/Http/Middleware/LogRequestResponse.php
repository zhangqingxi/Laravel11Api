<?php

namespace App\Http\Middleware;

use App\Jobs\Admin\ProcessRequestLog;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Str;

/**
 * 日志请求响应中间件
 *
 * @package App\Http\Middleware
 * @autor Qasim
 * @time 2023/6/27 16:06
 */
class LogRequestResponse
{

    /**
     * 处理传入的请求
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {

        // 生成全局唯一的请求 ID
        $requestId = (string) Str::uuid();

        $request->attributes->set('request_id', $requestId);

        $response = $next($request);

        // 检查请求是否被重复
        if (!$request->attributes->get('is_duplicate')) {

            $this->log($request, $response);
        }

        return $response;
    }

    /**
     * 记录请求和响应数据
     *
     * @param Request $request 请求数据
     * @param JsonResponse|Response $response 响应数据
     */
    private function log(Request $request, JsonResponse|Response $response): void
    {

        $request->headers->remove('accept-encoding');
        $request->headers->remove('user-agent');
        $request->headers->remove('accept');
        $request->headers->remove('host');
        $request->headers->remove('content-length');
        $request->headers->remove('connection');

        $logData = [
            'request_id' => $request->attributes->get('request_id'),
            'host' => $request->getHost(),
            'url' => $request->url(),
            'controller' => class_basename($request->route()->getController()),
            'method' => $request->method(),
            'ip' => $request->ip(),
            'user_agent' => $request->header('User-Agent') ?: '',
            'headers' => $request->headers->all(),
            'http_status' => $response->status(),
            'request_data' => $request->except(['s']),
            'encrypt_request_data' => $request->attributes->get('encrypt_request_data'),
            'response_data' => $response->getData(true),
        ];

        //异常数据
        if ($response->exception) {
            $logData['exception_data'] = [
                'code' => $response->exception->getCode(),
                'msg' => $response->exception->getMessage(),
                'file' => $response->exception->getFile(),
                'line' => $response->exception->getLine(),
            ];
        }

        ProcessRequestLog::dispatch($logData)->onConnection('sync');
    }
}
