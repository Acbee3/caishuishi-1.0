<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBussinessDatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bussiness_datas', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('company_id')->comment('公司id');
            $table->string('name')->comment('名称');
            $table->string('short_name')->nullable()->comment('别名');
            $table->unsignedTinyInteger('status')->default('1')->comment('状态 0:冻结 1:正常');
            $table->unsignedTinyInteger('type')->comment('分类 1:客户 2:供应商 3: 其他往来 4:投资方');
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
        Schema::dropIfExists('bussiness_datas');
    }
}
