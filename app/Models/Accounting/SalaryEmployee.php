<?php

namespace App\Models\Accounting;

use App\Entity\Company;
use App\Models\MaatExcel;
use Illuminate\Database\Eloquent\Model;
use App\Entity\Salary as SalaryEntity;

/**
 * App\Models\Accounting\SalaryEmployee
 *
 * @property int $id
 * @property int $company_id 代账公司id
 * @property int $salary_id 薪酬id
 * @property int $employee_id 员工id
 * @property string $employee_name 员工姓名
 * @property float $personal_tax 代扣个税
 * @property int $salary_type 薪酬类型（正常工资、临时工工资……）
 * @property string $fylx 费用类型
 * @property float $year_bonus 全年一次性奖金
 * @property float $jcfy 全年一次性奖金-减除费用（补差）
 * @property float $sfjj 全年一次性奖金-实发奖金
 * @property float $salary 工资
 * @property float $txf 工资-通讯费
 * @property float $yanglaobx 工资-养老保险
 * @property float $yiliaobx 工资-医疗保险
 * @property float $sybx 工资-失业保险
 * @property float $dbyl 工资-大病医疗
 * @property float $dkgjj 工资-代扣公积金
 * @property float $other_fee 工资-其他费用
 * @property float $real_salary 工资-实发工资
 * @property float $lwbc 劳务报酬
 * @property float $sflwbc 实发劳务报酬
 * @property string $remark 备注
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\SalaryEmployee whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\SalaryEmployee whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\SalaryEmployee whereDbyl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\SalaryEmployee whereDkgjj($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\SalaryEmployee whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\SalaryEmployee whereEmployeeName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\SalaryEmployee whereFylx($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\SalaryEmployee whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\SalaryEmployee whereJcfy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\SalaryEmployee whereLwbc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\SalaryEmployee whereOtherFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\SalaryEmployee wherePersonalTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\SalaryEmployee whereRealSalary($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\SalaryEmployee whereRemark($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\SalaryEmployee whereSalary($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\SalaryEmployee whereSalaryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\SalaryEmployee whereSalaryType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\SalaryEmployee whereSfjj($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\SalaryEmployee whereSflwbc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\SalaryEmployee whereSybx($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\SalaryEmployee whereTxf($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\SalaryEmployee whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\SalaryEmployee whereYanglaobx($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\SalaryEmployee whereYearBonus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\SalaryEmployee whereYiliaobx($value)
 * @mixin \Eloquent
 */
class SalaryEmployee extends Model
{
    protected $guarded = [];
    protected $table = 'salary_employee';

