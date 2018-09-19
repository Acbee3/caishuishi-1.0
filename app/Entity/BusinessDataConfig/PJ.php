<?php
namespace App\Entity\BusinessDataConfig;


use App\Entity\Company;
use App\Models\AccountSubject;
use App\Models\BankAccount;
use App\Models\BussinessData;
use App\Models\BussinessDatasAccountSubject;

class PJ
{
    const TYPE_1= 1; //往来单位

    const JDFX_1 = 1; //借（借贷方向）
    const JDFX_2 = 2; //贷

    public $data = [
        ["type" => YH::TYPE_1, 'JDFX'=>PJ::JDFX_1, "number" => "1121", "name" => "收到银行承兑汇票","full_name" => "应收票据", 'child' => []],
        ["type" => YH::TYPE_1, 'JDFX'=>PJ::JDFX_2, "number" => "2201", "name" => "支付银行承兑汇票","full_name" => "应付票据", 'child' => []],
        ["type" => YH::TYPE_1, 'JDFX'=>PJ::JDFX_1, "number" => "1121", "name" => "背书转让","full_name" => "应收票据", 'child' => []],
    ];

    /**
     * @param null $number
     * @return array
     */
    public function getBusinessData($number=null){

        foreach ($this->data as $key=>$value){
            switch ($value["type"]){
                case 1:
                    $this->wldw($key);
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
}