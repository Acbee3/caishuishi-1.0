<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoleRelationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_relations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('menuaction_id')->comment("菜单路由ID");
            $table->integer('agent_id')->comment("代账公司ID");
            $table->integer('user_id')->nullable()->comment("用户ID");
            $table->integer('role_id')->comment("用户角色ID");
            $table->integer('permission')->comment("权限 0查看；1操作；2无权限");
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
        Schema::dropIfExists('role_relations');
    }
}
