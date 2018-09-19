<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAccountIdToCostItem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if (!Schema::hasColumn('cost_item', 'account_id')) {
            Schema::table('cost_item', function (Blueprint $table) {
                $table->integer('account_id')->default(0)->comment('费用科目id');
            });
        }

        if (!Schema::hasColumn('cost_item', 'cash')) {
            Schema::table('cost_item', function (Blueprint $table) {
                $table->decimal('cash', 11, 2)->default(0)->comment('现金结算金额');
            });
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
