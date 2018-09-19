<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfit extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create("profit",function (Blueprint $table){
            $table->increments("id");
            $table->integer("company_id")->default(0)->comment("企业ID");
            $table->date("fiscal_period")->nullable()->comment("会计期间");
            $table->decimal("yysr")->nullable()->comment("营业收入");
            $table->decimal("yycb")->nullable()->comment("营业成本");
            $table->decimal("yysjjfj")->nullable()->comment("营业税金及附加");
            $table->decimal("xfs")->nullable()->comment("消费税");
            $table->decimal("yys")->nullable()->comment("营业税");
            $table->decimal("csjswhs")->nullable()->comment("城市建设维护税");
            $table->decimal("zys")->nullable()->comment("资源税");
            $table->decimal("tdzzs")->nullable()->comment("土地增值税");
            $table->decimal("cztdsys")->nullable()->comment("城镇土地使用税、房产税、
车船税、印花税");
            $table->decimal("jyfj")->nullable()->comment("教育附加、矿产资源、排污费");

            $table->decimal("xsfy")->nullable()->comment("销售费用");
            $table->decimal("cpwxf")->nullable()->comment("商品维修费");
            $table->decimal("ggfhywxcf")->nullable()->comment("广告费和业务宣传费");
            $table->decimal("glfy")->nullable()->comment("管理费用");
            $table->decimal("kbf")->nullable()->comment("开办费");
            $table->decimal("ywzdf")->nullable()->comment("业务招待费");
            $table->decimal("yjfy")->nullable()->comment("研究费用");
            $table->decimal("cwfy")->nullable()->comment("财务费用");
            $table->decimal("lxfy")->nullable()->comment("利息费用");
            $table->decimal("tzsy")->nullable()->comment("投资收益");
            $table->decimal("yylr")->nullable()->comment("营业利润");
            $table->decimal("yywsr")->nullable()->comment("营业外收入");
            $table->decimal("zfbz")->nullable()->comment("政府补助");
            $table->decimal("yywzc")->nullable()->comment("营业外支出");
            $table->decimal("hzss")->nullable()->comment("坏账损失");
            $table->decimal("zqtzss")->nullable()->comment("无法收回的长期债券投资损失");
            $table->decimal("gqtzss")->nullable()->comment("无法收回的长期股权投资损失");
            $table->decimal("zrzhss")->nullable()->comment("自然灾害等不可抗力因素造成的损失");
            $table->decimal("ssznj")->nullable()->comment("税收滞纳金");
            $table->decimal("lrze")->nullable()->comment("利润总额");
            $table->decimal("sdsfy")->nullable()->comment("所得税费用");
            $table->decimal("clr")->nullable()->comment("净利润");
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
        //
    }
}
