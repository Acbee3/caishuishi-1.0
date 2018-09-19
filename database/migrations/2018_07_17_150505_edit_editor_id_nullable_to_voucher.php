<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditEditorIdNullableToVoucher extends Migration
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
            $table->integer("auditor_id")->nullable()->comment("审核人id")->change();
            $table->string("auditor_name")->nullable()->comment("审核人名称")->change();
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