    /**
     * 获取员工薪酬初始数据
     * @param $request
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public static function SalaryEmployeeList($request)
    {
        new Company();
        $company_id = Company::$company->id;
        $id = $request->id;
        $belong_time = SalaryEntity::Get_Belong_Time();

        $query = self::query();
        $data = $query->where('company_id', '=', $company_id)->where('salary_id', '=', $id)->where('belong_time', '=', $belong_time)->orderBy('id', 'ASC');

        if (!empty($request->sv)) {
            $data->where('employee_name', 'like', '%' . $request->sv . '%');
        }

        $data = $data->get();

        return $data;
    }

    /**
     * 计算列表项和值
     * @param $request
     * @return array
     */
    public static function SumManyEmployeeSalaryList($request)
    {
        new Company();
        $company_id = Company::$company->id;
        $id = $request->id;
        $belong_time = SalaryEntity::Get_Belong_Time();

        $query = self::query();
        $data = $query->where('company_id', '=', $company_id)->where('salary_id', '=', $id)->where('belong_time', '=', $belong_time)->orderBy('id', 'ASC');

        if (!empty($request->sv)) {
            $data->where('employee_name', 'like', '%' . $request->sv . '%');
        }

        if (count($data->get()) > 0) {
            $sum_arr = $data->first(
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
     * 正常工资薪酬 列表
     * @param $request
     * @return array|string
     */
    public static function SalaryEmployeeJsonList_A($request)
    {
        $data = self::SalaryEmployeeList($request);

        if (count($data) > 0) {
            $list_arr = array();
            foreach ($data as $key => $v) {
                $list_arr[$key]['id'] = $v->employee_id;
                $list_arr[$key]['name'] = $v->employee_name;
                $list_arr[$key]['type'] = $v->fylx;
                $list_arr[$key]['money'] = $v->salary;
                $list_arr[$key]['communication'] = $v->txf;
                $list_arr[$key]['ylbx'] = $v->yanglaobx;
                $list_arr[$key]['Medical'] = $v->yiliaobx;
                $list_arr[$key]['sybx'] = $v->sybx;
                $list_arr[$key]['dbyl'] = $v->dbyl;
                $list_arr[$key]['total'] = self::TotalSheBao(array($v->yanglaobx, $v->yiliaobx, $v->sybx, $v->dbyl, $v->txf));
                $list_arr[$key]['accumulation'] = $v->dkgjj;
                $list_arr[$key]['otherMoney'] = $v->other_fee;
                $list_arr[$key]['dkgs'] = $v->personal_tax;
                $list_arr[$key]['realwages'] = $v->real_salary;
                $list_arr[$key]['bz'] = $v->remark;
                $list_arr[$key]['top'] = true;
                $list_arr[$key]['select'] = false;
                $list_arr[$key]['payroll'] = false;
                $list_arr[$key]['do'] = 'update';
                $list_arr[$key]['se_id'] = $v->id;
            }
        } else {
            $list_arr = '';
        }

        return $list_arr;
    }

    /**
     * 临时工资薪金 列表
     * @param $request
     * @return array|string
     */
    public static function SalaryEmployeeJsonList_B($request)
    {
        $data = self::SalaryEmployeeList($request);

        if (count($data) > 0) {
            $list_arr = array();
            foreach ($data as $key => $v) {
                $list_arr[$key]['id'] = $v->employee_id;
                $list_arr[$key]['name'] = $v->employee_name;
                $list_arr[$key]['moneyType'] = $v->fylx;
                $list_arr[$key]['money'] = $v->salary;
                $list_arr[$key]['ylbx'] = $v->yanglaobx;
                $list_arr[$key]['doctor'] = $v->yiliaobx;
                $list_arr[$key]['sybx'] = $v->sybx;
                $list_arr[$key]['dbbx'] = $v->dbyl;
                $list_arr[$key]['other'] = $v->other_fee;
                $list_arr[$key]['total'] = self::TotalSheBao(array($v->yanglaobx, $v->yiliaobx, $v->sybx, $v->dbyl, $v->other_fee));
                $list_arr[$key]['dkgj'] = $v->dkgjj;
                $list_arr[$key]['sfgz'] = $v->real_salary;
                $list_arr[$key]['top'] = true;
                $list_arr[$key]['select'] = false;
                $list_arr[$key]['payroll'] = false;
                $list_arr[$key]['do'] = 'update';
                $list_arr[$key]['se_id'] = $v->id;
            }
        } else {
            $list_arr = '';
        }

        return $list_arr;
    }

    /**
     * 全年一次性奖金
     * @param $request
     * @return array|string
     */
    public static function SalaryEmployeeJsonList_C($request)
    {
        $data = self::SalaryEmployeeList($request);

        if (count($data) > 0) {
            $list_arr = array();
            foreach ($data as $key => $v) {
                $list_arr[$key]['id'] = $v->employee_id;
                $list_arr[$key]['name'] = $v->employee_name;
                $list_arr[$key]['type'] = $v->fylx;
                $list_arr[$key]['totalMoney'] = $v->year_bonus;
                $list_arr[$key]['daff'] = $v->jcfy;
                $list_arr[$key]['dkgs'] = $v->personal_tax;
                $list_arr[$key]['bonus'] = $v->sfjj;
                $list_arr[$key]['top'] = true;
                $list_arr[$key]['select'] = false;
                $list_arr[$key]['payroll'] = false;
                $list_arr[$key]['do'] = 'update';
                $list_arr[$key]['se_id'] = $v->id;
            }
        } else {
            $list_arr = '';
        }

        return $list_arr;
    }

    /**
     * 外籍人员正常工资薪金  暂缓
     * @param $request
     * @return array|string
     */
    public static function SalaryEmployeeJsonList_D($request)
    {
        $data = self::SalaryEmployeeList($request);

        if (count($data) > 0) {
            $list_arr = array();
            foreach ($data as $key => $v) {
                $list_arr[$key]['id'] = $v->employee_id;
                $list_arr[$key]['name'] = $v->employee_name;
                $list_arr[$key]['type'] = $v->fylx;

                $list_arr[$key]['top'] = true;
                $list_arr[$key]['select'] = false;
                $list_arr[$key]['payroll'] = false;
                $list_arr[$key]['do'] = 'update';
                $list_arr[$key]['se_id'] = $v->id;
            }
        } else {
            $list_arr = '';
        }

        return $list_arr;
    }

    /**
     * 劳务报酬
     * @param $request
     * @return array|string
     */
    public static function SalaryEmployeeJsonList_E($request)
    {
        $data = self::SalaryEmployeeList($request);

        if (count($data) > 0) {
            $list_arr = array();
            foreach ($data as $key => $v) {
                $list_arr[$key]['id'] = $v->employee_id;
                $list_arr[$key]['name'] = $v->employee_name;
                $list_arr[$key]['type'] = $v->fylx;
                $list_arr[$key]['money'] = $v->lwbc;
                $list_arr[$key]['sflwbc'] = $v->sflwbc;
                $list_arr[$key]['dkgs'] = $v->personal_tax;
                $list_arr[$key]['top'] = true;
                $list_arr[$key]['select'] = false;
                $list_arr[$key]['payroll'] = false;
                $list_arr[$key]['do'] = 'update';
                $list_arr[$key]['se_id'] = $v->id;
            }
        } else {
            $list_arr = '';
        }

        return $list_arr;
    }

    /**
     * 计算社保合计  保留2位小数
     * @param $arr
     * @return string
     */
    public static function TotalSheBao($arr)
    {
        $total = array_sum($arr);
        $format_total = sprintf("%.2f", $total);
        return $format_total;
    }


    /**
     * 导出员工薪酬
     * @param $request
     * @return bool
     *
     * MaatExcel::export($table_name, $sheet_name, $data, $sort )
     * $sort:   csv  xls  xlsx
     *
     */
    public static function Export_SalaryEmployee_A($request)
    {
        new Company();
        $company_id = Company::$company->id;
        $salary_id = $request->salary_id;

        $belong_time = SalaryEntity::Get_Belong_Time();

        if ($company_id) {
            if ($request->ids == 'all') {
                SalaryEmployee::where('company_id', $company_id)->where('belong_time', $belong_time)->where('salary_id', $salary_id)->chunk(500, function ($lists) {
                    $cellData[] = array('0' => Company::$company->company_name . '_工资表');
                    $cellData[] = array('0' => '日期：' . SalaryEntity::Get_Belong_Time() . ' ');
                    $cellData[] = ['序号', '姓名', '费用类型', '应发工资', '代扣社保', '', '', '', '', '代扣公积金', '其他费用', '代扣个税', '实发工资', '备注'];
                    $cellData[] = ['', '', '', '', '养老保险', '医疗保险', '失业保险', '大病保险', '合计', '', '', '', '', ''];
                    if (count($lists) > 0) {
                        foreach ($lists as $key => $v) {
                            $cellData[] = [
                                $key + 1,
                                $v->employee_name,
                                $v->fylx,
                                $v->salary,
                                $v->yanglaobx,
                                $v->yiliaobx,
                                $v->sybx,
                                $v->dbyl,
                                SalaryEntity::Js_Total_SheBao(array($v->yanglaobx, $v->yiliaobx, $v->sybx, $v->dbyl)),
                                $v->dkgjj,
                                $v->other_fee,
                                $v->personal_tax,
                                $v->real_salary,
                                $v->remark
                            ];
                        }

                        // 添加合计金额
                        $total = count($lists) + 4;
                        $cellData[] = [
                            '合计：',
                            '',
                            '',
                            '=SUM(D5:D' . $total . ')',
                            '=SUM(E5:E' . $total . ')',
                            '=SUM(F5:F' . $total . ')',
                            '=SUM(G5:G' . $total . ')',
                            '=SUM(H5:H' . $total . ')',
                            '=SUM(I5:I' . $total . ')',
                            '=SUM(J5:J' . $total . ')',
                            '=SUM(K5:K' . $total . ')',
                            '=SUM(L5:L' . $total . ')',
                            '=SUM(M5:M' . $total . ')',
                            ''
                        ];

                        MaatExcel::ExportSalaryEmployee_A($cellData, "xls");
                    }
                });
            } else {
                //批次 导出所选
                $ids_arr = $request->ids;
                $ids_arr = explode(',', $ids_arr);

                SalaryEmployee::where('company_id', $company_id)->where('belong_time', $belong_time)->where('salary_id', $salary_id)->whereIn('id', $ids_arr)->chunk(500, function ($lists) {
                    $cellData[] = array('0' => Company::$company->company_name . '_工资表');
                    $cellData[] = array('0' => '日期：' . SalaryEntity::Get_Belong_Time() . ' ');
                    $cellData[] = ['序号', '姓名', '费用类型', '应发工资', '代扣社保', '', '', '', '', '代扣公积金', '其他费用', '代扣个税', '实发工资', '备注'];
                    $cellData[] = ['', '', '', '', '养老保险', '医疗保险', '失业保险', '大病保险', '合计', '', '', '', '', ''];
                    if (count($lists) > 0) {
                        foreach ($lists as $key => $v) {
                            $cellData[] = [
                                $key + 1,
                                $v->employee_name,
                                $v->fylx,
                                $v->salary,
                                $v->yanglaobx,
                                $v->yiliaobx,
                                $v->sybx,
                                $v->dbyl,
                                SalaryEntity::Js_Total_SheBao(array($v->yanglaobx, $v->yiliaobx, $v->sybx, $v->dbyl)),
                                $v->dkgjj,
                                $v->other_fee,
                                $v->personal_tax,
                                $v->real_salary,
                                $v->remark
                            ];
                        }

                        // 添加合计金额
                        $total = count($lists) + 4;
                        $cellData[] = [
                            '合计：',
                            '',
                            '',
                            '=SUM(D5:D' . $total . ')',
                            '=SUM(E5:E' . $total . ')',
                            '=SUM(F5:F' . $total . ')',
                            '=SUM(G5:G' . $total . ')',
                            '=SUM(H5:H' . $total . ')',
                            '=SUM(I5:I' . $total . ')',
                            '=SUM(J5:J' . $total . ')',
                            '=SUM(K5:K' . $total . ')',
                            '=SUM(L5:L' . $total . ')',
                            '=SUM(M5:M' . $total . ')',
                            ''
                        ];

                        MaatExcel::ExportSalaryEmployee_A($cellData, "xls");
                    }
                });
            }

        } else {
            return false;
        }
    }
}
