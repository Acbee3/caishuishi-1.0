<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCashFlow extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //

        Schema::create("cash_flow",function (Blueprint $table){
            $table->increments("id");
            $table->integer("company_id")->default(0)->comment("企业ID");
            $table->date("fiscal_period")->nullable()->comment("会计期间");
            $table->decimal("sccpsp")->nullable()->comment("售产成品、商品、提供劳务收到的现金");
            $table->decimal("sdqt")->nullable()->comment("收到其他与经营活动有关的现金");
            $table->decimal("gmycl")->nullable()->comment("购买原材料、商品、接受劳务支付的现金");
            $table->decimal("zfdzgxc")->nullable()->comment("支付的职工薪酬");
            $table->decimal("zfdsf")->nullable()->comment("支付的税费");
            $table->decimal("zfqt")->nullable()->comment("支付其他与经营活动有关的现金");
            $table->decimal("jyhdcsje")->nullable()->comment("经营活动产生的现金流量净额");
            $table->decimal("shdqtz")->nullable()->comment("收回短期投资、长期债券投资和长期股权投资收到的现金");
            $table->decimal("qdtzsy")->nullable()->comment("取得投资收益收到的现金");
            $table->decimal("czgdzc")->nullable()->comment("处置固定资产、无形资产和其他非流动资产收回的现金净额");
            $table->decimal("dqtzzf")->nullable()->comment("短期投资、长期债券投资和长期股权投资支付的现金");
            $table->decimal("gmgdzc")->nullable()->comment("购建固定资产、无形资产和其他非流动资产支付的现金");
            $table->decimal("tzhdcsje")->nullable()->comment("投资活动产生的现金流量净额");
            $table->decimal("qdjk")->nullable()->comment("取得借款收到的现金");
            $table->decimal("xstzz")->nullable()->comment("吸收投资者投资收到的现金");
            $table->decimal("chjkbj")->nullable()->comment("偿还借款本金支付的现金");
            $table->decimal("chjklx")->nullable()->comment("偿还借款利息支付的现金");
            $table->decimal("fplrzf")->nullable()->comment("分配利润支付的现金");
            $table->decimal("czhdcsje")->nullable()->comment("筹资活动产生的现金流量净额");
            $table->decimal("xjjzje")->nullable()->comment("现金净增加额");
            $table->decimal("qcxjye")->nullable()->comment("期初现金余额");
            $table->decimal("qmxjye")->nullable()->comment("期末现金余额");
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
