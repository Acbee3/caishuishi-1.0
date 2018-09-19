<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AddExampleToTaxConfig extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('tax_config', 'example')) {
            Schema::table('tax_config', function (\Illuminate\Database\Schema\Blueprint $table) {
                $table->string('example', 255)->default('')->comment('示例');
            });


            $sql = "
            UPDATE `tax_config` SET `example` = '【示例】（仅做参考） 借：营业税金及附加_营业税  贷：应交税费_应交营业税 ' WHERE `tax_config`.`id` = 1;
            UPDATE `tax_config` SET `example` = '【示例】（仅做参考） 借：营业税金及附加_营业税  贷：应交税费_应交营业税 ' WHERE `tax_config`.`id` = 2;
            UPDATE `tax_config` SET `example` = '【示例】（仅做参考） 借：营业税金及附加_营业税  贷：应交税费_应交营业税 ' WHERE `tax_config`.`id` = 3;
            UPDATE `tax_config` SET `example` = '【示例】（仅做参考） 借：所得税费用_当年  贷：应交税费_应交企业所得税 ' WHERE `tax_config`.`id` = 4;
            UPDATE `tax_config` SET `example` = '【示例】（仅做参考） 借：管理费用_印花税  贷：应交税费_应交印花税 ' WHERE `tax_config`.`id` = 5;
            UPDATE `tax_config` SET `example` = '【示例】（仅做参考） 借：应交税费_应交增值税（小规模纳税人）  贷：营业外收入_政府补助_征税收入 ' WHERE `tax_config`.`id` = 6
            ";

            $sql = explode(';', $sql);

            foreach ($sql as $item) {
                $item != '' && \Illuminate\Support\Facades\DB::statement($item);
            }
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
