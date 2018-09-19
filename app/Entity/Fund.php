<?php

namespace App\Entity;

use App\Entity\BusinessDataConfig\BusinessConfig;
use App\Models\Accounting\Fund as FundModel;
use App\Models\Accounting\FundItem;
use App\Models\BussinessDatasAccountSubject;
use App\Models\Common;
use DB;
use Illuminate\Support\Facades\Schema;
use Validator;

/**
 * 资金类
 * Class Fund
 * @package App\Entity
 */
class Fund
{
    const BANK = 1;
    const CASH = 2;
    const BILL = 3;

    const SOURCE_TYPE_AUTO = 0;//自动
    const SOURCE_TYPE_USER = 1;//手动

    const FUND_TYPE_IN = 1;  //收入
    const FUND_TYPE_OUT = 2; //支出

    public static function getChanneltype()
    {
        return [
            self::BANK => 'bank',
            self::CASH => 'cash',
            self::BILL => 'bill',
        ];
    }

    /**
     * 银行业务类型候选
     * @return array
     */
    public static function ywOptions(): array
    {
        $ret = [];
        $list = (new BusinessConfig(4))->getData();
        //dd($list);
        foreach ($list as $item) {
            if (!empty($item['child'])) {
                foreach ($item['child'] as $item_child) {
                    $item_child['full_name'] = !empty($item_child['full_name']) ? $item_child['full_name'] : $item_child['name'];
                    $ret[] = [
                        'value' => $item_child['number'],
                        'label' => $item['name'] . ':' . $item_child['name'],
                        'type' => $item['type'],
                        'jdfx' => $item['JDFX'],
                    ];
                }
            } else {
                $ret[] = [
                    'value' => $item['number'],
                    'label' => $item['name'],
                    'type' => $item['type'],
                    'jdfx' => $item['JDFX'],
                ];
            }
        }
        return $ret;
    }

    /**
     * 资金 银行/现金/票据首页列表数据
     * @param $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|null
     */
    public static function fundList($request)
    {
        $list = null;
        !empty($request->channel_type) && $request->channel_type == Fund::BANK && $list = self::bankList($request);
        !empty($request->channel_type) && in_array($request->channel_type, [Fund::CASH, Fund::BILL]) && $list = self::cashBillFundList($request);
        return $list;
    }


    /**
     * 现金/票据 首页数据
     * @param $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function cashBillFundList($request)
    {
        new Company();
        $query = FundModel::with('voucher')->where('channel_type', $request->channel_type)->where('company_id', Company::$company->id);
        if ($request->status)
            $query->where('voucher_id', '>', 0);

        $list = $query->paginate(20);

        $list->each(function ($item) {
            $item->dw = false;
            $item->cash = false;
            $item->top = true;
            $item->select = false;
            $item->editor = true;
            $item->keep = false;
        });
        return $list;
    }

    /**
     * 新增/更新 资金-现金/票据
     * @param $request
     * @return bool|string
     */
    public static function newFund($request)
    {
        new Company();
        $data = $request->all();
        if ($request->channel_type == Fund::BILL) {
            $rules = [
                'money' => 'required',
                'ywlx' => 'required',
            ];
        } else {
            $rules = [
                'fund_date' => 'required',
                'money' => 'required',
                'ywlx' => 'required',
            ];
        }

        $messages = [
            'fund_date.required' => '请输入日期',
            'money.required' => '请输入金额',
            'ywlx.required' => '请选择业务类型',
        ];
        $validator = Validator::make($data, $rules, $messages, []);
        if ($validator->fails()) {
            return $validator->messages()->first();
        }
        $data['company_id'] = Company::$company->id;
        !isset($data['fund_date']) && $data['fund_date'] = date('Y-m-d', time());
        DB::beginTransaction();
        try {
            $result = FundModel::updateOrCreate(['id' => $data['id']], $data);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            return '操作失败';
        }
    }

    /**
     * 删除 资金-现金/票据
     * @param $request
     * @return bool|string
     */
    public static function del($request)
    {
        $id = $request->id;
        if (!$id) return '请选择要删除的记录';
        $result = FundModel::destroy($id);
        return $result ? true : '操作失败';
    }

