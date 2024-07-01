<?php

namespace App\Http\Controllers\Admin;

use App\Constants\AdminStatusCodes;
use App\Exceptions\AdminException;
use Illuminate\Http\JsonResponse;
use Request;

/**
 * 初始化控制器
 * @Auther Qasim
 * @date 2023/6/29
 */
class IndexController extends BaseController
{

    /**
     * 初始化方法
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {

        $publicKey = file_get_contents(config('admin.encryption.rsa_public_key'));

        $data = [
            'public_key' => $publicKey,
            'encryption_enabled' => env('ENABLE_ENCRYPTION', false),
            'request_url' => config('admin.url') . '/' . config('admin.route_prefix'),
        ];

        return json(AdminStatusCodes::SUCCESS, $this->getMessage('fetch_success'), $data);
    }
}
