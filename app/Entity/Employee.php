<?php

namespace App\Entity;

//use DB;
use Validator;
use App\Models\Employee as EmployeeModel;

/**
 * 雇员类
 * Class Employee
 * @package App\Entity
 */
class Employee
{
    //是与否
    const SUB_PD_YES = 1;//是
    const SUB_PD_NO = 0;//否
    const SUB_PD_NULL = '';//为空默认为否

    //国籍 country
    const SUB_COUNTRY_CN = 0;//境内
    const SUB_COUNTRY_EN = 1;//境外
    const SUB_COUNTRY_EN_NULL = '';//为空默认为境内

    //证件类型 境内
    const SUB_TYPE_ZJLX_JMSFZ = 0;//居民身份证
    const SUB_TYPE_ZJLX_JGZ = 1;//军官证
    const SUB_TYPE_ZJLX_SBZ = 2;//士兵证
    const SUB_TYPE_ZJLX_WJJGZ = 3;//武警警官证
    const SUB_TYPE_ZJLX_ZGHZ = 4;//中国护照

    //证件类型 境外
    //const SUB_TYPE_ZJLX_ZGHZ = 4;//中国护照
    const SUB_TYPE_ZJLX_WGHZ = 5;//外国护照
    const SUB_TYPE_ZJLX_XGSFZ = 6;//香港身份证
    const SUB_TYPE_ZJLX_XGYGSFZ = 7;//香港永久性居民身份证
    const SUB_TYPE_ZJLX_AMSFZ = 8;//澳门身份证
    const SUB_TYPE_ZJLX_AMYGSFZ = 9;//澳门永久性居民身份证
    const SUB_TYPE_ZJLX_GANDTXZ = 10;//港澳居民来往内地通行证
    const SUB_TYPE_ZJLX_TWDLTXZ = 11;//台湾居民来往大陆通行证
    const SUB_TYPE_ZJLX_TWNDTXZ = 12;//台湾居民来往内地通行证
    const SUB_TYPE_ZJLX_TWSFZ = 13;//台湾身份证
    const SUB_TYPE_ZJLX_WGYJSFZ = 14;//外国人永久居留身份证（外国人永久居留证）
    const SUB_TYPE_ZJLX_WGYJJLZ = 15;//外国人永久居留证
    const SUB_TYPE_ZJLX_WJGZ = 16;//外交官证

    //性别
    const SUB_SEX_MALE = 0;//男
    const SUB_SEX_FEMALE = 1;//女

    //人员状态
    const SUB_MEMBER_STATUS_0 = 0;//非正常
    const SUB_MEMBER_STATUS_1 = 1;//正常

    //判断是与否
    public static $PD_Labels = [
        self::SUB_PD_YES => '是',
        self::SUB_PD_NO => '否',
        self::SUB_PD_NULL => '否',
    ];

    //国籍数组
    public static $COUNTRY_Labels = [
        self::SUB_COUNTRY_CN => '境内',
        self::SUB_COUNTRY_EN => '境外',
        self::SUB_COUNTRY_EN_NULL => '境内',
    ];

    //证件类型数组
    public static $ZJLX_Labels = [
        self::SUB_TYPE_ZJLX_JMSFZ => '居民身份证',
        self::SUB_TYPE_ZJLX_JGZ => '军官证',
        self::SUB_TYPE_ZJLX_SBZ => '士兵证',
        self::SUB_TYPE_ZJLX_WJJGZ => '武警警官证',
        self::SUB_TYPE_ZJLX_ZGHZ => '中国护照',
        self::SUB_TYPE_ZJLX_WGHZ => '外国护照',
        self::SUB_TYPE_ZJLX_XGSFZ => '香港身份证',
        self::SUB_TYPE_ZJLX_XGYGSFZ => '香港永久性居民身份证',
        self::SUB_TYPE_ZJLX_AMSFZ => '澳门身份证',
        self::SUB_TYPE_ZJLX_AMYGSFZ => '澳门永久性居民身份证',
        self::SUB_TYPE_ZJLX_GANDTXZ => '港澳居民来往内地通行证',
        self::SUB_TYPE_ZJLX_TWDLTXZ => '台湾居民来往大陆通行证',
        self::SUB_TYPE_ZJLX_TWSFZ => '台湾身份证',
        self::SUB_TYPE_ZJLX_WGYJSFZ => '外国人永久居留身份证（外国人永久居留证）',
        self::SUB_TYPE_ZJLX_WGYJJLZ => '外国人永久居留证',
        self::SUB_TYPE_ZJLX_WJGZ => '外交官证',
    ];

    //性别数组
    public static $SEX_Labels = [
        self::SUB_SEX_MALE => '男',
        self::SUB_SEX_FEMALE => '女',
    ];

    //人员状态数组
    public static $MEMBER_Status_Labels = [
        self::SUB_MEMBER_STATUS_0 => '非正常',
        self::SUB_MEMBER_STATUS_1 => '正常',
    ];

