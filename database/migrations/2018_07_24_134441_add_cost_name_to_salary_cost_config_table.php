<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCostNameToSalaryCostConfigTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('salary_cost_config', function (Blueprint $table) {
            $table->string('cost_name',50)->nullable()->comment("费用名称");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('salary_cost_config', function (Blueprint $table) {
            //
        });
    }
}
