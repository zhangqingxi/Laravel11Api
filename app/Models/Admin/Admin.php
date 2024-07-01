<?php

namespace App\Models\Admin;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * 
 *
 * @property int $id
 * @property string $account 管理员账号
 * @property string $nickname 管理员昵称
 * @property string $email 邮箱
 * @property string $phone 手机
 * @property string $password 密码
 * @property string|null $avatar 头像
 * @property string|null $login_ip 登录IP
 * @property \Illuminate\Support\Carbon|null $last_login_at 最近登录时间
 * @property int $status 状态：0禁用，1启用
 * @property int $login_failed_attempts 登录失败次数
 * @property int $is_locked 账号是否被锁定 0: 否 1：是
 * @property string|null $last_failed_login_at 最后一次登录失败时间
 * @property string|null $last_failed_attempts_reset_at 登录失败尝试重置时间
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Illuminate\Database\Eloquent\Builder|Admin newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Admin newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Admin query()
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereAccount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereIsLocked($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereLastFailedAttemptsResetAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereLastFailedLoginAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereLastLoginAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereLoginFailedAttempts($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereLoginIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereNickname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Admin extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $connection = 'admin';

    protected $fillable = [
        'account', 'nickname', 'email', 'phone', 'password', 'avatar', 'status', 'login_ip', 'last_login_at', 'login_attempts'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'last_login_at' => 'datetime',
    ];
}
