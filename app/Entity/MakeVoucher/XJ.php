<?php
namespace App\Entity\MakeVoucher;


use App\Entity\AccountSubject;
use App\Entity\Period;
use App\Entity\Voucher;


class XJ
{
    protected $period;
    protected $periodCN;
    protected $JieFang;
    protected $DaiFang;
    protected $total_money = 0;
    protected $attach = 1;
    protected $number = "1001";

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

        $subject = AccountSubject::getLastAccountSub($this->number);
        $subject = !empty($subject) ? $subject[0]:'';
        if($obj->fund_type == \App\Entity\BusinessDataConfig\XJ::JDFX_1){

            $this->JieFang[] = [
                'zhaiyao' => $obj->ywlx,
                'account_id' => $subject['id'],
                'account_number' => $subject['number'],
                'account_name' => $subject['name'],
                'debit_money' => $obj->money,
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

            $this->JieFang[] = [
                'zhaiyao' => $obj->ywlx,
                'account_id' => $obj->dw_id,
                'account_number' => $obj->dw_num,
                'account_name' => $obj->dw_name,
                'debit_money' => '',
                'credit_money' => $obj->money,
                'balance' => false,
                'newAdd' => false,
                'hiddenInput' => false,
                'hiddenText' => false,
                'lendInput' => false,
                'lendShow' => true,
                'loanInput' => false,
                'loanShow' => true
            ];

        }else{

            $this->JieFang[] = [
                'zhaiyao' => $obj->ywlx,
                'account_number' => $obj->dw_id,
                'account_name' => $obj->dw_name,
                'debit_money' => $obj->money,
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

            $this->JieFang[] = [
                'zhaiyao' => $obj->ywlx,
                'account_number' => $subject['number'],
                'account_name' => $subject['name'],
                'debit_money' => '',
                'credit_money' => $obj->money,
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

        //self::makeJieFang($obj);
        //self::makeDaiFang($obj);


        $data = [
            'maxVoucherNum' => $maxVoucherNum,
            'attach' => $attach,
            'voucherDate' => $voucherDate,
            'period' => $this->periodCN,
            'data' => $this->JieFang,
            //'credit' => $this->DaiFang,
        ];
        return $data;
    }


    /**
     * 进项发票借方数据组装
     * @param $obj
     * @return array
     */
    public function makeJieFang($obj){

    }

    /**
     * 进项发票贷方数据组装
     * @param $obj
     * @return array
     */
    public function makeDaiFang($obj){

    }
}