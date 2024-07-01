<?php

namespace App\Http\Middleware;

use App\Constants\CommonStatusCodes;
use App\Exceptions\CustomException;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerifyToken
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param \Closure $next
     * @return mixed
     * @throws CustomException
     */
    public function handle(Request $request, Closure $next): mixed
    {

        $response = $next($request);

        if ($request->is(config('admin.route_prefix') . '/login') || check_route($request, 'init')) {

            // 登录请求不需要验证Token
            return $response;
        }

        $token = $request->bearerToken();

        if (!$token) {


            throw new CustomException(message('token_not_provided'), CommonStatusCodes::TOKEN_NOT_PROVIDED);
        }

        // 验证 Token
        if (Auth::guard('sanctum')->check()) {

            return $response;
        }

        throw new CustomException(message('token_invalid'), CommonStatusCodes::TOKEN_INVALID);
    }
}
