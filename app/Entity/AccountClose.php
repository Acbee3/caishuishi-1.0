<?php

namespace App\Entity;

use App\Entity\SubjectBalance as SubjectBalanceEntity;
use App\Models\AccountBook\SubjectBalance;
use App\Models\Accounting\AccountClose as AccountCloseModel;
use App\Models\Accounting\AssetAlter;
use App\Models\Accounting\Cost as CostModel;
use App\Models\Accounting\Fund as FundModel;
use App\Models\Accounting\Invoice as InvoiceModel;
use App\Models\Accounting\Salary as SalaryModel;
use App\Models\Accounting\TaxConfig;
use App\Models\Accounting\Voucher as VoucherModel;
use App\Models\Accounting\VoucherItem;
use App\Models\Common;
use Illuminate\Http\Request;

/**
 * 会计结账类
 * Class AccountClose
 * @package App\Entity
 */
class AccountClose
{

    /**
     * 结账检查
     * 逐项检查
     * @param array $param
     * @return array
     */
    public static function check(Array $param)
    {

        $data = [
            [
                'name' => '清单检查',
                'type' => 'invoice',
                'num' => 0,
                'list' => [
                    ['name' => '工资表', 'status' => '0', 'msg' => '已生成'],
                    ['name' => '成本结转', 'status' => '0', 'msg' => '已结转成本'],
                    ['name' => '账卡核对', 'status' => '0', 'msg' => '账卡符合'],
                ],
            ],
            [
                'name' => '凭证检查',
                'type' => 'voucher',
                'num' => 0,
                'list' => [
                    ['name' => '清单凭证', 'status' => '0', 'msg' => '全部生成'],
                    ['name' => '汇兑损益结转凭证', 'status' => '0', 'msg' => '不需要生成'],
                    ['name' => '凭证断号', 'status' => '0', 'msg' => '无断号'],
                    ['name' => '凭证审核', 'status' => '0', 'msg' => '全部审核'],
                    ['name' => '结转凭证', 'status' => '0', 'msg' => '结转凭证正常'],
                ],
            ],
            [
                'name' => '科目检查',
                'type' => '',
                'num' => 0,
                'list' => [
                    ['name' => '资金负数', 'status' => '0', 'msg' => '资金正常', 'url' => ''],
                    ['name' => '往来科目长期挂账', 'status' => '0', 'msg' => '无长期挂账'],
                    ['name' => '往来科目对冲', 'status' => '0', 'msg' => '无可对冲往来科目'],
                    ['name' => '往来科目合并', 'status' => '0', 'msg' => '无可合并往来'],
                ],
            ],
            [
                'name' => '报表检查',
                'type' => '',
                'num' => 0,
                'list' => [
                    ['name' => '资产负债表平衡', 'status' => '0', 'msg' => '平', 'url' => ''],
                    ['name' => '成本倒挂', 'status' => '0', 'msg' => '无'],
                    ['name' => '资不抵债', 'status' => '0', 'msg' => '无'],
                ],
            ],
            [
                'name' => '其他指标',
                'type' => '',
                'num' => 0,
                'list' => [
                    ['name' => '福利费扣除标准', 'status' => '0', 'msg' => '未超标'],
                    ['name' => '连续零申报', 'status' => '0', 'msg' => '无'],
                    ['name' => '资产负债率', 'status' => '0', 'msg' => '正常'],
                    ['name' => '增值税税负率', 'status' => '0', 'msg' => '正常'],
                    ['name' => '所得税税负率', 'status' => '0', 'msg' => '正常'],
                    ['name' => '毛利率', 'status' => '0', 'msg' => '正常'],
                    ['name' => '税负波动', 'status' => '0', 'msg' => '正常'],
                    //['name' => '一般纳税人资格认定提醒', 'status' => '0', 'msg' => '不符合认定'],
                ],
            ],
        ];

        $invoice_check = self::checkInvoice($param);
        $voucher_check = self::checkVoucher($param);
        $ret3 = self::checkSubjectBalance($param);
        $ret4 = self::checkSheet($param);
        $ret5 = self::checkOther($param);

        foreach ($data as &$datum) {
            if ($datum['type'] == 'invoice') {
                $datum['list'] = $invoice_check;
                foreach ($invoice_check as $invoice_check_item) {
                    $datum['num'] += $invoice_check_item['status'];
                }
            }

            if ($datum['type'] == 'voucher') {
                $datum['list'] = $voucher_check;
                foreach ($voucher_check as $voucher_check_item) {
                    $datum['num'] += $voucher_check_item['status'];
                }
            }
        }

        return $data;
    }

