<?php
namespace App\Entity\MakeVoucher;


use App\Entity\Period;
use App\Entity\Voucher;
use App\Models\BankAccount;

class YH
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

        $bank = BankAccount::find($obj->bank_id);
        $kemu = \App\Models\AccountSubject::find($bank->subject_id);


        foreach ($obj->FundItems as $item){
            $km = \App\Models\AccountSubject::where('number',$item['dw_num'])->first()->toArray();
            if(!$km){
                throw new \Exception("科目不存在！");
            }

            if($item->fund_type == \App\Entity\BusinessDataConfig\XJ::JDFX_1){
                $this->JieFang[] = [
                    'zhaiyao' => $item->ywlx,
                    'account_id' => $kemu['id'],
                    'account_number' => $kemu['number'],
                    'account_name' => $kemu['name'],
                    'debit_money' => $item->money,
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
                    'zhaiyao' => $item->ywlx,
                    'account_id' => $km['id'],
                    'account_number' => $item['dw_num'],
                    'account_name' => $km['name'].'_'.$item['dw_name'],
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

            }else{
                $this->JieFang[] = [
                    'zhaiyao' => $item->ywlx,
                    'account_id' => $km['id'],
                    'account_number' => $item['dw_num'],
                    'account_name' => $km['name'].'_'.$item['dw_name'],
                    'debit_money' => $item->money,
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
                    'zhaiyao' => $item->ywlx,
                    'account_id' => $kemu['id'],
                    'account_number' => $kemu['number'],
                    'account_name' => $kemu['name'],
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
            }

        }


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