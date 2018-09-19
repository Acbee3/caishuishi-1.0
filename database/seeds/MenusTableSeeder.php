<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $list = DB::table('menus')->get();
        $num = count($list);
        if($num == 0){
            DB::table('menus')->insert(
                array (
                    0 => array (
                        'id' => 1,
                        'menu_name' => '代账中心菜单',
                        'menu_code' => 'agent_center_menu',
                        'role_ids' => 1,
                        'status' => 'yes',
                        'created_at' => '2018-05-30 15:00:00',
                        'updated_at' => '2018-05-30 15:00:00',
                    )
                )
            );
        }
    }
}
