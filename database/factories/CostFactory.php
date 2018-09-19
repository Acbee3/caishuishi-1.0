<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Accounting\Cost::class, function (Faker $faker) {
    return [
        'company_id' => 1,
        'voucher_id' => 0,
        'total_money' => 0,
        'fiscal_period' => date('Y-m-d', strtotime('2018-' . rand(7, 12))),
    ];
});
