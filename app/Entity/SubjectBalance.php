<?php
/**
 * Created by PhpStorm.
 * User: 24115
 * Date: 2018/7/27
 * Time: 16:04
 */

namespace App\Entity;

use App\Models\AccountBook\SubjectBalance as SubjectBalanceModel;
use App\Models\AccountBook\SubsidiaryLedger as SubLedgerModel;
use App\Models\Accounting\AccountClose as AccountCloseModel;
use App\Models\Accounting\Voucher as VoucherModel;
use App\Models\Accounting\VoucherItem;
use App\Models\AccountSubject;
use App\Models\Company as CompanyModel;
use Carbon\Carbon;
use DB;

/**
 * 科目余额
 * Class SubjectBalance
 * @package App\Entity
 */
class SubjectBalance
{
    const ACCOUNT_OPEN = 0; //未结账
    const ACCOUNT_CLOSED = 1; //已结账

    private static $pids = [];
    private static $treeList = []; //格式化 科目列表

    /**
     * 科目余额列表
     * @param $request
     * @return mixed
     */
    public static function subjectBalanceList($request)
    {
        $data = $request->all();
        unset($data['export1']);
        if (isset($data['endkjqj'])) $endkjqj = date("Y-m-d", strtotime("+1 month", strtotime($data['endkjqj'])));
        if (isset($data['startkjqj'])) $startkjqj = Carbon::parse($data['startkjqj'])->toDateString();
        $query = SubjectBalanceModel::where('company_id', Company::sessionCompany()->id)->orderBy('id', 'asc');
        if (isset($data['startkjqj'])) $query->where('fiscal_period', '>=', $startkjqj);
        if (isset($data['endkjqj'])) $query->where('fiscal_period', '<', $endkjqj);
        if (!isset($data['startkjqj']) || !isset($data['endkjqj'])) $query->where('fiscal_period', Period::currentPeriod());;
        $list = $query->get()->groupBy('account_subject_number');
        $result = [];
        $i = 0;
        //科目余额表数据重组
        foreach ($list as $key => $value) {
            $qcye_j = $value->first()->qcye_j;
            $qcye_d = $value->first()->qcye_d;
            $bqfse_j = $value->sum('bqfse_j');
            $bqfse_d = $value->sum('bqfse_d');
            $bn = SubjectBalanceModel::where('account_subject_number', $key)
                ->where('company_id', Company::sessionCompany()->id)
                ->where('fiscal_period', 'like', substr(Period::currentPeriod(), 0, 4) . '%')->get();
            //判断启用时间是否为当前年份-计算本年累计发生额
            $isPeriodYear = substr(Period::currentPeriod(), 0, 4) == Company::sessionCompany()->used_year ? true : false;
            $bnljfse_j = $isPeriodYear ? $bn->sum('bqfse_j') + $bn->first()->bnljfse_j : $bn->sum('bqfse_j');
            $bnljfse_d = $isPeriodYear ? $bn->sum('bqfse_d') + $bn->first()->bnljfse_d : $bn->sum('bqfse_d');
            $result[$i] = [
                'subject_pid' => $value->first()->subject_pid,
                'level' => $value->first()->level,
                'account_subject_id' => $value->first()->account_subject_id,
                'account_subject_number' => $key,
                'account_subject_name' => str_repeat('　　', $value->first()->level) . $value->first()->account_subject_name,
                'qcye_j' => $qcye_j ? number_format($qcye_j, 2, '.', '') : '',
                'qcye_d' => $qcye_d ? number_format($qcye_d, 2, '.', '') : '',
                'bqfse_j' => $bqfse_j ? number_format($bqfse_j, 2, '.', '') : '',
                'bqfse_d' => $bqfse_d ? number_format($bqfse_d, 2, '.', '') : '',
                'bnljfse_j' => $bnljfse_j ? number_format($bnljfse_j, 2, '.', '') : '',
                'bnljfse_d' => $bnljfse_d ? number_format($bnljfse_d, 2, '.', '') : '',
            ];
            if ($value->first()->balance_direction == '借') $result[$i]['qmye_j'] = number_format($qcye_j + $bqfse_j - $bqfse_d, 2);
            if ($value->first()->balance_direction == '贷') $result[$i]['qmye_d'] = number_format($qcye_d + $bqfse_d - $bqfse_j, 2);
            $i++;
        }
        $result = self::tree($result);
        return ['result' => $result, 'qj_options' => SubLedgerModel::Get_Month_Arr()];
    }

