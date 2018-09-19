<?php

namespace App\Entity;
use App\Models\Accounting\VoucherItem;
use Illuminate\Http\Request;


/**
 * 现金流量表
 * Class CashFlow
 * @package App\Entity
 */
class CashFlow
{
    const type_1 = 1; //加
    const type_2 = 2; //减

    protected $company_id;
    protected $period;

    protected $profit;
    protected $balance;
    protected $subjectBalance;

    public function __construct()
    {
        $this->company_id = Company::sessionCompany()->id;
        $this->period = Period::currentPeriod();

        $this->profit = \App\Models\AccountBook\Profit::where("fiscal_period",$this->period)->where('company_id',$this->company_id)->first();
        $this->balance = \App\Models\Accounting\BalanceSheet::where("fiscal_period",$this->period)->where('company_id',$this->company_id)->first();
        $this->subjectBalance = \App\Models\AccountBook\SubjectBalance::where("fiscal_period",$this->period)->where('company_id',$this->company_id)->all();

    }


    public function makeCashFlow(){
        $cashFlow = \App\Models\AccountBook\CashFlow::where("company_id",$this->company_id)
            ->where("fiscal_period",$this->period)->first();

    }

    /**
     * 销售产成品、商品、提供劳务收到的现金
     * @return int
     */
    public function sccpsp(){
        //利润表中主营业务收入×（1＋16%）＋利润表中其他业务收入＋（应收票据期初余额－应收票据期末余额）
        //＋（应收账款期初余额－应收账款期末余额）＋（预收账款期末余额－预收账款期初余额）
        //－计提的应收账款坏账准备期末余额

        /**
         * TODO
         * 计提的应收账款坏账准备期末余额没做
         */

        $zyywsr = round(($this->profit["yysr"]*1.16),2);
        $qtywsr = $this->profit["yywsr"];
        $yspj = 0;
        $yszk = 0;
        $yuszk = 0;
        $hzzb = 0;

        //
        foreach ($this->subjectBalance as $v){
            if($v['account_subject_number'] =='1121'){
                $f = self::JieDaiFuHao($v);
                $yspj = ($f['dai'].$v['bqfse_j']) + ($f['dai'].$v['bqfse_d']);
            }

            if($v['account_subject_number'] =='1122'){
                $f = self::JieDaiFuHao($v);
                $yszk = ($f['dai'].$v['bqfse_j']) + ($f['dai'].$v['bqfse_d']);
            }

            if($v['account_subject_number'] =='2203'){
                $f = self::JieDaiFuHao($v);
                $yuszk = ($f['dai'].$v['bqfse_j']) + ($f['dai'].$v['bqfse_d']);
            }

        }

        return $zyywsr + $qtywsr + $yspj + $yszk + $yuszk - $hzzb;
    }


    /**
     * 收到其他与经营活动有关的现金
     * @return int
     */
    public function sdqt(){
        //营业外收入相关明细本期贷方发生额＋其他业务收入相关明细本期贷方发生额＋其他应收款相关明细本期贷方发生额
        //＋其他应付款相关明细本期贷方发生额＋银行存款利息收入
        $yywsr_df = 0;
        $qtywsr_df = 0;
        $qtysk_df = 0;
        $qtyfk_df = 0;
        $yhcklxsr = 0;

        foreach ($this->subjectBalance as $v){
            if($v['account_subject_number'] =='5301'){
                $yywsr_df = $v['bqfse_d'];
            }

            if($v['account_subject_number'] =='5051'){
                $qtywsr_df = $v['bqfse_d'];
            }

            if($v['account_subject_number'] =='1221'){
                $qtysk_df = $v['bqfse_d'];
            }

            if($v['account_subject_number'] =='2241'){
                $qtyfk_df = $v['bqfse_d'];
            }

            if($v['account_subject_number'] =='560302'){
                $yhcklxsr = $v['bqfse_d'];
            }

        }

        return $yywsr_df + $qtywsr_df + $qtysk_df + $qtyfk_df + $yhcklxsr;
    }