    /**
     * 检查当期是否结账
     * @param array $param
     */
    public static function checkClose(Array $param)
    {
        $close_status = AccountCloseModel::query()->where('company_id', $param['company_id'])
            ->where('fiscal_period', $param['fiscal_period'])
            ->value('close_status');

        return $close_status == AccountCloseModel::CLOSE_STATUS_YES;
    }

    /**
     * 凭证检查
     * @param $param
     */
    private static function checkVoucher($param)
    {
        $data = [
            'qingdanpingzheng' => ['name' => '清单凭证', 'status' => '0', 'msg' => '全部生成'],
            //['name' => '汇兑损益结转凭证', 'status' => '0', 'msg' => '不需要生成'], 下期处理
            'pingzhengduanhao' => ['name' => '凭证断号', 'status' => '0', 'msg' => '无断号'],
            'pingzhengshenhe' => ['name' => '凭证审核', 'status' => '0', 'msg' => '全部审核'],
            'pingzhengjiezhuan' => ['name' => '结转凭证', 'status' => '0', 'msg' => '结转凭证正常'],
        ];

        !Voucher::checkUndo($param) && $data['qingdanpingzheng'] = ['name' => '清单凭证', 'status' => '1', 'msg' => '未全部生成'];
        !Voucher::checkDuanhao($param) && $data['pingzhengduanhao'] = ['name' => '凭证断号', 'status' => '1', 'msg' => '有断号'];
        !Voucher::checkAudit($param) && $data['pingzhengshenhe'] = ['name' => '凭证审核', 'status' => '1', 'msg' => '未全部审核'];
        !Voucher::checkJiezhuan($param) && $data['pingzhengjiezhuan'] = ['name' => '结转凭证', 'status' => '1', 'msg' => '未结转'];

        return $data;
    }

    /**
     * 清单检查
     * @param $param
     */
    private static function checkInvoice($param)
    {

        $data = [
            'gongzijiti' => ['name' => '工资计提', 'status' => '0', 'msg' => '已完成'],
            'chengbenjiezhuan' => ['name' => '成本结转', 'status' => '0', 'msg' => '已结转成本'],
            'zhangkahedui' => ['name' => '账卡核对', 'status' => '0', 'msg' => '帐卡不符'],
        ];

        //判断工资 是否计提
        $voucherItem_gongzi = VoucherItem::query()->where('company_id', $param['company_id'])
            ->where('fiscal_period', $param['fiscal_period'])
            ->where(function ($query) {
                $query->where('kuaijibianhao', 'like', '2211%')->orWhere('kuaijibianhao', 'like', '560201');
            })->get();


        if (count($voucherItem_gongzi) < 2)
            $data['gongzijiti'] = ['name' => '工资计提', 'status' => '1', 'msg' => '未完成'];

        //判断成本是否结转 1查询库存商品的科目余额；2判断是否有库存商品对应的凭证分录明细
        $kucunshangpin_subject_ballance = \App\Entity\SubjectBalance::get($param['company_id'], '1405');
        if (!empty($kucunshangpin_subject_ballance)) {
            $voucherItem_kucunshangpin = VoucherItem::query()->where('company_id', $param['company_id'])
                ->where('fiscal_period', $param['fiscal_period'])
                ->where(function ($query) {
                    $query->where('kuaijibianhao', 'like', '5401%')->orWhere('kuaijibianhao', 'like', '1405%');
                })->get();

            if (count($voucherItem_kucunshangpin) < 2)
                $data['chengbenjiezhuan'] = ['name' => '成本结转', 'status' => '1', 'msg' => '未结转成本'];
        }

        //账卡核对 下一期处理

        return $data;
    }

