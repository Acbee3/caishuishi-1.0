<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/27
 * Time: 15:06
 */

namespace App\Models;

use App\Entity\Employee as EmployeeClass;
use Illuminate\Database\Eloquent\Model;
use App\Entity\Company;

class Employee extends Model
{
    protected $guarded = [];
    protected $table = "employee";

    const pageSize = 15;

    /**
     * 全部人员列表
     * @param $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function EmployeeList($request)
    {
        new Company();
        $company_id = Company::$company->id;
        $query = Employee::query();

        // 根据部门取数据
        $department_id = $request->dep_id;
        if (empty($department_id)) {
            $data = $query->where('company_id', '=', $company_id);
        } else {
            $data = $query->where('company_id', '=', $company_id)->where('department_id', '=', $department_id);
        }

        // 搜索  姓名employee_name   证件号码zjhm
        if ($request->tit && $request->sv && $request->tit == '姓名') {
            $data->where('employee_name', 'like', '%' . $request->sv . '%')->orderBy('id', 'DESC');
        } elseif ($request->tit && $request->sv && $request->tit == '证件号码') {
            $data->where('zjhm', 'like', '%' . $request->sv . '%')->orderBy('id', 'DESC');
        } else {
            $data = $query->orderBy('id', 'DESC');
        }

        return $data;
    }

    /**
     * 获取该公司员工总数量
     * @return int
     */
    public static function EmployeeNum()
    {
        new Company();
        $company_id = Company::$company->id;
        $list = Employee::query()->where('company_id', $company_id)->get();
        $num = count($list);
        return $num;
    }

    /**
     * 人员分页处理
     * @param $request
     * @param $pageSize
     * @return mixed
     */
    public static function pager($request, $pageSize)
    {
        $data = self::EmployeeList($request);
        return $data->paginate($pageSize);
    }

    /**
     * 全部人员列表 处理后的数据
     * @param $request
     * @param $pageSize
     * @return array|string
     */
    public static function EmployeeJsonList($request, $pageSize)
    {
        $data = self::EmployeeList($request);
        $data = $data->paginate($pageSize);

        if ($data) {
            $list_arr = array();
            foreach ($data as $key => $v) {
                $list_arr[$key]['id'] = $v->id;
                $list_arr[$key]['employee_name'] = $v->employee_name;
                $list_arr[$key]['zjlx'] = self::Checkzjlx($v->zjlx);
                $list_arr[$key]['zjhm'] = $v->zjhm;
                $list_arr[$key]['country'] = self::CheckCountry($v->country);
                $list_arr[$key]['sf_shareholder'] = self::CheckYesOrNo($v->sf_shareholder);
                $list_arr[$key]['department_name'] = self::FindDepartmentById($v->department_id);
                $list_arr[$key]['personnelState'] = $v->status;
            }
        } else {
            return '[]';
        }

        return $list_arr;
    }

    /**
     * 证件类型转中文
     * @param $val
     * @return string
     * 表需要更新 然后根据表内容 判断 转中文文本
     */
    public static function Checkzjlx($val)
    {
        $arr = EmployeeClass::$ZJLX_Labels;
        return $arr[$val];
    }

    /**
     * 证件类型转代码
     * @param $str
     * @return false|int|string
     */
    public static function Checkzjlx_Code($str)
    {
        $arr = EmployeeClass::$ZJLX_Labels;
        $val = array_search($str, $arr);
        return $val;
    }

    /**
     * 国籍处理
     * 境内：中国    境外：空或其他
     * @param $val
     * @return string
     */
    public static function CheckCountry($val)
    {
        $arr = EmployeeClass::$COUNTRY_Labels;
        return $arr[$val];
    }

    /**
     * 国籍转代码
     * @param $str
     * @return false|int|string
     */
    public static function CheckCountry_Code($str)
    {
        $arr = EmployeeClass::$COUNTRY_Labels;
        $val = array_search($str, $arr);
        return $val;
    }

