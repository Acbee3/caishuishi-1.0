<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInfosToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::getConnection()->getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
        Schema::table('users', function (Blueprint $table) {
            $table->dateTime("login_at")->nullable()->comment("最近登录时间");
            //$table->string("phone")->nullable()->comment("手机号");
            //$table->string("job_number")->nullable()->comment("工号");
            ////$table->tinyInteger("status")->nullable()->comment("状态");
            $table->enum('status', ['yes', 'no'])->default('yes')->comment("启用状态");
            //$table->string('email')->nullable()->change();
            $table->string('avatar', 120)->nullable()->comment("用户头像");
            $table->float('logintimes')->default('0')->comment("登录次数");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //Schema::dropIfExists('users');
    }
}
