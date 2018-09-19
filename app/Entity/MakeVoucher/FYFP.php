<?php
namespace App\Entity\MakeVoucher;


use App\Entity\Period;
use App\Entity\Voucher;
use App\Models\BussinessData;

class FYFP
{

    protected $period;
    protected $periodCN;
    protected $JieFang;
    protected $DaiFang;
    protected $total_money = 0;
    protected $attach = 1;

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
        $attach = $this->attach;

        foreach ($obj->costItem as $item){
            self::makeJieFang($item);
            self::makeDaiFang($item);
        }

        $data = [
            'maxVoucherNum' => $maxVoucherNum,
            'attach' => $attach,
            'voucherDate' => $voucherDate,
            'period' => $this->periodCN,
            'data' => $this->JieFang,
            //'credit' => $this->DaiFang,
        ];

        //dd($data);
        return $data;
    }


    /**
     * 进项发票借方数据组装
     * @param $obj
     * @return array
     */
    public function makeJieFang($obj){
            $this->JieFang[] = [
                'zhaiyao' => $obj->fylx,
                'account_id' => $obj->account_id,
                'account_number' => $obj->account_number,
                'account_name' => $obj->account_name,
                'debit_money' => $obj->money + $obj->cash,
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

        return $this->JieFang;
    }

    /**
     * 进项发票贷方数据组装
     * @param $obj
     * @return array
     */
    public function makeDaiFang($obj){
        $bussiness = BussinessData::where('id',$obj->dw_id)->with("account_subjects")->first();
        return $this->JieFang[] = [
            'zhaiyao' => $obj->fylx,
            'account_id' => empty($bussiness->account_subjects[0]) ? '' : $bussiness->account_subjects[0]->id,
            'account_number' => empty($bussiness->account_subjects[0]) ? '' : $bussiness->account_subjects[0]->number,
            'account_name' => empty($bussiness->account_subjects[0]) ? '' : $bussiness->account_subjects[0]->name."_".$bussiness->name,
            'debit_money' => '',
            'credit_money' => $obj->money + $obj->cash,
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