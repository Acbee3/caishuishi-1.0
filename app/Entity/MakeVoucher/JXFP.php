<?php
namespace App\Entity\MakeVoucher;


use App\Entity\Period;
use App\Entity\Voucher;
use App\Models\BussinessData;

class JXFP
{

    protected $period;
    protected $periodCN;
    protected $JieFang;
    protected $DaiFang;
    protected $total_money = 0;

    public function __construct()
    {
        $this->periodCN = Period::currentPeriodToCN();
        $this->period = Period::currentPeriod();
    }

    public function makeVoucher($obj){
        return self::makeData($obj);
    }

    /**
     * 数据组装
     * @param $obj
     * @return array
     */
    public function makeData($obj){
        $maxVoucherNum = Voucher::getCurrentMaxVoucherNum($this->period);
        $voucherDate = date("Y-m-d");
        $attach = $obj->attach;
        self::makeJieFang($obj);
        self::makeDaiFang($obj);
        $data = [
            'maxVoucherNum' => $maxVoucherNum,
            'attach' => $attach,
            'voucherDate' => $voucherDate,
            'period' => $this->periodCN,
            'data' => $this->JieFang,
            //'credit' => ,
        ];
        return $data;
    }


    /**
     * 进项发票借方数据组装
     * @param $obj
     * @return array
     */
    public function makeJieFang($obj){

        foreach ($obj->invoiceItem as $v){
            $this->JieFang[] = [
                'zhaiyao' => $v->ywlx_name,
                'account_id' => $v->account_id,
                'account_number' => $v->account_number,
                'account_name' => $v->account_name,
                'debit_money' => $v->money,
                'credit_money' => '',
                'balance' => false,
                'newAdd' => false,
                'hiddenInput' => false,
                'hiddenText' => false,
                'lendInput' => false,
                'lendShow' => true,
                'loanInput' => false,
                'loanShow' => true
            ];
            $this->total_money += $v->money;
        }

        return $this->JieFang;
    }

    /**
     * 进项发票贷方数据组装
     * @param $obj
     * @return array
     */
    public function makeDaiFang($obj){
        $bussiness = BussinessData::where('id',$obj->xfdw_id)->with("account_subjects")->first();
        return $this->JieFang[] = [
            'zhaiyao' => empty($this->JieFang) ? '' : $this->JieFang[0]["zhaiyao"],
            'account_id' => empty($bussiness->account_subjects[0]) ? '' : $bussiness->account_subjects[0]->id,
            'account_number' => empty($bussiness->account_subjects[0]) ? '' : $bussiness->account_subjects[0]->number,
            'account_name' => empty($bussiness->account_subjects[0]) ? '' : $bussiness->account_subjects[0]->name,
            'debit_money' => '',
            'credit_money' => $this->total_money,
            'balance' => false,
            'newAdd' => false,
            'hiddenInput' => false,
            'hiddenText' => false,
            'lendInput' => false,
            'lendShow' => true,
            'loanInput' => false,
            'loanShow' => true
        ];
    }

}