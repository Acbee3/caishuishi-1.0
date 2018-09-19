<?php

namespace App\Models\AccountBook;

use App\Entity\Period;
use App\Models\Accounting\Voucher;
use Illuminate\Database\Eloquent\Model;

class Profit extends Model
{
    //
    protected $table='profit';


    /**
     * 更新利润表
     * @param $company_id
     * @param $period
     * @param $data
     * @return mixed
     */
    public static function saveProfit($company_id,$period,$data){

        $model = Profit::where('company_id',$company_id)->where('fiscal_period',$period)->first();
        if(!$model){
            $model = new Profit();
        }

        $model->company_id = $company_id;
        $model->fiscal_period = $period;
        foreach ($data as $k=>$v){
            $model->$k += round(floatval($v),2);
        }
        $return = $model->save();
        return $return;
    }


    /**
     * 查询利润表
     * @param $company_id
     * @param $period
     * @return mixed
     */
    public static function search($company_id,$period){
        $data_start = date("Y-01",strtotime($period));
        $data_end = date("Y-m-01",strtotime($period));

        $model = Profit::where('company_id',$company_id)
            ->where('fiscal_period',">=",$data_start)
            ->where('fiscal_period',"<=",$data_end)
            ->orderBy('id', 'desc')->get();

        return $model;
    }

}
