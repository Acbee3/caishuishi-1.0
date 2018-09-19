<?php

use Illuminate\Database\Seeder;

use App\Models\Accounting\BusinessConfig;
class BusinessConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $data = [
            ['type' => BusinessConfig::TYPE_1,'name'=>'原材料','kjkmbm'=>'原材料','level'=>BusinessConfig::LEVEL_1,'level'=>BusinessConfig::LEVEL_1],
        ];

    }
}
