<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInfosToCompanyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company', function (Blueprint $table) {
            $table->string('company_code', 120)->nullable()->comment("编码");
            $table->string('reg_sort', 120)->nullable()->comment("登记注册类型");
            $table->dateTime('reg_date')->nullable()->comment("注册日期");
            $table->string('company_sort')->nullable()->comment("企业类型");
            $table->string('credit_code', 18)->nullable()->comment("社会统一信用代码");
            $table->integer('area_id')->nullable()->comment("地区");
            $table->string('company_address')->nullable()->comment("营业地址");
            $table->longText('scope_business')->nullable()->comment("经营范围");
            $table->string('legal_person')->nullable()->comment("法定代表人");
            $table->string('legal_personphone')->nullable()->comment("法定代表人电话");
            $table->string('finance_person')->nullable()->comment("财务联系人");
            $table->string('finance_personphone')->nullable()->comment("财务联系人电话");
            $table->string('company_person')->nullable()->comment("企业联系人");
            $table->string('company_personphone')->nullable()->comment("企业联系人电话");
            $table->string('taxpayer_number', 20)->nullable()->comment("纳税人识别号");
            $table->string('taxpayer_rights')->nullable()->comment("纳税人资格");
            $table->string('taxpayer_rank')->nullable()->comment("纳税人信用等级");
            $table->string('registered_capital')->nullable()->comment("注册资本");
            $table->string('paidup_capital')->nullable()->comment("实收资本");
            $table->string('stop_using')->nullable()->comment("停用账期");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('company', function (Blueprint $table) {
            //
        });
    }
}