    /**
     * 科目检查
     * @param $param
     */
    private static function checkSubjectBalance($param)
    {

    }

    /**
     * 报表检查
     * @param $param
     */
    private static function checkSheet($param)
    {
    }

    /**
     * 其他指标检查
     * @param $param
     */
    private static function checkOther($param)
    {
    }

    /**
     * 检查上一期是否结账
     * @param string $company_id
     * @param string $fiscal_period
     * @return bool
     */
    private static function checkFormer($company_id = '', $fiscal_period = '')
    {
        $last_period = Period::lastPeriod($fiscal_period);
        $model = AccountCloseModel::query()->where('company_id', $company_id)
            ->where('fiscal_period', $last_period)
            ->first();

        return empty($model) || (!empty($model->close_status) && $model->close_status == 1);
    }

    /**
     * 检查税目当期是否计提
     * @param $tax_id
     * @param string $company_id
     * @param string $fiscal_period
     * @return bool 已经计提返回 true | 否则返回 false
     */
    public static function checkTaxJiti($tax_id, $company_id = '', $fiscal_period = '')
    {
        $company_id == '' && $company_id = Company::sessionCompany()->id;
        $fiscal_period == '' && $fiscal_period = Period::currentPeriod();

        $config = TaxConfig::query()->where('company_id', $company_id)
            ->where('tax_id', $tax_id)
            ->firstOrFail();

        $debit_voucher_num = VoucherItem::query()->where('company_id', $company_id)
            ->where('fiscal_period', $fiscal_period)
            ->where('kuaijibianhao', $config['debit_number'])
            ->count();

        $credit_voucher_num = VoucherItem::query()->where('company_id', $company_id)
            ->where('fiscal_period', $fiscal_period)
            ->Where('kuaijibianhao', $config['credit_number'])
            ->count();

        return $debit_voucher_num >= 1 && $credit_voucher_num >= 1;
    }

    /**
     * 检查 库存商品结转至主营业务成本
     * @param string $company_id
     * @param string $fiscal_period
     */
    public static function checkJiezhuanKcsp($company_id = '', $fiscal_period = '')
    {
        $company_id == '' && $company_id = Company::sessionCompany()->id;
        $fiscal_period == '' && $fiscal_period = Period::currentPeriod();

        //库存商品科目余额
        $kcsp_subject_ballance = SubjectBalanceEntity::get($company_id, '1101', $fiscal_period);

        //库存商品
        $kcsp_voucher_item = VoucherItem::query()->where('company_id', $company_id)
            ->where('fiscal_period', $fiscal_period)
            ->where('kuaijibianhao', 'like', '1001%')->count();

        //主营业务成本
        $zyywcb_voucher_item = VoucherItem::query()->where('company_id', $company_id)
            ->where('fiscal_period', $fiscal_period)
            ->where('kuaijibianhao', 'like', '5401%')->count();

        return $kcsp_subject_ballance == 0 || ($kcsp_voucher_item >= 1 && $zyywcb_voucher_item >= 1);
    }

    /**
     * 检查成本是否结转
     * @param string $company_id
     * @param string $fiscal_period
     */
    public static function checkJieZhuanChengBen($company_id = '', $fiscal_period = '')
    {
        $company_id == '' && $company_id = Company::sessionCompany()->id;
        $fiscal_period == '' && $fiscal_period = Period::currentPeriod();
        $ballance = \App\Entity\SubjectBalance::activeGet($company_id, '5401', $fiscal_period);
        return $ballance == 0;
    }

