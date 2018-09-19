<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateLotsofBaseTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
            CREATE TABLE IF NOT EXISTS `asset` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `company_id` int(11) NOT NULL COMMENT '代账公司id',
              `zcmc` varchar(255) NOT NULL COMMENT '资产名称',
              `zclb` varchar(255) NOT NULL COMMENT '资产类别(房屋、建筑物……)',
              `num` int(11) NOT NULL COMMENT '数量',
              `rzrq` date NOT NULL COMMENT '入账日期',
              `zjff` int(11) NOT NULL COMMENT '折旧方法(平均年限法……)',
              `zjqx` int(11) NOT NULL COMMENT '折旧期限（月）',
              `yzkm` varchar(255) NOT NULL COMMENT '原值科目',
              `ljzjkm` varchar(255) NOT NULL COMMENT '累计折旧科目',
              `yzkm_id` int(11) NOT NULL COMMENT '原值科目id',
              `ljzjkm_id` int(11) NOT NULL COMMENT '累计折旧科目id',
              `cbfykm_id` int(11) NOT NULL COMMENT '成本费用科目id',
              `cbfykm` varchar(255) NOT NULL COMMENT '成本费用科目',
              `yz` decimal(11,2) NOT NULL COMMENT '原值',
              `cz` decimal(11,2) NOT NULL COMMENT '残值',
              `remark` varchar(255) NOT NULL COMMENT '备注',
              `created_at` timestamp NULL DEFAULT NULL,
              `updated_at` timestamp NULL DEFAULT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='资产';
        ");

        DB::statement("
            CREATE TABLE IF NOT EXISTS `asset_alter` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `company_id` int(11) NOT NULL COMMENT '代账公司id',
              `zclx` varchar(255) NOT NULL COMMENT '资产类型(固定资产……)',
              `zcmc` varchar(255) NOT NULL COMMENT '资产名称',
              `zclb` varchar(255) NOT NULL COMMENT '资产类别(房屋、建筑物……)',
              `bdlx` tinyint(4) NOT NULL COMMENT '变动类型(购入、卖出……)',
              `dbx` varchar(255) NOT NULL COMMENT '变动项(原值……)',
              `bdje` decimal(11,2) NOT NULL COMMENT '变动金额',
              `voucher_id` int(11) NOT NULL COMMENT '凭证id',
              `created_at` timestamp NULL DEFAULT NULL,
              `updated_at` timestamp NULL DEFAULT NULL,  
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='资产变动';
        ");

        DB::statement("
            CREATE TABLE IF NOT EXISTS `company_account` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `company_id` int(11) NOT NULL COMMENT '代账公司id',
              `type` tinyint(4) NOT NULL COMMENT '账户类型（银行、现金、票据）',
              `money` decimal(11,2) NOT NULL COMMENT '余额',
              `bank_id` int(11) NOT NULL COMMENT '银行id',
              `bank_name` varchar(255) NOT NULL COMMENT '银行名称',
              `bank_bz_id` int(11) NOT NULL COMMENT '银行币种id',
              `bank_bz_name` int(11) NOT NULL COMMENT '银行币种',
              `created_at` timestamp NULL DEFAULT NULL,
              `updated_at` timestamp NULL DEFAULT NULL,  
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='代账公司余额';
        ");

        DB::statement("
            CREATE TABLE IF NOT EXISTS `cost` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `company_id` int(11) NOT NULL COMMENT '代账公司id',
              `voucher_id` int(11) NOT NULL COMMENT '凭证id',
              `total_money` decimal(11,2) NOT NULL COMMENT '总金额',
              `created_at` timestamp NULL DEFAULT NULL,
              `updated_at` timestamp NULL DEFAULT NULL,  
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='费用表';        
        ");

        DB::statement("
            CREATE TABLE IF NOT EXISTS `cost_item` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `company_id` int(11) NOT NULL COMMENT '公司id',
              `cost_id` int(11) NOT NULL COMMENT '费用id',
              `fyrq` date NOT NULL COMMENT '费用日期',
              `fylx` varchar(255) NOT NULL COMMENT '费用类型',
              `money` decimal(10,2) NOT NULL COMMENT '费用金额',
              `dw_id` int(11) NOT NULL COMMENT '单位id',
              `dw_name` varchar(255) NOT NULL COMMENT '单位名称',
              `remark` varchar(255) NOT NULL COMMENT '备注',
              `created_at` timestamp NULL DEFAULT NULL,
              `updated_at` timestamp NULL DEFAULT NULL,  
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='费用项目明细';        
        ");

        DB::statement("
            CREATE TABLE IF NOT EXISTS `department` (
              `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '11',
              `company_id` int(11) NOT NULL COMMENT '代账公司id',
              `dept_name` varchar(255) NOT NULL COMMENT '部门名称',
              `status` tinyint(4) NOT NULL COMMENT '状态（暂停、启用）',
              `created_at` timestamp NULL DEFAULT NULL,
              `updated_at` timestamp NULL DEFAULT NULL,  
              PRIMARY KEY (`id`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='部门表';
        ");

        DB::statement("
            CREATE TABLE IF NOT EXISTS `employee` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `company_id` int(11) NOT NULL COMMENT '代账公司id',
              `employee_num` varchar(255) NOT NULL COMMENT '工号',
              `employee_name` varchar(255) NOT NULL COMMENT '姓名',
              `department_id` int(11) DEFAULT NULL COMMENT '部门id',
              `lxdh` varchar(255) NOT NULL COMMENT '联系电话',
              `gender` varchar(255) NOT NULL COMMENT '性别',
              `zjlx` varchar(255) NOT NULL COMMENT '证件类型',
              `zjhm` varchar(255) NOT NULL COMMENT '证件号码',
              `email` varchar(255) NOT NULL COMMENT '邮箱',
              `address` varchar(255) NOT NULL COMMENT '地址',
              `birthday` date NOT NULL COMMENT '出生日期',
              `remark` varchar(255) NOT NULL COMMENT '备注',
              `created_at` timestamp NULL DEFAULT NULL,
              `updated_at` timestamp NULL DEFAULT NULL,  
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='员工表';        
        ");

        DB::statement("
            CREATE TABLE IF NOT EXISTS `fund` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `company_id` int(11) NOT NULL COMMENT '代账公司id',
              `fund_date` date NOT NULL COMMENT '资金日期',
              `fund_type` tinyint(4) NOT NULL COMMENT '资金变动（入账、出账）',
              `channel_type` tinyint(4) NOT NULL COMMENT '变动形式（银行、现金、票据）',
              `source_type` tinyint(4) NOT NULL COMMENT '变动来源（手动、自动）',
              `money` decimal(11,2) NOT NULL COMMENT '金额',
              `ywlx_id` int(11) NOT NULL COMMENT '业务类型id',
              `ywlx` varchar(255) NOT NULL COMMENT '业务类型',
              `voucher_id` int(11) NOT NULL COMMENT '关联凭证号',
              `dw_id` int(11) NOT NULL COMMENT '单位id',
              `dw_name` int(11) NOT NULL COMMENT '单位名称',
              `invoice_id` int(11) NOT NULL COMMENT '发票id',
              `bank_id` int(11) NOT NULL COMMENT '银行id',
              `bank_name` int(11) NOT NULL COMMENT '银行名称',
              `created_at` timestamp NULL DEFAULT NULL,
              `updated_at` timestamp NULL DEFAULT NULL,  
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;        
        ");

        DB::statement("
            CREATE TABLE IF NOT EXISTS `invoice` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `company_id` int(11) NOT NULL COMMENT '代账公司id',
              `fpdm` varchar(255) NOT NULL COMMENT '发票代码',
              `fphm` varchar(255) NOT NULL COMMENT '发票号码',
              `kprq` date NOT NULL COMMENT '开票日期',
              `type` tinyint(4) NOT NULL COMMENT '发票大类(进项、销项)',
              `sub_type` tinyint(4) NOT NULL COMMENT '发票细分类型(增值税专用发票、增值税普通发票…)',
              `gfdw_name` varchar(255) NOT NULL COMMENT '购方单位-名词',
              `gfdw_nsrsbh` varchar(255) NOT NULL COMMENT '购方单位-纳税人识别号',
              `gfdw_yhzh` varchar(255) NOT NULL COMMENT '购方单位-银行账号',
              `gfdw_dzdh` varchar(500) NOT NULL COMMENT '购方单位-地址电话',
              `gfdw_id` int(11) NOT NULL COMMENT '购方单位-id',
              `xfdw_id` int(11) NOT NULL COMMENT '销方单位',
              `xfdw_name` varchar(255) NOT NULL COMMENT '销方单位名词',
              `xfdw_nsrsbh` varchar(255) NOT NULL COMMENT '销方单位-纳税人识别号',
              `xfdw_yhzh` varchar(500) NOT NULL COMMENT '销方单位-银行账号',
              `xfdw_dzdh` varchar(255) NOT NULL COMMENT '销方单位-地址电话',
              `dkzt` tinyint(4) NOT NULL COMMENT '抵扣状态',
              `dkfs` tinyint(4) NOT NULL COMMENT '抵扣方式',
              `fpzs` tinyint(4) NOT NULL COMMENT '发票张数',
              `voucher_id` int(11) NOT NULL COMMENT '凭证id',
              `jszt` tinyint(4) NOT NULL COMMENT '结算状态',
              `wbhs` tinyint(4) NOT NULL COMMENT '外币核算',
              `wbhs_wbbz` varchar(255) NOT NULL COMMENT '外币核算_外币币种',
              `wbhs_sjhl` decimal(15,5) NOT NULL COMMENT '外币核算_实际汇率',
              `created_at` timestamp NULL DEFAULT NULL,
              `updated_at` timestamp NULL DEFAULT NULL,  
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='发票表';        
        ");

        DB::statement("
            CREATE TABLE IF NOT EXISTS `invoice_item` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `company_id` int(11) NOT NULL COMMENT '代账公司id',
              `invoice_id` int(11) NOT NULL COMMENT '发票id',
              `ywlx_id` int(11) NOT NULL COMMENT '业务类型id',
              `ywlx_name` varchar(255) NOT NULL COMMENT '业务类型-名词',
              `kpxm_id` int(11) NOT NULL COMMENT '开票项目id',
              `kpxm_name` varchar(255) NOT NULL COMMENT '开票项目-名词',
              `ggxh` varchar(255) NOT NULL COMMENT '规格型号',
              `dw` varchar(50) NOT NULL COMMENT '单位',
              `num` decimal(11,2) NOT NULL COMMENT '数量',
              `money` decimal(11,2) NOT NULL COMMENT '金额',
              `tax_rate` decimal(11,2) NOT NULL COMMENT '税率',
              `tax_money` decimal(11,2) NOT NULL COMMENT '税额',
              `fee_tax_sum` decimal(11,2) NOT NULL COMMENT '价税合计',
              `created_at` timestamp NULL DEFAULT NULL,
              `updated_at` timestamp NULL DEFAULT NULL,  
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='发票明细项目';        
        ");

        DB::statement("
            CREATE TABLE IF NOT EXISTS `salary` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `company_id` int(11) NOT NULL COMMENT '代账公司id',
              `xclx` int(11) NOT NULL COMMENT '薪酬类型',
              `begin_date` date NOT NULL COMMENT '薪酬所属期起',
              `end_date` date NOT NULL COMMENT '薪酬所属期止',
              `pay_type` tinyint(4) NOT NULL COMMENT '支付方式',
              `voucher_id` int(11) NOT NULL COMMENT '凭证id',
              `created_at` timestamp NULL DEFAULT NULL,
              `updated_at` timestamp NULL DEFAULT NULL,  
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='薪酬';
        ");

        DB::statement("
            CREATE TABLE IF NOT EXISTS `salary_employee` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `company_id` int(11) NOT NULL COMMENT '代账公司id',
              `salary_id` int(11) NOT NULL COMMENT '薪酬id',
              `employee_id` int(11) NOT NULL COMMENT '员工id',
              `employee_name` varchar(255) NOT NULL COMMENT '员工姓名',
              `personal_tax` decimal(11,2) NOT NULL COMMENT '代扣个税',
              `salary_type` tinyint(4) NOT NULL COMMENT '薪酬类型（正常工资、临时工工资……）',
              `fylx` varchar(255) NOT NULL COMMENT '费用类型',
              `year_bonus` decimal(11,2) NOT NULL COMMENT '全年一次性奖金',
              `jcfy` decimal(11,2) NOT NULL COMMENT '全年一次性奖金-减除费用（补差）',
              `sfjj` decimal(11,2) NOT NULL COMMENT '全年一次性奖金-实发奖金',
              `salary` decimal(11,2) NOT NULL COMMENT '工资',
              `txf` decimal(11,2) NOT NULL COMMENT '工资-通讯费',
              `yanglaobx` decimal(11,2) NOT NULL COMMENT '工资-养老保险',
              `yiliaobx` decimal(11,2) NOT NULL COMMENT '工资-医疗保险',
              `sybx` decimal(11,2) NOT NULL COMMENT '工资-失业保险',
              `dbyl` decimal(11,2) NOT NULL COMMENT '工资-大病医疗',
              `dkgjj` decimal(11,2) NOT NULL COMMENT '工资-代扣公积金',
              `other_fee` decimal(11,2) NOT NULL COMMENT '工资-其他费用',
              `real_salary` decimal(11,2) NOT NULL COMMENT '工资-实发工资',
              `lwbc` decimal(11,2) NOT NULL COMMENT '劳务报酬',
              `sflwbc` decimal(11,2) NOT NULL COMMENT '实发劳务报酬',
              `remark` varchar(255) NOT NULL COMMENT '备注',
              `created_at` timestamp NULL DEFAULT NULL,
              `updated_at` timestamp NULL DEFAULT NULL,  
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;        
        ");

        DB::statement("
            CREATE TABLE IF NOT EXISTS `voucher` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `company_id` int(11) NOT NULL COMMENT '代账公司id',
              `voucher_num` varchar(255) NOT NULL COMMENT '记账号',
              `attach` int(11) NOT NULL COMMENT '附件张数',
              `voucher_date` date NOT NULL COMMENT '记账日期',
              `total_debit_money` decimal(11,2) NOT NULL COMMENT '借方总金额',
              `total_credit_money` decimal(11,2) NOT NULL COMMENT '贷方总金额',
              `total_cn` varchar(255) DEFAULT NULL COMMENT '合计金额（中文大写）',
              `creator_id` int(11) NOT NULL COMMENT '制作人id',
              `creator_name` varchar(255) NOT NULL COMMENT '制作人名称',
              `auditor_id` int(11) NOT NULL COMMENT '审核人id',
              `auditor_name` varchar(255) NOT NULL COMMENT '审核人名称',
              `audit_status` tinyint(4) NOT NULL COMMENT '审核状态',
              `created_at` timestamp NULL DEFAULT NULL,
              `updated_at` timestamp NULL DEFAULT NULL,  
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='凭证';        
        ");

        DB::statement("
            CREATE TABLE IF NOT EXISTS `voucher_item` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `company_id` int(11) NOT NULL COMMENT '代账公司id',
              `zhaiyao` varchar(255) NOT NULL COMMENT '摘要',
              `kuaijikemu_id` int(11) NOT NULL COMMENT '会计科目id',
              `kuaijikemu` varchar(255) NOT NULL COMMENT '会计科目',
              `debit_money` decimal(11,2) NOT NULL COMMENT '借方金额',
              `credit_money` decimal(11,2) NOT NULL COMMENT '贷方金额',
              `voucher_id` int(11) DEFAULT NULL COMMENT '凭证id',
              `created_at` timestamp NULL DEFAULT NULL,
              `updated_at` timestamp NULL DEFAULT NULL,  
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='凭证项目明细';
        ");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('voucher');
        Schema::dropIfExists('voucher_item');
        Schema::dropIfExists('salary_employee');
        Schema::dropIfExists('salary');
        Schema::dropIfExists('department');
        Schema::dropIfExists('employee');
        Schema::dropIfExists('invoice');
        Schema::dropIfExists('invoice_item');
        Schema::dropIfExists('cost');
        Schema::dropIfExists('cost_item');
        Schema::dropIfExists('asset');
        Schema::dropIfExists('asset_alter');
        Schema::dropIfExists('company_account');
        Schema::dropIfExists('fund');
    }
}
