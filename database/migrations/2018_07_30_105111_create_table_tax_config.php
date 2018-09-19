<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableTaxConfig extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('tax_config')) {
            Schema::create('tax_config', function (Blueprint $table) {
                $table->increments('id');
                $table->integer("company_id")->default(0)->comment("企业ID");
                $table->integer("tax_id")->default(0)->comment("税金id");
                $table->string('tax_name', 255)->default('')->comment("税金");
                $table->string('debit_number', 255)->default('')->comment("借方科目编号");
                $table->string('debit_name', 255)->default('')->comment("借方科目");
                $table->string('credit_number', 255)->default('')->comment("贷方科目编号");
                $table->string('credit_name', 255)->default('')->comment("贷方科目");
                $table->smallInteger('status')->default('0')->comment("状态(1-启用 0-停用)");
                $table->timestamps();
            });
        }
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
