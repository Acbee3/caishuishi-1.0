<?php
namespace App\Entity\BusinessDataConfig;


use App\Entity\Company;
use App\Models\AccountSubject;
use App\Models\BankAccount;
use App\Models\BussinessData;
use App\Models\BussinessDatasAccountSubject;

class YH
{

    const TYPE_1 = 1; //客户和往来单位
    const TYPE_2 = 2; //缴纳税费、基金及其他
    const TYPE_3 = 3; //缴纳社保
    const TYPE_4 = 4; //公积金
    const TYPE_5 = 5; //转账支出
    const TYPE_6 = 6; //支付费用
    const TYPE_7 = 7; //承兑汇票承兑进账
    const TYPE_8 = 8; //收到政府补助
    const TYPE_9 = 9; //其他无


    const JDFX_1 = 1; //借（借贷方向）
    const JDFX_2 = 2; //贷

    public $data = [
        ["type" => YH::TYPE_1, 'JDFX'=>YH::JDFX_1, "number" => "", "name" => "收销售款","full_name" => "", 'child' => []],
        ["type" => YH::TYPE_1, 'JDFX'=>YH::JDFX_1, "number" => "", "name" => "收其他往来款","full_name" => "", 'child' => []],
        ["type" => YH::TYPE_1, 'JDFX'=>YH::JDFX_1, "number" => "", "name" => "收退回的采购款","full_name" => "", 'child' => []],
        ["type" => YH::TYPE_1, 'JDFX'=>YH::JDFX_1, "number" => "", "name" => "收投资款","full_name" => "", 'child' => []],
        ["type" => YH::TYPE_1, 'JDFX'=>YH::JDFX_2, "number" => "", "name" => "付采购款","full_name" => "", 'child' => []],
        ["type" => YH::TYPE_1, 'JDFX'=>YH::JDFX_2, "number" => "", "name" => "付其他往来款","full_name" => "", 'child' => []],
        ["type" => YH::TYPE_1, 'JDFX'=>YH::JDFX_2, "number" => "", "name" => "退回销售款","full_name" => "", 'child' => []],

        ["type" => YH::TYPE_2, 'JDFX'=>YH::JDFX_2, "number" => "", "name" => "缴纳税费、基金及其他","full_name" => "", 'child' => []],
        ["type" => YH::TYPE_3, 'JDFX'=>YH::JDFX_2, "number" => "", "name" => "缴纳社保","full_name" => "", 'child' => []],
        ["type" => YH::TYPE_4, 'JDFX'=>YH::JDFX_2, "number" => "", "name" => "缴纳公积金","full_name" => "", 'child' => []],
        ["type" => YH::TYPE_5, 'JDFX'=>YH::JDFX_2, "number" => "", "name" => "转账支出","full_name" => "", 'child' => []],
        ["type" => YH::TYPE_6, 'JDFX'=>YH::JDFX_2, "number" => "", "name" => "支付费用","full_name" => "", 'child' => []],
        ["type" => YH::TYPE_7, 'JDFX'=>YH::JDFX_1, "number" => "", "name" => "承兑汇票承兑进账","full_name" => "", 'child' => []],
        ["type" => YH::TYPE_8, 'JDFX'=>YH::JDFX_1, "number" => "", "name" => "收到政府补助","full_name" => "", 'child' => []],

        ["type" => YH::TYPE_9, 'JDFX'=>YH::JDFX_2, "number" => "221101", "name" => "支付工资","full_name" => "应付职工薪酬_职工工资", 'child' => []],
        ["type" => YH::TYPE_9, 'JDFX'=>YH::JDFX_2, "number" => "221102", "name" => "支付补贴、奖金","full_name" => "应付职工薪酬_奖金、津贴和补贴", 'child' => []],
        ["type" => YH::TYPE_9, 'JDFX'=>YH::JDFX_1, "number" => "1001", "name" => "存现","full_name" => "库存现金", 'child' => []],
        ["type" => YH::TYPE_9, 'JDFX'=>YH::JDFX_2, "number" => "1001", "name" => "提现","full_name" => "库存现金", 'child' => []],
        ["type" => YH::TYPE_9, 'JDFX'=>YH::JDFX_2, "number" => "560301", "name" => "银行手续费","full_name" => "财务费用_银行手续费", 'child' => []],
        ["type" => YH::TYPE_9, 'JDFX'=>YH::JDFX_1, "number" => "560302", "name" => "利息收入","full_name" => "财务费用_利息收入", 'child' => []],
        ["type" => YH::TYPE_9, 'JDFX'=>YH::JDFX_2, "number" => "560303", "name" => "利息支出","full_name" => "财务费用_利息支出", 'child' => []],
        ["type" => YH::TYPE_9, 'JDFX'=>YH::JDFX_1, "number" => "2001", "name" => "向银行贷款","full_name" => "短期借款", 'child' => []],
        ["type" => YH::TYPE_9, 'JDFX'=>YH::JDFX_2, "number" => "2001", "name" => "还银行贷款","full_name" => "短期借款", 'child' => []],
        ["type" => YH::TYPE_9, 'JDFX'=>YH::JDFX_1, "number" => "", "name" => "付款失败退回","full_name" => "", 'child' => []],
        ["type" => YH::TYPE_9, 'JDFX'=>YH::JDFX_1, "number" => "1606", "name" => "固定资产清理","full_name" => "固定资产清理", 'child' => []],

    ];