    /**
     * 检查收入是否结转
     * @param string $company_id
     * @param string $fiscal_period
     * @return bool
     */
    public static function checkJieZhuanShouRu($company_id = '', $fiscal_period = '')
    {
        $company_id == '' && $company_id = Company::sessionCompany()->id;
        $fiscal_period == '' && $fiscal_period = Period::currentPeriod();
        $ballance = \App\Entity\SubjectBalance::activeGet($company_id, '5001', $fiscal_period);
        return $ballance == 0;
    }

    /**
     * 检查费用/税金是否结转
     * @param string $company_id
     * @param string $fiscal_period
     * @return bool
     */
    public static function checkJieZhuanFeiYong($company_id = '', $fiscal_period = '')
    {
        $company_id == '' && $company_id = Company::sessionCompany()->id;
        $fiscal_period == '' && $fiscal_period = Period::currentPeriod();

        $account_number = [
            '5403',//税金及附加
            '4101',//制造费用
            '5601',//销售费用
            '5602',//管理费用
            '5603',//财务费用
            '5801',//所得税费用
        ];

        $account_number = self::getAllSubNumbers($account_number, $company_id, $fiscal_period);
        foreach ($account_number as $number) {
            $ballance = \App\Entity\SubjectBalance::activeGet($company_id, $number, $fiscal_period);
            //if ($ballance != 0) Log::info($number);
            if ($ballance != 0)
                return false;
        }

        return true;
    }

    /**
     * 返回当前科目的所有最后一级子明细科目
     * @param $number
     * @param $company_id
     * @return array
     */
    public static function getAllSubNumbers($numbers, $company_id, $fiscal_period)
    {
        $retNumber = [];
        foreach ($numbers as $number) {
            $subSubjectBalance = SubjectBalance::query()
                ->where('company_id', $company_id)
                ->where(function ($query) {
                    $query->where('qmye_j', '!=', 0)->orWhere('qmye_d', '!=', 0);
                })
                ->where('account_subject_number', 'like', $number . '%')->get();
            //dd($subSubjectBalance);
            foreach ($subSubjectBalance as $item) {
                if (!SubjectBalanceEntity::isChild($item['account_subject_id'], $company_id, $fiscal_period)) {
                    $retNumber[] = $item['account_subject_number'];
                }
            }
        }
        return $retNumber;
    }

    /**
     * 结账操作
     * 1、凭证生成：全部的 发票、费用、资金、摊销、薪酬生成
     * 2、税金计提：几个主要的税收 生成对应的凭证
     * 3、损益结转：将收入 和 税收、费用结转到本年利润
     * @param string $company_id
     * @param string $fiscal_period
     * @return bool
     * @throws \Exception
     */
    public static function Run($company_id = '', $fiscal_period = '')
    {
        $company_id == '' && $company_id = Company::sessionCompany()->id;
        $fiscal_period == '' && $fiscal_period = Period::currentPeriod();

        if (!self::checkFormer($company_id, $fiscal_period))
            throw new \Exception('请先处理本期之前的结账业务');

        //科目余额固化
        self::jiezhuanQmye($company_id, $fiscal_period);

        //结转成本和收入
        self::sunYiJieZhuan($company_id, $fiscal_period);

        //修改结账状态
        AccountCloseModel::query()->where('company_id', $company_id)
            ->where('fiscal_period', $fiscal_period)
            ->updateOrCreate(['company_id' => $company_id, 'fiscal_period' => $fiscal_period], [
                'company_id' => $company_id,
                'fiscal_period' => $fiscal_period,
                'close_status' => AccountCloseModel::CLOSE_STATUS_YES,
            ]);

        //修改科目余额表结账状态
        SubjectBalance::query()->where('company_id', $company_id)
            ->where('fiscal_period', $fiscal_period)
            ->update([
                'account_closed' => SubjectBalance::ACCOUNT_CLOSED_YES,
            ]);

        //创建下一期的科目余额数据
        SubjectBalanceEntity::createForAccountClose($param['company_id'], $param['fiscal_period']);

        return true;
    }

