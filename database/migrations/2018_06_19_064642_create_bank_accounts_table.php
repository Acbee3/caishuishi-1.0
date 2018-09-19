<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBankAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('company_id')->comment('公司id');
            $table->string('name')->comment('账户简称');
            $table->unsignedInteger('subject_id')->comment('对应科目');
            $table->unsignedTinyInteger('status')->default(1)->comment('状态 0:冻结 1:正常');
            $table->string('currency')->nullable()->comment('币种');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bank_accounts');
    }
}
