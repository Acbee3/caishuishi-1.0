<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBelongTimeToSalaryEmployeeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('salary_employee', function (Blueprint $table) {
            //$table->string("belong_time")->nullable()->comment("薪酬所属期")->change();
            $table->string("belong_time")->nullable()->comment("薪酬所属期");
            $table->string("remark")->nullable()->comment("备注")->change();
            $table->decimal("personal_tax", 11, 2)->nullable()->default(0)->comment("代扣个税")->change();
            $table->decimal("year_bonus", 11, 2)->nullable()->default(0)->comment("全年一次性奖金")->change();
            $table->decimal("jcfy", 11, 2)->nullable()->default(0)->comment("全年一次性奖金-减除费用（补差）")->change();
            $table->decimal("sfjj", 11, 2)->nullable()->default(0)->comment("全年一次性奖金-实发奖金")->change();
            $table->decimal("salary", 11, 2)->nullable()->default(0)->comment("工资")->change();
            $table->decimal("txf", 11, 2)->nullable()->default(0)->comment("工资-通讯费")->change();
            $table->decimal("yanglaobx", 11, 2)->nullable()->default(0)->comment("工资-养老保险")->change();
            $table->decimal("yiliaobx", 11, 2)->nullable()->default(0)->comment("工资-医疗保险")->change();
            $table->decimal("sybx", 11, 2)->nullable()->default(0)->comment("工资-失业保险")->change();
            $table->decimal("dbyl", 11, 2)->nullable()->default(0)->comment("工资-大病医疗")->change();
            $table->decimal("dkgjj", 11, 2)->nullable()->default(0)->comment("工资-代扣公积金")->change();
            $table->decimal("other_fee", 11, 2)->nullable()->default(0)->comment("工资-其他费用")->change();
            $table->decimal("real_salary", 11, 2)->nullable()->default(0)->comment("工资-实发工资")->change();
            $table->decimal("lwbc", 11, 2)->nullable()->default(0)->comment("劳务报酬")->change();
            $table->decimal("sflwbc", 11, 2)->nullable()->default(0)->comment("实发劳务报酬")->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('salary_employee', function (Blueprint $table) {
            //
        });
    }
}
