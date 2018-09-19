<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*
         * 新增 角色表
         */
		Schema::getConnection()->getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('role_name',120)->comment("角色名称");
            $table->longText('role_list')->nullable()->comment("权限列表");
            $table->longText('role_desc')->nullable()->comment("权限描述");
            $table->enum('status', ['yes', 'no'])->default('yes')->comment("启用状态");
            $table->softDeletes();
            $table->timestamps();
        });

        /*
         * 新增 权限列表、权限匹配表
         */
        Schema::create('role_lists', function (Blueprint $table) {
            $table->increments('id');
            $table->string('action_name',120)->comment("权限名称");
            $table->string('action_code',120)->comment("权限代码");
            $table->integer('parent_id')->default('0')->nullable()->comment("父级ID");
            $table->integer('sort_order')->default('50')->comment("排序");
            $table->enum('status', ['yes', 'no'])->default('yes')->comment("启用状态");
            $table->softDeletes();
            $table->timestamps();
        });

        /*
         * 新增 公司类型表
         * status no 禁用； yes 启用
         */
        Schema::create('company_sorts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',120)->comment("公司类型名称");
            $table->enum('status', ['yes', 'no'])->default('yes')->comment("启用状态");
            $table->softDeletes();
            $table->timestamps();
        });

        /*
         * 新增 业务公司表
         */
        Schema::create('company', function (Blueprint $table) {
            $table->increments('id');
            $table->string('company_name',120)->comment("公司名称");
            $table->longText('company_encode')->nullable()->comment("公司识别码");

            $table->unsignedInteger('sort_id')->nullable()->comment("公司类型ID");
            $table->foreign('sort_id')->references('id')->on('company_sorts')->onDelete('cascade');

            $table->enum('status', ['yes', 'no'])->default('yes')->comment("启用状态");
            $table->softDeletes();
            $table->timestamps();

            //$table->primary(['id','sort_id']);
        });

        /*
         * 新增 系统配置表
         * sys_type:  组group   文本text   文本域textarea    下拉框select     文件（图片路径）file
         */
        Schema::create('sys_config', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sort_id')->default('0')->nullable()->comment("类别ID");
            $table->string('sys_name',120)->comment("设置名称");
            $table->string('sys_code',30)->nullable()->comment("设置代码");
            $table->string('sys_type',10)->nullable()->comment("设置类型");
            $table->longText('sys_value')->nullable()->comment("设置值");
            $table->string('store_range',255)->nullable()->comment("默认存储值");
            $table->string('store_dir',255)->nullable()->comment("存储路径");
            $table->integer('sort_order')->default('30')->comment("排序");
            $table->timestamps();
        });

        /*
         * 修改 用户表
         * 增加 角色和 公司 关联
         * 增加 同步 相关字段
         * status   0:未同步   1:已同步
         * sync_direction    up:上行方向   down:下行方向
         */
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedInteger('role_id')->nullable()->comment("权限组ID");
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');

            $table->unsignedInteger('company_id')->nullable()->comment("企业ID")->change();
            $table->foreign('company_id')->references('id')->on('users')->onDelete('cascade');

            //$table->enum('sync_status', ['yes', 'no'])->default('no')->comment("同步状态");
            //$table->enum('sync_direction', ['up', 'down','none'])->default('none')->comment("同步方向");
            //$table->dateTime('sync_firtime')->nullable()->comment("首次同步时间");
            //$table->dateTime('sync_lasttime')->nullable()->comment("上次同步时间");
            //$table->json('sync_jsontext')->nullable()->comment("同步信息");

            $table->longText('encode')->nullable()->comment("会员识别码");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles');
        Schema::dropIfExists('role_lists');
        Schema::dropIfExists('company_sorts');
        Schema::dropIfExists('company');

        Schema::dropIfExists('sys_config');
    }
}
