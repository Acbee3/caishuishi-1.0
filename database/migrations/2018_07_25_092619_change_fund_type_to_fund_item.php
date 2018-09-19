<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeFundTypeToFundItem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fund_item', function (Blueprint $table){
            $table->string('dw_num')->nullable()->comment('单位科目编码')->after('dw_name');
            $table->unsignedTinyInteger('fund_type')->nullable()->comment('借 贷');
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
