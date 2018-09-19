<?php

use Illuminate\Database\Seeder;

class InvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Accounting\Invoice::query()->truncate();
        \App\Models\Accounting\InvoiceItem::query()->truncate();
        $invoice = factory(\App\Models\Accounting\Invoice::class, 500)->create();
        $this->call(InvoiceItemSeeder::class);
    }
}
