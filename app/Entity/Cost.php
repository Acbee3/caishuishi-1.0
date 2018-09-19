<?php

namespace App\Entity;

use App\Models\Accounting\CostItem;
use App\Models\BussinessData;

/**
 * 费用类
 * Class Cost
 * @package App\Entity
 */
class Cost
{
    /**
     * 费用列表
     * @param \App\Models\Company $company
     * @param array $param
     * @param int $pagesize
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function list(\App\Models\Company $company, $param = [], $pagesize = 10)
    {
        $query = \App\Models\Accounting\Cost::query()->where('cost.company_id', '=', $company->id)
            ->with('costItem')->with('voucher');

        //筛选会计区间
        isset($param['fiscal_period']) && $query = $query->where('cost.fiscal_period', date('Y-m-d', strtotime($param['fiscal_period'])));

        if (!empty($param['q_key']) && !empty($param['q_val'])) {

            //凭证状态
            $param['q_key'] == 'pzzt' && $param['q_val'] == 'done' && $query = $query->where('voucher_id', '!=', '0');
            $param['q_key'] == 'pzzt' && $param['q_val'] == 'todo' && $query = $query->where('voucher_id', '=', '0');

            //凭证状态
            $param['q_key'] == 'qdzt' && $param['q_val'] == 'finished' &&
            $query = $query->leftJoin('cost_item', 'cost_item.cost_id', '=', 'cost_id')->where('cost_item.fylx', '!=', '');
            $param['q_key'] == 'qdzt' && $param['q_val'] == 'unfinished' &&
            $query = $query->leftJoin('cost_item', 'cost_item.cost_id', '=', 'cost_id')->where('cost_item.fylx', '=', '');

        }

        $list = $query->with(['costItem', 'voucher'])->with('voucher')->paginate($pagesize);
        foreach ($list as &$item) {
            $item['voucher_num'] = !empty($item->voucher->voucher_num) ? '记-' . $item->voucher->voucher_num : '';
        }
        return $list;
    }

    /**
     * 组装新增参数
     * @param array $reqParam
     * @return array
     */
    public static function makeAddParam($reqParam)
    {
        $param = [
            'company_id' => $reqParam['company_id'],
            'fiscal_period' => $reqParam['fiscal_period'],
            'total_money' => 0,
        ];
        foreach ($reqParam['expenseTable'] as $item) {

            $param['total_money'] += $item['money'];

            $param['item'][] = [
                'company_id' => $reqParam['company_id'],
                'fyrq' => $item['fyrq'],
                'fylx' => $item['expenseVal'],
                'money' => $item['money'],
                'dw_id' => $item['unit_id'],
                'dw_name' => $item['unit'],
                'remark' => (string)$item['remarks'],
                //'account_id' => $item['account_id'],
                'cash' => $item['price'],
                'account_name' => $item['account_name'],
                'account_number' => $item['account_number'],
                'fiscal_period' => $reqParam['fiscal_period'],
            ];
        }

        return $param;
    }

    /**
     * 组装修改参数
     * @param $request
     * @return array
     */
    public static function makeUpdateParam($request)
    {
        $reqParam = $request->all();
        $param = [
            'id' => $reqParam['id'],
            'total_money' => $reqParam['expenseMoney'],
            'fiscal_period' => $reqParam['fiscal_period'],
            'company_id' => $reqParam['company_id'],
        ];

        foreach ($reqParam['expenseTable'] as $item) {
            $param['item'][] = [
                'id' => !empty($item['id']) ? $item['id'] : null,
                'fyrq' => $item['fyrq'],
                'fylx' => $item['expenseVal'],
                'money' => $item['money'],
                'dw_id' => $item['unit_id'],
                'dw_name' => $item['unit'],
                'remark' => strval($item['remarks']),
                'account_id' => 0,
                'cash' => $item['price'],
                'account_name' => $item['account_name'],
                'account_number' => $item['account_number'],
            ];
        }

        return $param;
    }

