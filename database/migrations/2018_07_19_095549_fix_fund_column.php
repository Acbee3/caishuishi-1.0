<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixFundColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fund', function (Blueprint $table) {
            if (Schema::hasColumn('fund', 'dw_name')) {
                $table->string('dw_name', '255')->default('')->change();
            }

            if (Schema::hasColumn('fund', 'bank_name')) {
                $table->string('bank_name', '255')->default('')->change();
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