    /**
     * 性别 性别代码转中文
     * 0：男  1：女
     * @param $val
     * @return string
     */
    public static function CheckSex($val)
    {
        $arr = EmployeeClass::$SEX_Labels;
        return $arr[$val];
    }

    /**
     * 性别转代码
     * 0：男  1：女
     * @param $str
     * @return false|int|string
     */
    public static function CheckSex_Code($str)
    {
        $arr = EmployeeClass::$SEX_Labels;
        $val = array_search($str, $arr);
        return $val;
    }

    /**
     * 人员状态转中文
     * 0：非正常  1：正常
     * @param $val
     * @return string
     */
    public static function CheckStatus($val)
    {
        $arr = EmployeeClass::$MEMBER_Status_Labels;
        return $arr[$val];
    }

    /**
     * 人员状态转代码
     * @param $str
     * @return false|int|string
     */
    public static function CheckStatus_Code($str)
    {
        $arr = EmployeeClass::$MEMBER_Status_Labels;
        $val = array_search($str, $arr);
        return $val;
    }

    /**
     * 是、否转中文
     * 0: 否    1：是
     * @param $val
     * @return string
     */
    public static function CheckYesOrNo($val)
    {
        $arr = EmployeeClass::$PD_Labels;
        return $arr[$val];
    }

    /**
     * 是、否转代码
     * @param $str
     * @return false|int|string
     */
    public static function CheckYesOrNo_Code($str)
    {
        $arr = EmployeeClass::$PD_Labels;
        $val = array_search($str, $arr);
        return $val;
    }

    /**
     * 根据部门ID查找部门名称
     * @param $id
     * @return mixed
     */
    public static function FindDepartmentById($id)
    {
        if ($id) {
            $row_info = Department::find($id);
            $department_name = $row_info->department_name;
        } else {
            $department_name = '--';
        }
        return $department_name;
    }

    /**
     * 导出员工
     * @param $request
     * @return bool
     *
     * MaatExcel::export($table_name, $sheet_name, $data, $sort )
     * $sort:   csv  xls  xlsx
     *
     */
    public static function ExportEmployee($request)
    {
        new Company();
        $company_id = Company::$company->id;
        //$company_name = Company::$company->company_name;

        if ($company_id) {
            if ($request->ids == 'all') {
                //批次 导出全部员工
                Employee::query()->where('company_id', $company_id)->chunk(200, function ($lists) {
                    $cellData[] = array('0' => Company::$company->company_name . '_员工明细表');
                    $cellData[] = ['工号', '姓名', '国籍（地区）', '证照类型', '证照号码', '是否残疾烈属孤老', '是否雇员', '是否股东、投资者', '是否特定行业', '人员状态', '联系电话'];
                    foreach ($lists as $key => $v) {
                        $cellData[] = [
                            $v->employee_num,
                            $v->employee_name,
                            self::CheckCountry($v->country),
                            self::Checkzjlx($v->zjlx),
                            "\t".$v->zjhm."\t",
                            self::CheckYesOrNo($v->sf_cjlsgl),
                            self::CheckYesOrNo($v->sf_employee),
                            self::CheckYesOrNo($v->sf_shareholder),
                            self::CheckYesOrNo($v->sf_tdhy),
                            self::CheckStatus($v->status),
                            $v->lxdh
                        ];
                    }

                    MaatExcel::Export_Employee($cellData, "xls");
                });
            } else {
                //批次 导出所选
                $ids_arr = $request->ids;
                $ids_arr = explode(',', $ids_arr);

                Employee::query()->where('company_id', $company_id)->whereIn('id', $ids_arr)->chunk(200, function ($lists) {

                    $cellData[] = array('0' => Company::$company->company_name . '_员工明细表');
                    $cellData[] = ['工号', '姓名', '国籍（地区）', '证照类型', '证照号码', '是否残疾烈属孤老', '是否雇员', '是否股东、投资者', '是否特定行业', '人员状态', '联系电话'];
                    foreach ($lists as $key => $v) {
                        $cellData[] = [
                            $v->employee_num,
                            $v->employee_name,
                            self::CheckCountry($v->country),
                            self::Checkzjlx($v->zjlx),
                            "\t".$v->zjhm."\t",
                            self::CheckYesOrNo($v->sf_cjlsgl),
                            self::CheckYesOrNo($v->sf_employee),
                            self::CheckYesOrNo($v->sf_shareholder),
                            self::CheckYesOrNo($v->sf_tdhy),
                            self::CheckStatus($v->status),
                            $v->lxdh
                        ];
                    }

                    MaatExcel::Export_Employee($cellData, "xls");
                });
            }

            return true;
        } else {
            return false;
        }
    }