    /**
     * 删除单条费用记录
     * @param $id
     * @return bool
     * @throws \Exception
     */
    public static function delete($id)
    {
        try {
            $voucher_id = \App\Models\Accounting\Invoice::query()->whereKey($id)->value('voucher_id');

            //删除发票数据
            \App\Models\Accounting\Cost::query()->whereKey($id)->delete();
            CostItem::query()->where('cost_id', $id)->delete();

            //删除凭证
            !empty($voucher_id) && \App\Models\Accounting\Voucher::query()->where('id', $voucher_id)->delete();
            !empty($voucher_id) && \App\Models\Accounting\VoucherItem::query()->where('voucher_id', $voucher_id)->delete();

            return true;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * 删除单条费用记录
     * @param $id
     * @return bool
     * @throws \Exception
     */
    public static function deleteItem($id)
    {
        try {
            $cost_id = CostItem::query()->whereKey($id)->value('cost_id');
            CostItem::query()->whereKey($id)->delete();

            $count = CostItem::query()->where('cost_id', $cost_id)->count();
            $count == 0 && self::delete($cost_id);

            return true;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * 删除多条费用记录
     * @param array $ids
     * @return bool
     * @throws \Exception
     */
    public static function deleteAll($ids)
    {
        try {
            foreach ($ids as $id) {
                self::delete($id);
            }

            return true;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * 新增费用记录
     * @param $param
     * @return bool
     * @throws \Exception
     */
    public static function add($param)
    {
        try {

            $cost = \App\Models\Accounting\Cost::forceCreate([
                'company_id' => $param['company_id'],
                'total_money' => $param['total_money'],
                'fiscal_period' => $param['fiscal_period'],
            ]);

            foreach ($param['item'] as $item) {

                $account = AccountSubject::getKMbyNumberAndCompanyId($item['account_number'], $param['company_id']);

                CostItem::forceCreate([
                    'company_id' => $param['company_id'],
                    'cost_id' => $cost['id'],
                    'fyrq' => $item['fyrq'],
                    'fylx' => $item['fylx'],
                    'money' => $item['money'],
                    'dw_id' => $item['dw_id'],
                    'dw_name' => $item['dw_name'],
                    'cash' => intval($item['cash']),
                    'remark' => $item['remark'],
                    'account_id' => $account['id'],
                    'account_name' => $item['account_name'],
                    'account_number' => $item['account_number'],
                    'fiscal_period' => $cost['fiscal_period'],
                ]);
            }

            return true;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * 修改费用记录
     * @param $param
     * @return bool
     * @throws \Exception
     */
    public static function update($param)
    {
        try {

            $cost = \App\Models\Accounting\Cost::query()->whereKey($param['id'])->first();
            if (empty($cost))
                throw new \Exception("费用{$param['id']}信息不存在");

            \App\Models\Accounting\Cost::query()->whereKey($param['id'])->update([
                'total_money' => $param['total_money'],
                'fiscal_period' => $param['fiscal_period'],
            ]);

            //dd($param['item']);

            foreach ($param['item'] as $item) {

                empty($item['id']) && $item['id'] = null;
//                if (!empty($item['id'])) {
//                    $company_id = CostItem::whereKey($item['id'])->first()->company_id;
//                }

                $account = AccountSubject::getKMbyNumberAndCompanyId($item['account_number'], $param['company_id']);

                CostItem::updateOrCreate(['id' => $item['id']], [
                    'cost_id' => $param['id'],
                    'fyrq' => $item['fyrq'],
                    'fylx' => $item['fylx'],
                    'money' => $item['money'],
                    'dw_id' => $item['dw_id'],
                    'dw_name' => $item['dw_name'],
                    'remark' => $item['remark'],
                    'account_id' => $account['id'],
                    //'account_id' => $item['account_id'],
                    'cash' => intval($item['cash']),
                    'account_name' => $item['account_name'],
                    'account_number' => $item['account_number'],
                    'fiscal_period' => $param['fiscal_period'],
                ]);
            }

            return true;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * excel 导入 费用信息
     * @param $datas
     * @throws \Exception
     */
    public static function excelAdd($datas)
    {

        unset($datas[0]);

        foreach ($datas as $data) {
            $data = self::makeExcelParam($data);

            $company_id = Company::sessionCompany()->id;
            $dw = BussinessData::getCompanyByName($data['dw_name']);
            $param = [
                'company_id' => $company_id,
                'voucher_id' => 0,
                'total_money' => $data['money'],
                'item' => [
                    [
                        'fyrq' => $data['fyrq'],
                        'fylx' => $data['fylx'],
                        'money' => $data['money'],
                        'dw_id' => $dw['id'],
                        'dw_name' => $data['dw_name'],
                        'cash' => $data['cash'],
                        'remark' => '',
                    ],
                ],
            ];

            self::add($param);
        }

    }

    /**
     * excel 数据转换为 新增参数
     * @param $data
     * @return array
     */
    private static function makeExcelParam($data)
    {
        return [
            'dw_name' => $data[4],
            'fylx' => $data[1],
            'money' => $data[2],
            'cash' => $data[3] == '是' ? $data[4] : 0,
            'fyrq' => date('Y-m-d', strtotime($data[0])),
        ];
    }

}