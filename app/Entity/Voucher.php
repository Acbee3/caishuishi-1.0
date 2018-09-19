<?php

namespace App\Entity;

use App\Entity\Fund as FundEntity;
use App\Entity\MakeVoucher\MakeVoucher;
use App\Entity\MakeVoucher\VoucherData;
use App\Models\Accounting\Cost;
use App\Models\Accounting\Fund;
use App\Models\Accounting\Invoice;
use App\Models\Accounting\Voucher as VoucherModel;
use App\Models\Accounting\VoucherItem;
use App\Models\AccountSubject;
use App\Models\Common;
use App\Models\Company as CompanyModel;

/**
 * 凭证类
 * Class Voucher
 * @package App\Entity
 */
class Voucher
{


    /**
     * @param $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function search($request)
    {
        $request = \App\Models\Accounting\Voucher::search($request);
        foreach ($request as &$v) {
            foreach ($v->voucherItem as &$d) {
                $d->kuaijikemu = $d->kuaijibianhao . ' ' . $d->kuaijikemu;
            }
        }
        return $request;
    }

    /**
     * 保存凭证
     * @param $request
     * @return bool
     * @throws \Exception
     */
    public static function saveVoucher($request)
    {

        //先删除之前的子项数据
        if ($request->id > 0) {

            //删除科目余额表
            $oldVoucher = \App\Models\Accounting\Voucher::where('id', $request->id)->with("voucherItem")->first();
            SubjectBalance::subjectBalanceDelForVoucher($oldVoucher);

            //如果是编辑执行以下删除子项
            VoucherItem::where("voucher_id", $request->id)->delete();
        }

        $company = Company::sessionCompany();
        $user = Common::loginUser();
        $result = \App\Models\Accounting\Voucher::where("company_id", $company->id)
            ->where("voucher_num", $request->voucher_num)
            ->where("fiscal_period", $request->fiscal_period)->first();
        if ($result) {
            throw new \Exception("记账凭证号已被创建！");
        }

        // 检查当前账期科目余额信息是否已做过初始化  有则跳过  没有就生成（已转至账薄首页处理初始化）
        SubjectBalance::checkPeriodSubjectBalance();

        $request->company_id = $company->id;
        $request->creator_id = $user->id;
        $request->creator_name = $user->name;
        $request->audit_status = \App\Models\Accounting\Voucher::AUDIT_STATUS_0;
        $request->fiscal_period = !empty($request->voucher_date) ? date("Y-m-01", strtotime($request->voucher_date)) : '';
        $voucher = \App\Models\Accounting\Voucher::createVoucher($request);
        if (!$voucher) {
            throw new \Exception("凭证创建失败！");
        }

        $request->items = is_array($request->items) ? $request->items : json_decode($request->items);

        foreach ($request->items as $v) {
            $kuaijikemu = AccountSubject::find($v['kuaijikemu_id']);
            if (!$kuaijikemu) {
                throw new \Exception("科目不存在");
            }

            $v['company_id'] = $company->id;
            $v['kuaijikemu'] = \App\Entity\AccountSubject::getAllkMName($kuaijikemu->number);

            $v['kuaijibianhao'] = $kuaijikemu->number;
            $v['voucher_id'] = $voucher->id;
            $v['fiscal_period'] = $request->fiscal_period;

            $return = VoucherItem::createVoucherItem($v);
            if (!$return) {
                throw new \Exception("科目子项创建失败！");
            }
        }

        //编辑业务表中凭证ID字段
        if (!empty($request->yw_id) && !empty($request->type)) {
            VoucherData::setVoucherID($request->type, $request->yw_id, $voucher->id);
        }

        SubjectBalance::subjectBalanceCreateForVoucher($voucher);

        return $voucher;
    }

    /**
     * 凭证审核
     * @param $request
     * @return bool
     * @throws \Exception
     */
    public static function audit($request)
    {

        $user = Common::loginUser();
        $request->auditor_id = $user->id;
        $request->auditor_name = $user->name;

        if (empty($request->id)) {
            throw new \Exception("ID不能为空！");
        }
        if (empty($request->audit_status) && $request->audit_status != \App\Models\Accounting\Voucher::AUDIT_STATUS_0) {
            throw new \Exception("审核状态不能为空！");
        }

        if (!key_exists($request->audit_status, \App\Models\Accounting\Voucher::$auditStatusLabels)) {
            throw new \Exception("审核状态不合法！");
        }

        if ($request->audit_status == \App\Models\Accounting\Voucher::AUDIT_STATUS_0) {
            $request->auditor_id = null;
            $request->auditor_name = '';
        }
        return \App\Models\Accounting\Voucher::audit($request);
    }