    /**
     * 购买原材料、商品、接受劳务支付的现金
     * @return int
     */
    public function gmycl(){
        //〔利润表中主营业务成本＋（存货期末余额－存货期初余额）〕×（1＋16%）＋其他业务支出（剔除税金）
        //＋（应付票据期初余额－应付票据期末余额）＋（应付账款期初余额－应付账款期末余额）
        //＋（预付账款期末余额－预付账款期初余额）

        //存货(库存商品、材料物资、低值易耗品、包装物、委托加工物资、在产品、产成品、半成品、委托代销商品)
        $zyywcb = 0;
        $qtywzc = 0;

        $yspj = 0;
        $yszk = 0;
        $yuszk = 0;

        $kcsp = 0;
        $clwz = 0;
        $dzyhp = 0;
        $bzw = 0;
        $wtjgwz = 0;
        $zcp = 0;
        $ccp = 0;
        $bcp = 0;
        $wtdxsp = 0;




        foreach ($this->subjectBalance as $v){
            if($v['account_subject_number'] =='1121'){
                $f = self::JieDaiFuHao($v);
                $yspj = ($f['dai'].$v['bqfse_j']) + ($f['dai'].$v['bqfse_d']);
            }

            if($v['account_subject_number'] =='1122'){
                $f = self::JieDaiFuHao($v);
                $yszk = ($f['dai'].$v['bqfse_j']) + ($f['dai'].$v['bqfse_d']);
            }

            if($v['account_subject_number'] =='2203'){
                $f = self::JieDaiFuHao($v);
                $yuszk = ($f['dai'].$v['bqfse_j']) + ($f['dai'].$v['bqfse_d']);
            }

            if($v['account_subject_number'] =='1405'){
                $f = self::JieDaiFuHao($v);
                $kcsp = ($f['dai'].$v['bqfse_j']) + ($f['dai'].$v['bqfse_d']);
            }

            if($v['account_subject_number'] ==''){
                $f = self::JieDaiFuHao($v);
                $clwz = ($f['dai'].$v['bqfse_j']) + ($f['dai'].$v['bqfse_d']);
            }

            if($v['account_subject_number'] =='1413'){
                $f = self::JieDaiFuHao($v);
                $dzyhp = ($f['dai'].$v['bqfse_j']) + ($f['dai'].$v['bqfse_d']);
            }

            if($v['account_subject_number'] ==''){
                $f = self::JieDaiFuHao($v);
                $bzw = ($f['dai'].$v['bqfse_j']) + ($f['dai'].$v['bqfse_d']);
            }

            if($v['account_subject_number'] ==''){
                $f = self::JieDaiFuHao($v);
                $wtjgwz = ($f['dai'].$v['bqfse_j']) + ($f['dai'].$v['bqfse_d']);
            }

            if($v['account_subject_number'] ==''){
                $f = self::JieDaiFuHao($v);
                $zcp = ($f['dai'].$v['bqfse_j']) + ($f['dai'].$v['bqfse_d']);
            }

            if($v['account_subject_number'] ==''){
                $f = self::JieDaiFuHao($v);
                $ccp = ($f['dai'].$v['bqfse_j']) + ($f['dai'].$v['bqfse_d']);
            }

            if($v['account_subject_number'] ==''){
                $f = self::JieDaiFuHao($v);
                $bcp = ($f['dai'].$v['bqfse_j']) + ($f['dai'].$v['bqfse_d']);
            }

            if($v['account_subject_number'] ==''){
                $f = self::JieDaiFuHao($v);
                $wtdxsp = ($f['dai'].$v['bqfse_j']) + ($f['dai'].$v['bqfse_d']);
            }

            if($v['account_subject_number'] =='5711'){
                $f = self::JieDaiFuHao($v);
                $qtywzc = ($f['dai'].$v['bqfse_j']) + ($f['dai'].$v['bqfse_d']);
            }

        }

        $ch = $kcsp + $clwz + $dzyhp + $bzw + $wtjgwz + $zcp + $ccp + $bcp + $wtdxsp;
        return (($zyywcb + $ch) * 1.16) + $qtywzc + $yspj + $yszk + $yuszk;

    }

