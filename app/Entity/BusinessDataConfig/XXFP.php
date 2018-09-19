<?php

namespace App\Entity\BusinessDataConfig;


use App\Entity\Company;
use App\Models\AccountSubject;

class XXFP
{
    const TYPE_1 = 1; //一般科目
    const TYPE_2 = 2; //成本费用
    const TYPE_3 = 3; //资产

    public $data = [
        ["type" => XXFP::TYPE_1, "number" => "5001", "name" => "主营业务收入","full_name" => "主营业务收入", 'child' => []],
        ["type" => XXFP::TYPE_1, "number" => "5051", "name" => "其他业务收入","full_name" => "其他业务收入", 'child' => []],
        ["type" => XXFP::TYPE_1, "number" => "5301", "name" => "营业外收入","full_name" => "营业外收入", 'child' => []],
        ["type" => XXFP::TYPE_1, "number" => "1606", "name" => "固定资产清理","full_name" => "固定资产清理", 'child' => []],

    ];



    public function getBusinessData(){
        foreach ($this->data as $key => $arr){
            $km = [];
            if($arr['type'] == XXFP::TYPE_1){
                $this->loopKM($arr,$km);
                unset($this->data[$key]);
            }
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
