<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeCompanyIdToDepartmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('department', function (Blueprint $table) {
            $table->integer('company_id')->comment("客户公司ID")->change();
            $table->integer('status')->default('1')->comment("状态 0:停用 1:启用")->change();
            $table->dropColumn('dept_name');
            $table->string('department_name')->nullable()->comment("客户公司部门名称");
        });

        Schema::table('employee', function (Blueprint $table) {
            $table->integer('company_id')->comment("客户公司ID")->change();
            $table->string('country')->nullable()->comment("国籍");
            $table->integer('sf_cjlsgl')->nullable()->comment("是否残疾烈属孤老 0:否 1:是");
            $table->integer('sf_employee')->nullable()->comment("是否雇员 0:否 1:是");
            $table->string('company_name')->nullable()->comment("工作单位");
            $table->string('postcode', 6)->nullable()->comment("邮政编码");
            $table->integer('status')->nullable()->comment("人员状态 0:非正常 1:正常");
            $table->integer('sf_tdhy')->nullable()->comment("是否特定行业 0:否 1:是");

            $table->integer('sf_shareholder')->nullable()->comment("是否股东 0:否 1:是");
            $table->integer('gsgbze')->nullable()->comment("公司股本总额");
            $table->integer('grtzze')->nullable()->comment("个人投资总额");
            $table->integer('fpbl')->nullable()->comment("分配比例");

            $table->dateTime('lhsj')->nullable()->comment("来华时间");
            $table->integer('rzqx')->nullable()->comment("任职期限");
            $table->dateTime('ljsj')->nullable()->comment("预计离境时间");
            $table->string('ljdd')->nullable()->comment("预计离境地点");
            $table->string('jnzw')->nullable()->comment("境内职务 0:普通 1:中层 2:高层");
            $table->string('jwzw')->nullable()->comment("境外职务 0:普通 1:中层 2:高层");
            $table->string('zfd')->nullable()->comment("支付地 0:境内支付 1:境外支付 2:境内、外同时支付");
            $table->string('jwzfd')->nullable()->comment("境外支付地");
            $table->string('name_cn')->nullable()->comment("中文姓名");

            $table->string('company_cn')->nullable()->comment("境内任职受雇单位名称");
            $table->string('kjywrbm')->nullable()->comment("扣缴义务人编码");
            $table->string('company_address')->nullable()->comment("单位地址");
            $table->string('company_postcode')->nullable()->comment("境内任职邮政编码");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //Schema::dropIfExists('department');
        //Schema::dropIfExists('employee');
    }
}
