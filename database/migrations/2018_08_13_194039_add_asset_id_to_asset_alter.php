<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AddAssetIdToAssetAlter extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('asset_alter', 'asset_id')) {
            Schema::table('asset_alter', function (\Illuminate\Database\Schema\Blueprint $table) {
                $table->integer('asset_id')->default(0)->comment('资产id');
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
