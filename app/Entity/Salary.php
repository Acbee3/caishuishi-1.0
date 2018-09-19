<?php

namespace App\Entity;

use App\Models\Accounting\SalaryConfig;
use App\Models\Accounting\SalaryCostConfig;
use Carbon\Carbon;
use Validator;
use App\Models\Accounting\Salary as SalaryModel;
use App\Models\Employee as EmployeeModel;
use App\Models\Accounting\SalaryEmployee;
use App\Models\AccountSubject as AccountSubjectModel;
use App\Models\Common;
use App\Models\Accounting\Voucher as VoucherModel;

/**
 * 薪酬类
 * Class Salary
 * @package App\Entity
 */
class Salary
{
    const pageSize = 15;

    //薪酬类型
    const Salary_Type_0 = 0;//正常工资薪酬
    const Salary_Type_1 = 1;//临时工资薪金
    const Salary_Type_2 = 2;//全年一次性奖金
    const Salary_Type_3 = 3;//外籍人员正常工资薪金
    const Salary_Type_4 = 4;//劳务报酬
    const Salary_Type_5 = 5;//利息股息红利所得
    const Salary_Type_6 = 6;//个人生产经营所得（核定）
    const Salary_Type_7 = 7;//个人生产经营所得（查账）

    //支付方式
    const PAY_CASH = 0;//现金
    const PAY_ACCOUNT = 1;//挂账
    const PAY_BANK = 2;//银行

    //企业类型
    const Company_Sort_0 = 0;//个体工商户
    const Company_Sort_1 = 1;//承包、承租经营单位
    const Company_Sort_2 = 2;//个人独资企业
    const Company_Sort_3 = 3;//合伙企业

    //征收方式
    const ZSFS_0 = 0;//核定应税所得率征收
    const ZSFS_1 = 1;//核定应纳税所得额征收

    /**
     * 薪酬类型对应数组
     * @var array
     */
    public static $Salary_Labels = [
        self::Salary_Type_0 => '正常工资薪酬',
        self::Salary_Type_1 => '临时工资薪金',
        self::Salary_Type_2 => '全年一次性奖金',
        self::Salary_Type_3 => '外籍人员正常工资薪金',
        self::Salary_Type_4 => '劳务报酬',
        self::Salary_Type_5 => '利息股息红利所得',
        self::Salary_Type_6 => '个人生产经营所得（核定）',
        self::Salary_Type_7 => '个人生产经营所得（查账）',
    ];

    /**
     * 支付方式数组
     * @var array
     */
    public static $PAY_Labels = [
        self::PAY_CASH => '现金',
        self::PAY_ACCOUNT => '挂账',
        self::PAY_BANK => '银行',
    ];

    /**
     * 企业类型数组
     * @var array
     */
    public static $Company_Sort_Labels = [
        self::Company_Sort_0 => '个体工商户',
        self::Company_Sort_1 => '承包、承租经营单位',
        self::Company_Sort_2 => '个人独资企业',
        self::Company_Sort_3 => '合伙企业',
    ];

    /**
     * 征收方式数组
     * @var array
     */
    public static $ZSFS_Labels = [
        self::ZSFS_0 => '核定应税所得率征收',
        self::ZSFS_1 => '核定应纳税所得额征收',
    ];

    /**
     * 费用类型
     * @return array
     */
    public static function GetSalaryCostType()
    {
        $arr = [
            ["value" => "5602", "label" => "管理费用"],
            ["value" => "5601", "label" => "销售费用"],
            ["value" => "4101", "label" => "制造费用"],
            ["value" => "4001", "label" => "生产成本"],
            ["value" => "4401", "label" => "工程施工"],
            ["value" => "5401", "label" => "主营业务成本"],
            ["value" => "4002", "label" => "劳务成本"],
            ["value" => "4301", "label" => "研发成本"],
        ];

        return $arr;
    }

    /**
     * 根据费用类型会计科目编码取名称
     * @param $value
     * @return false|int|string
     * 如：6601  返回结果：管理费用
     */
    public static function GetSalaryCostType_Name($value)
    {
        if (!empty($value)) {
            $arr = self::GetSalaryCostType();
            $val = '';
            foreach ($arr as $key => $v) {
                if ($v['value'] == $value) {
                    $val = $v['label'];
                }
            }
        } else {
            $val = '--';
        }
        return $val;
    }

    /**
     * 根据费用类型名称 取 会计科目编码
     * @param $name
     * @return string
     * 如： 管理费用  返回结果: 6601
     */
    public static function GetSalaryCostType_ID($name)
    {
        if (!empty($name)) {
            $arr = self::GetSalaryCostType();
            $val = '';
            foreach ($arr as $key => $v) {
                if ($v['label'] == $name) {
                    $val = $v['value'];
                }
            }
        } else {
            $val = '--';
        }
        return $val;
    }

    /**
     * 个税起征点
     * @var int
     */
    public static $Personal_Tax_Threshold = 3500;

    /**
     * 个税含税级距
     * @var array
     */
    public static $Personal_Tax_Levels = [1500, 4500, 9000, 35000, 55000, 80000, PHP_INT_MAX];

    /**
     * 个税税率
     * @var array
     */
    public static $Personal_Tax_Rates = [0.03, 0.1, 0.2, 0.25, 0.3, 0.35, 0.45];

    /**
     * 获取所属期间  薪酬模块 获取 所属期间公共方法
     * 薪酬模块 (会计期间) belong_time
     * 其他会计期间   fiscal_period
     * 因 belong_time 先于 fiscal_period，且涉及广，不建议更换
     * @return string
     */
    public static function Get_Belong_Time()
    {
        // 后期获取SESSION里的所属期间   fiscal_period
        new Period();
        $fiscal_period = Period::currentPeriod();
        $fiscal_period = mb_substr($fiscal_period, 0, 7, 'utf-8');

        return $fiscal_period;
    }

    /**
     * 获取所属期间   年份
     * @return string
     */
    public static function Get_Belong_Time_Year()
    {
        $fiscal_period = self::Get_Belong_Time();
        $fiscal_period = mb_substr($fiscal_period, 0, 4, 'utf-8');
        return $fiscal_period;
    }

    /**
     * 通过所属期间 计算 所属期间开始日期
     * @param $year_month_day
     * @return false|string
     */
    public static function Get_Belong_Time_Start($year_month_day)
    {
        if (empty($year_month_day)) {
            $belong_time = self::Get_Belong_Time();
            /*$year_month_day = $belong_time . '-15';
            $year_month_day = date('Y-m-d', strtotime("$year_month_day -1 month"));*/

            $year_month_day = date('Y-m-01', strtotime($belong_time));
        }
        return $year_month_day;
    }

    /**
     * 通过所属期间 计算 所属期间结束日期
     * @param $year_month_day
     * @return false|string
     */
    public static function Get_Belong_Time_End($year_month_day)
    {
        if (empty($year_month_day)) {
            $belong_time = self::Get_Belong_Time();
            /*$year_month_day = $belong_time . '-15';*/

            $year_month_day = date('Y-m-d', strtotime("$belong_time +1 month -1 day"));
        }

        return $year_month_day;
    }

    /**
     * 获取指定时间  第一天
     * @param $datetime
     * @return false|string
     */
    public static function Get_Belong_Time_FirstDay($datetime)
    {
        $year_month_day = date('Y-m-01', strtotime($datetime));
        return $year_month_day;
    }

    /**
     * 获取指定时间  最后一天
     * @param $datetime
     * @return false|string
     */
    public static function Get_Belong_Time_LastDay($datetime)
    {
        $year_month_day = date('Y-m-d', strtotime("$datetime +1 month -1 day"));
        return $year_month_day;
    }

    /**
     * 获取指定时间  当年第一天
     * @param $datetime
     * @return false|string
     */
    public static function Get_Belong_Time_Year_FirstDay($datetime)
    {
        $year_month_day = date('Y-01-01', strtotime($datetime));
        return $year_month_day;
    }

    /**
     * 获取会计期间转化数据
     * @return string
     */
    public static function Get_Belong_Time_Type_A()
    {
        $belong_time = self::Get_Belong_Time();
        $str = date("Y年第n期", strtotime($belong_time));
        return $str;
    }

    /**
     * 薪酬类型  代码转中文
     * @param $val
     * @return mixed
     */
    public static function Change_Salary_Cn($val)
    {
        if (trim($val) == "") {
            return '--';
        } else {
            return self::$Salary_Labels[$val];
        }
    }

    /**
     * 薪酬类型  中文转代码
     * @param $str
     * @return false|int|string
     */
    public static function Change_Salary_Code($str)
    {
        if (!empty($str)) {
            $arr = self::$Salary_Labels;
            $val = array_search($str, $arr);
        } else {
            $val = '--';
        }
        return $val;
    }

    /**
     * 支付方式  代码转中文
     * @param $val
     * @return mixed
     */
    public static function Change_Pay_Cn($val)
    {
        if (trim($val) == "") {
            return '--';
        } else {
            return self::$PAY_Labels[$val];
        }
    }

