<?php

namespace App\Entity\BusinessDataConfig;


use App\Entity\Company;
use App\Models\AccountSubject;

class FYFP
{
    const TYPE_1 = 1; //一般科目
    const TYPE_2 = 2; //成本费用
    const TYPE_3 = 3; //资产

    public $data = [
        //["number" => "", "name" => "防伪税控设备费（可全额抵扣）","full_name" => "防伪税控设备费（可全额抵扣）"],
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



    public function getBusinessData(){
        foreach ($this->data as $key => $arr){
            $km = [];
            $this->loopKM($arr,$km);
            unset($this->data[$key]);
            unset($this->data[$key]['full_name']);
        }
        return array_values($this->data);
    }


    /**
     * 递归查询 科目分类
     * @param $arr
     * @param $kmAll
     */
    public function loopKM($arr, &$kmAll,$bm=false){

        $company = Company::sessionCompany();
        $km = AccountSubject::where('number', $arr["number"])->where("company_id", $company->id)->first();
        !empty($km) && $km = $km->toArray();

        if($km){
            $kmList = AccountSubject::where('pid',$km['id'])->where("company_id",$company->id)->get();
            !empty($kmList) && $kmList = $kmList->toArray();
            if($kmList){
                foreach ($kmList as $v){
                    $v['full_name'] = $arr['full_name'].'_'.$v['name'];
                    $this->loopKM($v,$kmAll,$bm);
                }
            }else{
                $full_name = $bm ? $arr["number"]."_".$arr['full_name'] : $arr['full_name'];
                array_push($this->data,["type" => XXFP::TYPE_1, "number" => $arr["number"], "name" => $full_name]);
            }
        }
    }

    /**
     * 递归查询 费用科目分类
     * @param $arr
     * @param $kmAll
     */
    public function loopFY($arr, &$kmAll,$bm=false){

        $company = Company::sessionCompany();
        $km = AccountSubject::where('number', $arr["number"])->where("company_id", $company->id)->first();
        !empty($km) && $km = $km->toArray();

        if($km){
            $kmList = AccountSubject::where('pid',$km['id'])->where("company_id",$company->id)->get();
            !empty($kmList) && $kmList = $kmList->toArray();

            if($kmList){
                foreach ($kmList as $v){
                    $v['full_name'] = $arr['full_name'].'_'.$v['name'];
                    $this->loopFY($v,$kmAll,$bm);
                }
            }else{
                $full_name = $bm ? $arr["number"]."_".$arr['full_name'] : $arr['full_name'];
                array_push($kmAll,["type" => XXFP::TYPE_1, "number" => $arr["number"], "name" => $full_name]);
            }
        }
    }


}
