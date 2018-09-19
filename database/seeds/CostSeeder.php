<?php

use Illuminate\Database\Seeder;

class CostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Accounting\Cost::query()->truncate();
        \App\Models\Accounting\CostItem::query()->truncate();
        $invoice = factory(\App\Models\Accounting\Cost::class, 200)->create();

        $list = \App\Models\Accounting\Cost::query()->get();

        $feeList = \App\Models\AccountSubject::query()
            ->select(['name', 'id', 'number'])->where('name', 'like', '%费用%')
            ->get()->toArray();

        foreach ($list as $cost) {

            $faker = Faker\Factory::create();
            $num = $faker->numberBetween(1, 5);

            while ($num-- > 0) {

                $fee = $feeList[array_rand($feeList)];
                $money = $faker->numberBetween(50, 500);
                $cost->total_money = $cost->total_money + $money;
                $cost->save();

                \App\Models\Accounting\CostItem::create([
                    'company_id' => $cost['company_id'],
                    'cost_id' => $cost['id'],
                    'fyrq' => $faker->date('Y-m-d'),
                    'fylx' => $fee['number'] . $fee['name'],
                    'account_number' => $fee['number'],
                    'account_name' => $fee['name'],
                    'account_id' => $fee['id'],
                    'money' => $money,
                    'dw_id' => 1,
                    'dw_name' => '北京大学',
                    'remark' => $faker->company,
                    'fiscal_period' => $cost['fiscal_period']
                ]);
            }

        }
    }
}
