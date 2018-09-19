<?php

namespace App\Entity;


use App\Models\Accounting\VoucherItem;
use App\Models\Pinyin;
use App\Models\AccountBook\SubjectBalance as SubjectBalanceModel;
use App\Models\AccountSubject;
/**
 * 利润表
 * Class Profit
 * @package App\Entity
 */
class Profit
{
    public $company_id;
    public $fiscal_period;

    public $data_tpl = [
        '营业收入' => ['5001','5051'],
        '减：营业成本' => ['5401','5402'],
        '营业税金及附加' => ['5403'],
        '其中：消费税' => [],
        '营业税' => [],
        '城市维护建设税' => ['222102'],
        '资源税' => [],
        '土地增值税' => [],
        '城镇土地使用税、房产税、车船税、印花税' => ['222107'],
        '教育费附加、矿产资源补偿税、排污费' => ['222103','222104'],
        '销售费用' => ['5601'],
        '其中：商品维修费' => [],
        '广告费和业务宣传费' => [],
        '管理费用' => ['5602'],
        '其中：开办费' => [],
        '业务招待费' => [],
        '研究费用' => [],
        '财务费用' => ['5603'],
        '其中：利息费用（收入以“-”号填列）' => ['560302'],
        '加：投资收益（损失以“-”号填列）' => ['5111'],
        '二、营业利润（亏损以“-”号填列）' => [],
        '加：营业外收入' => ['5301'],
        '其中：政府补助' => ['530101'],
        '营业外支出' => ['5711'],
        '其中：坏账损失' => [],
        '无法收回的长期债券投资损失' => [],
        '无法收回的长期股权投资损失' => [],
        '自然灾害等不可抗力因素造成的损失' => [],
        '税收滞纳金' => [],
        '三、利润总额（亏损总额以“-”号填列）' => [],
        '减：所得税费用' => ['5801'],
        '四：净利润（净亏损以“-”号填列）' => [],
    ];

    public function __construct(Array $attr)
    {

        if (empty($attr['company_id']) || empty($attr['fiscal_period']))
            throw new \Exception('缺少初始化参数');

        !empty($attr['company_id']) && $this->company_id = $attr['company_id'];
        !empty($attr['fiscal_period']) && $this->fiscal_period = $attr['fiscal_period'];
    }

    /**
     * 当前状态
     * @throws \Exception
     */
    public function active()
    {
        $ret = [];
        $row = 1;
        $profitModel = new \App\Models\AccountBook\Profit();

        //计算各个字段的数据
        //行数规整
        foreach ($this->data_tpl as $k => $v) {
            $field = Pinyin::utf8_to($k);

            $byje = self::Byje($v, $this->company_id, $this->fiscal_period);
            $begin_of_year = self::bnje($v,$this->company_id, $this->fiscal_period);

            $attr = [
                'belong' => $k,
                'name' => $k,
                'row_num' => $row,
                'begin_of_year' => $begin_of_year,
                'end_of_period' => $byje,
            ];

            $ret[] = new ProfitItem($attr);
            $row++;
        }

        return $ret;
    }



