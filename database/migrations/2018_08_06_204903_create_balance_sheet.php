<?php

use App\Models\Pinyin;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateBalanceSheet extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $data = [
            '流动资产' => [
                '货币资金',
                '短期投资',
                '应收票据',
                '应收账款',
                '预付账款',
                '应收股利',
                '应收利息',
                '其他应收款',
                '存货',
                '原材料',
                '在产品',
                '库存商品',
                '周转材料',
                '其他流动资产',
                '流动资产合计',
            ],
            '非流动资产' => [
                '长期债券投资',
                '长期股权投资',
                '固定资产原价',
                '减：累计折旧',
                '固定资产账面价值',
                '在建工程',
                '工程物资',
                '固定资产清理',
                '生产性生物资产',
                '无形资产',
                '开发支出',
                '长期待摊费用',
                '其他非流动资产',
                '非流动资产合计',
            ],
            '资产合计',
            '流动负债' => [
                '短期借款',
                '应付票据',
                '应付账款',
                '预收帐款',
                '应付职工薪酬',
                '应交税费',
                '应付利息',
                '应付利润',
                '其他应付款',
                '其他流动负债',
                '流动负债合计',
            ],
            '非流动负债' => [
                '长期借款',
                '长期应付款',
                '递延收益',
                '其他非流动负债',
                '非流动负债合计',
            ],
            '负债合计',
            '所有者权益' => [
                '实收资本 （或股本）',
                '资本公积',
                '盈余公积',
                '未分配利润',
                '所有者权益合计',
            ],
            '负债和所有者权益合计',
        ];
        if (!Schema::hasTable('balance_sheet')) {
            Schema::create('balance_sheet', function (\Illuminate\Database\Schema\Blueprint $table) use ($data) {

                $table->increments('id');
                $table->integer('company_id')->default(0)->comment('公司id');
                $table->date('fiscal_period')->nullable()->comment('会计期间');

                foreach ($data as $datum) {
                    if (is_array($datum)) {
                        foreach ($datum as $item) {
                            $table->decimal(Pinyin::utf8_to($item), 11, 2)->default(0)->comment($item);
                        }
                    } else {
                        $table->decimal(Pinyin::utf8_to($datum), 11, 2)->default(0)->comment($datum);
                    }
                }
                $table->timestamps();
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