    /**
     * 支付方式  中文转代码
     * @param $str
     * @return false|int|string
     */
    public static function Change_Pay_Code($str)
    {
        if (!empty($str)) {
            $arr = self::$PAY_Labels;
            $val = array_search($str, $arr);
        } else {
            $val = '--';
        }
        return $val;
    }

    /**
     * 取薪酬表关联的项的薪酬类型
     * @param $id
     * @return mixed|string
     */
    public static function Get_Salary_Type($id)
    {
        $info = SalaryModel::find($id);

        $val = '';
        if ($info) {
            //$val = self::Change_Salary_Cn($info->xclx);
            $val = $info->xclx;
        }
        return $val;
    }

    /**
     * 新增或更新薪酬 并 返回处理结果给相关控制器
     * @param $param
     * @return array
     * @throws \Exception
     */
    public static function SaveSalary($param)
    {
        new Company();

        $data = $param->all();

        // 添加、编辑 验证  （***后续需优化严格验证***）
        $rules = [
            'xclx' => 'required',
        ];
        $messages = [
            'xclx.required' => '请选择薪酬类型',
        ];
        $validator = Validator::make($data, $rules, $messages, []);
        if ($validator->fails()) {
            $result = array('status' => false, 'msg' => $validator->messages()->first());
            return $result;
        }

        // 附加处理数据
        $data['company_id'] = Company::$company->id;

        // 对提交的data进行处理以匹配8类薪酬
        $data = self::FormatData($data);

        $result = array('status' => true, 'msg' => '数据异常');
        if ($data['do'] == 'insert') {
            // 新增
            $belong_time = self::Get_Belong_Time();
            $xclx = $data['xclx'];
            $count = SalaryModel::query()->where('belong_time', $belong_time)->where('company_id', $data['company_id'])->where('xclx', $xclx)->count();

            $data['belong_time'] = $belong_time;

            if ($count >= 1) {
                // 同期已有此类型提示不能再新增
                $result = array('status' => false, 'msg' => '当前薪酬所属期已存在此薪酬类型了。');
            } else {
                // 新增
                $status = self::add($data);
                $result = array('status' => $status, 'msg' => '新增薪酬成功');
            }
        } elseif ($data['do'] == 'update') {
            // 修改
            $edit_info = SalaryModel::find($data['id']);
            if ($edit_info->voucher_id == "") {
                $status = self::update($data);
                $result = array('status' => $status, 'msg' => '编辑薪酬成功');
            } else {
                $result = array('status' => false, 'msg' => '此薪酬类型已生成凭证，不可再编辑。');
            }
        } else {
            $result['status'] = true;
            $result['msg'] = '操作失败';
        }

        return $result;
    }

    /**
     * 处理 待新增、更新到薪酬表里的数据
     * @param $param
     * @return mixed
     */
    public static function FormatData($param)
    {
        // 根据薪酬类型处理数据
        $xc = $param['xclx'];
        if ($xc == 0 || $xc == 1 || $xc == 2 || $xc == 3 || $xc == 4) {
            //正常工资薪酬、临时工资薪金、全年一次性奖金、外籍人员正常工资薪金、劳务报酬
            $param['begin_date'] = '';
            $param['end_date'] = '';
            $param['company_sort_id'] = null;
            $param['zsfs_id'] = null;

            // 支付方式为 空、现金或挂账的 设置银行账户为空
            if ($param['pay_type_id'] != 2) {
                $param['bank_account_id'] = null;
            }
        } elseif ($xc == 5) {
            //利息股息红利所得
            $param['begin_date'] = '';
            $param['end_date'] = '';
            $param['company_sort_id'] = null;
            $param['zsfs_id'] = null;

            $param['pay_type_id'] = null;
            $param['bank_account_id'] = null;
        } elseif ($xc == 6 || $xc == 7) {
            //个人生产经营所得（核定）、个人生产经营所得（查账）
            $param['belong_time'] = '';
            $param['pay_type_id'] = null;
            $param['bank_account_id'] = null;
        } else {
            //扩充备用 不处理
            $param['belong_time'] = '';
        }

        return $param;
    }

