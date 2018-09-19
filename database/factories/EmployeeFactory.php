<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\Models\Employee::class, function (Faker $faker) {
    return [
        'company_id' => $faker->numberBetween(1,10),// $faker->numberBetween(1,10)
        'employee_num' => $faker->numberBetween(1000001,9999999),
        'employee_name' => $faker->name,
        'department_id' => $faker->numberBetween(1,10),//$faker->numberBetween(1,10)
        'lxdh' => $faker->phoneNumber,
        'gender' => $faker->numberBetween(0,1),
        'zjlx' => $faker->numberBetween(0,1),
        'zjhm' => $faker->numberBetween(1000001,9999999),
        'email' => $faker->unique()->safeEmail,
        'address' => $faker->address,
        'birthday' => $faker->date(),
        'remark' => '无',
        'status' => $faker->numberBetween(0,1),
        'sf_shareholder' => $faker->numberBetween(0,1),
        'country' => $faker->numberBetween(0,1),
    ];
});