    /**
     * 格式化数据
     * @param $data
     * @param int $subject_pid
     * @param int $level
     * @return array
     */
    public static function tree($data, $subject_pid = 0, $level = 0)
    {
        foreach ($data as $key => $value) {
            if ($value['subject_pid'] == $subject_pid) {
                $value['level'] = $level;
                self::$treeList[] = $value;
                unset($data[$key]);
                self::tree($data, $value['account_subject_id'], $level + 1);
            }
        }
        return self::$treeList;
    }

    /**
     * 初始科目余额更新
     * @param $request
     * @return bool
     */
    public static function subjectBalanceEdit($request)
    {
        $data = $request->all();
        unset($data['_token']);
        $data['company_id'] = Company::sessionCompany()->id;
        if (!$data['id']) return false;
        DB::beginTransaction();
        try {
            $old = SubjectBalanceModel::find($data['id']);
            $new_qcye_j = $data['qcye_j'] - $old->qcye_j;
            $new_qcye_d = $data['qcye_d'] - $old->qcye_d;
            $new_bnljfse_j = $data['bnljfse_j'] - $old->bnljfse_j;
            $new_bnljfse_d = $data['bnljfse_d'] - $old->bnljfse_d;
            $old->update($data);
            //所有父级同步更新
            self::$pids = [];
            $pids = self::subjectParent($old);
            SubjectBalanceModel::whereIn('id', $pids)->update(['qcye_j' => DB::raw('qcye_j +' . $new_qcye_j), 'bnljfse_j' => DB::raw('bnljfse_j +' . $new_bnljfse_j)]);
            SubjectBalanceModel::whereIn('id', $pids)->update(['qcye_d' => DB::raw('qcye_d +' . $new_qcye_d), 'bnljfse_d' => DB::raw('bnljfse_d +' . $new_bnljfse_d)]);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
    }

    /**
     * 科目余额修改for生成凭证
     */
    public static function subjectBalanceCreateForVoucher($voucher)
    {
        foreach ($voucher->voucherItem as $key => $v) {
            $old = SubjectBalanceModel::where('company_id', $v->company_id)->where('fiscal_period', $v->fiscal_period)
                ->where('account_subject_id', $v->kuaijikemu_id)->first();

            $data['bqfse_j'] = $old->bqfse_j + $v->debit_money;
            $data['bqfse_d'] = $old->bqfse_d + $v->credit_money;
            $old->update($data);
            //所有父级同步更新
            self::$pids = [];
            $pids = self::subjectParent($old);
            $v->debit_mone = number_format($v->debit_money, 2, '.', '');
            $v->credit_money = number_format($v->credit_money, 2, '.', '');
            SubjectBalanceModel::whereIn('id', $pids)->increment('bqfse_j', $v->debit_money);
            SubjectBalanceModel::whereIn('id', $pids)->increment('bqfse_d', $v->credit_money);
        }
    }

    /**
     * 科目余额修改for修改凭证 弃用
     */
    public static function subjectBalanceEditForVoucher($voucher)
    {
        foreach ($voucher->voucherItem as $key => $v) {
            $old = SubjectBalanceModel::where('company_id', $v->company_id)->where('fiscal_period', $v->fiscal_period)
                ->where('account_subject_id', $v->kuaijikemu_id)->first();

            $data['bqfse_j'] = $v->debit_money;
            $data['bqfse_d'] = $v->credit_money;
            $old->update($data);
            //所有父级同步更新
            $pids = self::subjectParent($old);
            $v->debit_mone = number_format($v->debit_money, 2, '.', '');
            $v->credit_money = number_format($v->credit_money, 2, '.', '');
            SubjectBalanceModel::whereIn('id', $pids)->increment('bqfse_j', $v->debit_money - $old->bqfse_j);
            SubjectBalanceModel::whereIn('id', $pids)->increment('bqfse_d', $v->credit_money - $old->bqfse_d);
        }
    }

    /**
     * 科目余额修改for删除凭证
     */
    public static function subjectBalanceDelForVoucher($voucher)
    {
        foreach ($voucher->voucherItem as $key => $v) {

            $query = SubjectBalanceModel::where('company_id', $v->company_id)->where('fiscal_period', $v->fiscal_period)
                ->where('account_subject_id', $v->kuaijikemu_id);
            $old = $query->first();

            $v->debit_mone = number_format($v->debit_money, 2, '.', '');
            $v->credit_money = number_format($v->credit_money, 2, '.', '');

            $query->decrement('bqfse_j', $v->debit_money);
            $query->decrement('bqfse_d', $v->credit_money);

//            $data['bqfse_j'] = $old->bqfse_j - $v->debit_money;
//            $data['bqfse_d'] = $old->bqfse_d - $v->credit_money;
//            $old->update($data);
            //所有父级同步更新
            self::$pids = [];
            $pids = self::subjectParent($old);
            SubjectBalanceModel::whereIn('id', $pids)->decrement('bqfse_j', $v->debit_money);
            SubjectBalanceModel::whereIn('id', $pids)->decrement('bqfse_d', $v->credit_money);
        }
    }

    /**
     * 科目余额初始化
     * @param $company_id
     * @param $fiscal_period
     * @return bool
     * @throws \Exception
     */
    public static function subjectBalanceNew($company, $fiscal_period)
    {
        $first = SubjectBalanceModel::where('company_id', $company->id)->where('fiscal_period', $fiscal_period)->first();
        if ($first) return true;

        //获取公司科目并格式化
        $accountSubjects = self::getAccountSubject($company->id);
        DB::beginTransaction();
        try {
            //循环插入 初始化
            foreach ($accountSubjects as $v) {
                $data['account_subject_id'] = $v['id'];
                $data['company_id'] = $v['company_id'];
                $data['account_subject_number'] = $v['number'];
                $data['subject_pid'] = $v['pid'];
                $data['account_subject_name'] = $v['name'];
                $data['balance_direction'] = $v['balance_direction'];
                $data['type'] = $v['type'];
                $data['level'] = $v['level'];
                $data['fiscal_period'] = $fiscal_period;
                SubjectBalanceModel::create($data);
            }
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
    }

    /**
     * 获取公司科目并格式化
     * @param $company_id
     * @return array
     */
    private static function getAccountSubject($company_id)
    {
        $accountSubjects = AccountSubject::where('company_id', $company_id)->where('status', AccountSubject::USED)->get();
        $accountSubjects = AccountSubject::tree($accountSubjects);
        return $accountSubjects;
    }

    /**
     * 科目余额数据初始化-财务
     * @return mixed
     */
    public static function subjectBalanceFirst()
    {
        $list = SubjectBalanceModel::with('subjectBalanceItem')
            ->where('company_id', Company::sessionCompany()->id)
            ->orderBy('subject_balances.id', 'asc')
            ->get()->groupBy('fiscal_period')->first();
        //判断是否已有凭证
        $subject_ids = $list->pluck('account_subject_id')->all();
        $hasVoucher = VoucherItem::whereIn('kuaijikemu_id', $subject_ids)->first();
        $list->each(function ($item) use ($hasVoucher) {
            $item->isHasVoucher = $hasVoucher ? true : false;
            $item->subjectBalanceItem->first() ? $item->qcye_canedit = false : $item->qcye_canedit = true;
            $item->account_subject_name = str_repeat('　　', $item->level) . $item->account_subject_name;
            $item->qcye_jTop = true;
            $item->qcye_jSelect = false;
            $item->qcye_dTop = true;
            $item->qcye_dSelect = false;
            $item->editor = true;
            $item->keep = false;
        });
        $qcye_j_total = $list->where('level', 0)->sum('qcye_j');
        $qcye_d_total = $list->where('level', 0)->sum('qcye_d');
        $bnljfse_j_total = $list->where('level', 0)->sum('bnljfse_j');
        $bnljfse_d_total = $list->where('level', 0)->sum('bnljfse_d');
        $list = self::tree($list);
        return [
            'qcye_j_total' => number_format($qcye_j_total, 2, '.', ''),
            'qcye_d_total' => number_format($qcye_d_total, 2, '.', ''),
            'bnljfse_j_total' => number_format($bnljfse_j_total, 2, '.', ''),
            'bnljfse_d_total' => number_format($bnljfse_d_total, 2, '.', ''),
            'list' => $list,
        ];
    }

    /**
     * 获取当前科目余额
     * @param $company_id 公司id
     * @param $number 科目编码
     * @param string $fiscal_period 会计编码
     * @return mixed
     */
    public static function get($company_id, $number, $fiscal_period = '')
    {

        $number = abs($number);
        $account_subject = AccountSubject::query()->where('company_id', $company_id)
            ->where('number', $number)
            ->first();

        if (empty($account_subject))
            return 0;

        $column = $account_subject['balance_direction'] == '借' ? 'qmye_j' : 'qmye_d';

        $fiscal_period == '' && $fiscal_period = Period::currentPeriod();
        return $ballance = SubjectBalanceModel::query()->where('company_id', $company_id)
            ->where('fiscal_period', $fiscal_period)
            ->where('account_subject_number', 'like', $number . '%')
            ->value($column);
    }

    /**
     * 当前科目余额数据 ：期初余额 + 本期发生额
     * @param $company_id
     * @param $number
     * @param string $fiscal_period
     */
    public static function activeGet($company_id, $number, $fiscal_period = '')
    {
        $number = abs($number);
        $account_subject = AccountSubject::query()->where('company_id', $company_id)
            ->where('number', $number)
            ->first();

        $fiscal_period == '' && $fiscal_period = Period::currentPeriod();
        $ballance = SubjectBalanceModel::query()->where('company_id', $company_id)
            ->where('fiscal_period', $fiscal_period)
            ->where('account_subject_number', 'like', $number . '%')
            ->first();

        if (empty($account_subject) || empty($ballance))
            return 0;

        $ret = 0.00;
        if ($account_subject['balance_direction'] == '借') {
            $ret = $ballance['qcye_j'] + $ballance['bqfse_j'] - $ballance['bqfse_d'];
        } else {
            $ret = $ballance['qcye_d'] + $ballance['bqfse_d'] - $ballance['bqfse_j'];
        }
        //$column = $account_subject['balance_direction'] == '借' ? 'qmye_j' : 'qmye_d';
        return $ret;
    }

    /**
     * 资产负债表 年初余额
     * @param $company_id
     * @param $number
     */
    public static function beginOfYear($company_id, $number)
    {
        //获取年初会计期间
        $firstOfYear = AccountCloseModel::query()->where('company_id', $company_id)
            ->where('fiscal_period', 'like', date('Y-') . '%')
            ->orderBy('fiscal_period', 'asc')
            ->value('fiscal_period');

        $balance = SubjectBalanceModel::query()->where('company_id', $company_id)
            ->where('fiscal_period', $firstOfYear)
            ->where('account_subject_number', $number)
            ->first();

        $ret = $balance['balance_direction'] == '借' ? $balance['qcye_j'] : $balance['qcye_d'];

        return $ret;
    }

    /**
     *
     * @param $company_id
     * @param $number
     */
    public static function getSumOfbeginOfYear($company_id, $number)
    {
        $sum = 0;
        if (empty($number))
            return $sum;

        if (is_string($number))
            $sum = self::beginOfYear($company_id, $number);

        if (is_array($number)) {
            foreach ($number as $item) {
                $tmp = self::beginOfYear($company_id, $item);
                strpos($item, '-') !== false && $tmp = -$tmp;
                $sum += $tmp;
            }
        }

        return $sum;
    }

    /**
     * 计算多个科目余额求和
     * @param mixed $number 类似 ['1601', '-1602']
     * @param $company_id
     * @param $fiscal_period
     * @return int|mixed
     */
    public static function getSum($number, $company_id, $fiscal_period)
    {
        $sum = 0;
        if (empty($number))
            return $sum;

        if (is_string($number))
            $sum = self::activeGet($company_id, $number, $fiscal_period);

        if (is_array($number)) {
            foreach ($number as $item) {
                $tmp = self::activeGet($company_id, $item, $fiscal_period);
                strpos($item, '-') !== false && $tmp = -$tmp;
                $sum += $tmp;
            }
        }

        return $sum;
    }

    /**
     * 新增会计科目，科目余额表改变
     * @param $data
     */
    public static function subjectBalanceCreate($data)
    {
        if (!self::isClosed(Period::currentPeriod())) {
            $new['fiscal_period'] = Period::currentPeriod();
            $new['account_subject_id'] = $data->id;
            $new['account_subject_number'] = $data->number;
            $new['account_subject_name'] = $data->name;
            $new['type'] = $data->type;
            $new['company_id'] = $data->company_id;
            $new['level'] = $data->level;
            $new['subject_pid'] = $data->pid;
            $new['balance_direction'] = $data->balance_direction;
            //判断是否为第一个下级
            $count = self::isBrother($data->pid)->count();
            if ($count == 0) {
                $parent = SubjectBalanceModel::where('account_subject_id', $new['subject_pid'])
                    ->where('fiscal_period', Period::currentPeriod())
                    ->where('company_id', $new['company_id'])->first();
                $new['qcye_j'] = $parent ? $parent->qcye_j : 0;
                $new['qcye_d'] = $parent ? $parent->qcye_d : 0;
                $new['bqfse_j'] = $parent ? $parent->bqfse_j : 0;
                $new['bqfse_d'] = $parent ? $parent->bqfse_d : 0;
                $new['bnljfse_j'] = $parent ? $parent->bnljfse_j : 0;
                $new['bnljfse_d'] = $parent ? $parent->bnljfse_d : 0;
                $new['qmye_j'] = $parent ? $parent->qmye_j : 0;
                $new['qmye_d'] = $parent ? $parent->qmye_d : 0;
                SubjectBalanceModel::create($new);
                //已有凭证转移到新科目
                $voucher_item = VoucherItem::where('company_id', Company::sessionCompany()->id)
                    ->where('kuaijikemu_id', $parent->account_subject_id)
                    ->where('fiscal_period', $new['fiscal_period'])
                    ->get();
                if ($voucher_item) {
                    VoucherItem::where('company_id', Company::sessionCompany()->id)
                        ->where('kuaijikemu_id', $parent->account_subject_id)
                        ->where('fiscal_period', $new['fiscal_period'])
                        ->update([
                            'kuaijikemu_id' => $new['account_subject_id'],
                            'kuaijikemu' => $new['account_subject_name'],
                            'kuaijibianhao' => $new['account_subject_number'],
                        ]);
                }
            } else if ($count > 0) {
                SubjectBalanceModel::create($new);
            }
        }
    }

    /**
     * 是否已结账
     * @param null $fiscal_period
     * @return bool
     */
    public static function isClosed($fiscal_period = null)
    {
        $query = SubjectBalanceModel::where('company_id', Company::sessionCompany()->id)
            ->where('account_closed', SubjectBalance::ACCOUNT_CLOSED);
        if ($fiscal_period) $query->where('fiscal_period', $fiscal_period);
        $condition = $query->first();
        return $condition ? true : false;
    }

    /**
     * 判断是否有同级科目
     * @param $pid
     * @return mixed
     */
    public static function isBrother($pid)
    {
        $result = SubjectBalanceModel::where('company_id', Company::sessionCompany()->id)
            ->where('fiscal_period', Period::currentPeriod())
            ->where('subject_pid', $pid)
            ->get();
        return $result;
    }

    /**
     * 判断某个科目余额是否有子科目余额
     * @param $id
     * @param $company_id
     * @param $fiscal_period
     * @return bool
     */
    public static function isChild($id, $company_id, $fiscal_period)
    {
        $result = SubjectBalanceModel::where('company_id', $company_id)
            ->where('fiscal_period', $fiscal_period)
            ->where('subject_pid', $id)
            ->count();
        return $result != 0;
    }

    /**
     * 查询所有父级科目余额
     * @param SubjectBalanceModel $subjectbalance
     * @return array
     */
    public static function subjectParent(SubjectBalanceModel $subjectbalance)
    {
        $parent = $subjectbalance->subjectBalanceParent;
        if ($parent) {
            self::$pids[] = $parent->id;
            self::subjectParent($parent);
        }
        return self::$pids;
    }

    /**
     * 检查当前账期科目余额表是否已做过初始化
     *
     * 未初始化会导致添加不了凭证
     *
     * @return bool
     * @throws \Exception
     */
    public static function checkPeriodSubjectBalance()
    {
        $company_id = Company::sessionCompany()->id;
        $fiscal_period = Period::currentPeriod();

        //\Log::info($company_id);
        //\Log::info($fiscal_period);

        $list = SubjectBalanceModel::query()->where('company_id', $company_id)->where('fiscal_period', $fiscal_period)->get();
        if (count($list) == 0) {
            //创建当期科目余额表
            //$model = new \App\Models\Company();
            //$model->id = $company_id;
            $model = (object)'';
            $model->id = $company_id;
            $result = self::subjectBalanceNew($model, $fiscal_period);
            return $result;
        } else {
            return false;
        }
    }

    /**
     * 结转新增科目余额
     * @param $company_id
     * @param $fiscal_period
     */
    public static function createForAccountClose($company_id, $fiscal_period)
    {
        $new_fiscal_period = date("Y-m-d", strtotime("+1 month", strtotime($fiscal_period)));
        $last_fiscal_period = SubjectBalanceModel::where('company_id', $company_id)->where('fiscal_period', $fiscal_period)->get();
        foreach ($last_fiscal_period as $v) {
            $data['company_id'] = $company_id;
            $data['account_subject_id'] = $v->account_subject_id;
            $data['account_subject_number'] = $v->account_subject_number;
            $data['subject_pid'] = $v->subject_pid;
            $data['account_subject_name'] = $v->account_subject_name;
            $data['qcye_j'] = $v->qmye_j ? $v->qmye_j : 0;
            $data['qcye_d'] = $v->qmye_d ? $v->qmye_d : 0;
            $data['balance_direction'] = $v->balance_direction;
            $data['type'] = $v->type;
            $data['level'] = $v->level;
            $data['fiscal_period'] = $new_fiscal_period;
            SubjectBalanceModel::updateOrCreate(['company_id' => $company_id, 'fiscal_period' => $new_fiscal_period, 'account_subject_id' => $v->account_subject_id], $data);
        }
    }

    /**
     * 判断是否可以导入科目余额初始数据
     * @param $company_id
     * @param $fiscal_period
     * @return bool 1-可以导入 0-无法导入
     */
    public static function checkInit($company_id, $fiscal_period)
    {
        //1.是否有凭证生成
        //2.是否为初始会计期间
        $Voucher = VoucherModel::query()->where('company_id', $company_id)->count();
        return $fiscal_period == Period::first($company_id) && $Voucher == 0;
    }

    /**
     * 校验导入的excel数据格式
     * @param array $data
     * @return bool
     */
    public static function checkExcelFormat(Array $data)
    {
        $tpl = [
            0 => [0 => '科目编码', 1 => '科目名称', 2 => '期初余额', 3 => null, 4 => '本年累计发生额', 5 => null,],
            1 => [0 => null, 1 => null, 2 => '借方', 3 => '贷方', 4 => '借方', 5 => '贷方',],
        ];

        $data = array_sort($data);

        foreach ($tpl as $key => $value) {
            $data[$key] = array_sort($data[$key]);
            foreach ($value as $key2 => $val2) {

                if ($val2 == null && $data[$key][$key2] == null)
                    continue;

                if (!isset($data[$key][$key2]) || strval($data[$key][$key2]) != strval($val2)) {
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * 导入初始科目余额数据
     * @param $company_id
     * @param $data
     * @throws \Exception
     */
    public static function import($company_id, $data)
    {
        //检测数据格式是否正确
        if (!self::checkExcelFormat($data))
            throw new \Exception('数据格式不正确');

        //清空原来的数据
        AccountSubject::query()->where('company_id', $company_id)->delete();
        SubjectBalanceModel::query()->where('company_id', $company_id)->delete();

        $company = CompanyModel::query()->whereKey($company_id)->firstOrFail();
        $level = explode(',', $company['subject_length']);
        list($level1, $level2, $level3) = $level;
        $configData = array_column(AccountSubject::configData(), null, 'number');

        //设置层级
        $tmpData = array_column($data, '0');
        $levelSet = AccountSubject::getLevelSetByExcelData($tmpData);
        if ($levelSet === false)
            throw new \Exception('数据格式有误，请检查excel数据格式');
        CompanyModel::query()->whereKey($company_id)->update(['level_set' => $levelSet, 'subject_length' => $levelSet]);

        foreach ($data as $key => $row) {

            /**
             * @var $row 格式:
             * [
             * 0 => 5603.0,
             * 1 => '财务费用',
             * 2 => NULL, //期初余额借方
             * 3 => NULL, //期初余额贷方
             * 4 => NULL, //本年累计发生额借方
             * 5 => NULL, //本年累计发生额贷方
             * ]
             */
            $number = strval($row[0]);
            $account_name = $row[1];
            $update = [
                'qcye_j' => floatval($row[2]),
                'qcye_d' => floatval($row[3]),
                'bnljfse_j' => floatval($row[4]),
                'bnljfse_d' => floatval($row[5]),
            ];

            if ($key == 0 || $key == 1)
                continue;

            if (empty($number) && empty($account_name))
                break;

            if (strlen($number) == 4 && empty($configData[$number]))
                continue;

            $pid = intval(AccountSubject::query()->where('company_id', $company_id)
                ->where('number', AccountSubject::getParentNumber($number))
                ->value('id'));


            //新增会计科目数据
            $account = AccountSubject::query()->updateOrCreate(['company_id' => $company_id, 'number' => $number,],
                [
                    'number' => $number,
                    'name' => $account_name,
                    'type' => AccountSubject::getType($number),
                    'company_id' => $company_id,
                    'pid' => $pid,
                    'status' => AccountSubject::USED,
                    'level' => AccountSubject::getCompanyLevel($number, $company_id),
                    'balance_direction' => AccountSubject::getDirection($number),
                ]);

            //新增科目余额数据
            SubjectBalanceModel::query()->updateOrCreate(['company_id' => $company_id, 'account_subject_number' => $number,],
                [
                    'company_id' => $company_id,
                    'account_subject_id' => $account['id'],
                    'account_subject_number' => $account['number'],
                    'subject_pid' => $account['pid'],
                    'account_subject_name' => $account['name'],
                    'qcye_j' => $update['qcye_j'],
                    'qcye_d' => $update['qcye_d'],
                    'bnljfse_j' => $update['bnljfse_j'],
                    'bnljfse_d' => $update['bnljfse_d'],
                    'fiscal_period' => Period::first($company_id),
                    'balance_direction' => $account['balance_direction'],
                    'type' => $account['type'],
                    'level' => intval($account['level']),
                    'account_closed' => SubjectBalanceModel::ACCOUNT_CLOSED_NO,
                ]);
        }
    }

}