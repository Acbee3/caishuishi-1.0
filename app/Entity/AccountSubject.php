<?php

namespace App\Entity;

use App\Entity\BusinessDataConfig\JXFP;
use App\Models\AccountSubject as AccountSubjectModel;


/**
 * 会计科目类
 * Class Voucher
 * @package App\Entity
 */
class AccountSubject
{

    /**
     * @return array
     */
    public static function subsetList()
    {
        $company = Company::sessionCompany();
        $data = [];
        $d = \App\Models\AccountSubject::where('company_id', $company->id)
            ->where('status', \App\Models\AccountSubject::USED)->where("pid", 0)->get()->toArray();
        $subject = new JXFP();
        foreach ($d as $k => $v) {
            $v['full_name'] = $v['name'];
            $subject->loopFY($v, $data);
        }

        foreach ($data as $k => $v) {
            unset($data[$k]['full_name']);
            unset($data[$k]['type']);
        }

        return $data;
    }

    /***
     * 根据会计科目编号获取最下级科目
     * @param $kuaijibianhao
     * @return array
     */
    public static function getLastAccountSub($kuaijibianhao)
    {
        $company = Company::sessionCompany();
        $data = [];
        $d = \App\Models\AccountSubject::where('company_id', $company->id)
            ->where('status', \App\Models\AccountSubject::USED)->where("number", $kuaijibianhao)->get()->toArray();

        $subject = new JXFP();
        foreach ($d as $k => $v) {
            $v['full_name'] = $v['name'];
            $subject->loopFY($v, $data);
            unset($d[$k]['full_name']);
        }

        foreach ($data as $k => $v) {
            unset($data[$k]['full_name']);
            unset($data[$k]['type']);
        }
        return $data;
    }

    /**
     * 根据编码获取科目信息
     * @param $number
     * @param null $company
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     */
    public static function getKMbyNumber($number, $company = null)
    {
        $company == null && $company = Company::sessionCompany();
        return $km = AccountSubjectModel::query()->where('company_id', $company->id)
            ->where('number', $number)->firstOrFail();
    }

    /**
     * 根据编码获取科目信息
     * @param $number
     * @param $company_id
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     */
    public static function getKMbyNumberAndCompanyId($number, $company_id = '')
    {
        $company_id == '' && $company_id = Company::sessionCompany()->id;
        return $km = AccountSubjectModel::query()->where('company_id', $company_id)
            ->where('number', $number)->firstOrFail();
    }

    /**
     * 根据会计编码获取完整的会计名称 例如"应交税费_应交增值税_进项税额"
     * @param $number
     * @return bool|string
     */
    public static function getAllkMName($number){
        $company = Company::sessionCompany();
        $level_arr = explode(',',$company->level_set);
        $arr = [];
        $leng = 0;

        foreach ($level_arr as $level){
            $leng += $level;
            if($leng <= strlen($number)){
                $arr[] = substr($number,0,$leng);
            }
        }

        $model = AccountSubjectModel::whereIn("number",$arr)->where("company_id",$company->id)->orderBy("id")->get();
        $str = '';
        foreach ($model as $v){
            if(!empty($v)){
                $str .= $v->name."_";
            }
        }

        $str = substr($str,0,-1);
        return $str;
    }


}