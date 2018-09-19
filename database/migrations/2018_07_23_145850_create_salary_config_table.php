<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalaryConfigTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salary_config', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id')->comment('公司ID');
            $table->integer('status')->default('0')->comment("状态 0:可修改 1:锁定不可修改");
            $table->integer('gz')->comment('工资科目ID');
            $table->integer('nzj')->comment('年终奖科目ID');
            $table->integer('qy_gjj')->comment('企业公积金科目ID');
            $table->integer('qy_sb')->comment('企业社保科目ID');
            $table->integer('gr_gjj')->comment('个人公积金科目ID');
            $table->integer('gr_sb')->comment('个人社保科目ID');
            $table->integer('gs')->comment('个税科目ID');
            $table->integer('gx')->comment('股息红利科目ID');
            $table->timestamps();
        });
        Schema::create('salary_cost_config', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id')->comment('公司ID');
            $table->integer('status')->default('0')->comment("状态 0:可修改 1:锁定不可修改");
            $table->integer('cost_type')->comment('费用类型');
            $table->integer('gz')->comment('工资科目ID');
            $table->integer('nzj')->comment('年终奖科目ID');
            $table->integer('qy_gjj')->comment('企业公积金科目ID');
            $table->integer('qy_sb')->comment('企业社保科目ID');
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
        Schema::dropIfExists('salary_config');
        Schema::dropIfExists('salary_cost_config');
    }
}
