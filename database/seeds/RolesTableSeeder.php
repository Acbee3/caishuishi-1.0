<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $list = DB::table('roles')->get();
        $num = count($list);
        if($num == 0){
            //DB::table('roles')->delete();
            DB::table('roles')->insert(
                array (
                    0 => array (
                        'id' => 1,
                        'role_name' => '超级管理员',
                        'role_list' => 'all',
                        'role_desc' => '超级管理员，拥有全部权限。',
                        'status' => 'yes',
                        'add_by' => 'seeder',
                        'created_at' => '2018-05-30 15:00:00',
                        'updated_at' => '2018-05-30 15:00:00',
                    ),
                    1 => array (
                        'id' => 2,
                        'role_name' => '系统管理员',
                        'role_list' => 'all',
                        'role_desc' => '代账公司总管理员权限。',
                        'status' => 'yes',
                        'add_by' => 'sys',
                        'created_at' => '2018-05-30 15:00:00',
                        'updated_at' => '2018-05-30 15:00:00',
                    ),
                    2 => array (
                        'id' => 3,
                        'role_name' => '会计组',
                        'role_list' => '',
                        'role_desc' => '会计组权限',
                        'status' => 'yes',
                        'add_by' => 'sys',
                        'created_at' => '2018-05-30 15:00:00',
                        'updated_at' => '2018-05-30 15:00:00',
                    )
                )
            );
        }

    }
}