    /**
     * 支付的职工薪酬
     * @param $obj
     * @return int
     */
    public function zfdzgxc(){
        //=应付工资”科目本期借方发生额累计数＋“应付福利费”科目本期借方发生额累计数
        //＋管理费用中“养老保险金”、“待业保险金”、“住房公积金”、“医疗保险金”
        //＋成本及制造费用明细表中的“劳动保护费”
        $yfgz = 0;
        $yfflf = 0;
        $ylbxj = 0;
        $dybxj = 0;
        $zfgjj = 0;
        $yilbxj = 0;
        $ldbhf = 0;

        foreach ($this->subjectBalance as $v){

            if($v['account_subject_number'] ==''){
                $yfgz = $v['bqfse_j'];
            }

            if($v['account_subject_number'] ==''){
                $yfflf = $v['bqfse_j'];
            }

            if($v['account_subject_number'] ==''){
                $ylbxj = $v['bqfse_j'];
            }

            if($v['account_subject_number'] ==''){
                $dybxj = $v['bqfse_j'];
            }

            if($v['account_subject_number'] =='560204'){
                $zfgjj = $v['bqfse_j'];
            }

            if($v['account_subject_number'] ==''){
                $yilbxj = $v['bqfse_j'];
            }

            if($v['account_subject_number'] ==''){
                $ldbhf = $v['bqfse_j'];
            }

        }

        return $yfgz + $yfflf + $ylbxj + $dybxj + $zfgjj + $yilbxj + $ldbhf;

    }

    /**
     * 支付的税费
     * @param $obj
     * @return int
     */
    public function zfdsf($obj){
        //＝“应交税金”各明细账户本期借方发生额累计数＋“其他应交款”各明细账户借方数
        //＋“管理费用”中“税金”本期借方发生额累计数＋“其他业务支出”中有关税金项目
        //即：实际缴纳的各种税金和附加税，不包括进项税。

        $yjsj = 0;
        $qtyjk = 0;
        $sj = 0;
        $qtywzc = 0;
        $zfgjj = 0;

        $xxse = 0;
        $jxse = 0;



        foreach ($this->subjectBalance as $v){

            if($v['account_subject_number'] =='2221'){
                $yfgz = $v['bqfse_j'];
            }

            if($v['account_subject_number'] =='22210101'){
                $xxse = $v['bqfse_j'];
            }

            if($v['account_subject_number'] =='22210102'){
                $jxse = $v['bqfse_j'];
            }

            if($v['account_subject_number'] ==''){
                $dybxj = $v['bqfse_j'];
            }

            if($v['account_subject_number'] =='560204'){
                $zfgjj = $v['bqfse_j'];
            }

            if($v['account_subject_number'] ==''){
                $yilbxj = $v['bqfse_j'];
            }

            if($v['account_subject_number'] ==''){
                $ldbhf = $v['bqfse_j'];
            }
        }
        return $yjsj - $xxse - $jxse;

    }

    /**
     * 支付其他与经营活动有关的现金
     * @param $obj
     * @return int
     */
    public function zfqt($obj){

        //＝营业外支出（剔除固定资产处置损失）
        //＋管理费用(剔除工资、福利费、劳动保险金、待业保险金、住房公积金、养老保险、医疗保险、折旧、坏账准备或坏账损失、列入的各项税金等)
        //＋营业费用、成本及制造费用(剔除工资、福利费、劳动保险金、待业保险金、住房公积金、养老保险、医疗保险等)
        //＋其他应收款本期借方发生额＋其他应付

        foreach ($this->subjectBalance as $v){

            if($v['account_subject_number'] =='5711'){
                $yfgz = $v['bqfse_j'];
            }

            if($v['account_subject_number'] =='22210101'){
                $xxse = $v['bqfse_j'];
            }

            if($v['account_subject_number'] =='22210102'){
                $jxse = $v['bqfse_j'];
            }

        }

        return 0;
    }


