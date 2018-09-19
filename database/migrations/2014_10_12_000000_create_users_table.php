<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment("用户名");
            $table->string('email')->nullable()->comment("邮箱");
            $table->string('phone')->nullable()->comment("手机");
            $table->string('job_number')->nullable()->comment("工号");

            $table->tinyInteger('level')->nullable()->comment("用户等级：1后台；2代账公司；3企业主");
            $table->integer('agent_id')->nullable()->comment("代账公司ID");
            $table->integer('company_id')->nullable()->comment("企业ID");
            $table->string('password')->comment("密码");
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
