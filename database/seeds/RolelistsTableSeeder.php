<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolelistsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $list = DB::table('role_lists')->get();
        $num = count($list);
        if($num == 0){
            DB::table('role_lists')->insert(
                array (
                    0 => array (
                        'id' => 1,
                        'action_name' => '全部',
                        'action_code' => 'all',
                        'parent_id' => 0,
                        'sort_order' => 10,
                        'status' => 'yes',
                        'created_at' => '2018-05-30 15:00:00',
                        'updated_at' => '2018-05-30 15:00:00',
                    ),
                    1 => array (
                        'id' => 2,
                        'action_name' => '会员管理',
                        'action_code' => 'sysuser_manage',
                        'parent_id' => 0,
                        'sort_order' => 20,
                        'status' => 'yes',
                        'created_at' => '2018-05-30 15:00:00',
                        'updated_at' => '2018-05-30 15:00:00',
                    ),
                    2 => array (
                        'id' => 3,
                        'action_name' => '添加会员',
                        'action_code' => 'sysuser_add',
                        'parent_id' => 2,
                        'sort_order' => 1,
                        'status' => 'yes',
                        'created_at' => '2018-05-30 15:00:00',
                        'updated_at' => '2018-05-30 15:00:00',
                    ),
                    3 => array (
                        'id' => 4,
                        'action_name' => '编辑会员',
                        'action_code' => 'sysuser_edit',
                        'parent_id' => 2,
                        'sort_order' => 2,
                        'status' => 'yes',
                        'created_at' => '2018-05-30 15:00:00',
                        'updated_at' => '2018-05-30 15:00:00',
                    ),
                    4 => array (
                        'id' => 5,
                        'action_name' => '删除会员',
                        'action_code' => 'sysuser_del',
                        'parent_id' => 2,
                        'sort_order' => 3,
                        'status' => 'yes',
                        'created_at' => '2018-05-30 15:00:00',
                        'updated_at' => '2018-05-30 15:00:00',
                    ),
                    5 => array (
                        'id' => 6,
                        'action_name' => '角色管理',
                        'action_code' => 'sysrole_manage',
                        'parent_id' => 0,
                        'sort_order' => 21,
                        'status' => 'yes',
                        'created_at' => '2018-05-30 15:00:00',
                        'updated_at' => '2018-05-30 15:00:00',
                    ),
                    6 => array (
                        'id' => 7,
                        'action_name' => '添加角色',
                        'action_code' => 'sysrole_add',
                        'parent_id' => 6,
                        'sort_order' => 1,
                        'status' => 'yes',
                        'created_at' => '2018-05-30 15:00:00',
                        'updated_at' => '2018-05-30 15:00:00',
                    ),
                    7 => array (
                        'id' => 8,
                        'action_name' => '编辑角色',
                        'action_code' => 'sysrole_edit',
                        'parent_id' => 6,
                        'sort_order' => 2,
                        'status' => 'yes',
                        'created_at' => '2018-05-30 15:00:00',
                        'updated_at' => '2018-05-30 15:00:00',
                    ),
                    8 => array (
                        'id' => 9,
                        'action_name' => '删除角色',
                        'action_code' => 'sysrole_del',
                        'parent_id' => 6,
                        'sort_order' => 3,
                        'status' => 'yes',
                        'created_at' => '2018-05-30 15:00:00',
                        'updated_at' => '2018-05-30 15:00:00',
                    ),
                    9 => array (
                        'id' => 10,
                        'action_name' => '角色权限管理',
                        'action_code' => 'sysrolelist_manage',
                        'parent_id' => 0,
                        'sort_order' => 22,
                        'status' => 'yes',
                        'created_at' => '2018-05-30 15:00:00',
                        'updated_at' => '2018-05-30 15:00:00',
                    ),
                    10 => array (
                        'id' => 11,
                        'action_name' => '添加角色权限',
                        'action_code' => 'sysrolelist_add',
                        'parent_id' => 10,
                        'sort_order' => 1,
                        'status' => 'yes',
                        'created_at' => '2018-05-30 15:00:00',
                        'updated_at' => '2018-05-30 15:00:00',
                    ),
                    11 => array (
                        'id' => 12,
                        'action_name' => '编辑角色权限',
                        'action_code' => 'sysrolelist_edit',
                        'parent_id' => 10,
                        'sort_order' => 2,
                        'status' => 'yes',
                        'created_at' => '2018-05-30 15:00:00',
                        'updated_at' => '2018-05-30 15:00:00',
                    ),
                    12 => array (
                        'id' => 13,
                        'action_name' => '删除角色权限',
                        'action_code' => 'sysrolelist_del',
                        'parent_id' => 10,
                        'sort_order' => 3,
                        'status' => 'yes',
                        'created_at' => '2018-05-30 15:00:00',
                        'updated_at' => '2018-05-30 15:00:00',
                    )
                )
            );
        }

    }
}
