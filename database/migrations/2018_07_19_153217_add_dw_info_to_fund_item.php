<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDwInfoToFundItem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fund_item', function (Blueprint $table) {
            if (!Schema::hasColumn('fund_item', 'dw_id')) {
                $table->integer('dw_id')->default('0')->comment('单位id');
            }

            if (!Schema::hasColumn('fund_item', 'dw_name')) {
                $table->string('dw_name', '255')->default('')->comment('单位名称');
            }
        });
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
