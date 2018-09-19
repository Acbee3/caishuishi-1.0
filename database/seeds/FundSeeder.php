<?php

use Illuminate\Database\Seeder;

class FundSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        \App\Models\Accounting\Fund::query()->truncate();
        \App\Models\Accounting\FundItem::query()->truncate();

        $invoice = factory(\App\Models\Accounting\Fund::class, 500)->create();
        $this->call(FundItemSeeder::class);
    }
}
