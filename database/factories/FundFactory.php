<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Accounting\Fund::class, function (Faker $faker) {
    //$yw = (new App\Entity\BusinessDataConfig\BusinessConfig(4))->getData();
    $month = $faker->numberBetween(7, 12);
    return [
        //
        'company_id' => 1,
        'fund_date' => $faker->date('Y-m-d'),
        'fund_type' => $faker->numberBetween(0, 1),
        'channel_type' => $faker->numberBetween(1, 3),
        'source_type' => 0,
        'money' => $faker->randomFloat(2, 100, 2000),
        'bank_name' => '银行1',
        'bank_id' => '1',
        'fiscal_period' => date('Y-m-d', strtotime("2018-{$month}")),
    ];
});
