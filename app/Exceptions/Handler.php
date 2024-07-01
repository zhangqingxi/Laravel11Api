<?php

namespace App\Exceptions;

use App\Constants\CommonStatusCodes;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Throwable;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Validation\ValidationException;

/**
 * 自定义异常处理器
 * @Auther Qasim
 * @date 2023/6/28
 */
class Handler extends ExceptionHandler
{
    /**
     * 渲染异常为 HTTP 响应
     *
     * @param Request $request 请求
     * @param Throwable $e 异常
     * @return JsonResponse|Response
     * @throws Throwable
     */
    public function render($request, Throwable $e): JsonResponse|Response
    {
        // 检查异常类型并设置适当的消息和状态码
        $msg = message('general_error');
        $data = [];
        $code = CommonStatusCodes::EXCEPTION_ERROR;

        if ($e instanceof AdminException || $e instanceof CustomException) {

            $msg = $e->getMessage();
            $code = $e->getCode();
        } elseif ($e instanceof QueryException) {

            $msg = message('database_error');
            $code = CommonStatusCodes::DATABASE_ERROR;
        } elseif ($e instanceof ValidationException) {

            $msg = message('validation_error');
            $code = CommonStatusCodes::VALIDATION_ERROR;
            $data['errors'] = $e->errors();
        } elseif ($e instanceof NotFoundHttpException) {

            $msg = message('not_found');
            $code = CommonStatusCodes::NOT_FOUNT_EXCEPTION;
        } elseif ($e instanceof MethodNotAllowedHttpException){

            $msg = message('method_not_allowed');
            $code = CommonStatusCodes::METHOD_NOT_ALLOWED;
        }

//        // 设置异常信息到请求属性中
//        $request->attributes->set('exception_data', [
//            'code' => $e->getCode(),
//            'msg' => $e->getMessage(),
//            'file' => $e->getFile(),
//            'line' => $e->getLine(),
//        ]);

        // 验证是API还是WEB
        if ($request->isJson() || $request->ajax()) {

            return json($code, $msg, $data, 500);
        }

        // TODO: 其他非API请求的处理逻辑
        return parent::render($request, $e);
    }
}
