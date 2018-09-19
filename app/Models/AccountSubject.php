<?php

namespace App\Models;

use App\Entity\Company;
use App\Entity\SubjectBalance;
use App\Models\Company as CompanyModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Validator;

class AccountSubject extends Model
{
    protected $guarded = ['items'];

    static private $treeList = [];  //格式化 存放科目列表

    const FREEZR = 0;
    const USED = 1;

    //会计科目冗余字段配置
    const ACCOUNT_NUMBER = [
        'account_subjects' => ['number'], //表名 => 字段
        'asset' => ['yzkm', 'ljzjkm', 'cbfykm'],
        'cost_item' => ['account_number',],
        'fund' => ['ywlx_num'],
        'fund_item' => ['dw_num'],
        'invoice_item' => ['account_number'],
        'ledgers' => ['account_subject_number'],
        'subject_balances' => ['account_subject_number'],
        'tax_config' => ['debit_number', 'credit_number'],
        'voucher_item' => ['kuaijibianhao'],
    ];

    public static function getStatus()
    {
        return [
            self::FREEZR => '已冻结',
            self::USED => '已启用',
        ];
    }

    /**
     *新增科目
     * @param $input
     * @return bool|string
     */
    public static function createSubject($data)
    {
        new Company();
        $subjectParent = AccountSubject::find($data['pid']);
        if ($subjectParent->status == AccountSubject::FREEZR) {
            return $subjectParent->name . '已冻结，无法增加下级科目';
        }
        $rules = [
            'name' => 'required',
            'pid' => 'required',
            'level' => 'required',
        ];
        $messages = [
            'name.required' => '请输入会计科目名称',
        ];
        $validator = Validator::make($data, $rules, $messages, []);
        if ($validator->fails()) {
            return $validator->messages()->first();
        }
        //科目层级判断
        if ($data['level'] >= Company::$level_set) {
            return '科目层级已达最大值，无法新增下级';
        }
        //去重判断
        $pre = self::isExist(Company::$company->id, $data['pid'], $data['level'], $data['name']);
        if ($pre) {
            return '该科目已存在';
        }
        $max = AccountSubject::where('company_id', Company::$company->id)->where('pid', $data['pid'])->orderBy('number', 'desc')->first();
        $sub_level = Company::$level_detail[(int)$data['level']];
        if ($max) {
            if (substr($max->number, -$sub_level) == str_repeat(9, $sub_level)) {
                return '科目编码超出范围';
            } else {
                $data['number'] = $max->number + 1;
            }
        } else {
            $data['number'] = $subjectParent->number . str_repeat(0, $sub_level - 1) . '1';
        }
        $data['company_id'] = Company::$company->id;
        \DB::beginTransaction();
        try {
            $result = AccountSubject::create($data);
            SubjectBalance::subjectBalanceCreate($result);
            \DB::commit();
            return true;
        } catch (\Exception $e) {
            \DB::rollBack();
            return '操作失败';
        }
    }

    /**
     *格式化数据
     * @param $data
     * @param int $pid
     * @param int $level
     * @return array
     */
    public static function tree($data, $pid = 0, $level = 0)
    {
        foreach ($data as $key => $value) {
            if ($value['pid'] == $pid) {
                $value['level'] = $level;
                self::$treeList[] = $value->toarray();
                unset($data[$key]);
                self::tree($data, $value['id'], $level + 1);
            }
        }
        return self::$treeList;
    }

    /**
     * 科目列表
     * @param $request
     * @return array
     */
    public static function subjectList($request)
    {
        new Company();
        $query = AccountSubject::where('company_id', Company::$company->id);
        $query->where(function ($row) use ($request) {
            $row->where('number', 'like', '%' . $request->search . '%')->orWhere('name', 'like', '%' . $request->search . '%');
        });
        if ($request->ajax()) {
            $data = $query->where('status', AccountSubject::USED)->get();
        } else {
            $data = $query->get();
        }
        $list = AccountSubject::tree($data);
        $old_search = $request->search;
        return ['list' => $list, 'old_search' => $old_search];
    }

    /**
     * 科目编辑
     * @param $data
     * @param $id
     * @return bool|string
     */
    public static function editSubject($data, $id)
    {
        new Company();
        $data = $data->input();
        $rules = [
            'name' => 'required',
            'balance_direction' => 'required',
            'status' => 'required',
        ];
        $messages = [
            'name.required' => '请输入会计科目名称',
        ];
        $validator = Validator::make($data, $rules, $messages, []);
        if ($validator->fails()) {
            return $validator->messages()->first();
        }
        $isExist = self::isExist(Company::$company->id, $data['pid'], $data['level'], $data['name'], $id);
        if ($isExist) return '科目名称已存在';
        $subject = AccountSubject::find($id);
        return $subject->update($data) ? true : '操作失败';
    }

