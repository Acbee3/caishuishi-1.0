<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKplxZsfsToInvoice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('invoice', 'kplx')) {
            Schema::table('invoice', function (Blueprint $table) {
                $table->string('kplx', '255')->default('')->comment('开票类型');
            });
        }
        if (!Schema::hasColumn('invoice', 'zsfs')) {
            Schema::table('invoice', function (Blueprint $table) {
                $table->string('zsfs', '255')->default('')->comment('征收方式');
            });
        }
        if (!Schema::hasColumn('invoice', 'cezs')) {
            Schema::table('invoice', function (Blueprint $table) {
                $table->decimal('cezs', '11', '2')->default('0')->comment('差额征税');
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
