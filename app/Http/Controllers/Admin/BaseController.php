<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

/**
 * 后台控制器基类
 * @Auther Qasim
 * @date 2023/6/29
 */
class BaseController extends Controller
{


    /**
     * 获取 `message.admin` 语言包
     *
     * @param string $key 语言包的键
     * @param array $replace 替换的数据
     * @param string|null $locale 语言环境
     * @return string
     */
    protected function getMessage(string $key, array $replace = [], string $locale = null): string
    {
        return message($key, 'admin', $replace, $locale);
    }
}