    /**
     * 保存员工信息
     * @param $param
     * @return array
     * @throws \Throwable
     * $result  返回  status:true/false   msg:txt类型
     */
    public static function SaveEmployee($param)
    {
        new Company();

        $data = $param->all();

        // 添加、编辑员工 验证  （***后续需优化严格验证***）
        $rules = [
            'employee_name' => 'required',
            'zjlx' => 'required',
            'zjhm' => 'required',
            'country' => 'required',
            'sf_cjlsgl' => 'required',
            'sf_employee' => 'required',
            'lxdh' => 'required',
            'email' => 'required|email',//required|email
            'company_name' => 'required',
            'employee_num' => 'required',
            'birthday' => 'required|date',
            'status' => 'required',
            'sf_shareholder' => 'required',
            'employee_num' => 'required',
            'sf_tdhy' => 'required',
        ];
        $messages = [
            'employee_name.required' => '请输入员工姓名',
            'zjlx.required' => '证件类型必选',
            'zjhm.required' => '证件号码必填',
            'country.required' => '国籍地区必选',
            'sf_cjlsgl.required' => '是否残疾烈属孤老必选',
            'sf_employee.required' => '是否雇员必选',
            'lxdh.required' => '联系电话必填',
            'email.required' => '电子邮件必填',
            'email.email' => '邮件格式不正确',
            'company_name.required' => '工作单位必填',
            'employee_num.required' => '工号必填',
            'birthday.required' => '出生年月必填',
            'status.required' => '人员状态必选',
            'sf_shareholder.required' => '股东、投资者必选',
            'employee_num.required' => '工号必填',
            'sf_tdhy.required' => '特定行业必选',

        ];
        $validator = Validator::make($data, $rules, $messages, []);
        if ($validator->fails()) {
            $result = array('status' => false, 'msg' => $validator->messages()->first());
            return $result;
        }

        // 附加处理数据
        $data['company_id'] = Company::$company->id;
        //$data['birthday'] = date('Y-m-d', $data['birthday']);
        $data['gender'] = EmployeeModel::CheckSex_Code($data['gender']);
        $data['zjlx'] = EmployeeModel::Checkzjlx_Code($data['zjlx']);
        $data['country'] = EmployeeModel::CheckCountry_Code($data['country']);
        $data['status'] = EmployeeModel::CheckStatus_Code($data['status']);
        $data['sf_shareholder'] = EmployeeModel::CheckYesOrNo_Code($data['sf_shareholder']);
        $data['sf_cjlsgl'] = EmployeeModel::CheckYesOrNo_Code($data['sf_cjlsgl']);
        $data['sf_tdhy'] = EmployeeModel::CheckYesOrNo_Code($data['sf_tdhy']);
        $data['sf_employee'] = EmployeeModel::CheckYesOrNo_Code($data['sf_employee']);

        $result = array('status' => true, 'msg' => '数据异常');
        if ($data['do'] == 'insert') {
            // 新增
            $status = self::add($data);

            $result = array('status' => $status, 'msg' => '新增员工成功');
        } elseif ($data['do'] == 'update') {
            // 修改
            $status = self::update($data);

            $result = array('status' => $status, 'msg' => '编辑员工成功');
        } else {
            $result['status'] = true;
            $result['msg'] = '操作失败';
        }

        return $result;
    }

    /**
     * 添加员工
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
                'employee_num' => $param['employee_num'],
                'employee_name' => $param['employee_name'],
                'company_name' => $param['company_name'],
                'lxdh' => $param['lxdh'],
                'gender' => $param['gender'],
                'zjlx' => $param['zjlx'],
                'zjhm' => $param['zjhm'],
                'email' => $param['email'],
                'birthday' => $param['birthday'],
                'remark' => $param['remark'],
                'status' => $param['status'],
                'sf_shareholder' => $param['sf_shareholder'],
                'country' => $param['country'],
                'sf_cjlsgl' => $param['sf_cjlsgl'],
                'sf_tdhy' => $param['sf_tdhy'],
                'sf_employee' => $param['sf_employee'],
            );

            /*DB::transaction(function () use ($param) {
            }, 5);*/

            $status = EmployeeModel::create($data);
            return $status;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * 编辑更新员工
     * @param $param
     * @return mixed
     * @throws \Exception
     */
    public static function update($param)
    {
        try {
            // 处理更新
            $data = array(
                'company_id' => $param['company_id'],
                'employee_num' => $param['employee_num'],
                'employee_name' => $param['employee_name'],
                'company_name' => $param['company_name'],
                'lxdh' => $param['lxdh'],
                'gender' => $param['gender'],
                'zjlx' => $param['zjlx'],
                'zjhm' => $param['zjhm'],
                'email' => $param['email'],
                'birthday' => $param['birthday'],
                'remark' => $param['remark'],
                'status' => $param['status'],
                'sf_shareholder' => $param['sf_shareholder'],
                'country' => $param['country'],
                'sf_cjlsgl' => $param['sf_cjlsgl'],
                'sf_tdhy' => $param['sf_tdhy'],
                'sf_employee' => $param['sf_employee'],
            );

            $status = EmployeeModel::where('id', $param['id'])->update($data);
            return $status;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * 更新单个员工部门ID
     * @param $id
     * @param $department_id
     * @return mixed
     * @throws \Exception
     */
    public static function update_department_id($id, $department_id)
    {
        try {
            // 处理更新
            $data = array(
                'department_id' => $department_id,
            );

            $status = EmployeeModel::where('id', $id)->update($data);
            return $status;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * 批量更新员工部门
     * @param $ids
     * @param $department_id
     * @return bool
     * @throws \Exception
     */
    public static function updateDepartmentIds($ids, $department_id)
    {
        try {
            foreach ($ids as $id) {
                self::update_department_id($id, $department_id);
            }

            return true;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}