    public static function makeTable($param)
    {
        $yysr = $yycb = $yysjjfj = $xsfy = $glfy = $cwfy = $tzsy = $yywsr = $yywzc = $sdsfy = [];
        $bnlj = $bqlj = $bnlrze = $bqlrze = $bnjlr = $bqjlr = 0;

        $profit = (new Profit($param))->active();
        $table = [['项目', '行次', '本年累计金额', '本月金额']];


        for ($i = 0; $i < count($profit); $i++) {
            $table[] = $profit[$i]->toArray();

            if ($profit[$i]->name == '营业收入') {
                $yysr = [$profit[$i]->begin_of_year , $profit[$i]->end_of_period];
            }

            if ($profit[$i]->name == '减：营业成本') {
                $yycb = [$profit[$i]->begin_of_year , $profit[$i]->end_of_period];
            }

            if ($profit[$i]->name == '营业税金及附加') {
                $yysjjfj = [$profit[$i]->begin_of_year , $profit[$i]->end_of_period];
            }

            if ($profit[$i]->name == '销售费用') {
                $xsfy = [$profit[$i]->begin_of_year , $profit[$i]->end_of_period];
            }

            if ($profit[$i]->name == '管理费用') {
                $glfy = [$profit[$i]->begin_of_year , $profit[$i]->end_of_period];
            }

            if ($profit[$i]->name == '财务费用') {
                $cwfy = [$profit[$i]->begin_of_year , $profit[$i]->end_of_period];
            }

            if ($profit[$i]->name == '加：投资收益（损失以“-”号填列）') {
                $tzsy = [$profit[$i]->begin_of_year , $profit[$i]->end_of_period];
            }

            //营业利润 = 营业收入－营业成本－营业税金及附加－销售费用－管理费用－财务费用
            //－资产减值损失+公允价值变动收益（－公允价值变动损失）+投资收益（－投资损失）
            if ($profit[$i]->name == '二、营业利润（亏损以“-”号填列）') {
                $bnlj =  (round(($yysr[0] - $yycb[0] - $yysjjfj[0] - $xsfy[0] - $glfy[0] - $cwfy[0] - $tzsy[0]),2)) ;
                $bqlj =  (round(($yysr[1] - $yycb[1] - $yysjjfj[1] - $xsfy[1] - $glfy[1] - $cwfy[1] - $tzsy[1]),2));
                $table[$i+1] = ['二、营业利润（亏损以“-”号填列）', $profit[$i]->row_num, $bnlj, $bqlj];
            }

            if($profit[$i]->name == '加：营业外收入'){
                $yywsr = [$profit[$i]->begin_of_year , $profit[$i]->end_of_period];
            }

            if($profit[$i]->name == '营业外支出'){
                $yywzc = [$profit[$i]->begin_of_year , $profit[$i]->end_of_period];
            }

            if($profit[$i]->name == '三、利润总额（亏损总额以“-”号填列）'){
                $bnlrze = round(($bnlj + $yywsr[0] - $yywzc[0]),2);
                $bqlrze = round(($bqlj + $yywsr[1] - $yywzc[1]),2);
                $table[$i+1] = ['三、利润总额（亏损总额以“-”号填列）', $profit[$i]->row_num, $bnlrze, $bqlrze];
            }

            if($profit[$i]->name == '减：所得税费用'){
                $sdsfy = [$profit[$i]->begin_of_year , $profit[$i]->end_of_period];
            }

            if($profit[$i]->name == '四：净利润（净亏损以“-”号填列）'){

                $bnjlr = round(($bnlrze - $sdsfy[0]),2);
                $bqjlr = round(($bqlrze - $sdsfy[1]),2);
                $table[$i+1] = ['四：净利润（净亏损以“-”号填列）', $profit[$i]->row_num, $bnjlr, $bqjlr];
            }

            $table[$i+1] =[$table[$i+1][0], $table[$i+1][1], number_format($table[$i+1][2],2,'.',','), number_format($table[$i+1][3],2,'.',',')];

        }

        $ret = [
            'list' => $table,
            //'html' => $tableHtml,
            'companyName' => Company::sessionCompany()->company_name,
            'period' => Period::currentPeriod(),
        ];
        return $ret;
    }


    /**
     * 本月金额
     * @param $numbers
     * @param $company_id
     * @param string $fiscal_period
     * @return float|int|mixed
     */
    public static function Byje($numbers, $company_id, $fiscal_period=''){

        $ret = 0.00;
        foreach ($numbers  as $number){
            $number = abs($number);
            $account_subject = AccountSubject::query()->where('company_id', $company_id)
                ->where('number', $number)
                ->first();

            $fiscal_period == '' && $fiscal_period = Period::currentPeriod();
            $ballance = SubjectBalanceModel::query()->where('company_id', $company_id)
                ->where('fiscal_period', $fiscal_period)
                ->where('account_subject_number', $number)
                ->first();

            if (empty($account_subject) || empty($ballance))
                return 0;

            if ($account_subject['balance_direction'] == '借') {
                if($ballance['bqfse_j'] == $ballance['bqfse_d']){
                    $ret += $ballance['bqfse_j'];
                }else{
                    $ret += $ballance['bqfse_j'] - $ballance['bqfse_d'];
                }
            } else {
                if($ballance['bqfse_j'] == $ballance['bqfse_d']){
                    $ret += $ballance['bqfse_d'];
                }else{
                    $ret += $ballance['bqfse_d'] - $ballance['bqfse_j'];
                }
            }
            //$column = $account_subject['balance_direction'] == '借' ? 'qmye_j' : 'qmye_d';
            return $ret;
        }

    }

    /**
     * 本年金额
     * @param $numbers
     * @param $company_id
     * @param string $fiscal_period
     * @return float|int|mixed
     */
    public static function bnje($numbers, $company_id, $fiscal_period=''){

        $ret = 0.00;
        foreach ($numbers  as $number){
            $number = abs($number);
            $account_subject = AccountSubject::query()->where('company_id', $company_id)
                ->where('number', $number)
                ->first();

            $fiscal_period == '' && $fiscal_period = Period::currentPeriod();

            $ballances = SubjectBalanceModel::where('account_subject_number', $number)
                ->where('company_id', Company::sessionCompany()->id)
                ->where('fiscal_period', 'like', substr($fiscal_period, 0, 4) . '%')->get();


            foreach ($ballances as $ballance){
                if (empty($account_subject) || empty($ballance))
                    return 0;

                if ($account_subject['balance_direction'] == '借') {
                    if($ballance['bqfse_j'] == $ballance['bqfse_d']){
                        $ret += $ballance['bqfse_j'];
                    }else{
                        $ret += $ballance['bqfse_j'] - $ballance['bqfse_d'];
                    }

                } else {
                    if($ballance['bqfse_j'] == $ballance['bqfse_d']){
                        $ret += $ballance['bqfse_d'];
                    }else{
                        $ret += $ballance['bqfse_d'] - $ballance['bqfse_j'];
                    }
                }
                //$column = $account_subject['balance_direction'] == '借' ? 'qmye_j' : 'qmye_d';
            }
            return $ret;
        }

    }






}