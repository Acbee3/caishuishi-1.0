<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeAreaIdToCompanyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::getConnection()->getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
        Schema::table('company', function (Blueprint $table) {
            $table->string("area_id")->nullable()->comment("地区")->change();
            $table->integer('taxpayer_rights')->nullable()->comment("纳税人资格 0:增值税一般纳税人 1:增值税小规模纳税人")->change();
            $table->integer('used_year')->nullable()->comment("启用期间年");
            $table->integer('used_month')->nullable()->comment("启用期间月");
            $table->integer('accounting_system')->nullable()->comment("会计制度 0:企业会计准则 1:小企业会计准则");
            $table->string('standard_money')->default('RMB')->comment("本位币 RMB:人民币");
            $table->integer('accounting_trade')->default('0')->comment("会计行业 0:通用行业");
            $table->string('subject_length')->default('4,2,2')->comment("科目长度");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