    /**
     * 科目禁用
     * @param $request
     * @return bool|string
     */
    public static function freeze($request)
    {
        $subject = AccountSubject::find($request->id);
        if (!$subject) return '操作失败';
        if ($request->status == AccountSubject::FREEZR) {
            $subjectchild = self::subjectChild($request->id, true);
            if (!$subjectchild->isEmpty()) return '请先冻结子科目';
        } else if ($request->status == AccountSubject::USED) {
            $subjectparent = self::subjectParent($subject->pid, true);
            if ($subject->level !== 0) {
                if ($subjectparent->isEmpty()) return '请先解冻上级科目';
            }
        }
        if ($request->status == $subject->status) {
            return true;
        } else {
            $subject->status = $request->status;
            $subject->save();
            return true;
        }
    }

    /**
     * 去重
     * @param $company_id
     * @param $pid
     * @param $level
     * @param null $name
     * @param null $id
     * @return bool
     */
    public static function isExist($company_id, $pid, $level, $name = null, $id = null)
    {
        $query = AccountSubject::where('company_id', $company_id)->where('pid', $pid)->where('level', $level);
        $query->where(function ($row) use ($name) {
            if ($name) $row->where('name', $name);
        });
        if ($id) $query->where('id', '<>', $id);
        $result = $query->first();
        return $result ? true : false;
    }

    /**
     * 查询子科目
     * @param $id
     * @param bool $freeze
     * @return mixed
     */
    public static function subjectChild($id, $freeze = false)
    {
        $query = AccountSubject::where('pid', $id);
        if ($freeze) $query->where('status', AccountSubject::USED);
        $result = $query->get();
        return $result;
    }

    /**
     * 查询父级科目
     * @param $pid
     * @param bool $freeze
     * @return mixed
     */
    public static function subjectParent($pid, $freeze = false)
    {
        $query = AccountSubject::where('id', $pid);
        if ($freeze) $query->where('status', AccountSubject::USED);
        $result = $query->get();
        return $result;
    }

    /**
     * 初始化公司科目
     * @param $company_id
     * @return bool
     * @throws \Exception
     */
    public static function companySubjects($company_id, $accounting_system)
    {
        $subjects = config('accountingrules.'.CompanyModel::getAccountingRules()[$accounting_system]);
        //判断是否已经初始化
        $first = AccountSubject::where('company_id', $company_id)->first();
        if ($first) return true;
        \DB::beginTransaction();
        try {
            self::subjectBegin($subjects, $company_id);
            \DB::commit();
            return true;
        } catch (\Exception $e) {
            \DB::rollBack();
            return false;
        }
    }

    /**
     * 递归添加科目
     * @param $subjects
     * @param $company_id
     * @param int $pid
     */
    private static function subjectBegin($subjects, $company_id, $pid = 0, $level = 0)
    {
        foreach ($subjects as $value) {
            $value['company_id'] = $company_id;
            $value['pid'] = $pid;
            $value['level'] = $level;
            $result = AccountSubject::create($value);
            $id = $result->id;
            $level = $result->level;
            if (!empty($value['items'])) {
                self::subjectBegin($value['items'], $company_id, $id, $level + 1);
            }
        }
    }

    /**
     * 通过科目ID取 科目编码和名称
     * @param $id
     * @return string
     */
    public static function getAccountSubjectStrById($id)
    {
        $info = AccountSubject::query()->where('id', $id)->first();
        if ($info) {
            $str = $info->number . ' ' . $info->name;
        } else {
            $str = '--';
        }

        return $str;
    }

    /**
     * 配置里的会计科信息
     * @return array
     */
    public static function configData()
    {
        return self::init(config('accountsubject'));
    }

    /**
     * 配置里的会计科信息
     * 将子项拿出来
     * @param $data
     * @return array
     */
    private static function init($data)
    {
        $ret = [];
        foreach ($data as $datum) {
            if (empty($datum['items'])) {
                $ret[] = $datum;
            } else {
                $item = $datum['items'];
                $datum['items'] = [];
                $ret[] = $datum;
                $ret = array_merge($ret, self::init($item));
            }
        }
        return $ret;
    }

    /**
     * 获取直接父级科目编码
     * @param $number
     * @return string
     */
    public static function getParentNumber($number)
    {
        $ret = '';
        $data = config('accountsubject');
        $data = self::init($data);

        foreach ($data as $datum) {
            if (strpos($number, $datum['number']) === 0 && strlen($ret) < strlen($datum['number']) && $number != $datum['number']) {
                $ret = $datum['number'];
            }
        }

        if ($ret == $number)
            $ret = '';

        return $ret;
    }

    /**
     * 获取借贷方向
     * @param string $number 科目编码
     */
    public static function getDirection($number)
    {
        $configData = array_column(AccountSubject::configData(), null, 'number');
        //dd($configData[$number]['balance_direction']);
        $ret = !empty($configData[$number]['balance_direction']) ?
            $configData[$number]['balance_direction'] : self::getDirection(self::getParentNumber($number));
        return $ret;
    }

