<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/27
 * Time: 15:06
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Entity\Company;

class Department extends Model
{
    protected $table = "department";

    /**
     * 部门列表
     */
    public static function DepartmentList()
    {
        new Company();
        $company_id = Company::$company->id;
        //$id = $request->id;
        $query = Department::query();
        $data = $query->where('company_id', '=', $company_id)->where('status', '1')->get();
        return $data;
    }

    /**
     * json ZTREE 数据
     * @param $request
     * @return array
     */
    public static function DepartmentListJson($request)
    {
        new Company();
        $company_id = Company::$company->id;

        $query = Department::query();
        $data = $query->where('company_id', '=', $company_id)->get();
        $now_department_id = $request->id;

        $CompanyName = self::DepartmentCompanyName();
        $list_arr0 = array(array('id' => 0, 'pId' => 0, 'name' => $CompanyName, 'open' => true));

        if ($data) {
            $list_arr = array();
            foreach ($data as $key => $v) {
                $list_arr[$key]['id'] = $v->id;
                $list_arr[$key]['pId'] = 0;
                $list_arr[$key]['name'] = $v->department_name;
                $list_arr[$key]['url'] = route('department.index', ['id' => $v->id]);
                $list_arr[$key]['target'] = '_self';
                $list_arr[$key]['click'] = "change_employee_list('$v->id');";
                $list_arr[$key]['now_depId'] = $now_department_id;
            }

            $list_arr = array_merge($list_arr0, $list_arr);
        } else {
            $list_arr = $list_arr0;
        }

        /*if (isset($list_arr)) {
            return $list_arr;
        }*/

        return $list_arr;
    }

    /**
     * 部门人员 公司ID
     * @return mixed
     */
    public static function DepartmentCompanyId()
    {
        new Company();
        $company_id = Company::$company->id;
        return $company_id;
    }

    /**
     * 部门人员 公司名称
     * @return mixed
     */
    public static function DepartmentCompanyName()
    {
        new Company();
        $company_name = Company::$company->company_name;
        return $company_name;
    }

    /**
     * 部门ID
     * @param $request
     * @return mixed
     */
    public static function DepartmentId($request)
    {
        $id = $request->id;
        return $id;
    }

    /**
     * 部门名称
     * @param $request
     * @return mixed
     */
    public static function DepartmentName($request)
    {
        if (!empty($request->id) && $request->id >= 1) {
            $info = Department::find($request->id);
            if($info){
                $name = $info->department_name;
            }else{
                $name = '';
            }
        } else {
            $name = '';
        }

        return $name;
    }

}