    /**
     * 删除凭证
     * @param $request
     * @return bool
     * @throws \Exception
     */
    public static function del($request)
    {
        if (empty($request->id)) {
            throw new \Exception("ID不能为空！");
        }
        return \App\Models\Accounting\Voucher::del($request);
    }

    /**
     * 根据凭证来源删除凭证
     * @param $source
     * @param string $company_id
     * @param string $fiscal_period、
     * @return void
     */
    public static function deleteBySource($source, $company_id = '', $fiscal_period = '')
    {
        $company_id == '' && $company_id = Company::sessionCompany()->id;
        $fiscal_period == '' && $fiscal_period = Period::currentPeriod();

        $query = VoucherModel::query()->where('company_id', $company_id)
            ->where('fiscal_period', $fiscal_period)
            ->where('voucher_source', $source);

        $voucher_ids = $query->pluck('id');

        foreach ($voucher_ids as $voucher_id) {
            $voucher = VoucherModel::query()->whereKey($voucher_id)->first();
            SubjectBalance::subjectBalanceDelForVoucher($voucher);
        }

        VoucherItem::query()->whereIn('voucher_id', $voucher_ids)->delete();
        return $query->delete();
    }

    /**
     * 获取凭证最大的凭证字号数字 例如：记-1
     * @param $period
     * @return int|mixed
     */
    public static function getCurrentMaxVoucherNum($period)
    {
        $company = Company::sessionCompany();
        $max = \App\Models\Accounting\Voucher::where('company_id', $company->id)->where("fiscal_period", $period)->max("voucher_num");
        return ($max + 1);
    }

    /***
     * 从业务生成预览凭证
     * @param $request
     * @return mixed
     * @throws \Exception
     */
    public static function preview($request)
    {
        if (empty($request->type)) {
            throw new \Exception("没有类型");
        }
        if (empty($request->id)) {
            throw new \Exception("没有ID");
        }
        $model = VoucherData::BCFactory($request->type, $request->id);
        if (!$model) {
            throw new \Exception('业务数据没有找到');
        }

        return (new MakeVoucher($request->type))->getData($model);
    }

    /**
     * 展示凭证
     * @param $request
     * @return array
     * @throws \Exception
     */
    public static function showVoucher($request)
    {
        if (empty($request->id)) {
            throw new \Exception("没有ID");
        }
        $voucher = \App\Models\Accounting\Voucher::where("id", $request->id)->with("voucherItem")->first();
        $d = [];
        foreach ($voucher->voucherItem as $v) {
            $d[] = [
                'zhaiyao' => $v->zhaiyao,
                'account_id' => $v->kuaijikemu_id,
                'account_number' => $v->kuaijibianhao,
                'account_name' => $v->kuaijikemu,
                'debit_money' => $v->debit_money,
                'credit_money' => $v->credit_money,
                'balance' => false,
                'newAdd' => false,
                'hiddenInput' => false,
                'hiddenText' => false,
            ];
        }

        $data = [
            'maxVoucherNum' => $voucher->voucher_num,
            'attach' => $voucher->attach,
            'voucherDate' => $voucher->voucher_date,
            'period' => date("Y年第n期", strtotime($voucher->fiscal_period)),
            'data' => $d,
        ];

        return $data;
    }

    /**
     * 检查凭证是否全部生成
     * @param $param
     * @return bool 全部生成返回 true | 其他情况返回false
     */
    public static function checkUndo($param)
    {
        //发票、费用、资金
        $invoice = Invoice::query()->where('company_id', $param['company_id'])
            ->where('fiscal_period', $param['fiscal_period'])
            ->where('voucher_id', 0)->count();
        $cost = Cost::query()->where('company_id', $param['company_id'])
            ->where('fiscal_period', $param['fiscal_period'])
            ->where('voucher_id', 0)->count();
        $fund = Fund::query()->where('company_id', $param['company_id'])
            ->where('fiscal_period', $param['fiscal_period'])
            ->where('source_type', FundEntity::SOURCE_TYPE_USER)
            ->where('voucher_id', 0)->count();

        return empty($invoice) && empty($cost) && empty($fund);

    }

