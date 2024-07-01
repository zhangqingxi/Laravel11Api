<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

/**
 * 请求日志模型
 *
 * @Auther Qasim
 * @date 2023/6/28
 * @method static \Illuminate\Database\Eloquent\Builder|RequestLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RequestLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RequestLog query()
 * @mixin \Eloquent
 */
class RequestLog extends Base
{
    use HasFactory;

    protected $fillable = [
        'host', 'url', 'method', 'ip', 'user_agent', 'headers', 'request_id', 'exception_data',
        'request_data', 'encrypt_request_data', 'response_data', 'encrypt_response_data', 'http_status'
    ];

    protected $casts = [
        'headers' => 'array',
        'request_data' => 'array',
        'exception_data' => 'array',
        'response_data' => 'array',
    ];
}
