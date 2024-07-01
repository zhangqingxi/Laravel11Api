<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('admin')->create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('account', 16)->unique()->comment('管理员账号');
            $table->string('nickname', 10)->comment('管理员昵称');
            $table->string('email', 30)->unique()->comment('邮箱');
            $table->string('phone', 15)->unique()->comment('手机');
            $table->string('password', 100)->comment('密码');
            $table->string('avatar', 50)->nullable()->comment('头像');
            $table->string('login_ip', 45)->nullable()->comment('登录IP');
            $table->timestamp('last_login_at')->nullable()->comment('最近登录时间');
            $table->tinyInteger('status')->default(1)->comment('状态：0禁用，1启用');
            $table->tinyInteger('login_failed_attempts')->default(0)->comment('登录失败次数');
            $table->tinyInteger('is_locked')->default(0)->comment('账号是否被锁定 0: 否 1：是');
            $table->timestamp('last_failed_login_at')->nullable()->comment('最后一次登录失败时间');
            $table->timestamp('last_failed_attempts_reset_at')->nullable()->comment('登录失败尝试重置时间');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
            $table->comment('后台用户表');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('admin')->dropIfExists('admins');
    }
}
