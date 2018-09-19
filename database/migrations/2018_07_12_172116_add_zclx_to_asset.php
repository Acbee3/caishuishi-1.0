<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddZclxToAsset extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('asset', function (Blueprint $table){
            $table->string('zclx')->comment('资产类型');
            $table->tinyInteger('status')->default(1)->comment('状态 1:正常 0:折旧完毕');
            $table->string('zjje')->nullable()->comment('折旧金额');
            $table->date('txks')->nullable()->comment('摊销开始');
            $table->decimal('yz', 11 ,2)->default('0')->comment('原值')->change();
            $table->decimal('cz', 11 ,2)->default('0')->comment('残值')->change();
            $table->string('zjff')->comment('折旧方法(平均年限法……) ')->change();
            $table->integer('yzkm_id')->nullable()->comment('原值科目id')->change();
            $table->integer('ljzjkm_id')->nullable()->comment('累计折旧科目id')->change();
            $table->integer('cbfykm_id')->nullable()->comment('成本费用科目id')->change();
            $table->string('cbfykm')->nullable()->comment('成本费用科目')->change();
            $table->string('remark')->nullable()->comment('备注')->change();
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
