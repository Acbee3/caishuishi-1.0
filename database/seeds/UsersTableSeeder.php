<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 测试环境使用 线上屏蔽
        DB::table('users')->insert(
            array (
                0 => array (
                    'name' => '18801226488',
                    'email' => '18801226488@163.com',
                    'password' => bcrypt('111111'),
                    'phone' => '18801226488',
                    'status' => 'yes',
                    'role_id' => 1,
                    'created_at' => '2018-05-30 15:00:00',
                    'updated_at' => '2018-05-30 15:00:00',
                )
            )
        );
    }
}
