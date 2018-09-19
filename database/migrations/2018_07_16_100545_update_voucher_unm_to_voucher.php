<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateVoucherUnmToVoucher extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table("voucher",function (Blueprint $table){
            $table->integer("voucher_num")->default(0)->comment("记账号")->change();
        });

        Schema::table("voucher_item",function (Blueprint $table){
            $table->string("kuaijibianhao")->default('')->comment("记账号");
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
