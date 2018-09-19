<?php

use Illuminate\Database\Seeder;

class FundItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $list = \App\Models\Accounting\Fund::query()->get();
        foreach ($list as $item) {

            $faker = Faker\Factory::create();
            $count = $faker->numberBetween(2, 4);
            while ($count-- >= 0) {
                \App\Models\Accounting\FundItem::create([
                    'fund_id' => $item->id,
                    'funditem_date' => $item->fund_date,
                    'money' => $faker->randomFloat(2, 100, 300),
                    'remark' => $faker->name,
                    'fiscal_period' => $item->fiscal_period,
                ]);
            }

            $sum = \App\Models\Accounting\FundItem::query()->where('fund_id', $item->id)->sum('money');
            \App\Models\Accounting\Fund::whereKey($item->id)->update(['money' => $sum]);

        }
    }
}