    /**
     * 检查是否断号
     * @param $param
     * @return bool 有断号返回false | 否则返回true
     */
    public static function checkDuanhao($param)
    {
        $num = VoucherModel::query()->where('company_id', $param['company_id'])
            ->where('fiscal_period', $param['fiscal_period'])
            ->pluck('voucher_num')->toArray();

        sort($num);

        if (count($num))
            for ($i = 0; $i < count($num) - 1 && count($num) > 0; $i++) {
                if ($num[$i + 1] != $num[$i] + 1) {
                    return false;
                }
            }

        return true;
    }

    /**
     * 检查是否全部审核
     * @param $param
     * @return bool 全部审核返回true | 否则返回false
     */
    public static function checkAudit($param)
    {
        $num = VoucherModel::query()->where('company_id', $param['company_id'])
            ->where('fiscal_period', $param['fiscal_period'])
            ->where('audit_status', VoucherModel::AUDIT_STATUS_0)
            ->count();

        return empty($num);
    }

    /**
     * 检查是否全部结转
     * @param $param
     * @return bool 已结转 true| false
     */
    public static function checkJiezhuan($param)
    {
        $num = VoucherItem::query()->where('company_id', $param['company_id'])
            ->where('fiscal_period', $param['fiscal_period'])
            ->where('kuaijibianhao', '3103')//本年利润
            ->get();

        return !empty($num);

    }

    /**
     * 获取新的凭证编码
     * @param null $company
     */
    public static function newVoucherNum($company = null, $period = '')
    {
        $company == null && $company = Company::sessionCompany();
        $period == '' && $period = Period::currentPeriod();

        $maxVoucherNum = VoucherModel::query()
            ->where('company_id', $company->id)
            ->where('fiscal_period', $period)
            ->max('voucher_num');

        return intval($maxVoucherNum) + 1;
    }

    /**
     * 取指定凭证的相关信息
     * @param $param
     * @return array
     */
    public static function GetVoucherList($param)
    {
        $voucher_id = $param->voucher_id;
        $voucher = VoucherModel::find($voucher_id);

        new Company();
        $company_id = Company::$company->id;

        if ($voucher_id > 0 && $voucher) {
            $d = \App\Entity\AccountSubject::subsetList();
            //$period = Period::currentPeriod();
            $period = date("Y年第n期", (strtotime($voucher->fiscal_period)));

            $items = [];
            $list = VoucherItem::query()->where('voucher_id', $voucher_id)->where('company_id', $company_id)->get();
            $t_debit_money = '0.00';
            $t_credit_money = '0.00';
            if (count($list) > 0) {
                foreach ($list as $key => $v) {
                    $items[$key]['zy'] = $v->zhaiyao;
                    $items[$key]['codeInput'] = $v->kuaijibianhao.' '.$v->kuaijikemu;
                    $items[$key]['account_id'] = $v->kuaijikemu_id;
                    $items[$key]['val'] = self::FormatMoneyForPZ($v->debit_money);
                    $items[$key]['sendVal'] = self::FormatMoneyForPZ($v->credit_money);
                    $items[$key]['balance'] = false;
                    $items[$key]['newAdd'] = false;
                    $items[$key]['hiddenInput'] = false;
                    $items[$key]['hiddenText'] = false;

                    $t_debit_money += $v->debit_money;
                    $t_credit_money += $v->credit_money;
                }
            }

            if ($t_debit_money > 0 && $t_credit_money > 0 && $t_debit_money == $t_credit_money && $t_debit_money == $voucher->total_debit_money && $voucher->total_debit_money == $voucher->total_credit_money) {
                $msg = '';
            } else {
                $msg = '此凭证借贷数据异常！';
            }

            $data = [
                'status' => 'success',
                'kuaijikemu' => $d,
                'period' => $period,
                'data' => $voucher,
                'items' => $items,
                'msg' => $msg,
            ];

        } else {
            $data = [
                'status' => 'err',
            ];

        }

        return $data;
    }

    /**
     * 根据会计科目ID取编码和名称组合体
     * @param $km_id
     * @return string
     */
    public static function GetVoucherCode($km_id)
    {
        $info = AccountSubject::find($km_id);
        if ($info) {
            $val = $info['number'] . ' ' . $info['name'];
        } else {
            $val = '';
        }
        return $val;
    }

