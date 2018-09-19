<?php
namespace App\Entity\MakeVoucher;



use App\Models\Accounting\Asset;
use App\Models\Accounting\Cost;
use App\Models\Accounting\Fund;
use App\Models\Accounting\Invoice;

class VoucherData
{

    public static function BCFactory($type ,$id){
        $model = '';
        switch ($type){
            case 2:
                $model = Invoice::where("id",$id)->with('invoiceItem')->first();
                break;
            case 3:
                $model = Invoice::where("id",$id)->with('invoiceItem')->first();
                break;
            case 4:
                $model = Cost::where("id",$id)->with('costItem')->first();
                break;
            case 5:
                $model = Fund::where("id",$id)->with('FundItems')->first();
                break;
            case 6:
                $model = Fund::where("id",$id)->with('FundItems')->first();
                break;
            case 7:
                $model = Fund::where("id",$id)->with('FundItems')->first();
                break;
            case 8:
                $model = Asset::where("id",$id)->first();
                break;
            case 9:
                $model = Invoice::where("id",$id)->with('invoiceItem')->first();
                break;
            case 10:
                $model = Invoice::where("id",$id)->with('invoiceItem')->first();
                break;
            case 11:
                $model = Invoice::where("id",$id)->with('invoiceItem')->first();
                break;
            case 12:
                $model = Invoice::where("id",$id)->with('invoiceItem')->first();
                break;
            case 13:
                $model = Invoice::where("id",$id)->with('invoiceItem')->first();
                break;
            case 15:
                $model = Invoice::where("id",$id)->with('invoiceItem')->first();
                break;

        }
        return $model;
    }



    public static function setVoucherID($type ,$id, $voucher_id){
        $model = self::BCFactory($type ,$id);
        if($type>=2 && $type<=3){
            $model->voucher_id = $voucher_id;
            $model->save();
        }

        if($type==4){
            $model->voucher_id = $voucher_id;
            $model->save();
        }

        if($type>=5 && $type<=7){
            $model->voucher_id = $voucher_id;
            $model->save();
        }

        if($type==8){
            $model->voucher_id = $voucher_id;
            $model->save();
        }

        return true;
    }

}