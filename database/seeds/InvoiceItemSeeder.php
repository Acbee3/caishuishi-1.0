<?php

use Illuminate\Database\Seeder;

class InvoiceItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $list = \App\Models\Accounting\Invoice::query()->get();
        foreach ($list as $item) {

            $faker = Faker\Factory::create();
            $count = $faker->numberBetween(3, 8);
            while ($count-- >= 0) {

                $ywlx_id = $faker->numberBetween(1, 324);
                $account_subject = \App\Models\AccountSubject::query()->whereKey($ywlx_id)->first();

                if (empty($account_subject))
                    continue;

                $money = $faker->numberBetween(50, 1000);
                $tax_rate = [0.17, 0.03][rand(0, 1)];
                $tax_money = $money * $tax_rate;
                $fee_tax_sum = $money + $tax_money;

                $account_id = \App\Entity\Invoice::get_account_id($item->company_id, $account_subject->number);

                \App\Models\Accounting\InvoiceItem::create([
                    'company_id' => $item->company_id,
                    'invoice_id' => $item->id,
                    'ywlx_id' => $account_subject->number,
                    'ywlx_name' => $account_subject->name,
                    'kpxm_id' => $faker->numberBetween(1, 20),
                    'kpxm_name' => $faker->userName,
                    'num' => $faker->numberBetween(1, 10),
                    'money' => $money,
                    'tax_rate' => $tax_rate,
                    'tax_money' => $tax_money,
                    'fee_tax_sum' => $fee_tax_sum,
                    'account_number' => $account_subject->number,
                    'account_name' => $account_subject->name,
                    'fiscal_period' => $item->fiscal_period,
                    'account_id' => $account_id,
                ]);

            }

            $sum = \App\Models\Accounting\InvoiceItem::query()->where('invoice_id', $item->id)->sum('fee_tax_sum');
            \App\Models\Accounting\Invoice::where('id', $item->id)->update(['total_fee_tax_money' => $sum]);

        }
    }
}
