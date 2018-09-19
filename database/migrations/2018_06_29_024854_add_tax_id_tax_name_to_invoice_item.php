<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AddTaxIdTaxNameToInvoiceItem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('invoice_item', 'tax_id')) {
            Schema::table('invoice_item', function (\Illuminate\Database\Schema\Blueprint $table) {
                $table->integer('tax_id')->default(0)->comment('税目id');
            });
        }
        if (!Schema::hasColumn('invoice_item', 'tax_name')) {
            Schema::table('invoice_item', function (\Illuminate\Database\Schema\Blueprint $table) {
                $table->string('tax_name', 255)->default('')->comment('税目');
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
