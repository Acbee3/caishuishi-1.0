<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgentDepartment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create("agent_department",function (Blueprint $table){
            $table->increments("id");
            $table->integer('agent_id')->comment('代账公司ID');
            $table->integer('department_name')->comment('部门名称');
            $table->integer('status')->comment('状态 1正常；-1删除');
            $table->integer('level')->comment('等级 1总公司；2部门');
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
        //
    }
}
