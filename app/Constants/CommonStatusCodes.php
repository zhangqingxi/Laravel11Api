<?php

namespace App\Constants;

/**
 * 通用接口状态码常量
 * @Auther Qasim
 * @date 2023/6/28
 */
class CommonStatusCodes
{

    // Request
    const REQUEST_EXPIRED = 6400;
    const REQUEST_DUPLICATE = 6401;
    const TOO_MANY_REQUESTS = 6402;


    // 加密解密
    const ENCRYPTION_FAILED = 6500;
    const DECRYPTION_FAILED = 6501;

    const ENCRYPTION_KEY_NOT_FOUND = 6502;

    //Token
    const TOKEN_INVALID = 6600;
    const TOKEN_NOT_PROVIDED = 6601;

    //路由
    const ROUTE_NOT_FOUND = 6700;

    //数据库错误
    const DATABASE_ERROR = 6900;

    //数据校验错误
    const VALIDATION_ERROR = 7000;

    //异常错误
    const EXCEPTION_ERROR = 7100;
    const NOT_FOUNT_EXCEPTION = 7101;
    const METHOD_NOT_ALLOWED = 7102;


}
