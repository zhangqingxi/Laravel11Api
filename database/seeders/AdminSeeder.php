<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin\Admin;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Admin::query()->truncate();
        Admin::query()->create([
            'account' => 'admin',
            'nickname' => '超级管理员',
            'email' => 'admin@example.com',
            'phone' => '157xxxxxxxx',
            'password' => Hash::make('123456789'),
            'status' => 1,
        ]);
    }
}