    // 批量导入员工
    public static function ImportEmployee($request)
    {
        new Company();
        $company_id = Company::$company->id;

        $data = $request->all();
        $file_path = $data['file_path'];// 员工信息路径
        $file_Name = $data['file_Name'];// 员工文件名称
        $type_Name = $data['type_Name'];// 员工信息文件后缀
        $cid = $data['cid'];

        $cfs_file = $data['cfs_file'];

        if (empty($file_path) || empty($file_Name) || empty($type_Name)) {
            $result = array('status' => false, 'msg' => '请上传员工信息文件!');
            return $result;
        }

        if ($company_id != $cid) {
            $result = array('status' => false, 'msg' => '公司信息异常，请重新登录操作!');
            return $result;
        }

        // 处理excel上传逻辑  20180710
        // 暂时只处理导入数据，后续优化可以创建文件夹（base_addr/YMD/company_id/文件），存储上传文件，采用队列或计划任务处理导入操作，
        // session记录导入错误数据，队列续执行
        if ($type_Name == 'xlsx' || $type_Name == 'xls') {
            $excel_file_path = $file_path;
            MaatExcel::Import_Employee($excel_file_path, $cfs_file, $type_Name);

            $result = array('status' => true, 'msg' => '批量导入员工成功!');
        } else {
            $result = array('status' => false, 'msg' => '请上传xls或xlsx后缀的员工文件!');
        }

        return $result;
    }

    /**
     * 更新人员状态
     * @param $request
     * @return bool
     * @throws \Exception
     */
    public static function ChangeStatus($request)
    {
        new Company();
        $company_id = Company::$company->id;

        if ($company_id) {
            try {
                if ($request->status == 0) {
                    $status = 1;
                } else {
                    $status = 0;
                }
                $data = array('status' => $status);
                $result = Employee::query()->where('id', $request->id)->update($data);
                return $result;
            } catch (\Exception $e) {
                throw $e;
            }
        } else {
            return false;
        }
    }


    /**
     * 批量更新部门
     * @param $request
     * @return bool
     * @throws \Exception
     */
    public static function ChangeDepartment($request)
    {
        $ids = $request->ids;
        $department_id = $request->dep_id;

        if (!empty($ids) && !empty($department_id) && $department_id > 0) {
            $result = EmployeeClass::updateDepartmentIds($ids, $department_id);
            return $result;
        } else {
            return false;
        }
    }


    /**
     * 批量操作员工->  调整部门 ->   获取部门
     * @param $request
     * @return array
     */
    public static function GetDepartment($request)
    {
        new Company();
        $company_id = $request->cid;

        $department_data = Department::DepartmentList();

        if ($company_id == Company::$company->id && !empty($company_id) && !empty(Company::$company->id) && !empty($department_data)) {
            $department_options = '';
            foreach ($department_data as $key => $v) {
                $department_options .= '<option value="' . $v->id . '">' . $v->department_name . '</option>';
            }

            $options = $department_options;
            $result = array('status' => true, 'msg' => '您还没有新建部门，请先新建部门!', 'options' => $options);
        } else {
            $options = '<option value="">请选择</option>';
            $result = array('status' => true, 'msg' => '您还没有新建部门，请先新建部门!', 'options' => $options);
        }

        return $result;
    }

}