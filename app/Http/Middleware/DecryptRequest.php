<?php

namespace App\Http\Middleware;

use App\Constants\CommonStatusCodes;
use App\Exceptions\CustomException;
use Closure;
use Illuminate\Http\Request;
use App\Services\AesEncryptionService;
use App\Services\RsaEncryptionService;
use Exception;

/**
 * 解密中间件
 * @Auther Qasim
 * @date 2023/6/28
 */
class DecryptRequest
{
    protected AesEncryptionService $aesService;
    protected RsaEncryptionService $rsaService;


    public function __construct(AesEncryptionService $aesService, RsaEncryptionService $rsaService)
    {
        $this->aesService = $aesService;
        $this->rsaService = $rsaService;
    }

    /**
     * 处理传入请求
     * @param Request $request 请求对象
     * @param Closure $next 下一个中间件
     * @return mixed
     * @throws Exception
     */
    public function handle(Request $request, Closure $next): mixed
    {
        //初始化请求不需要解密数据
        if (!check_route($request, 'init')) {

            $this->decryptRequestData($request);
        }

        return $next($request);
    }

    /**
     * 处理请求参数解密
     * @param Request $request
     * @return void
     * @throws Exception
     */
    private function decryptRequestData(Request $request): void
    {

        //获取路由类型
        $routeName = $request->attributes->get('route_name');

        if(!config($routeName . '.enable_encryption')) {

            return;
        }

        //验证请求是否包含加密密钥
        if (!$request->hasHeader('X-AES-KEY') ||  !($aesKey = $request->header('X-AES-KEY'))) {

            throw new CustomException(message('encryption_key_not_found'), CommonStatusCodes::ENCRYPTION_KEY_NOT_FOUND);
        }

        //设置对应的公钥私钥
        $this->rsaService->setKeys($routeName);

        //请求过期时间
        $requestExpiredTime = config($routeName . '.request.expired_time');

        // 解密 AES 密钥
        $aesKey = $this->rsaService->decrypt($aesKey);

        $encryptRequestData = $request->input('data');

        if ($encryptRequestData){

            // 使用 AES 密钥解密数据
            $decryptedData = $this->aesService->decrypt($encryptRequestData, $aesKey);

            $requestData = json_decode($decryptedData, true);

            // 验证请求是否过期
            $requestTime = $requestData['request_time'] ?? null;

            if (!$requestTime || now()->diffInSeconds($requestTime) > $requestExpiredTime) {

                throw new CustomException(message('request_expired'), CommonStatusCodes::REQUEST_EXPIRED);
            }

            $request->attributes->set('encrypt_request_data', $encryptRequestData);

            $request->replace($requestData);
        }
    }
}
