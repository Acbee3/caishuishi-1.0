<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeRegDateCompanyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    /**
     * @throws \Doctrine\DBAL\DBALException
     */
    public function up()
    {
        Schema::getConnection()->getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
        Schema::table('company', function (Blueprint $table) {
            $table->date("reg_date")->nullable()->comment("注册日期")->change();
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