    /**
     * 获取类型 （资产类...）
     * @param string $number 科目编码
     */
    public static function getType($number)
    {
        $pnumber = substr($number, 0, 4);
        $configData = array_column(AccountSubject::configData(), null, 'number');
        return $configData[$pnumber]['type'];
    }

    /**
     * 获取level
     * 若不存在返回false
     * @param string $number 科目编码
     * @param $company_id 公司id
     * @return bool|int
     */
    public static function getCompanyLevel($number, $company_id)
    {
        $company = CompanyModel::query()->whereKey($company_id)->firstOrFail();
        $levelSet = $company['level_set'];
        return self::getLevel($number, $levelSet);
    }

    /**
     * 获取科目层级 例如 1001 => 0 , 100101 => 1 ...
     * @param $number 科目编码
     * @param $levelSet 科目层级设置 例如 4,2,2
     * @return bool|int|string
     */
    public static function getLevel($number, $levelSet)
    {
        if (!is_numeric($number))
            return false;

        $levels = explode(',', $levelSet);
        $len = strlen($number);
        $stdLevel = 0;
        $ret = false;

        foreach ($levels as $key => $level) {
            $stdLevel = $stdLevel + $level;
            if ($len == $stdLevel) {
                $ret = $key;
                break;
            }
        }

        return $ret;
    }

    /**
     * 更新公司会计科目编码
     * 同时更新关联的 凭证，科目余额，发票等表里的字段数据
     * @param string $company_id 公司id
     * @param string $levelSet 科目界别设置 例如：4,4,4,2
     * @throws \Throwable
     */
    public static function updateNumberByLevel($company_id, $oldLevelSet, $newLevelSet)
    {
        $ret = DB::transaction(function () use ($company_id, $oldLevelSet, $newLevelSet) {
            $company = CompanyModel::query()->whereKey($company_id)->firstOrFail();
            $config = self::ACCOUNT_NUMBER;

            foreach ($config as $table => $columns) {
                foreach ($columns as $column) {
                    if (Schema::hasTable($table) && Schema::hasColumn($table, $column)) {
                        $query = DB::query()->select($column)->from($table)->where('company_id', $company_id);
                        $data = $query->get();
                        foreach ($data as $datum) {
                            $oldNumber = $datum->$column;
                            $newNumber = self::convertAccountNumber($oldNumber, $oldLevelSet, $newLevelSet);
                            DB::table($table)->where('company_id', $company_id)
                                ->where($column, $oldNumber)
                                ->where($column, '>', 9999)
                                ->update([$column => $newNumber]);
                        }
                    }
                }
            }
        }, 5);

        return $ret;
    }

    /**
     * 根据科目设置更新科目层级
     * @param $number 科目编码
     * @param $oldLevelSet 旧科目编码 例如 4,2,2
     * @param $newLevelSet 新科目编码 例如 4,4,4,2
     * @throws \Exception
     */
    public static function convertAccountNumber($number, $oldLevelSet, $newLevelSet)
    {
        $levels = explode(',', $oldLevelSet);
        $newLevels = explode(',', $newLevelSet);
        $ret = [];
        $lastPosition = 0;
        foreach ($levels as $key => $level) {
            $ret[$key] = strval(substr($number, $lastPosition, $level));
            $lastPosition = $lastPosition + $level;
        }

        foreach ($newLevels as $key => $newLevel) {
            if (!empty($levels[$key]) && $newLevel < $levels[$key])
                throw new \Exception('新明细科目长度不得小于原长度');

            if (!empty($ret[$key])) {
                $ret[$key] = self::fillNumber($ret[$key], $newLevel);
            }
        }

        return implode('', $ret);

    }

    /**
     * 扩展数字长度 例如 01 => 0001
     * @param $number 数字
     * @param $len 目标长度
     */
    public static function fillNumber($number, $len)
    {
        if (strlen($number) > $len)
            return false;

        $ret = str_repeat('0', $len - strlen($number)) . $number;
        return $ret;
    }

    /**
     * 根据excel编码数据获取 科目层级设置
     * @param $data
     */
    public static function getLevelSetByExcelData($data)
    {
        $count = [];
        foreach ($data as $number) {
            if (!in_array(strlen($number), $count) && strlen($number) >= 4) {
                $count[] = strlen($number);
            }
        }

        $count = array_unique($count);
        sort($count);

        //dd($count);
        if (count($count) > 4 || empty($count) || $count[0] != 4)
            return false;

        for ($i = count($count) - 1; $i > 0; $i--) {
            $count[$i] = $count[$i] - $count[$i - 1];
        }

        return implode(',', $count);
    }

}