    /**
     * 新增薪酬
     * @param $param
     * @return mixed
     * @throws \Exception
     */
    public static function add($param)
    {
        try {
            // 处理新增
            $data = array(
                'company_id' => $param['company_id'],
                'xclx' => $param['xclx'],
                'begin_date' => $param['begin_date'],
                'end_date' => $param['end_date'],
                'belong_time' => $param['belong_time'],
                'pay_type_id' => $param['pay_type_id'],
                'bank_account_id' => $param['bank_account_id'],
                'company_sort_id' => $param['company_sort_id'],
                'zsfs_id' => $param['zsfs_id']
            );

            $status = SalaryModel::create($data);
            return $status;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * 更新薪酬
     * @param $param
     * @return mixed
     * @throws \Exception
     */
    public static function update($param)
    {
        try {
            // 处理更新  公司ID、xclx不能更新
            $data = array(
                //'company_id' => $param['company_id'],
                //'xclx' => $param['xclx'],
                'begin_date' => $param['begin_date'],
                'end_date' => $param['end_date'],
                'belong_time' => $param['belong_time'],
                'pay_type_id' => $param['pay_type_id'],
                'bank_account_id' => $param['bank_account_id'],
                'company_sort_id' => $param['company_sort_id'],
                'zsfs_id' => $param['zsfs_id']
            );

            $status = SalaryModel::where('id', $param['id'])->update($data);
            return $status;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * 删除薪酬
     * @param $id
     * @return bool
     * @throws \Exception
     */
    public static function del($id)
    {
        try {
            $voucher_id = SalaryModel::query()->whereKey($id)->value('voucher_id');

            // 凭证号为空可以删除薪酬
            if ($voucher_id == "") {
                SalaryModel::query()->whereKey($id)->delete();
            }

            return true;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * 薪酬类型 主表 查看和编辑 链接
     * @param $param
     * @return array
     */
    public static function Get_Link($param)
    {
        $data = $param->all();
        $xclx = $data['xclx'];
        $id = $data['id'];

        //\Log::info($data);

        // 用于创建打开新窗口链接数据
        //$new_info = "javascript:parent.creatIframe('$to_link', '薪酬子表')";
        //$link = '<a class="to_link"  data-href="'.$to_link.'" data-title="薪酬" href="'.$new_info.'">薪酬</a>';

        //不同薪酬类型打开对应的列表
        if ($xclx == 0) {
            $link = route('salary.list_a', ['id' => $id]);
        } elseif ($xclx == 1) {
            $link = route('salary.list_b', ['id' => $id]);
        } elseif ($xclx == 2) {
            $link = route('salary.list_c', ['id' => $id]);
        } elseif ($xclx == 3) {
            $link = route('salary.list_d', ['id' => $id]);

            //$to_link = route('salary.list_a', ['id' => $id]);
            //$new_info = "javascript:parent.creatIframe('$to_link', '薪酬子表')";
            //$link = '<a class="to_link"  data-href="'.$to_link.'" data-title="薪酬" href="'.$new_info.'">薪酬</a>';
        } elseif ($xclx == 4) {
            $link = route('salary.list_e', ['id' => $id]);
        } elseif ($xclx == 5) {
            $link = route('salary.list_f', ['id' => $id]);
        } elseif ($xclx == 6) {
            $link = route('salary.list_g', ['id' => $id]);
        } elseif ($xclx == 7) {
            $link = route('salary.list_h', ['id' => $id]);
        } else {
            $link = route('salary.list');
        }

        $result = array('status' => true, 'msg' => '...', 'link' => $link);

        return $result;
    }

    /**
     * 获取该公司名下的员工信息
     * @param $param
     * @return array
     */
    public static function Get_Company_Employee_List($param)
    {
        // 薪酬表对应 的 薪酬ID
        $salary_id = $param->salary_id;

        // 已生成凭证不允许再添加员工薪酬
        $voucher_id = SalaryModel::find($salary_id)->voucher_id;
        if (empty($voucher_id)) {
            //获取薪酬表匹配的员工ID   待处理   20180717
            new Company();
            $company_id = Company::$company->id;
            $data = EmployeeModel::query()->where('company_id', $company_id)->where('status', 1)->orderBy('id', 'DESC')->get();
            $count = count($data);
            if ($count >= 1) {
                $list_arr = array();
                foreach ($data as $key => $v) {
                    $list_arr[$key]['id'] = $v->id;
                    $list_arr[$key]['name'] = $v->employee_name;
                    $list_arr[$key]['IDcard'] = EmployeeModel::Checkzjlx($v->zjlx);
                    $list_arr[$key]['IDnumber'] = $v->zjhm;

                    //排除列表里已添加的员工信息
                }

                $result = array('status' => true, 'msg' => '...', 'data' => $list_arr);
            } else {
                $result = array('status' => false, 'msg' => '请先添加员工!（ 清单处理=>薪酬=>员工 ）', 'data' => '');
            }
        } else {
            $result = array('status' => false, 'msg' => '此期间此类型薪酬凭证已生成，不可再添加员工薪酬！', 'data' => '');
        }

        return $result;
    }

    /**
     * 保存员工薪酬至员工薪酬表
     * @param $param
     * @return array
     * @throws \Exception
     */
    public static function Save_Employee_Salary($param)
    {
        new Company();
        $data = $param->all();

        // 添加、编辑 验证
        $rules = [
            'salary' => 'numeric|required',
        ];
        $messages = [
            'salary.numeric' => '必须为数字类型！',
            'salary.required' => '工资必填！',
        ];
        $validator = Validator::make($data, $rules, $messages, []);
        if ($validator->fails()) {
            $result = array('status' => false, 'msg' => $validator->messages()->first(), 'id' => '', 'data' => '');
            return $result;
        }

        // 取凭证号  检查是否已生成凭证  决定能否更新、新增
        $voucher_id = SalaryModel::find($data['salary_id'])->voucher_id;
        if (!empty($voucher_id)) {
            $result = array('status' => false, 'msg' => '已生成凭证，不可再编辑操作。', 'id' => '', 'data' => '');
            return $result;
        }

        // 工资必填后端验证
        if ($data['salary'] > '0') {
            // 附加处理数据
            $data['company_id'] = Company::$company->id;
            $data['salary_type'] = self::Get_Salary_Type($data['salary_id']);
            $data['belong_time'] = self::Get_Belong_Time();

            // 格式化8类数据
            $data = self::FormatEmployeeSalaryData($data);

            // 验证输入的相关数字类型值
            if (is_numeric($data['salary']) && is_numeric($data['yanglaobx']) && is_numeric($data['yiliaobx']) && is_numeric($data['sybx']) && is_numeric($data['dbyl']) && is_numeric($data['txf']) && is_numeric($data['dkgjj']) && is_numeric($data['year_bonus']) && is_numeric($data['jcfy']) && is_numeric($data['sfjj']) && is_numeric($data['other_fee']) && is_numeric($data['lwbc'])) {
                // 保存新增、更新员工薪酬
                $status = self::SaveAddOrUpdateEmployee($data);
                $result = array('status' => $status, 'msg' => '操作成功', 'id' => $status['id'], 'data' => $status['data']);
            } else {
                $result = array('status' => false, 'msg' => '请输入数字!', 'id' => '', 'data' => '');
            }
        } else {
            $result = array('status' => false, 'msg' => '请填写工资!', 'id' => '', 'data' => '');
        }
        return $result;
    }

    /**
     * 保存或更新数据  do: insert / update
     * @param $data
     * @return array
     * @throws \Exception
     */
    public static function SaveAddOrUpdateEmployee($data)
    {
        if ($data['do'] == 'insert' && $data['se_id'] == '') {
            $status = self::addEmployee($data);
            $id = $status->id;
            $info = SalaryEmployee::find($id);
        } elseif ($data['do'] == 'update' && $data['se_id'] > '0') {
            $status = self::updateEmployee($data);
            $id = $data['se_id'];
            $info = SalaryEmployee::find($id);
        } else {
            $status = true;
            $id = '';
            $info = '';
        }

        $result = array('status' => $status, 'id' => $id, 'data' => $info);
        return $result;
    }

    // 格式化员工薪酬表保存前数据
    public static function FormatEmployeeSalaryData($data)
    {
        // 需要验证资金格式
        $salary_type = $data['salary_type'];
        if ($salary_type == 0) {
            // 正常工资类型
            //$data['personal_tax'] = '0.00';
            $data['year_bonus'] = '0.00';
            $data['jcfy'] = '0.00';
            $data['sfjj'] = '0.00';
            $data['other_fee'] = '0.00';
            $data['lwbc'] = '0.00';
            $data['sflwbc'] = '0.00';

            $data['txf'] = '0.00';//强制通讯费为0（导出工资表未包含此项）

            // 个税
            $decrease_wx_arr = array($data['yanglaobx'], $data['yiliaobx'], $data['sybx'], $data['dbyl'], $data['txf'], $data['dkgjj'], $data['other_fee']);
            $data['personal_tax'] = self::Js_Total_Personal_Tax($data['salary'], $decrease_wx_arr);

            // 计算实际工资
            $add_total_arr = array($data['salary']);
            $decrease_total_arr = array($data['yanglaobx'], $data['yiliaobx'], $data['sybx'], $data['dbyl'], $data['txf'], $data['dkgjj'], $data['personal_tax'], $data['other_fee']);
            $real_salary = self::Js_Total_Real_Salary($add_total_arr, $decrease_total_arr);
            $data['real_salary'] = $real_salary;
        } elseif ($salary_type == 1) {
            // 临时薪酬
            $data['year_bonus'] = '0.00';
            $data['jcfy'] = '0.00';
            $data['sfjj'] = '0.00';
            $data['txf'] = '0.00';
            $data['lwbc'] = '0.00';
            $data['sflwbc'] = '0.00';
            $data['remark'] = '';

            // 个税
            //$decrease_wx_arr = array($data['yanglaobx'], $data['yiliaobx'], $data['sybx'], $data['dbyl'], $data['txf'], $data['dkgjj'], $data['other_fee']);
            //$data['personal_tax'] = self::Js_Total_Personal_Tax($data['salary'],$decrease_wx_arr);
            $data['personal_tax'] = '0.00';

            // 计算实际工资
            $add_total_arr = array($data['salary']);
            $decrease_total_arr = array($data['yanglaobx'], $data['yiliaobx'], $data['sybx'], $data['dbyl'], $data['txf'], $data['dkgjj'], $data['personal_tax'], $data['other_fee']);
            $real_salary = self::Js_Total_Real_Salary($add_total_arr, $decrease_total_arr);
            $data['real_salary'] = $real_salary;
        } elseif ($salary_type == 2) {
            // 全年一次性奖金
            $data['salary'] = '0.00';
            $data['yanglaobx'] = '0.00';
            $data['yiliaobx'] = '0.00';
            $data['sybx'] = '0.00';
            $data['dbyl'] = '0.00';
            $data['txf'] = '0.00';
            $data['other_fee'] = '0.00';
            $data['dkgjj'] = '0.00';
            $data['lwbc'] = '0.00';
            $data['sflwbc'] = '0.00';
            $data['remark'] = '';

            $data['real_salary'] = '0.00';

            // 个税
            $js_personal_tax = $data['year_bonus'] - $data['jcfy'];
            $js_personal_arr = array(0);
            $data['personal_tax'] = self::Js_Total_Personal_Tax($js_personal_tax, $js_personal_arr);

            // 计算实际工资
            $add_total_arr = array($data['year_bonus']);
            $decrease_total_arr = array($data['personal_tax']);
            $real_salary = self::Js_Total_Real_Salary($add_total_arr, $decrease_total_arr);
            $data['sfjj'] = $real_salary;

        } elseif ($salary_type == 3) {
            // 外籍人员正常工资薪金

        } elseif ($salary_type == 4) {
            // 劳务报酬
            $data['salary'] = '0.00';
            $data['yanglaobx'] = '0.00';
            $data['yiliaobx'] = '0.00';
            $data['sybx'] = '0.00';
            $data['dbyl'] = '0.00';
            $data['txf'] = '0.00';
            $data['other_fee'] = '0.00';
            $data['dkgjj'] = '0.00';
            $data['year_bonus'] = '0.00';
            $data['jcfy'] = '0.00';
            $data['sfjj'] = '0.00';
            $data['personal_tax'] = '0.00';
            $data['real_salary'] = '0.00';
            $data['remark'] = '';

            // 个税
            $data['personal_tax'] = self::Js_Total_Personal_Tax($data['lwbc'], array('0'));

            // 计算实际工资
            $add_total_arr = array($data['lwbc']);
            $decrease_total_arr = array(0, $data['personal_tax']);
            $real_salary = self::Js_Total_Real_Salary($add_total_arr, $decrease_total_arr);
            $data['sflwbc'] = $real_salary;
        } else {
            $data['remark'] = '';

        }
        //\Log::info($data);

        return $data;
    }

    /**
     * 计算工资个税   需要仔细处理的部分
     * 参考：http://www.lawtime.cn/info/shuifa/slb/2012111978933.html
     * 工资个税的计算公式为：应纳税额=(工资薪金所得 -“五险一金”-扣除数)×适用税率-速算扣除数
     * @param $salary
     * @param $add_total_arr
     * @return float|int|string
     */
    public static function Js_Total_Personal_Tax($salary, $add_total_arr)
    {
        // 五险一金等扣除合计
        $add_total = array_sum($add_total_arr);

        if (is_numeric($salary) && is_numeric($add_total)) {
            $personal_tax = self::Get_Personal_Income_Tax($salary, $add_total);
        } else {
            $personal_tax = '0.00';
        }

        return $personal_tax;
    }

    /**
     * 不使用速算扣除数计算个人所得税
     * @param float $salary 含税收入金额
     * @param int $deduction 五险一金 等应当扣除的金额 默认值为0
     * @return float|int 返回值为应缴税金额
     */
    public static function Get_Personal_Income_Tax($salary, $deduction = 0)
    {
        $threshold = self::$Personal_Tax_Threshold;//个税起征点
        $levels = self::$Personal_Tax_Levels;//含税级距
        $rates = self::$Personal_Tax_Rates;//个税税率

        // 小于起征点
        if ($salary <= $threshold) {
            return 0;
        }

        // 计算
        $taxableIncome = $salary - $threshold - $deduction;
        $tax = 0;
        foreach ($levels as $k => $level) {
            $previousLevel = isSet($levels[$k - 1]) ? $levels[$k - 1] : 0;
            if ($taxableIncome <= $level) {
                $tax += ($taxableIncome - $previousLevel) * $rates[$k];
                break;
            }
            $tax += ($level - $previousLevel) * $rates[$k];
        }
        $tax = round($tax, 2);

        return $tax;
    }

    /**
     * 计算实发工资  需要仔细处理的部分
     * @param $add_total_arr
     * @param $decrease_total_arr
     * @return string
     */
    public static function Js_Total_Real_Salary($add_total_arr, $decrease_total_arr)
    {
        $add_total = array_sum($add_total_arr);
        $decrease_total = array_sum($decrease_total_arr);
        $format_total = sprintf("%.2f", $add_total - $decrease_total);

        return $format_total;
    }

    /**
     * 计算社保总计
     * @param $add_total_arr
     * @return string
     */
    public static function Js_Total_SheBao($add_total_arr)
    {
        $add_total = array_sum($add_total_arr);
        $format_total = sprintf("%.2f", $add_total);
        return $format_total;

    }

    /**
     * 保存员工薪酬
     * @param $param
     * @return SalaryEmployee|\Illuminate\Database\Eloquent\Model
     * @throws \Exception
     */
    public static function addEmployee($param)
    {
        try {
            $data = array(
                'company_id' => $param['company_id'],
                'salary_id' => $param['salary_id'],
                'employee_id' => $param['employee_id'],
                'employee_name' => $param['employee_name'],
                'personal_tax' => $param['personal_tax'],
                'salary_type' => $param['salary_type'],
                'fylx' => $param['fylx'],
                'year_bonus' => $param['year_bonus'],
                'jcfy' => $param['jcfy'],
                'sfjj' => $param['sfjj'],
                'salary' => $param['salary'],
                'txf' => $param['txf'],
                'yanglaobx' => $param['yanglaobx'],
                'yiliaobx' => $param['yiliaobx'],
                'sybx' => $param['sybx'],
                'dbyl' => $param['dbyl'],
                'dkgjj' => $param['dkgjj'],
                'other_fee' => $param['other_fee'],
                'real_salary' => $param['real_salary'],
                'lwbc' => $param['lwbc'],
                'sflwbc' => $param['sflwbc'],
                'remark' => $param['remark'],
                'belong_time' => $param['belong_time']
            );

            $status = SalaryEmployee::create($data);
            return $status;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * 更新员工薪酬
     * @param $param
     * @return bool
     * @throws \Exception
     */
    public static function updateEmployee($param)
    {
        try {
            //'company_id' => $param['company_id'],
            //'salary_id' => $param['salary_id'],
            //'employee_id' => $param['employee_id'],
            //'employee_name' => $param['employee_name'],
            //'belong_time' => $param['belong_time']

            $data = array(
                'personal_tax' => $param['personal_tax'],
                'salary_type' => $param['salary_type'],
                'fylx' => $param['fylx'],
                'year_bonus' => $param['year_bonus'],
                'jcfy' => $param['jcfy'],
                'sfjj' => $param['sfjj'],
                'salary' => $param['salary'],
                'txf' => $param['txf'],
                'yanglaobx' => $param['yanglaobx'],
                'yiliaobx' => $param['yiliaobx'],
                'sybx' => $param['sybx'],
                'dbyl' => $param['dbyl'],
                'dkgjj' => $param['dkgjj'],
                'other_fee' => $param['other_fee'],
                'real_salary' => $param['real_salary'],
                'lwbc' => $param['lwbc'],
                'sflwbc' => $param['sflwbc'],
                'remark' => $param['remark']
            );

            $status = SalaryEmployee::where('id', $param['se_id'])->update($data);
            return $status;
        } catch (\Exception $e) {
            throw $e;
        }

    }

    /**
     * 删除单个员工薪酬
     * @param $id
     * @return bool
     * @throws \Exception
     */
    public static function delSalaryEmployee($id)
    {
        try {
            //已生成凭证不允许删除
            $salary_id = SalaryEmployee::query()->whereKey($id)->value('salary_id');
            //取凭证号
            $voucher_id = SalaryModel::find($salary_id)->voucher_id;

            // 凭证号为空可以删除薪酬
            if ($voucher_id == "") {
                SalaryEmployee::query()->whereKey($id)->delete();
                $status = true;
            } else {
                $status = false;
            }

            return $status;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * 通过凭证ID取凭证字号  需优化关联凭证表
     * @param $id
     * @return string
     */
    public static function Get_Certificate_Name_By_Id($id)
    {
        if (!empty($id)) {
            $voucher_num = VoucherModel::query()->whereKey($id)->value('voucher_num');
            $cert_name = '记-' . $voucher_num;
        } else {
            $cert_name = '';
        }
        return $cert_name;
    }

    /**
     * 统计相关期间、相关薪酬类型下的人数   需关联查找统计salary_employee相关信息
     * @param $salary_id
     * @return int
     */
    public static function Get_Belong_Time_Salary_Num($salary_id)
    {
        $belong_time = self::Get_Belong_Time();

        new Company();
        $company_id = Company::$company->id;

        $data = SalaryEmployee::query()->where('company_id', '=', $company_id)->where('salary_id', '=', $salary_id)->where('belong_time', '=', $belong_time)->get();
        $number = count($data);
        return $number;
    }

    /**
     * 处理复制往期薪酬        优化需要加入:  起 <  止 验证
     * @param $param
     * @return array
     */
    public static function CopyOldSalary($param)
    {
        new Company();
        $company_id = Company::$company->id;
        $salary_id = $param->salaryId;
        $copy_from = $param->copy_from;
        $copy_from_y = $param->copy_from_y;
        $copy_to = $param->copy_to;
        $copy_to_y = $param->copy_to_y;

        // 全年一性奖金特殊处理
        if ($salary_id == 2) {
            $copy_from = $copy_from_y;
            $copy_to = $copy_to_y;
            $msg = '被复制年没有薪酬数据，请修改复制年份。';
        } else {
            $msg = '被复制月份没有薪酬数据，请修改复制日期。';
        }

        if ($company_id > 0 && is_numeric($company_id) && $salary_id >= 0 && is_numeric($salary_id) && !empty($copy_from) && !empty($copy_to)) {
            $id = SalaryModel::query()->where('company_id', $company_id)->where('xclx', $salary_id)->where('belong_time', $copy_from)->value('id');
            if ($id > 0) {
                $check_row = SalaryModel::query()->where('company_id', $company_id)->where('xclx', $salary_id)->where('belong_time', $copy_to)->get();
                $count = count($check_row);
                if ($count == 0) {
                    $salary = SalaryModel::find($id)->replicate();
                    $salary->belong_time = $copy_to;
                    $salary->voucher_id = null;
                    $salary->save();

                    $result = array('status' => true, 'msg' => '复制往期薪酬成功。');
                } else {
                    $result = array('status' => false, 'msg' => '当前薪酬期内已存在此类型薪酬，操作失败。');
                }

            } else {
                $result = array('status' => false, 'msg' => $msg);
            }
        } else {
            $result = array('status' => false, 'msg' => '参数异常，请重新操作。');
        }

        return $result;
    }

    /**
     * 复制工资条
     * @param $param
     * @return array
     * @throws \Exception
     */
    public static function CopyOldSalaryBill($param)
    {
        new Company();
        $company_id = Company::$company->id;
        $salary_id = $param->salary_id;
        $copy_from = $param->copy_from;
        $copy_to = $param->copy_to;

        // 取凭证号  凭证号为空 可以复制
        $salary_row = SalaryModel::find($salary_id);
        $voucher_id = $salary_row->voucher_id;
        $salary_type = $salary_row->xclx;
        if (empty($voucher_id)) {
            if ($company_id > 0 && is_numeric($company_id) && $salary_id >= 0 && is_numeric($salary_id) && !empty($copy_from) && !empty($copy_to) && $copy_from != $copy_to) {

                // 检查复制源有无数据
                $list = SalaryEmployee::query()->where('company_id', $company_id)->where('salary_type', $salary_type)->where('belong_time', $copy_from)->get();
                if (count($list) > 0) {
                    $copy_result = self::Do_CopyOldSalaryBill($list, $copy_to, $salary_id);
                    $result = array('status' => $copy_result, 'msg' => '复制工资条成功。');
                } else {
                    $result = array('status' => false, 'msg' => '被复制月份没有薪酬数据，复制失败。');
                }
            } else {
                $result = array('status' => false, 'msg' => '参数异常，请重新操作。');
            }
        } else {
            $result = array('status' => false, 'msg' => '此薪酬类型已生成凭证，不可再复制工资条。');
        }

        return $result;
    }

    /**
     * 执行复制往期工资
     * @param $param
     * @param $copy_to
     * @param $salary_id
     * @return bool
     * @throws \Exception
     */
    public static function Do_CopyOldSalaryBill($param, $copy_to, $salary_id)
    {
        try {
            foreach ($param as $key => $v) {
                $salary_employee = SalaryEmployee::find($v->id)->replicate();
                $salary_employee->belong_time = $copy_to;
                $salary_employee->salary_id = $salary_id;
                $salary_employee->save();
            }
            return true;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * 自动创建并设置计提科目和成本费用科目
     * @return array
     * @throws \Exception
     */
    public static function AutoSettingCostConfig()
    {
        new Company();
        $company_id = Company::$company->id;

        $account_subjects_list = AccountSubjectModel::query()->where('company_id', $company_id)->get();
        if (count($account_subjects_list) > 10) {
            // 存在会计科目
        } else {
            // 初始化会计科目
            AccountSubjectModel::companySubjects($company_id);
        }

        // 处理自动设置 计提和成本费用 设置
        self::Do_AutoSettingCostConfig($company_id);

        $result = array('status' => true, 'msg' => '自动设置科目成功。');
        return $result;
    }

    /**
     * 处理自动设置计提和费用设置
     * @param $company_id
     * @return bool
     */
    public static function Do_AutoSettingCostConfig($company_id)
    {
        // 计提
        $config['status'] = 0;
        $config['company_id'] = $company_id;
        $config['gz'] = self::Search_KM_Id_By_CODE($company_id, '221101');// 221101
        $config['nzj'] = self::Search_KM_Id_By_CODE($company_id, '221102');// 221102
        $config['qy_gjj'] = self::Search_KM_Id_By_CODE($company_id, '221104');// 221104
        $config['qy_sb'] = self::Search_KM_Id_By_CODE($company_id, '221103');// 221103
        $config['gr_gjj'] = self::Search_KM_Id_By_CODE($company_id, '224102');// 224102
        $config['gr_sb'] = self::Search_KM_Id_By_CODE($company_id, '224101');// 224101
        $config['gs'] = self::Search_KM_Id_By_CODE($company_id, '222106');// 222106
        $config['gx'] = self::Search_KM_Id_By_CODE($company_id, '2241');// 2241

        $ck_config = SalaryConfig::query()->where('company_id', $company_id)->get();
        if (count($ck_config) > 0) {
            // 更改状态为2 不显示
            $data['status'] = 2;
            SalaryConfig::query()->where('company_id', $company_id)->update($data);

            SalaryConfig::updateOrCreate($config);
        } else {
            SalaryConfig::updateOrCreate($config);
        }

        // 成本费用
        $cost_config['status'] = 0;
        $cost_config['company_id'] = $company_id;
        $cost_config['cost_type'] = 5602;
        $cost_config['cost_name'] = '管理费用';
        $cost_config['gz'] = self::Search_KM_Id_By_CODE($company_id, '560201');//560201
        $cost_config['nzj'] = self::Search_KM_Id_By_CODE($company_id, '560201');// 560201
        $cost_config['qy_sb'] = self::Search_KM_Id_By_CODE($company_id, '560203');// 560203
        $cost_config['qy_gjj'] = self::Search_KM_Id_By_CODE($company_id, '560204');// 560204

        $ck_cost_config = SalaryCostConfig::query()->where('company_id', $company_id)->get();
        if (count($ck_cost_config) > 0) {
            // 更改旧的设置为不可见  status 状态： 0可修改  1锁定   2前台不显示
            $cost_data['status'] = 2;
            SalaryCostConfig::query()->where('company_id', $company_id)->update($cost_data);

            SalaryCostConfig::updateOrCreate($cost_config);
        } else {
            SalaryCostConfig::updateOrCreate($cost_config);
        }

        return true;
    }

    /**
     * 取会计科目ID
     * @param $company_id
     * @param $km_id
     * @return mixed
     */
    public static function Search_KM_Id_By_CODE($company_id, $km_id)
    {
        $info = AccountSubjectModel::query()->where('company_id', $company_id)->where('number', $km_id)->first();
        $id = $info->id;
        return $id;
    }

    /**
     * 获取会计科目配置信息表行数据
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|null|object
     */
    public static function GetSalaryConfigRowInfo()
    {
        new Company();
        $company_id = Company::$company->id;

        // status  0 可修改   1 锁定    2 禁用 前台不可见(作用相关于删除,但是为保留与之前业务关联，不建议删除)
        $status_arr = array(0, 1);
        $info = SalaryConfig::query()->where('company_id', $company_id)->whereIn('status', $status_arr)->first();
        return $info;
    }

    /**
     * 获取公司成本费用设置列表
     * @return array
     */
    public static function GetSalaryCostConfigRows()
    {
        new Company();
        $company_id = Company::$company->id;
        $status_arr = array(0, 1);
        $list = SalaryCostConfig::query()->where('company_id', $company_id)->whereIn('status', $status_arr)->get();

        if (count($list) > 0) {
            $list_arr = array();
            foreach ($list as $key => $v) {
                $list_arr[$key]['id'] = $v->id;
                $list_arr[$key]['cost_type'] = $v->cost_type;
                $list_arr[$key]['gz'] = $v->gz;
                $list_arr[$key]['nzj'] = $v->nzj;
                $list_arr[$key]['qy_gjj'] = $v->qy_gjj;
                $list_arr[$key]['qy_sb'] = $v->qy_sb;
                $list_arr[$key]['status'] = $v->status;
            }
        } else {
            $list_arr = [];
        }

        return $list_arr;
    }

    /**
     * 获取会计科目封装数组数据
     * @return array
     */
    public static function GetAccountSubjectList()
    {
        new Company();
        $company_id = Company::$company->id;
        $status = AccountSubjectModel::USED;
        //$company_id_arr = array(0, $company_id);

        $list = AccountSubjectModel::query()->where('company_id', $company_id)
            ->where('status', $status)->get();//->where("pid", 0)

        if ($list && count($list) > 0) {
            $list_arr_tit = array(array('value' => '', 'label' => '请选择'));

            $list_arr = array();
            foreach ($list as $key => $v) {
                $list_arr[$key]['value'] = $v->id;
                $list_arr[$key]['label'] = $v->number . ' ' . $v->type . '_' . $v->name;
            }

            $list_arr_total = array_merge($list_arr_tit, $list_arr);
            $result = array('status' => true, 'msg' => '获取会计科目成功。', 'items' => $list_arr_total);
        } else {
            $list_arr = array(array('value' => '', 'label' => '获取会计科目失败'));
            $result = array('status' => false, 'msg' => '获取会计科目失败。', 'items' => $list_arr);
        }

        return $result;
    }

    /**
     * 保存科目配置信息
     * status (0 可修改 / 1 不可再修改   初始时为0，首次生成凭证时更新status为1。)
     * @param $param
     * @return array
     */
    public static function SaveAccountSubjectConfig($param)
    {
        $gz = $param->gz;
        $nzj = $param->nzj;
        $qy_gjj = $param->qy_gjj;
        $qy_sb = $param->qy_sb;
        $gr_gjj = $param->gr_gjj;
        $gr_sb = $param->gr_sb;
        $gs = $param->gs;
        $gx = $param->gx;

        // 判断成本费用类型重复与否
        if (count($param->cost_list) >= 2) {
            $check_status = self::Check_Cost_List($param->cost_list);
            if ($check_status) {
                $check_result = array('status' => false, 'msg' => '存在相同的成本费用类型，请修改后再保存。');
                return $check_result;
            }
        }

        if ($gz != null && $nzj != null && $qy_gjj != null && $qy_sb != null && $gr_gjj != null && $gr_sb != null && $gs != null && $gx != null) {
            $info = self::Do_SaveSalaryConfig($param);
            $result = array('status' => $info['status'], 'msg' => $info['msg']);
        } else {
            $result = array('status' => false, 'msg' => '科目信息不完整，无法保存。');
        }

        return $result;
    }

    /**
     * 检查传入的成本费用类型有无重复
     * @param $param
     * @return bool
     */
    public static function Check_Cost_List($param)
    {
        // 构建费用类型数组
        $new_arr = array();
        foreach ($param as $key => $v) {
            $new_arr[$key] = $v['cost_type'];
        }

        // 判断重复
        if (count($new_arr) != count(array_unique($new_arr))) {
            // 有重复费用类型
            return true;
        } else {
            return false;
        }
    }

    /**
     * 执行保存科目配置
     * @param $param
     * @return array
     */
    public static function Do_SaveSalaryConfig($param)
    {
        new Company();
        $company_id = Company::$company->id;

        $id = intval($param->id);

        $item['company_id'] = $company_id;
        $item['gz'] = intval($param->gz);
        $item['nzj'] = intval($param->nzj);
        $item['qy_gjj'] = intval($param->qy_gjj);
        $item['qy_sb'] = intval($param->qy_sb);
        $item['gr_gjj'] = intval($param->gr_gjj);
        $item['gr_sb'] = intval($param->gr_sb);
        $item['gs'] = intval($param->gs);
        $item['gx'] = intval($param->gx);

        $item['status'] = $param->status;

        if ($param->id != null && $id > 0) {
            // 更新基础设置
            if ($item['status'] == 0) {
                SalaryConfig::updateOrCreate(['id' => $id], $item);
                $msg = '计提科目更新成功。';
            } else {
                $msg = '计提科目已生成过凭证，不可修改。';
            }

            // 更新成本费用表
            $cost_list = $param->cost_list;
            $result = self::Do_SaveSalaryCostConfig($company_id, $cost_list);
        } else {
            // 新增
            $item['status'] = 0;// 新增时设置初始状态
            SalaryConfig::updateOrCreate($item);
            $msg = '计提科目配置成功。';

            // 更新成本费用表
            $cost_list = $param->cost_list;
            $result = self::Do_SaveSalaryCostConfig($company_id, $cost_list);
        }

        //  合并操作提示消息
        $result['msg'] = $msg . ' ' . $result['msg'];
        return $result;
    }

    /**
     * 更新成本费用设置信息
     * @param $company_id
     * @param $cost_list
     * @return array
     */
    public static function Do_SaveSalaryCostConfig($company_id, $cost_list)
    {
        if (count($cost_list) > 0) {
            foreach ($cost_list as $key => $v) {
                !isset($v['status']) && $v['status'] = 0;
                if ($v['status'] == 0) {
                    $item = Common::filterColumn('salary_cost_config', $v);
                    !isset($item['id']) && $item['id'] = null;
                    $item['company_id'] = $company_id;

                    // 根据费用类型取费用名称
                    $item['cost_name'] = self::GetSalaryCostType_Name($v['cost_type']);
                    SalaryCostConfig::updateOrCreate(['id' => $item['id']], $item);

                } else {
                    $cost_type_name = self::GetSalaryCostType_Name($v['cost_type']);
                    $result = array('status' => false, 'msg' => '成本费用类型(' . $cost_type_name . ')已生成凭证，不可再次修改！');
                    return $result;
                }
            }

            $result = array('status' => true, 'msg' => '成本费用类型修改成功！');
            return $result;
        } else {
            $result = array('status' => true, 'msg' => '未添加成本费用类型！');
            return $result;
        }
    }

    /**
     * 处理删除成本费用 行设置 信息
     * @param $param
     * @return array
     * @throws \Exception
     */
    public static function DelSalaryCostConfig($param)
    {
        $id = $param->id;

        if ($id > 0 && $param->status == 0) {
            // 检查员工薪酬表里是否已添加应用过 此成本费用
            $row_info = SalaryCostConfig::find($id);
            $company_id = $row_info->company_id;
            $cost_name = $row_info->cost_name;

            $salary_employee_list = SalaryEmployee::query()->where('company_id', $company_id)->where('fylx', $cost_name)->get();
            if (count($salary_employee_list) > 0) {
                $result = array('status' => false, 'msg' => '此费用类型已关联至员工薪酬，不可删除！');
            } else {
                $status = self::Do_Del_SalaryCostConfig($id);
                $result = array('status' => $status, 'msg' => '删除成功！');
            }

        } else {
            $result = array('status' => false, 'msg' => '此成本费用类型已生成凭证，不可删除！');
        }

        return $result;
    }

    /**
     * 处理删除成本费用类型行信息
     * @param $id
     * @return bool
     * @throws \Exception
     */
    public static function Do_Del_SalaryCostConfig($id)
    {
        try {
            $status = SalaryCostConfig::query()->whereKey($id)->value('status');

            // 状态为0 可以删除
            if ($status == "0") {
                SalaryCostConfig::query()->whereKey($id)->delete();
            }

            return true;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * 取当前公司的费用类型列表
     * @return array|string
     */
    public static function GetSalaryCostTypeList()
    {
        new Company();
        $company_id = Company::$company->id;

        $list = SalaryCostConfig::query()->where('company_id', $company_id)->get();
        if (count($list) > 0) {
            $new_list = array();
            foreach ($list as $key => $v) {
                $new_list[$v->cost_type] = $v->cost_name;
            }
        } else {
            $new_list = '';
        }

        return $new_list;
    }

    /**
     * 复制员工工资条  未处理   未启用
     * @param $param
     * @return array
     */
    public static function CopyOldEmployeeSalary($param)
    {
        $data = $param->all();
        $result = array('status' => true, 'msg' => '复制员工薪酬成功。', 'data' => $data);
        return $result;
    }

    //处理生成记账凭证   如果需要对已生成凭证的薪酬类型进行再生成凭证，修改以下的逻辑就好   处理中……
    public static function CreateVoucher($param)
    {
        $id = $param->id;
        $token = $param->_token;
        try {
            $voucher_id = SalaryModel::query()->whereKey($id)->value('voucher_id');
            // 凭证号为空可以删除薪酬
            if ($voucher_id == "") {
                // 此处添加生成凭证相关逻辑 20180713

                // 检查科目配置及薪酬  能否生成相关凭证
                $check = self::Do_Check_CreateVoucher($id);
                if ($check['status']) {
                    $data = $check['data'];

                    // 格式化数据
                    $format_data = self::Do_Format_CreateVoucherData($data);

                    // 提交到生成凭证接口数据处理完成 打印效果
                    $data = self::Do_Format_VoucherData_Arr($format_data, $token);
                    //\Log::info($data);
                    //$status = self::Make_Voucher($format_data);
                    //\Log::info($status);

                    $result = array('status' => true, 'msg' => '生成记账凭证成功_' . $id . '。', 'data'=>$data);
                } else {
                    $result = array('status' => false, 'msg' => $check['msg'], 'data'=>'');
                }
                return $result;
            } else {
                $result = array('status' => false, 'msg' => '生成记账凭证失败。', 'data'=>'');
                return $result;
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * 检查能否 生成相关薪酬凭证
     * 并处理 薪酬及科目配置相关数组
     * @param $salary_id  薪酬ID
     * @return array
     */
    public static function Do_Check_CreateVoucher($salary_id)
    {
        new Company();
        $company_id = Company::$company->id;
        $belong_time = self::Get_Belong_Time();

        $config_info = self::Get_Salary_Config($company_id);
        if (empty($config_info)) {
            $ck_result = array('status' => false, 'msg' => '凭证科目配置（计提科目）没有配置。', 'data' => '');
            return $ck_result;
        }

        // 取相关薪酬类型数据
        $employee_salary_list = SalaryEmployee::query()->where('company_id', $company_id)->where('salary_id', $salary_id)->where('belong_time', $belong_time)->get();
        $list = $employee_salary_list->toArray();

        if (count($list) > 0) {
            // 重组薪酬数据
            $salary_list = array();
            foreach ($list as $k => $v) {
                $cost_type = $v['fylx'];

                $cost_config_info = self::Get_Salary_Cost_Config($company_id, $cost_type);//成本费用配置
                if (empty($cost_config_info)) {
                    $ck_result = array('status' => false, 'msg' => '凭证科目配置（成本费用:' . $cost_type . '）没有配置。', 'data' => '');
                    return $ck_result;
                } else {
                    $salary_list[$cost_type]['company_id'] = $company_id;
                    $salary_list[$cost_type]['salary_id'] = $salary_id;
                    $salary_list[$cost_type]['cost_name'] = $cost_type;//费用名称
                    $salary_list[$cost_type]['config_info'] = $config_info;//计提科目配置
                    $salary_list[$cost_type]['cost_config_info'] = $cost_config_info;

                    //$salary_list[$cost_type]['items'][] = $v;// 薪酬详细数组
                    $salary_list[$cost_type]['total'] = self::Sum_Format_Employee_Salary($salary_id, $cost_type);
                }
            }
            $salary_list = array_values($salary_list);

            $result = array('status' => true, 'msg' => '科目配置及薪酬数据OK，可以生成凭证', 'data' => $salary_list);
        } else {
            $result = array('status' => false, 'msg' => '无薪酬数据，不可生成凭证。', 'data' => '');
        }

        return $result;
    }

    /**
     * 取计提科目配置信息
     * @param $company_id
     * @return array|string
     */
    public static function Get_Salary_Config($company_id)
    {
        $status_arr = array(0, 1);
        $salary_config = SalaryConfig::query()->where('company_id', $company_id)->whereIn('status', $status_arr);
        $num = count($salary_config->get());
        if ($num == 1) {
            // 配置正常
            $info = $salary_config->first()->toArray();
        } else {
            $info = '';
        }
        return $info;
    }

    /**
     * 取成本费用相关配置信息
     * @param $company_id
     * @param $cost_name
     * @return array|string
     */
    public static function Get_Salary_Cost_Config($company_id, $cost_name)
    {
        $status_arr = array(0, 1);
        $salary_cost_config = SalaryCostConfig::query()->where('company_id', $company_id)->where('cost_name', $cost_name)->whereIn('status', $status_arr);
        $num = count($salary_cost_config->get());
        if ($num == 1) {
            // 配置正常
            $info = $salary_cost_config->first()->toArray();
        } else {
            $info = '';
        }
        return $info;
    }

    // 预处理生成凭证数据
    public static function Do_Format_CreateVoucherData($param)
    {
        // 至此已获取生成凭证相关  计提科目、成本费用科目、薪酬汇总数组
        $list = $param;

        // 正式处理生成凭证
        // 1. 判断科目凭证配置状态status( 更新salary_config表和salary_cost_config表相关 status为1不可修改)
        // 2. 匹配薪酬汇总及科目
        $items = array();
        foreach ($list as $k => $v) {
            $items[$k] = self::Do_Create_Voucher_Items($v);
        }

        return $items;
    }

    /**
     * 构建凭证项目列表
     * @param $param
     * @return array|string
     */
    public static function Do_Create_Voucher_Items($param)
    {
        //薪酬ID
        $salary_id = $param['salary_id'];
        // 薪酬类型
        $salary_type_Id = SalaryModel::query()->whereKey($salary_id)->value('xclx');

        $data = self::Do_Create_Voucher_Items_By_ID($salary_type_Id, $param);
        return $data;
    }

    // 根据不同薪酬类型生成相关凭证列表
    public static function Do_Create_Voucher_Items_By_ID($salary_type_Id, $param)
    {
        switch ($salary_type_Id) {
            case 0:
                // 正常工资薪酬

                // 工资
                $data[] = self::Do_Create_Voucher_Item($param['total']['total_salary'], $param['cost_config_info']['gz'], '工资', '');
                $data[] = self::Do_Create_Voucher_GZ_Item($param['total']['total_salary'], $param['config_info']['gz'], '工资', '', '贷');
                $data[] = self::Do_Create_Voucher_GZ_Item($param['total']['total_salary'], $param['config_info']['gz'], '工资', 'ff', '借');

                // 社保
                $wx_arr = array($param['total']['total_yanglaobx'], $param['total']['total_yiliaobx'], $param['total']['total_sybx'], $param['total']['total_dbyl'], '');
                $total_wx = array_sum($wx_arr);
                if ($total_wx > 0) {
                    $data[] = self::Do_Create_Voucher_Item($total_wx, $param['config_info']['gr_sb'], '个人社保', '');
                }

                // 公积金
                if ($param['total']['total_dkgjj'] > 0) {
                    $data[] = self::Do_Create_Voucher_Item($param['total']['total_dkgjj'], $param['config_info']['gr_gjj'], '个人公积金', '');
                }

                // 个税
                if ($param['total']['total_personal_tax'] > 0) {
                    $data[] = self::Do_Create_Voucher_Item($param['total']['total_personal_tax'], $param['config_info']['gs'], '个税', '');
                }

                // 实发工资  应用科目：2241
                if ($param['total']['total_real_salary'] > 0) {
                    $data[] = self::Do_Create_Voucher_RS_Item($param['total']['total_real_salary'], '2241', '工资', 'ff');
                }

                break;
            case 1:
                // 临时工资薪金

                // 工资
                $data[] = self::Do_Create_Voucher_Item($param['total']['total_salary'], $param['cost_config_info']['gz'], '工资', '');
                $data[] = self::Do_Create_Voucher_GZ_Item($param['total']['total_salary'], $param['config_info']['gz'], '工资', '', '贷');

                $data[] = self::Do_Create_Voucher_GZ_Item($param['total']['total_salary'], $param['config_info']['gz'], '工资', 'ff', '借');


                // 计提社保 公积金  借
                /*$wx_yj_arr = array($param['total']['total_yanglaobx'], $param['total']['total_yiliaobx'], $param['total']['total_sybx'], $param['total']['total_dbyl'], $param['total']['total_dkgjj']);
                $total_wx_yj = array_sum($wx_yj_arr);
                if ($total_wx_yj > 0) {
                    $data[] = self::Do_Create_Voucher_GZ_Item($total_wx_yj, $param['config_info']['gz'], '个人社保、公积金', '', '借');
                }*/

                // 社保
                $wx_arr = array($param['total']['total_yanglaobx'], $param['total']['total_yiliaobx'], $param['total']['total_sybx'], $param['total']['total_dbyl'], '');
                $total_wx = array_sum($wx_arr);
                if ($total_wx > 0) {
                    $data[] = self::Do_Create_Voucher_Item($total_wx, $param['config_info']['gr_sb'], '个人社保', '');
                }

                // 公积金
                if ($param['total']['total_dkgjj'] > 0) {
                    $data[] = self::Do_Create_Voucher_Item($param['total']['total_dkgjj'], $param['config_info']['gr_gjj'], '个人公积金', '');
                }

                // 实发工资  应用科目：2241
                if ($param['total']['total_real_salary'] > 0) {
                    $wx_ls_arr = array($param['total']['total_real_salary'],$param['total']['total_other_fee']);
                    $total_wx_ls = array_sum($wx_ls_arr);
                    $data[] = self::Do_Create_Voucher_RS_Item($total_wx_ls, '2241', '工资', 'ff');
                }

                break;
            case 2:
                // 全年一次性奖金
                //\Log::info($param);

                $data[] = self::Do_Create_Voucher_Item($param['total']['total_year_bonus'], $param['cost_config_info']['gz'], '奖金', '');
                $data[] = self::Do_Create_Voucher_GZ_Item($param['total']['total_year_bonus'], $param['config_info']['gz'], '奖金', '', '贷');
                $data[] = self::Do_Create_Voucher_GZ_Item($param['total']['total_year_bonus'], $param['config_info']['gz'], '奖金', 'ff', '借');

                // 减除费用

                // 个税
                if ($param['total']['total_personal_tax'] > 0) {
                    $data[] = self::Do_Create_Voucher_Item($param['total']['total_personal_tax'], $param['config_info']['gs'], '个税', '');
                }

                // 实发奖金
                if ($param['total']['total_sfjj'] > 0) {
                    $data[] = self::Do_Create_Voucher_RS_Item($param['total']['total_sfjj'], '2241', '奖金', 'ff');
                }

                break;
            case 3:
                $data = '';
                break;
            case 4:
                // 劳务报酬

                // 总劳务报酬
                $data[] = self::Do_Create_Voucher_Item($param['total']['total_lwbc'], $param['cost_config_info']['gz'], '劳务报酬', '');
                $data[] = self::Do_Create_Voucher_GZ_Item($param['total']['total_lwbc'], $param['config_info']['gz'], '劳务报酬', '', '贷');
                $data[] = self::Do_Create_Voucher_GZ_Item($param['total']['total_lwbc'], $param['config_info']['gz'], '劳务报酬', 'ff', '借');

                // 个税
                if ($param['total']['total_personal_tax'] > 0) {
                    $data[] = self::Do_Create_Voucher_Item($param['total']['total_personal_tax'], $param['config_info']['gs'], '个税', '');
                }

                // 实发劳务报酬
                if ($param['total']['total_sflwbc'] > 0) {
                    $data[] = self::Do_Create_Voucher_RS_Item($param['total']['total_sflwbc'], '2241', '劳务报酬', 'ff');
                }

                break;
            case 5:
                $data = '';
                break;
            case 6:
                $data = '';
                break;
            case 7:
                $data = '';
                break;
            default:
                $data = '';
                break;
        }

        return $data;
    }

    /**
     * 创建凭证单行信息     通用
     * @param $money
     * @param $km_id
     * @param $memo
     * @param $ff "ff"或空
     * @return array
     */
    public static function Do_Create_Voucher_Item($money, $km_id, $memo, $ff)
    {
        $belong_time = self::Get_Belong_Time();
        //$month_memo = str_replace("-", "", $belong_time);//月份
        $month_memo = date("Y年n月", strtotime($belong_time));

        // 会计科目 行信息
        $km_info = AccountSubjectModel::find($km_id);
        $number = $km_info->number;
        $km_type = $km_info->type;
        $km_name = $km_info->name;
        $balance_direction = $km_info->balance_direction;

        // memo
        if ($ff == 'ff') {
            $memo = '发放' . $month_memo . '' . $memo;
        } else {
            $memo = '计提' . $month_memo . '' . $memo;
        }

        // 科目
        $km = $number . ' ' . $km_type . '_' . $km_name;

        $data = ['memo' => $memo, 'km' => $km, 'fx' => $balance_direction, 'money' => $money, 'km_id' => $km_id];
        return $data;
    }

    /**
     * 工资  计提 和 发放
     * @param $money
     * @param $km_id
     * @param $memo
     * @param $ff "ff"或空
     * @param $jd
     * @return array
     */
    public static function Do_Create_Voucher_GZ_Item($money, $km_id, $memo, $ff, $jd)
    {
        $belong_time = self::Get_Belong_Time();
        $month_memo = date("Y年n月", strtotime($belong_time));

        // 会计科目 行信息
        $km_info = AccountSubjectModel::find($km_id);
        $number = $km_info->number;
        $km_type = $km_info->type;
        $km_name = $km_info->name;

        // memo
        if ($ff == 'ff') {
            $memo = '发放' . $month_memo . '' . $memo;
        } else {
            $memo = '计提' . $month_memo . '' . $memo;
        }

        // 科目
        $km = $number . ' ' . $km_type . '_' . $km_name;

        $data = ['memo' => $memo, 'km' => $km, 'fx' => $jd, 'money' => $money, 'km_id' => $km_id];
        return $data;
    }

    /**
     * 实发工资
     * @param $money
     * @param $km_code
     * @param $memo
     * @param $ff "ff"或空
     * @return array
     */
    public static function Do_Create_Voucher_RS_Item($money, $km_code, $memo, $ff)
    {
        new Company();
        $company_id = Company::$company->id;

        $belong_time = self::Get_Belong_Time();
        $month_memo = date("Y年n月", strtotime($belong_time));

        // 会计科目 行信息
        $km_info = AccountSubjectModel::query()->where('company_id', $company_id)->where('number', $km_code)->where('status', 1)->first();
        $number = $km_code;
        $km_type = $km_info->type;
        $km_name = $km_info->name;
        $balance_direction = $km_info->balance_direction;
        $km_id = $km_info->id;

        // memo
        if ($ff == 'ff') {
            $memo = '发放' . $month_memo . '' . $memo;
        } else {
            $memo = '计提' . $month_memo . '' . $memo;
        }

        // 科目
        $km = $number . ' ' . $km_type . '_' . $km_name;

        $data = ['memo' => $memo, 'km' => $km, 'fx' => $balance_direction, 'money' => $money, 'km_id' => $km_id];
        return $data;
    }

    /**
     * 给凭证生成接口提供参数
     * @param $param
     * @param $token
     * @return mixed
     */
    public static function Do_Format_VoucherData_Arr($param, $token)
    {
        //\Log::info($param);
        $list = array_reduce($param, 'array_merge', array());
        //\Log::info($list);

        $items = array();
        $total_debit_money = '0.00';
        $total_credit_money = '0.00';
        foreach ($list as $key => $v) {
            $items[$key]['zhaiyao'] = $v['memo'];
            $items[$key]['kuaijikemu_id'] = $v['km_id'];
            $items[$key]['debit_money'] = self::Js_Debit_Money($v['fx'], $v['money']);
            $items[$key]['credit_money'] = self::Js_Credit_Money($v['fx'], $v['money']);
        }

        foreach ($items as $key => $v) {
            $total_debit_money += $v['debit_money'];
            $total_credit_money += $v['credit_money'];
        }

        $data['_token'] = $token;

        $period = Period::currentPeriod();// 获取操作 会计期间
        $voucher_date = Carbon::now();
        // 当前正常会计期间
        $period_now = date("Y-m-01", (strtotime($voucher_date)));
        //$data['voucher_date'] = date("Y-m-d", (strtotime($voucher_date)));//凭证日期
        if($period == $period_now){
            $data['voucher_date'] = date("Y-m-d", (strtotime($voucher_date)));//凭证日期
        }else{
            $data['voucher_date'] = $period;//凭证日期
        }

        $data['voucher_num'] = Voucher::getCurrentMaxVoucherNum($period);//期间内凭证编号
        $data['attach'] = 0;// 附件数量
        $data['voucher_source'] = 12;// 薪酬
        $data['total_debit_money'] = $total_debit_money;
        $data['total_credit_money'] = $total_credit_money;

        if ($total_debit_money > 0 && $total_credit_money > 0 && $total_debit_money == $total_credit_money) {
            $total_cn = sprintf("%.2f", $total_debit_money);
            //\Log::info($total_cn);
            $total_cn = self::Change_CNY($total_cn);
        } else {
            $total_cn = '零元整';
        }
        $data['total_cn'] = $total_cn;

        $data['items'] = $items;

        return $data;
    }

    /**
     * 借方
     * @param $direction
     * @param $money
     * @return string
     */
    public static function Js_Debit_Money($direction, $money)
    {
        if ($direction == "借") {
            $data = $money;
        } else {
            $data = '0.00';
        }
        return $data;
    }

    /**
     * 贷方
     * @param $direction
     * @param $money
     * @return string
     */
    public static function Js_Credit_Money($direction, $money)
    {
        if ($direction == "贷") {
            $data = $money;
        } else {
            $data = '0.00';
        }
        return $data;
    }

    /**
     * 员工薪酬 按费用类型 汇总
     * @param $salary_id
     * @param $cost_name
     * @return array
     */
    public static function Sum_Format_Employee_Salary($salary_id, $cost_name)
    {
        new Company();
        $company_id = Company::$company->id;
        $belong_time = self::Get_Belong_Time();

        $SalaryEmployee = SalaryEmployee::query()->where('company_id', $company_id)->where('salary_id', $salary_id)->where('fylx', $cost_name)->where('belong_time', $belong_time);

        if (count($SalaryEmployee->get()) > 0) {
            $sum_arr = $SalaryEmployee->first(
                array(
                    \DB::raw('SUM(salary) as total_salary'),
                    \DB::raw('SUM(yanglaobx) as total_yanglaobx'),
                    \DB::raw('SUM(yiliaobx) as total_yiliaobx'),
                    \DB::raw('SUM(sybx) as total_sybx'),
                    \DB::raw('SUM(dbyl) as total_dbyl'),
                    \DB::raw('SUM(txf) as total_txf'),
                    \DB::raw('SUM(dkgjj) as total_dkgjj'),
                    \DB::raw('SUM(other_fee) as total_other_fee'),
                    \DB::raw('SUM(personal_tax) as total_personal_tax'),
                    \DB::raw('SUM(real_salary) as total_real_salary'),
                    \DB::raw('SUM(year_bonus) as total_year_bonus'),
                    \DB::raw('SUM(jcfy) as total_jcfy'),
                    \DB::raw('SUM(sfjj) as total_sfjj'),
                    \DB::raw('SUM(lwbc) as total_lwbc'),
                    \DB::raw('SUM(sflwbc) as total_sflwbc')

                )
            )->toArray();
        } else {
            $sum_arr = array(
                'total_salary' => '0.00',
                'total_yanglaobx' => '0.00',
                'total_yiliaobx' => '0.00',
                'total_sybx' => '0.00',
                'total_dbyl' => '0.00',
                'total_txf' => '0.00',
                'total_dkgjj' => '0.00',
                'total_other_fee' => '0.00',
                'total_personal_tax' => '0.00',
                'total_real_salary' => '0.00',
                'total_year_bonus' => '0.00',
                'total_jcfy' => '0.00',
                'total_sfjj' => '0.00',
                'total_lwbc' => '0.00',
                'total_sflwbc' => '0.00',
            );
        }

        return $sum_arr;
    }

    /**
     * 人民币 数字转换中文
     * @param $ns
     * @return mixed
     */
    public static function Change_CNY($ns)
    {
        static $cny_num = array("零", "壹", "贰", "叁", "肆", "伍", "陆", "柒", "捌", "玖"),
        $cny_unit = array("圆", "角", "分"),
        $cny_dw = array("拾", "佰", "仟", "万", "拾", "佰", "仟", "亿");

        @list($ns1, $ns2) = explode(".", $ns, 2);
        //$ns2 = array_filter(array($ns2[1],$ns2[0]));
        $ns2 = str_split($ns2);//此处为新增
        if (isset($ns2[1]))
            $ns2 = array_filter(array($ns2[1], $ns2[0]));
        else
            $ns2 = array_filter(array($ns2[0]));

        $ret = array_merge($ns2, array(implode("", self::CNY_Map_Unit(str_split($ns1), $cny_dw)), ""));
        $ret = implode("", array_reverse(self::CNY_Map_Unit($ret, $cny_unit)));
        $out = str_replace(array_keys($cny_num), $cny_num, $ret);
        if ($ns == round($ns)) $out .= "整";
        return $out;
    }

    /**
     * 人民币 数字转换中文 相关
     * @param $list
     * @param $units
     * @return array
     */
    public static function CNY_Map_Unit($list, $units)
    {
        $ul = count($units);
        $xs = array();
        foreach (array_reverse($list) as $x) {
            $l = count($xs);
            if ($x != "0" || !($l % 4)) $n = ($x == '0' ? '' : $x) . (isset($units[($l - 1) % $ul]) ? $units[($l - 1) % $ul] : '');
            //else $n = is_numeric($xs[0][0]) ? $x : '';
            else $n = '';

            array_unshift($xs, $n);
        }

        return $xs;
    }
}