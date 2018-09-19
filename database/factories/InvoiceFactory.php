<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Accounting\Invoice::class, function (Faker $faker) {

    $val = [
        'company_id' => 1,
        'fpdm' => $faker->numberBetween(100000000, 999999999),
        'fphm' => $faker->numberBetween(100000000, 999999999),
        'kprq' => $faker->date('Y-m-d'),
        'type' => rand(1, 2),
        'dkzt' => rand(0, 1),
        'dkfs' => rand(1, 2),
        'fpzs' => $faker->numberBetween(1, 20),
        'voucher_id' => 0,
        'jszt' => rand(0, 1),
        'fiscal_period' => date('Y-m-d', strtotime('2018-' . rand(7, 12))),
    ];

    $val['sub_type'] = $val['type'] == \App\Entity\Invoice::TYPE_IMPORT ? rand(11, 15) : rand(21, 30);

    if ($val['type'] == \App\Entity\Invoice::TYPE_EXPORT) {
        $val['gfdw_id'] = $faker->randomDigitNotNull;
        $val['gfdw_name'] = $faker->company;
        $val['gfdw_nsrsbh'] = $faker->creditCardNumber;
        $val['gfdw_yhzh'] = $faker->bankAccountNumber;
        $val['gfdw_dzdh'] = $faker->address . " " . $faker->phoneNumber;
    } else if ($val['type'] == \App\Entity\Invoice::TYPE_IMPORT) {
        $val['xfdw_id'] = $faker->randomDigitNotNull;
        $val['xfdw_name'] = $faker->company;
        $val['xfdw_nsrsbh'] = $faker->creditCardNumber;
        $val['xfdw_yhzh'] = $faker->bankAccountNumber;
        $val['xfdw_dzdh'] = $faker->address . " " . $faker->phoneNumber;
    }

    return $val;
});
