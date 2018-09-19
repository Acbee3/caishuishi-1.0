<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeCzlToAsset extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('asset', function (Blueprint $table) {
            if (!Schema::hasColumn('asset', 'czl')) {
                $table->decimal('czl', 5, 2)->default(0)->comment('残值率');
            } else {
                $table->decimal('czl', 5, 2)->default(0)->comment('残值率')->change();
            }
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
