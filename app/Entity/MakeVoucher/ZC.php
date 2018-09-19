<?php
namespace App\Entity\MakeVoucher;

use App\Entity\AccountSubject;
use App\Entity\Period;
use App\Entity\Voucher;
use App\Models\BussinessData;

class ZC
{
    protected $periodCN;
    protected $period;
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


        if($obj->zclx == "固定资产" || $obj->zclx == "无形资产"){
            $id = $obj->cbfykm_id;
            $id_o = $obj->ljzjkm_id;
        }else{
            $id = $obj->cbfykm_id;
            $id_o = $obj->yzkm_id;
        }

        $subject = AccountSubject::getKMbyNumber($id)->toArray();
        $subject_d = AccountSubject::getKMbyNumber($id_o)->toArray();

            $this->JieFang[] = [
                'zhaiyao' => "计提".$this->periodCN."资产折旧",
                'account_id' => $subject['id'],
                'account_number' => $subject['number'],
                'account_name' => $subject['name'],
                'debit_money' => $obj->zjje,
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
                'zhaiyao' => "计提".$this->periodCN."资产折旧",
                'account_id' => $subject_d['id'],
                'account_number' => $subject_d['number'],
                'account_name' => $subject_d['name'],
                'debit_money' => '',
                'credit_money' => $obj->zjje,
                'balance' => false,
                'newAdd' => false,
                'hiddenInput' => false,
                'hiddenText' => false,
                'lendInput' => false,
                'lendShow' => true,
                'loanInput' => false,
                'loanShow' => true
            ];




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