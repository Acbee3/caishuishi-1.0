<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLedgersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ledgers', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('company_id')->comment('所属公司id');
            $table->unsignedInteger('account_subject_id')->comment('科目id');
            $table->string('account_subject_number')->comment('科目编码');
            $table->string('account_subject_name')->comment('科目名称');
            $table->string('balance_direction')->comment('余额方向');
            $table->decimal('qcye',11,2)->default(0)->comment('期初余额');
            $table->decimal('qcye_j',11,2)->default(0)->comment('期初余额_借');
            $table->decimal('qcye_d',11,2)->default(0)->comment('期初余额-贷');
            $table->decimal('bqhj',11,2)->default(0)->comment('本期合计');
            $table->decimal('bqhj_j',11,2)->default(0)->comment('本期合计_借');
            $table->decimal('bqhj_d',11,2)->default(0)->comment('本期合计-贷');
            $table->decimal('bnlj',11,2)->default(0)->comment('本年累计');
            $table->decimal('bnlj_j',11,2)->default(0)->comment('本年累计_借');
            $table->decimal('bnlj_d',11,2)->default(0)->comment('本年累计-贷');
            $table->date('fiscal_period')->nullable()->comment('所属会计期间');
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
        Schema::dropIfExists('ledgers');
    }
}