    /**
     * 结账-反结账
     * @param string $company_id
     * @param string $fiscal_period
     * @throws \Exception
     */
    public static function reverse($company_id = '', $fiscal_period = '')
    {
        $company_id == '' && $company_id = Company::sessionCompany()->id;
        $fiscal_period == '' && $fiscal_period = Period::currentPeriod();

        $param = array_merge(compact('company_id'), compact('fiscal_period'));

        if (!self::checkClose($param))
            throw new \Exception('本期未结账');

        //删除批量生成的凭证
        $sources = [VoucherModel::VOUCHER_SOURCE_SYJZ, VoucherModel::VOUCHER_SOURCE_SJJT];
        foreach ($sources as $source) {
            self::deleteVoucher($source, $company_id, $fiscal_period);
        }

        //更新科目余额表余额为0
        SubjectBalance::query()->where('company_id', $company_id)
            ->where('fiscal_period', $fiscal_period)
            ->update(['qmye_j' => 0, 'qmye_d' => 0]);

        //更新科目余额表字段
        SubjectBalance::query()->where('company_id', $company_id)
            ->where('fiscal_period', $fiscal_period)
            ->update(['account_closed' => SubjectBalance::ACCOUNT_CLOSED_NO]);

        //更新状态
        return AccountCloseModel::query()->where('company_id', $company_id)
            ->where('fiscal_period', $fiscal_period)
            ->update(['close_status' => AccountCloseModel::CLOSE_STATUS_NO]);
    }

    /**
     * 结账-清单凭证-批量生成凭证
     * @param string $company_id
     * @param string $fiscal_period
     * @return array 检查信息
     */
    public static function makeVoucherByQingdan($company_id = '', $fiscal_period = '')
    {
        $company_id == '' && $company_id = Company::sessionCompany()->id;
        $fiscal_period == '' && $fiscal_period = Period::currentPeriod();

        $data = [
            ['name' => '发票', 'type' => 'invoice', 'msg' => '待生成凭证清单数', 'num' => '0', 'status' => '0'],
            ['name' => '工资', 'type' => 'salary', 'msg' => '待生成凭证清单数', 'num' => '0', 'status' => '0'],
            ['name' => '资产', 'type' => 'asset', 'msg' => '待生成凭证清单数', 'num' => '0', 'status' => '0'],
            ['name' => '资金', 'type' => 'fund', 'msg' => '待生成凭证清单数', 'num' => '0', 'status' => '0'],
            ['name' => '费用', 'type' => 'cost', 'msg' => '待生成凭证清单数', 'num' => '0', 'status' => '0'],
        ];

        foreach ($data as &$datum) {
            $datum['num'] = self::undoVoucher($datum['type'], $company_id, $fiscal_period);
            $datum['status'] = intval(boolval($datum));
            $datum['msg'] = $datum['msg'] . ':' . $datum['num'] . '条';
        }

        return $data;
    }

    /**
     * 结账-(税金计提/损益结转)-删除凭证
     * @param $voucher_source
     * @param string $company_id
     * @param string $fiscal_period
     * @return mixed
     */
    public static function deleteVoucher($voucher_source, $company_id = '', $fiscal_period = '')
    {
        return Voucher::deleteBySource($voucher_source, $company_id, $fiscal_period);
    }

    /**
     * 未生成凭证列表
     * @param $type
     * 可选类型为:
     * invoice-发票,salary-工资,asset-资产,fund-资金,cost-费用
     * @param string $company_id
     * @param string $fiscal_period
     * @return integer 本期未生成凭证数量
     */
    public static function undoVoucher($type, $company_id = '', $fiscal_period = '')
    {
        $company_id == '' && $company_id = Company::sessionCompany()->id;
        $fiscal_period == '' && $fiscal_period = Period::currentPeriod();

        $query = null;
        $type == 'invoice' && $query = InvoiceModel::query();
        $type == 'salary' && $query = SalaryModel::query();
        $type == 'asset' && $query = AssetAlter::query();
        $type == 'fund' && $query = FundModel::query();
        $type == 'cost' && $query = CostModel::query();

        $count = $query->where('company_id', $company_id)
            ->where('fiscal_period', $fiscal_period)
            ->where('voucher_id', 0)->count();

        return intval($count);
    }

