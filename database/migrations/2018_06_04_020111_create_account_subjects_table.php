<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountSubjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_subjects', function (Blueprint $table) {
            $table->increments('id');
            $table->string('number')->comment('编号');
            $table->string('name')->comment('会计科目名称');
            $table->string('description')->nullable()->comment('会计科目适用范围');
            $table->string('type')->comment('分类');
            $table->unsignedInteger('company_id')->default(0)->comment('所属公司id');
            $table->unsignedInteger('pid')->default(0)->comment('上级科目id');
            $table->unsignedTinyInteger('level')->default(0)->comment('科目层级');
            $table->unsignedTinyInteger('status')->default(1)->comment('状态 0:禁用 1:启用');
            $table->string('balance_direction')->nullable()->comment('余额方向 借 贷');
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
        Schema::dropIfExists('account_subjects');
    }
}
