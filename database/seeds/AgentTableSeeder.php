<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AgentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 测试环境使用 线上屏蔽
        DB::table('agent')->insert(
            array (
                0 => array (
                    'name' => '苏州财税狮网络科技有限公司',
                    'status' => '1',
                    'phone' => '40088888888',
                    'created_at' => '2018-05-30 15:00:00',
                    'updated_at' => '2018-05-30 15:00:00',
                ),
                1 => array (
                    'name' => '金算子财务有限公司',
                    'status' => '1',
                    'phone' => '40088888888',
                    'created_at' => '2018-05-30 15:00:00',
                    'updated_at' => '2018-05-30 15:00:00',
                )
            )
        );
    }
}