    /**
     * 损益结转-将收入和税费结转至本年利润
     * @param string $company_id
     * @param string $fiscal_period
     * @return bool|void
     * @throws \Exception
     */
    public static function sunYiJieZhuan($company_id = '', $fiscal_period = '')
    {
        $company_id == '' && $company_id = Company::sessionCompany()->id;
        $fiscal_period == '' && $fiscal_period = Period::currentPeriod();

        //结转本期损益-成本
        self::jieZhuanChengBen($company_id, $fiscal_period);

        //结转本期损益-收入
        self::jieZhuanShouRu($company_id, $fiscal_period);

        //结转本期损益-费用/税金
        self::jieZhuanFeiYong($company_id, $fiscal_period);

        return true;
    }

    /**
     * 损益结转-结转收入
     * @param string $company_id
     * @param string $fiscal_period
     * @return bool
     * @throws \Exception
     */
    private static function jieZhuanShouRu($company_id = '', $fiscal_period = '')
    {
        //检查本期是否结转收入
        if (self::checkJieZhuanShouRu($company_id, $fiscal_period))
            return true;

        //包括主营业务收入5001，其他业务收入5051
        $zyywsr_subject_ballance = SubjectBalanceEntity::activeGet($company_id, 5001, $fiscal_period);
        $qtywsr_subject_ballance = SubjectBalanceEntity::activeGet($company_id, 5051, $fiscal_period);
        $bnlr = $zyywsr_subject_ballance + $qtywsr_subject_ballance;

        //dd($zyywsr_subject_ballance);

        return self::jieZhuanPingZheng([
            //主营业务收入
            ['number' => '5001', 'balance_direction' => '借', 'money' => $zyywsr_subject_ballance, 'zhaiYao' => '结转本期损益'],
            //其他业务收入
            ['number' => '5051', 'balance_direction' => '借', 'money' => $qtywsr_subject_ballance, 'zhaiYao' => '结转本期损益'],
            //本年利润
            ['number' => '3103', 'balance_direction' => '贷', 'money' => $bnlr, 'zhaiYao' => '结转本期损益'],
        ], VoucherModel::VOUCHER_SOURCE_SYJZ);
    }

    /**
     * 损益结转-结转费用
     * @param string $company_id
     * @param string $fiscal_period
     * @return bool
     * @throws \Exception
     */
    private static function jieZhuanFeiYong($company_id = '', $fiscal_period = '')
    {
        $company_id == '' && $company_id = Company::sessionCompany()->id;
        $fiscal_period == '' && $fiscal_period = Period::currentPeriod();

        //检查费用税金是否已经结转
        if (self::checkJieZhuanFeiYong($company_id, $fiscal_period))
            return true;

        $account_number = [
            '5403',//税金及附加
            '4101',//制造费用
            '5601',//销售费用
            '5602',//管理费用
            '5603',//财务费用
            '5801',//所得税费用
        ];

        $account_number = self::getAllSubNumbers($account_number, $company_id, $fiscal_period);

        $data = [
            [
                'number' => '3103',
                'balance_direction' => '借',
                'money' => 0,
                'zhaiYao' => '结转本期损益-本年利润',
            ],
        ];
        $total = 0;
        foreach ($account_number as $number) {
            $account = AccountSubject::getKMbyNumber($number);
            $money = \App\Entity\SubjectBalance::activeGet($company_id, $number, $fiscal_period);
            $total = $total + $money;
            $money != 0 && $data[] = [
                'number' => $number,
                'balance_direction' => '贷',
                'money' => $money,
                'zhaiYao' => '结转本期损益-' . $account['name'],
            ];
        }

        $data[0]['money'] = $total;

        return self::jieZhuanPingZheng($data, VoucherModel::VOUCHER_SOURCE_SYJZ);
    }

