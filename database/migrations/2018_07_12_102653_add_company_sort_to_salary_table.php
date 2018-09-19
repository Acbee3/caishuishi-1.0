<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCompanySortToSalaryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('salary', function (Blueprint $table) {
            $table->string("begin_date")->nullable()->comment("薪酬所属期起")->change();
            $table->string("end_date")->nullable()->comment("薪酬所属期止")->change();
            $table->integer("voucher_id")->nullable()->comment("凭证id")->change();
            $table->integer("pay_type")->nullable()->comment("支付方式")->change();

            $table->string("belong_time")->nullable()->comment("薪酬所属期");
            $table->integer("pay_type_id")->nullable()->comment("支付方式id");
            $table->integer("bank_account_id")->nullable()->comment("银行账户id");
            $table->integer("company_sort_id")->nullable()->comment("企业类型id");
            $table->integer("zsfs_id")->nullable()->comment("征收方式id");

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('salary', function (Blueprint $table) {
            //
        });
    }
}
