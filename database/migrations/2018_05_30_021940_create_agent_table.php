<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agent', function (Blueprint $table) {
            $table->increments('id');
            $table->string("name")->comment("代账公司名称");
            $table->string("phone")->comment("联系电话");
            $table->string("contacts")->nullable()->comment("联系人");
            $table->string("address")->nullable()->comment("联系地址");
            $table->string("pic")->nullable()->comment("工商营业执照");
            $table->tinyInteger("status")->nullable()->comment("代账公司状态 0禁用；1正常");
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
        Schema::dropIfExists('agent');
    }
}
