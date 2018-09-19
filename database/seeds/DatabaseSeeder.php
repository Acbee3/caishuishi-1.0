<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RolesTableSeeder::class);
        $this->call(RolelistsTableSeeder::class);
        $this->call(MenusTableSeeder::class);
        $this->call(MenuactionsTableSeeder::class);
        //$this->call(UsersTableSeeder::class);// 测试环境使用 线上屏蔽
        //$this->call(AgentTableSeeder::class);// 测试环境使用 线上屏蔽
        //$this->call(AccountSubjectsSeeder::class);
        //$this->call(BusinessConfigSeeder::class);
    }
}
