<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateAccountClose extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('account_close')) {
            Schema::create('account_close', function (\Illuminate\Database\Schema\Blueprint $table) {
                $table->increments('id');
                $table->integer('company_id')->default('0')->comment('公司id');
                $table->date('fiscal_period')->comment('会计期间')->nullable();
                $table->smallInteger('close_status')->comment('状态 1-已结账 0-未结账')->default('0');
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
        //
    }
}
