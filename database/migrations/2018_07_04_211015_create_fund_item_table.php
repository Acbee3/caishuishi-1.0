<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFundItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fund_item', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('fund_id')->comment('资金id');
            $table->date('funditem_date')->comment('日期');
            $table->integer('ywlx_id')->comment('业务类型id');
            $table->string('ywlx')->comment('业务数据');
            $table->decimal('money',11,2)->comment('金额');
            $table->string('remark')->comment('备注');
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
        Schema::dropIfExists('fund_item');
    }
}