    public $jnsf = [
        ["number" => "2221", "name" => "应交税费",'full_name'=>'应交税费'],
    ];

    public $jnsb = [
        ["number" => "560203", "name" => "企业承担",'full_name'=>'管理费用_社会保险费'],
        ["number" => "224101", "name" => "个人承担",'full_name'=>'其他应付款_代扣社保'],
    ];

    public $jngjj = [
        ["number" => "560204", "name" => "企业承担",'full_name'=>'管理费用_住房公积金'],
        ["number" => "224102", "name" => "个人承担",'full_name'=>'其他应付款_代扣公积金'],
    ];

    public $cdhp = [
        ["number" => "1121", "name" => "票面金额",'full_name'=>'应收票据'],
        ["number" => "560303", "name" => "贴现利息",'full_name'=>'财务费用_利息支出'],
    ];

    public $zfbt = [
        ["number" => "53010101", "name" => "征税",'full_name'=>'营业外收入_政府补助_征税收入'],
        ["number" => "53010102", "name" => "不征税",'full_name'=>'营业外收入_政府补助_不征税收入'],
    ];

    //成本费用类数据
    public $fyData = [
        ["number" => "4001", "name" => "生产成本","full_name" => "生产成本"],
        ["number" => "4002", "name" => "劳务成本","full_name" => "劳务成本"],
        ["number" => "4101", "name" => "制造费用","full_name" => "制造费用"],
        ["number" => "4301", "name" => "研发支出","full_name" => "研发支出"],
        ["number" => "4401", "name" => "工程施工","full_name" => "工程施工"],
        ["number" => "4403", "name" => "机械作业","full_name" => "机械作业"],
        ["number" => "5401", "name" => "主营业务成本","full_name" => "主营业务成本"],
        ["number" => "5402", "name" => "其他业务成本","full_name" => "其他业务成本"],
        ["number" => "5601", "name" => "销售费用","full_name" => "销售费用"],
        ["number" => "5602", "name" => "管理费用","full_name" => "管理费用"],
        ["number" => "5603", "name" => "财务费用","full_name" => "财务费用"],
        ["number" => "5711", "name" => "营业外支出","full_name" => "营业外支出"],
    ];



