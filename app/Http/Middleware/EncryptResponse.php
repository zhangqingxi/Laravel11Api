<?php

namespace App\Http\Middleware;

use App\Constants\CommonStatusCodes;
use App\Exceptions\CustomException;
use App\Jobs\Admin\UpdateRequestLog;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Services\AesEncryptionService;
use App\Services\RsaEncryptionService;
use Exception;
use Illuminate\Http\Response;

/**
 * 解密中间件
 * @Auther Qasim
 * @date 2023/6/28
 */
class EncryptResponse
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

        $response = $next($request);

        $this->encryptResponseData($request, $response);

        return $response;
    }

    /**
     * 加密响应数据
     *
     * @param Request $request 请求数据
     * @param JsonResponse|Response $response 响应数据
     * @throws Exception
     */
    private function encryptResponseData(Request $request, JsonResponse|Response $response): void
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

        //响应数据
        $responseData = $response->getData(true);

        //获取是哪一种接口模式
        if (check_route($request, 'admin')) {

            //设置对应的公钥私钥
            $this->rsaService->setKeys('admin');

            // 解密 AES 密钥
            $aesKey = $this->rsaService->decrypt($aesKey);
        }

        if($responseData['data']){

            // 使用 AES 密钥解密数据
            $decryptedData = $this->aesService->encrypt(json_encode($responseData['data']), $aesKey);

            //更新日志数据
            UpdateRequestLog::dispatch($request->attributes->get('request_id'), $decryptedData)->onConnection('admin')->delay(10);

            $responseData['data'] = $decryptedData;
        }

        $response->setData($responseData);
    }
}