    /**
     * 金额相关处理
     * @param $val
     * @return string
     */
    public static function FormatMoney($val)
    {
        if (is_numeric($val) && $val > 0) {
            //$val = sprintf("%.2f", $val);
            $val = number_format($val, 2, '.', '');
            // 元转换成分  匹配生成前端模板页数据
            $val = $val * 100;
        } else {
            $val = '';
        }
        //return $val;
        return strval($val);
    }

    /**
     * 金额相关处理 凭证
     * @param $val
     * @return string
     */
    public static function FormatMoneyForPZ($val)
    {
        if (is_numeric($val) && $val > 0) {
            //$val = sprintf("%.2f", $val);
            $val = number_format($val, 2, '.', '');
            // 元转换成分  匹配生成前端模板页数据
            //$val = $val * 100;
        } else {
            $val = '';
        }
        //return $val;
        return strval($val);
    }

    /**
     * 取指定凭证的基本信息
     * @param $param
     * @return array
     */
    public static function GetSimpleVoucherInfo($param)
    {
        $voucher_id = $param->voucher_id;
        //$voucher = VoucherModel::find($voucher_id);
        $voucher = VoucherModel::query()->where('id', $voucher_id)->first();
        if ($voucher_id > 0 && $voucher) {
            $data = [
                'status' => 'success',
                'create_by' => $voucher->creator_name,
                'create_at' => date("Y-m-d H:i:s", strtotime($voucher->created_at)),
                'review_by' => $voucher->auditor_name,
                'review_at' => date("Y-m-d H:i:s", strtotime($voucher->updated_at)),
                'msg' => '审核成功。',
            ];
        } else {
            $data = [
                'status' => 'err',
                'create_by' => '',
                'create_at' => '',
                'review_by' => '',
                'review_at' => '',
                'msg' => '审核失败。',
            ];
        }
        return $data;
    }

    /**
     * 导出凭证明细数据 便于打印pdf操作
     * @param string $company_id
     * @param string $fiscal_period
     * @return array
     */
    public static function pdfList($company_id = '', $fiscal_period = '')
    {
        $company_id == '' && $company_id = Company::sessionCompany()->id;
        $fiscal_period == '' && $fiscal_period = Period::currentPeriod();

        $ret = [];
        $company = CompanyModel::query()->whereKey($company_id)->first();

        $voucherList = VoucherModel::query()->where('company_id', $company_id)
            ->where('fiscal_period', $fiscal_period)
            ->get();

        $table_count = 0;//记账表格数量
        foreach ($voucherList as $voucher) {
            $table_count += ceil(count($voucher->voucherItem) / 5);
        }

        foreach ($voucherList as $voucher) {
            //按照每5个分一组
            $partion = $voucher->voucherItem->chunk(5)->toArray();
            //全部分组数
            $partion_count = count($partion);
            foreach ($partion as $pkey => $pitem) {

                $tmp_item = $pitem;

                //填充不足5个的分组 为 每组5个
                if (($pitem_count = count($pitem)) < 5) {
                    $tmp_item = array_merge($pitem, array_fill($pitem_count, 5 - $pitem_count, [
                        'id' => '', 'company_id' => '', 'zhaiyao' => '', 'kuaijikemu_id' => '', 'kuaijikemu' => '', 'debit_money' => '',
                        'credit_money' => '', 'voucher_id' => '', 'created_at' => '', 'updated_at' => '', 'fiscal_period' => '', 'kuaijibianhao' => '',
                    ]));
                }

                //组装数据
                foreach ($tmp_item as &$titem) {
                    $titem = [
                        'zhaiyao' => $titem['zhaiyao'],
                        'kemu' => "{$titem['kuaijibianhao']} {$titem['kuaijikemu']}",
                        'jiefang' => $titem['debit_money']== 0 ? '' : $titem['debit_money'],
                        'daifang' => $titem['credit_money'] == 0 ? '' : $titem['credit_money'],
                    ];
                }

                $index = $pkey + 1;
                $ret[] = [
                    'company_name' => $company->company_name,
                    'voucher_date' => $voucher['voucher_date'],
                    'attach' => $voucher['attach'],
                    'voucher_num' => '记-' . $voucher['voucher_num'] . "({$index}/{$partion_count})",
                    'total_debit_money' => $voucher['total_debit_money'],
                    'total_credit_money' => $voucher['total_credit_money'],
                    'total_money_cn' => "合计：" . Common::num_to_rmb($voucher['total_debit_money']),
                    'item' => $tmp_item,
                ];
            }
        }

        //dd($ret);
        return $ret;
    }

}