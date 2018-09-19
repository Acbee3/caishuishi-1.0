<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuthRelationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auth_relations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('agent_id')->comment("代账公司ID");
            $table->integer('company_id')->comment("客户公司ID");
            $table->integer('role_id')->comment("用户角色ID");
            $table->integer('user_id')->comment("用户ID");
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
        Schema::dropIfExists('auth_relations');
    }
}