    /**
     * 结转成本
     * @param string $company_id
     * @param string $fiscal_period
     * @throws \Exception
     */
    private static function jieZhuanChengBen($company_id = '', $fiscal_period = '')
    {
        if (self::checkJieZhuanChengBen($company_id, $fiscal_period))
            return true;

        $ballance = \App\Entity\SubjectBalance::activeGet($company_id, '5401', $fiscal_period);

        $data = [
            //本年利润
            ['number' => '5401', 'balance_direction' => '贷', 'money' => $ballance, 'zhaiYao' => '结转本期损益'],
            //主营业务成本
            ['number' => '3103', 'balance_direction' => '借', 'money' => $ballance, 'zhaiYao' => '结转本期损益'],
        ];

        return self::jieZhuanPingZheng($data, VoucherModel::VOUCHER_SOURCE_SYJZ);
    }

    /**
     * 根据数据生成凭证
     * @param $data
     * $data格式
     * $data = [
     *     ['number' => '3103', 'balance_direction' => '借', 'money' => '2000', 'zhaiYao' => '结转本期损益'],
     *     ['number' => '5001', 'balance_direction' => '贷', 'money' => '1000', 'zhaiYao' => '结转本期损益'],
     *     ['number' => '5051', 'balance_direction' => '贷', 'money' => '1000', 'zhaiYao' => '结转本期损益'],
     * ];
     *
     * @param $voucher_source
     * @return bool
     * @throws \Exception
     */
    public static function jieZhuanPingZheng($data, $voucher_source)
    {

        $total_debit_money = $total_credit_money = 0;
        $item = [];
        foreach ($data as $datum) {

            if ($datum['money'] == 0)
                continue;

            $debit_money = $datum['balance_direction'] == '借' ? $datum['money'] : 0;
            $credit_money = $datum['balance_direction'] == '贷' ? $datum['money'] : 0;

            $total_debit_money += $debit_money;
            $total_credit_money += $credit_money;

            $account = AccountSubject::getKMbyNumber($datum['number']);

            $item[] = [
                'zhaiyao' => $datum['zhaiYao'],
                'kuaijikemu_id' => $account['id'],
                'debit_money' => $debit_money,
                'credit_money' => $credit_money,
            ];
        }

        if ($total_debit_money != $total_credit_money) {
            throw new \Exception('借贷金额不匹配');
        }

        //生成凭证
        $req = new Request([
            'voucher_num' => Voucher::newVoucherNum(),
            'attach' => '0',
            'voucher_date' => Period::currentPeriodLastDay(),
            'voucher_source' => $voucher_source,
            'total_debit_money' => $total_debit_money,
            'total_credit_money' => $total_credit_money,
            'total_cn' => Common::num_to_rmb($total_debit_money),
            'items' => $item,
        ]);

        //dd($req);

        return Voucher::saveVoucher($req);

    }

    /**
     * 计算期末余额
     * @param $company_id
     * @param $fiscal_period
     */
    public static function jiezhuanQmye($company_id, $fiscal_period)
    {
        $list = SubjectBalance::query()
            ->where('company_id', $company_id)
            ->where('fiscal_period', $fiscal_period)
            ->where(function ($query) {
//                $query->where('bqfse_j', '!=', '0')->orWhere('bqfse_d', '!=', '0');
            })
            //->lockForUpdate()
            ->get();

        //dd($list);

        foreach ($list as $item) {
            (self::handleQmye($item))->save();
        }

    }

    /**
     * @param SubjectBalance $model
     */
    private static function handleQmye(SubjectBalance $model)
    {
        $model->qmye_j = $model->qcye_j + $model->bqfse_j;
        $model->qmye_d = $model->qcye_d + $model->bqfse_d;


        if ($model->balance_direction == '借') {
            $model->qmye_j = $model->qmye_j - $model->qmye_d;
            $model->qmye_d = 0;
        } else {
            $model->qmye_d = $model->qmye_d - $model->qmye_j;
            $model->qmye_j = 0;
        }

        return $model;

    }


