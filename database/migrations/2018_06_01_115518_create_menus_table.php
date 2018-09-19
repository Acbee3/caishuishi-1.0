<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * 菜单管理表
         */
		Schema::getConnection()->getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
        Schema::create('menus', function (Blueprint $table) {
            $table->increments('id');
            $table->string('menu_name',50)->comment("菜单名称");
            $table->string('menu_code',50)->nullable()->comment("菜单代码");
            $table->string('role_ids',200)->nullable()->comment("应用此菜单角色");
            $table->enum('status', ['yes', 'no'])->default('yes')->comment("启用状态");
            $table->softDeletes();
            $table->timestamps();
        });

        /**
         * 菜单链接路由表
         */
        Schema::create('menu_actions', function (Blueprint $table) {
            $table->increments('id');

            //$table->string('menu_code',50)->nullable()->comment("所归属菜单代码");
            $table->unsignedInteger('menu_id')->nullable()->comment("所归属菜单ID");
            $table->foreign('menu_id')->references('id')->on('menus')->onDelete('cascade');

            $table->string('action_name',50)->comment("链接名称");
            $table->string('action_route',100)->nullable()->comment("链接路由");
            $table->integer('parent_id')->default('0')->nullable()->comment("父级ID");
            $table->string('child_ids',200)->nullable()->comment("子级ID");
            $table->string('role_ids',200)->nullable()->comment("应用此链接角色");
            $table->enum('status', ['yes', 'no'])->default('yes')->comment("启用状态");
            $table->integer('sort_order')->default('30')->comment("排序");
            $table->softDeletes();
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
        Schema::dropIfExists('menus');
        Schema::dropIfExists('menu_actions');
    }
}
