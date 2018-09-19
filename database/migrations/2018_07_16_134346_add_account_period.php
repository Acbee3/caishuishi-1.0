<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAccountPeriod extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tables = [
            'asset', 'asset_alter', 'cost',
            'cost_item', 'employee', 'fund',
            'fund_item', 'invoice', 'invoice_item',
            'salary', 'salary_employee', 'voucher',
            'voucher_item',
        ];

        $column = 'fiscal_period';

        foreach ($tables as $table) {
            if (!Schema::hasColumn($table, $column)) {
                Schema::table($table, function (Blueprint $table) use ($column) {
                    $table->date($column)->comment('所属会计期间')->nullable();
                });
            } else {
                Schema::table($table, function (Blueprint $table) use ($column) {
                    $table->date($column)->comment('所属会计期间')->nullable()->change();
                });
            }
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