    /**
     * 经营活动产生的现金流量净额
     * @param $obj
     * @return int
     */
    public function jyhdcsje(){
        return 0;
    }

    /**
     * 收回短期投资、长期债券投资和长期股权投资收到的现金
     * 资表[（短期投资期末-短期投资期初）+（长期债权投资合计期末数-长期债权投资合计期初数）+（长期股权投资期末数-长期股权投资期初数）+（长期应收款期末-长期应收款期初）]*-1 +利表[公允价值变动收益] 注：如值小于零，则计算结果*-1计入‘短期投资、长期债券投资和长期股权投资支付的现金’
     * @param $obj
     * @return int
     */
    public function shdqtz(){
        return 0;
    }

    /**
     * 取得投资收益收到的现金
     * @param $obj
     * @return int
     */
    public function qdtzsy(){
        return 0;
    }

    /**
     * 处置固定资产、无形资产和其他非流动资产收回的现金净额
     * @param $obj
     * @return int
     */
    public function czgdzc(){
        return 0;
    }

    /**
     * 短期投资、长期债券投资和长期股权投资支付的现金
     * @param $obj
     * @return int
     */
    public function dqtzzf(){
        return 0;
    }

    /**
     * 购建固定资产、无形资产和其他非流动资产支付的现金
     * @param $obj
     * @return int
     */
    public function gmgdzc(){
        return 0;
    }

    /**
     * 投资活动产生的现金流量净额
     * @param $obj
     * @return int
     */
    public function tzhdcsje(){
        return 0;
    }


    /**
     * 取得借款收到的现金
     * @param $obj
     * @return int
     */
    public function qdjk(){
        return 0;
    }


    /**
     * 吸收投资者投资收到的现金
     * @param $obj
     * @return int
     */
    public function xstzz(){
        return 0;
    }


    /**
     * 偿还借款本金支付的现金
     * @param $obj
     * @return int
     */
    public function chjkbj(){
        return 0;
    }

    /**
     * 偿还借款利息支付的现金
     * @param $obj
     * @return int
     */
    public function chjklx(){
        return 0;
    }


    /**
     * 分配利润支付的现金
     * @param $obj
     * @return int
     */
    public function fplrzf(){
        return 0;
    }


    /**
     * 筹资活动产生的现金流量净额
     * @param $obj
     * @return int
     */
    public function czhdcsje(){

        return 0;
    }

    /**
     * 现金净增加额
     * @param $obj
     * @return int
     */
    public function xjjzje(){
        return 0;
    }

    /**
     * 期初现金余额
     * @param $obj
     * @return int
     */
    public function qcxjye(){
        return 0 ;
    }

    /**
     * 期末现金余额
     * @param $obj
     * @return int
     */
    public function qmxjye(){
        return 0;
    }


    /**
     * 截取科目编码
     * @param VoucherItem $objs
     * @param int $len
     * @return bool|string
     */
    public function checkKMBM(VoucherItem $objs,$len=4){
        return substr($objs->kuaijikemu,0,$len);
    }

    /**
     * 会计科目
     * @param \App\Models\Accounting\Voucher $obj
     * @param $kmbm
     * @param $len
     * @param bool $jfhdf
     * @return int
     */
    public function returnJe(\App\Models\Accounting\Voucher $obj,$kmbm,$len,$jfhdf=true){
        $je = 0;
        foreach ($obj->voucherItem as $v){
            $kjkm = self::checkKMBM($v,$len);
            if($kmbm == $kjkm){
                $je +=  ($jfhdf == true) ? $v->debit_money : $v->credit_money;
            }
        }
        return $je;
    }


    /**
     * 借贷方向符号
     * @param $obj
     * @return array
     */
    public function JieDaiFuHao($obj){
        $jie = '';
        $dai = '-';
        if($obj['balance_direction'] == '贷'){
            $jie = '-';
            $dai = '';
        }
        return ['jie'=>$jie,'dai'=>$dai];
    }

}