    public function getBusinessData($number=null){

        foreach ($this->data as $key=>$value){
            switch ($value["type"]){
                case 1:
                    $this->wldw($key);
                    break;
                case 2:
                    $this->jnsf($key);
                    break;
                case 3:
                    $this->jnsb($key);
                    break;
                case 4:
                    $this->jngjj($key);
                    break;
                case 5:
                    $this->zzzc($key);
                    break;
                case 6:
                    $this->zffy($key);
                    break;
                case 7:
                    $this->cdhp($key);
                    break;
                case 8:
                    $this->zfbt($key);
                    break;
                case 9:
                    $this->other($key,$number);
                    break;
            }
        }

        return $this->data;
    }

    /**
     * 往来单位数据组装
     * @param $key
     */
    public function wldw($key){

        $return = [];
        $company = \App\Entity\Company::sessionCompany();
        $data = BussinessData::where("company_id",$company->id)->whereIn("type",[
            BussinessData::USED,
            BussinessData::SUPPLIER,
            BussinessData::OTHERCONTACT,
            BussinessData::INVESTOR])->with('account_subjects')->get();

        $type = BussinessData::getType();

        foreach ($data as $v){
            foreach ($v->account_subjects as $d){
                $return[] = [
                    'id' => $v->id,
                    'number' => $d->number,
                    'name' => $v->name.'_'.$d->name,
                    'full_name' => $d->name.'_'.$v->name,
                    'type' => key_exists($v->type,$type)?$type[$v->type]:'',
                ];
            }
        }

        $this->data[$key]['child'] = $return;
    }


    /**
     * 缴纳税费数据组装
     * @param $key
     */
    public function jnsf($key){
        $return = [];
        foreach ($this->jnsf as $v){
            (new JXFP())->loopFY($v,$return,true);
        }
        $this->data[$key]['child'] = $return;
    }

    /**
     * 社保业务数据组装
     * @param $key
     */
    public function jnsb($key){
        $this->data[$key]['child'] = $this->jnsb;
    }

    /**
     * 公积金业务数据组装
     * @param $key
     */
    public function jngjj($key){
        $this->data[$key]['child'] = $this->jngjj;
    }

    /**
     * 转账支出
     * @param $key
     */
    public function zzzc($key){
        $return = [];
        $company = \App\Entity\Company::sessionCompany();
        $km = AccountSubject::where("company_id",$company->id)->where("number","1002")->first();
        $bank = AccountSubject::where("company_id",$company->id)->where("pid",$km->id)->get();
        foreach ($bank as $v){
            $return[] = [
                'number' => $v->number,
                'name' => $v->name,
                'name' => $km->name.'_'.$v->name,
            ];
        }


        $this->data[$key]['child'] = $return;
    }

    /**
     * 支付费用
     * @param $key
     */
    public function zffy($key){
        $return = [];
        foreach ($this->fyData as $v){
            (new JXFP())->loopFY($v,$return,true);
        }
        $this->data[$key]['child'] = $return;
    }

    /**
     * 承兑汇票
     * @param $key
     */
    public function cdhp($key){
        $return = [];
        foreach ($this->cdhp as $k=>$v){
            $return[] = [
                'number' => $v['number'],
                'name' => $v['name'],
            ];
        }
        $this->data[$key]['child'] = $return;
    }

    /**
     * 政府补贴
     * @param $key
     */
    public function zfbt($key){
        $return = [];
        foreach ($this->zfbt as $k=>$v){
            $return[] = [
                'number' => $v['number'],
                'name' => $v['name'],
            ];
        }
        $this->data[$key]['child'] = $return;
    }

    /**
     *  其他的
     * @param $key
     */
    public function other($key,$number){
        $company = \App\Entity\Company::sessionCompany();
        if(empty($this->data[$key]['number']) && $number){
            $km = AccountSubject::where("company_id",$company->id)->where("number",$number)->first();
            $this->data[$key]['number'] = $km->number;
            $this->data[$key]['full_name'] = $km->name;
        }

    }
}