    /**
     * 计提税金
     * @param string $company_id
     * @param string $fiscal_period
     * @return array
     * @throws \Exception
     */
    public static function jiTiShuiJin($company_id = '', $fiscal_period = '')
    {
        $company_id == '' && $company_id = Company::sessionCompany()->id;
        $fiscal_period == '' && $fiscal_period = Period::currentPeriod();

        //获取对应公司的税金配置
        $configList = Tax::config($company_id);

        //获取当期增值税
        $zengZhiShui = Tax::zengZhiShui($company_id, $fiscal_period);

        //将对应科目的税金进项计提操作（更新科目余额表相关科目余额）
        foreach ($configList as $config) {

            $config['jiti_status'] = 0;

            if ($config['status'] == 0 || $config['debit_number'] == '' || $config['credit_number'] == '')
                continue;

            //计算税收
            $tax = self::jsTax($config['tax_id'], $zengZhiShui);

            if ($tax == 0)
                continue;

            //检查是否计提过
            if (self::checkTaxJiti($config['tax_id'], $company_id)) {
                $config['jiti_status'] = 1;
                continue;
            }

            //echo $tax . '-' . $config['tax_id'] . '<br>';

            $km_debit = AccountSubject::getKMbyNumber($config['debit_number']);
            $km_credit = AccountSubject::getKMbyNumber($config['credit_number']);

            //生成凭证
            $req = new Request([
                'voucher_num' => Voucher::newVoucherNum(),
                'attach' => '0',
                'voucher_date' => Period::currentPeriodLastDay(),
                'voucher_source' => VoucherModel::VOUCHER_SOURCE_SJJT,
                'total_debit_money' => $tax,
                'total_credit_money' => $tax,
                'total_cn' => Common::num_to_rmb($tax),
                'items' => [
                    [
                        'zhaiyao' => $km_debit['name'],
                        'kuaijikemu_id' => $km_debit['id'],
                        'debit_money' => $tax,
                        'credit_money' => 0,
                    ],
                    [
                        'zhaiyao' => $km_credit['name'],
                        'kuaijikemu_id' => $km_credit['id'],
                        'debit_money' => '0',
                        'credit_money' => $tax,
                    ],
                ],
            ]);
            Voucher::saveVoucher($req);

            $column_debit = $km_debit['balance_direction'] == '借' ? 'qmye_j' : 'qmye_d';
            $column_credit = $km_credit['balance_direction'] == '借' ? 'qmye_j' : 'qmye_d';

            //科目余额调整
            SubjectBalance::query()->where('company_id', $company_id)
                ->where('fiscal_period', $fiscal_period)
                ->where('account_subject_number', $config['debit_number'])
                ->decrement($column_debit, $tax);

            SubjectBalance::query()->where('company_id', $company_id)
                ->where('fiscal_period', $fiscal_period)
                ->where('account_subject_number', $config['credit_number'])
                ->decrement($column_credit, $tax);

            $config['jiti_status'] = 1;
        }

        return $configList;
    }

    /**
     * 计算各项税
     * @param $tax_id
     * @param $zengZhiShui TODO
     */
    private static function jsTax($tax_id, $zengZhiShui)
    {
        $tax = 0;
        $tax_id == 1 && $tax = $zengZhiShui * 0.07;
        $tax_id == 2 && $tax = $zengZhiShui * 0.07;
        $tax_id == 3 && $tax = $zengZhiShui * 0.07;
        $tax_id == 4 && $tax = $zengZhiShui * 0.07;
        $tax_id == 5 && $tax = $zengZhiShui * 0.07;
        $tax_id == 6 && $tax = $zengZhiShui * 0.07;
        return $tax;
    }
}