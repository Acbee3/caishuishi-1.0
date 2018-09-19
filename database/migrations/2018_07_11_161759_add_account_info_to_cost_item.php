<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAccountInfoToCostItem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('cost_item', 'account_number')) {
            Schema::table('cost_item', function (Blueprint $table) {
                $table->string('account_number', '255')->default('')->comment('会计科目编号');
            });
        }
        if (!Schema::hasColumn('cost_item', 'account_name')) {
            Schema::table('cost_item', function (Blueprint $table) {
                $table->string('account_name', '255')->default('')->comment('会计科目名称');
            });
        }
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