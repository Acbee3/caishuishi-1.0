<?php
namespace App\Entity\MakeVoucher;

use App\Entity\AccountSubject;
use App\Entity\Company;
use App\Entity\Period;
use App\Entity\Voucher;

class XXFP
{
    protected $period;
    protected $periodCN;
    protected $JieFang;
    protected $DaiFang;
    protected $total_money = 0;
    protected $BankNumber = "1002";
    protected $SJKMBianMa = "22210102";
    protected $SJKMMingCheng = "应交税费_应交增值税_销项税额";

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

        self::makeDaiFang($obj);
        self::makeJieFang($obj);

        $data = [
            'maxVoucherNum' => $maxVoucherNum,
            'attach' => $attach,
            'voucherDate' => $voucherDate,
            'period' => $this->periodCN,
            'data' => $this->JieFang,
            //'credit' => $credit,
        ];
        return $data;
    }


    /**
     * 进项发票借方数据组装
     * @param $obj
     * @return array
     */
    public function makeJieFang($obj){


        $subList = AccountSubject::getLastAccountSub($this->BankNumber);
        if(!$subList) throw new \Exception("会计科目不存在！");

        $kemu = $subList[0];
        $arr = [
            'zhaiyao' => $this->JieFang[0]['zhaiyao'] ,
            'account_id' => empty($subList)?'':$subList[0]['id'],
            'account_number' => empty($subList)?'':$subList[0]['number'],
            'account_name' => empty($subList)?'':$subList[0]['name'],
            'debit_money' => $obj->total_fee_tax_money,
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

        array_unshift($this->JieFang,$arr);
        return $this->JieFang;
    }

    /**
     * 进项发票贷方数据组装
     * @param $obj
     * @return array
     */
    public function makeDaiFang($obj){

        $total = 0;
        foreach ($obj->invoiceItem as $item){
            $this->JieFang[] = [
                'zhaiyao' => $item->ywlx_name,
                'account_id' => $item->account_id,
                'account_number' => $item->account_number,
                'account_name' => $item->account_name,
                'debit_money' => '',
                'credit_money' => $item->money,
                'balance' => false,
                'newAdd' => false,
                'hiddenInput' => false,
                'hiddenText' => false,
                'lendInput' => false,
                'lendShow' => true,
                'loanInput' => false,
                'loanShow' => true
            ];
            $total += $item->tax_money;
        }

        $SJMB = AccountSubject::getLastAccountSub($this->SJKMBianMa);
        if(!$SJMB) throw new \Exception("会计科目不存在！");
        $this->JieFang[] = [
            'zhaiyao' => $item->ywlx_name,
            'account_id' => $SJMB[0]['id'],
            'account_number' => $this->SJKMBianMa,
            'account_name' => $this->SJKMMingCheng,
            'debit_money' => '',
            'credit_money' => $total,
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

}