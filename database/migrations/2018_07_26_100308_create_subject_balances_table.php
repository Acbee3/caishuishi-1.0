<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubjectBalancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subject_balances', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('company_id')->comment('所属公司id');
            $table->unsignedInteger('account_subject_id')->comment('科目id');
            $table->string('account_subject_number')->comment('科目编码');
            $table->string('account_subject_name')->comment('科目名称');
            $table->decimal('qcye_j',11,2)->default(0)->comment('期初余额_借');
            $table->decimal('qcye_d',11,2)->default(0)->comment('期初余额-贷');
            $table->decimal('bqfse_j',11,2)->default(0)->comment('本期发生额_借');
            $table->decimal('bqfse_d',11,2)->default(0)->comment('本期发生额-贷');
            $table->decimal('bnljfse_j',11,2)->default(0)->comment('本年累计发生额_借');
            $table->decimal('bnljfse_d',11,2)->default(0)->comment('本年累计发生额_贷');
            $table->decimal('qmye_j',11,2)->default(0)->comment('期末余额_借');
            $table->decimal('qmye_d',11,2)->default(0)->comment('期末余额-贷');
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
        Schema::dropIfExists('subject_balances');
    }
}
