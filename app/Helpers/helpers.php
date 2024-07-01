<?php

use Illuminate\Contracts\Cache\Repository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

if (!function_exists('json')) {
    /**
     * @param int $code 接口状态码
     * @param string $message 接口信息
     * @param array|null $data 响应数据
     * @param int $status HTTP状态
     * @param array $headers 响应头
     * @return JsonResponse
     * @author Qasim
     * @time 2023/6/27 16:06
     */
    function json(int $code = 0, string $message = '', array|null $data = [], int $status = 200, array $headers = []): JsonResponse
    {

        if ($status === 500) //异常
        {

            unset($data['exception']);
        }

        return response()->json([
            'code' => $code,
            'message' => $message,
            'data' => $data,
        ], $status, $headers);
    }
}

if (!function_exists('json_log')) {
    /**
     * @param array $data 日志数据
     * @return string
     * @author Qasim
     * @time 2023/6/27 16:06
     */
    function json_log(array $data): string
    {
        return json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}


if (!function_exists('aes_encrypt')) {
    /**
     * @param string $data 加密数据
     * @param string $key AES秘钥
     * @return string
     * @author Qasim
     * @time 2023/6/28 15:40
     */
    function aes_encrypt(string $data, string $key): string
    {
        $iv = openssl_random_pseudo_bytes(16);
        $encrypted = openssl_encrypt($data, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
        return base64_encode($iv . $encrypted);
    }
}

if (!function_exists('aes_decrypt')) {

    /**
     * @param string $data 解密数据
     * @param string $key AES秘钥
     * @return string
     * @author Qasim
     * @time 2023/6/28 15:40
     */
    function aes_decrypt(string $data, string $key): string
    {
        $data = base64_decode($data);
        $iv = substr($data, 0, 16);
        $encrypted = substr($data, 16);
        return openssl_decrypt($encrypted, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
    }
}


if (!function_exists('check_route')) {
    /**
     * 检查路由
     * @param Request $request
     * @param string $route 路由名称
     * @return bool
     */
    function check_route(Request $request, string $route = ''): bool
    {

        if ($route === 'admin') {

            return $request->is(config('admin.route_prefix') . '/*');
        } elseif ($route === 'api') {

            return $request->is('api/*');
        } elseif ($route === 'init') {

            return $request->is('init/*');
        }else{

            return false;
        }
    }
}


if (!function_exists('route_type')) {
    /**
     * 路由类型
     * @param Request $request
     * @return string
     */
    function route_type(Request $request): string
    {

        if(check_route($request, 'admin') || $request->is('init/admin')){

            return 'admin';
        }elseif(check_route($request, 'api') || $request->is('init/api')){

            return 'api';

        }else{

            return '';
        }
    }
}


if (!function_exists('message')) {

    /**
     *  输出信息
     *
     * @param string $key 语言包的键
     * @param string $route 路由名称
     * @param array $replace 替换的数据
     * @param string|null $locale 语言环境
     * @return string
     */
    function message(string $key, string $route = '', array $replace = [], string $locale = null): string
    {

        if($route){

            return __('message.' .$route . '.'. $key, $replace, $locale);
        }

        return __('message.' . $key, $replace, $locale);
    }
}



if (!function_exists('cache_store')) {

    /**
     *  缓存保存
     *
     * @param string $route 路由名称
     * @return Repository
     */
    function cache_store(string $route): Repository
    {

        return Cache::store($route);
    }
}
