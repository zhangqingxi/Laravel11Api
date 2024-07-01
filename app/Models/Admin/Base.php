<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 模型基类
 *
 * @作者 Qasim
 * @日期 2023/6/28
 * @method static \Illuminate\Database\Eloquent\Builder|Base newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Base newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Base query()
 * @mixin \Eloquent
 */
class Base extends Model
{
    use HasFactory;

    //链接数据库
    protected $connection = 'admin';

    // 基本$casts属性
    protected array $baseCasts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->mergeBaseCasts();
    }

    /**
     * 合并 $casts 属性
     * @作者 Qasim
     * @日期 2023/6/28
     */
    public function mergeBaseCasts(): void
    {
        $this->casts = array_merge($this->baseCasts, $this->casts);
    }
}