    /**
     * 资金 银行列表
     * @param $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function bankList($request)
    {
        new Company();
        $data = $request->all();
        $query = FundModel::with('FundItems')->where('channel_type', Fund::BANK)->where('company_id', Company::$company->id)->orderBy('id', 'desc');
        foreach ($data as $k => $v) {
            if (Schema::hasColumn('fund', "{$k}") && !empty($v)) {
                $query->where("$k", $v);
            }
        }

        !empty($request->voucher_status) && $request->voucher_status == 1 && $query->where('voucher_id', '!=', '0');
        !empty($request->voucher_status) && $request->voucher_status == 2 && $query->where('voucher_id', '=', '0');

        !empty($request->fund_type) && $query->where('fund_type', $request->fund_type);

        $list = $query->paginate(20);
        $list->each(function ($item) {
            $item->editor = true;
            $item->keep = false;
            $item->check = false;
            $item->disableCkeck = false;
            $item->voucher_num = !empty($item->voucher->voucher_num) ? '记-' . $item->voucher->voucher_num : '';
            if ($item->FundItems) {
                $item->FundItems->each(function ($i) {
                    $i->expense = true;
                    $i->expenseUnit = false;
                    $i->top = true;
                    $i->select = false;
                });
            }
        });
        return $list;
    }

    /**
     * 新增/更新 资金-银行
     * @param $param
     * @return \Illuminate\Database\Eloquent\Model
     */
    public static function newBank($param)
    {

        //dd($param);

        !isset($param['id']) && $param['id'] = null;
        $fundColumns = Common::filterColumn('fund', $param);
        $fundColumns['channel_type'] = self::BANK;
        $fundColumns['source_type'] = self::SOURCE_TYPE_USER;
        $fund = FundModel::updateOrCreate(['id' => $fundColumns['id']], $fundColumns);

        if (!empty($fundColumns['invoice_id'])) {
            $fundColumns['source_type'] = self::SOURCE_TYPE_AUTO;
            \App\Models\Accounting\Invoice::whereKey($fundColumns['invoice_id'])->update([
                'jszt' => Invoice::JSZT_YES,
            ]);
        }

        foreach ($param['fund_items'] as $k => $v) {

            $itemData = Common::filterColumn('fund_item', $v);
            !isset($itemData['id']) && $itemData['id'] = null;

            $dw_num = \App\Models\AccountSubject::query()->where('id', BussinessDatasAccountSubject::query()
                ->where('bussiness_datas_id', $itemData['dw_id'])->value('account_subjects_id'))
                ->value('number');

            $itemData['company_id'] = Company::sessionCompany()->id;
            $itemData['ywlx_id'] = intval($itemData['ywlx_id']);
            $itemData['dw_num'] = $dw_num;
            $itemData['fund_type'] = intval($v['fund_type']);
            $itemData['fund_id'] = intval($fund['id']);

            //dd($itemData);

            FundItem::updateOrCreate(['id' => $itemData['id']], $itemData);
        }
        return $fund;
    }

    /**
     * 将发票数据转化为资金数据
     * @param $invoice_id
     * @throws \Exception
     */
    public static function convertInvoice($invoice_id, $channel_type = Fund::BANK)
    {
        $invoice = Invoice::detail($invoice_id);

        $dw_name = $invoice['type'] == Invoice::TYPE_IMPORT ? $invoice['xfdw_name'] : $invoice['gfdw_name'];
        $dw_id = $invoice['type'] == Invoice::TYPE_IMPORT ? $invoice['xfdw_id'] : $invoice['xfdw_id'];

        if ($channel_type == Fund::CASH) {
            $data = [
                'company_id' => $invoice['company_id'],
                'fund_date' => $invoice['kprq'],
                'fund_type' => $invoice['type'] == Invoice::TYPE_IMPORT ? 2 : 1,
                'channel_type' => Fund::CASH,
                'source_type' => self::SOURCE_TYPE_AUTO,
                'money' => $invoice['total_fee_tax_money'],
                'ywlx_id' => $invoice[''],
                'ywlx' => $invoice['type'] == Invoice::TYPE_IMPORT ? '付采购款' : '收销售款',
                'dw_id' => $dw_id,
                'dw_name' => $dw_name,
                'fiscal_period' => $invoice['fiscal_period'],
            ];
        } else {
            $data = [
                'company_id' => $invoice['company_id'],
                'fund_date' => $invoice['kprq'],
                'fund_type' => $invoice['type'] == Invoice::TYPE_IMPORT,
                'channel_type' => Fund::BANK,
                'source_type' => self::SOURCE_TYPE_AUTO,
                'money' => $invoice['total_fee_tax_money'],
                'ywlx_id' => $invoice[''],
                'ywlx' => $invoice[''],
                'dw_id' => $dw_id,
                'dw_name' => $dw_name,
                'fiscal_period' => $invoice['fiscal_period'],
            ];

            $data['fund_items'] = [
                'funditem_date' => $invoice['kprq'],
                'fund_type' => $invoice['type'] == Invoice::TYPE_IMPORT ? 2 : 1,
                'ywlx_id' => '',
                'ywlx' => $invoice['type'] == Invoice::TYPE_IMPORT ? '付采购款' : '收销售款',
                'money' => $invoice['total_fee_tax_money'],
                'fiscal_period' => $invoice['fiscal_period'],
                'dw_id' => $dw_id,
                'dw_name' => $dw_name,
            ];
        }
        return $data;
    }

    /**
     * 删除 资金-银行
     * @param $request
     * @return bool|string
     * @throws \Exception
     */
    public static function delBank($request)
    {
        $id = $request->id;
        if (!$id) return '请选择要删除的记录';
        DB::beginTransaction();
        try {

            if (is_array($id)) {
                FundModel::query()->whereIn('id', $id)->delete();
                FundItem::query()->whereIn('fund_id', $id)->delete();
            } else {
                FundModel::destroy($id);
                FundItem::where('fund_id', $id)->delete();
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            return '操作失败';
        }

    }

    /**
     * 删除 资金-银行(单行)
     * @param $request
     * @return bool|string
     */
    public static function delBankItem($request)
    {
        $id = $request->id;
        if (!$id) return '请选择要删除的记录';
        $result = FundItem::destroy($id);
        return $result ? true : '操作失败';
    }

}