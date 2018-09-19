<?php
/**
 * Created by PhpStorm V.2018.
 * User: Administrator - Newsboy9248@163.com
 * Date: 2018/6/6 - 16:09
 */

namespace App\Models;

use App\Entity\SubjectBalance;
use App\Entity\Tax;
use App\Models\Accounting\AccountClose;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Validator;

//use App\Entity\Period;
//use Auth;
//use App\Models\AccountSubject;


class Company extends Model
{
    protected $guarded = [];
    protected $table = "company";

    /**
     *  生成企业编码
     *  编码规则：　C + 年月日时分钞　+　R + 4位随机数字
     * @return string
     */
    public static function Create_Company_Code()
    {
        //$now_time = now()->toDateTimeString();
        $time_str = date('YmdHis');

        $random_str = Company::Create_Random(4);

        $code = 'C' . $time_str . 'R' . $random_str;
        return $code;
    }


    /**
     * 生成随机数字
     * @param $num
     * @return string
     */
    public static function Create_Random($num)
    {
        $str = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $random_num = "";
        for ($i = 0; $i < $num; $i++) {
            $random_num .= $str{mt_rand(0, 32)};
        }
        return $random_num;
    }

    /**
     * 生成企业识别码
     * @param $company_code
     * @return mixed|string
     */
    public static function Create_Company_Encode($company_code)
    {
        //$company_code = '';
        if (empty($company_code)) {
            $company_code = Company::Create_Company_Code();
            $code = 'COMPANYENCODE' . $company_code;
        } else {
            $code = 'COMPANYENCODE' . $company_code;
        }

        $encode = encrypt($code);// 加密构造后的企业编码
        $encode = str_replace('=', '', $encode);// 替换＝号
        $encode = str_limit($encode, $limit = 100, $end = '');// 截取100个长
        $encode = trim($encode);

        return $encode;
    }

    /**
     * 进入账簿
     * 根据公司ID及加密识别码取公司信息
     * 扩展：处理公司session信息
     * @param $id
     * @param $company_encode
     * @return \Illuminate\Support\Collection
     */
    public static function Get_Company_info($id, $company_encode)
    {
        $res = DB::table("company")->where("id", $id)->where('company_encode', $company_encode)->first();
        //$res = Company::query()->where('id', $id)->where('company_encode', $company_encode)->first();
        return $res;
    }

    /**
     * 搜索公司列表
     * @param $request
     * @param int $pageSize
     * @return mixed
     */
    public static function search($request, $set_status, $pageSize)
    {
        // 取代账公司id
        //$agent_id = Auth::user()->agent_id;
        $agent_id = Common::loginUser()->agent_id;

        // 取客户信息列表
        $company = DB::table("company")->where('agent_id', '=', $agent_id)->where('deleted_at', null)->orderBy('id', 'DESC');

        if (!empty($request->q)) {
            if ($request->s == 1) {
                $status = 'yes';
            } else {
                $status = 'no';
            }
            $company->where('status', $status);
            $company->where('company_name', 'like', '%' . $request->q . '%')->orwhere('company_code', 'like', '%' . $request->q . '%');
        }

        /*if(!empty($request->s)){
            if($request->s == 1){
                $status = 'yes';
            }else{
                $status = 'no';
            }
            $company->where('status',$status);
        }*/

        if (!empty($set_status)) {
            $company->where('status', $set_status);
        }

        return $company->paginate($pageSize);
    }

    /**
     * 获取科目长度
     * @param $cid
     * @return int
     */
    public static function Get_level_Set($cid)
    {
        //$res = DB::table("company")->where("id", $cid)->first();
        $res = Company::query()->where('id', $cid)->first();

        // 科目长度
        if (empty($res->level_set)) {
            $level = 3;//默认4,2,2
        } else {
            $level = count(explode(",", $res->level_set));
        }

        return $level;
    }

    /**
     * 新版  新增与编辑 公司基本信息
     * @param $param
     * @return array
     * @throws \Exception
     */
    public static function Save_Edit_Base_Info($param)
    {
        $id = $param->id;

        // 新增 OR 编辑
        if (!empty($id) && $id > 0) {
            //$model = Company::find($id);
            $model = Company::query()->where('id', $id)->first();
            $msg = '更新企业信息成功。';

            //$do = 'edit';
        } else {
            $model = new Company();
            $msg = '新增企业信息成功。请补充账套相关信息。';

            $agent_id = Common::loginUser()->agent_id;
            $model->agent_id = $agent_id;

            $now_time = Carbon::now();
            $period_year = date("Y", strtotime($now_time));
            $period_month = date("n", strtotime($now_time));

            $model->level_set = '4,2,2';
            $model->used_year = $period_year;
            $model->used_month = $period_month;

            //$do = 'insert';
        }

        $data = $param->all();

        $rules = [
            "company_name" => 'required|string',
            "credit_code" => 'required|alpha_num|min:18|max:18',
            //"taxpayer_number" => 'required|alpha_num',
            "company_address" => 'required|string',
            "finance_person" => 'required|string',
            "finance_personphone" => 'required|string',
            "taxpayer_rights" => 'required|string',
        ];
        $messages = [
            "company_name.required" => '公司名称必填！',
            "credit_code.required" => '社会统一信用代码必填！',
            "credit_code.alpha_num" => '社会统一信用代码为18位数字/字母！',
            "credit_code.min" => '社会统一信用代码为18位数字、字母字符串！',
            "credit_code.max" => '社会统一信用代码为18位数字、字母字符串！',
            //"taxpayer_number.required" => '纳税人识别号必填！',
            //"taxpayer_number.alpha_num" => '纳税人识别号为15、18或20位数字！',
            "company_address.required" => '营业地址必填！',
            "finance_person.required" => '财务联系人必填！',
            "finance_personphone.required" => '财务联系人联系方式必填！',
            "taxpayer_rights.required" => '纳税人资格必选！',
        ];
        $validator = Validator::make($data, $rules, $messages, []);
        if ($validator->fails()) {
            $result = ['status' => false, 'msg' => $validator->messages()->first(), 'id' => ''];
            return $result;
        }

        $model->company_name = $param->company_name;

        // 如果公司编码为空，补充公司编码
        $company_code = '';
        if (empty($param->company_code)) {
            $company_code = Company::Create_Company_Code();
            $model->company_code = $company_code;
        }

        if (empty($param->company_encode)) {
            $company_encode = Company::Create_Company_Encode($company_code);
            $model->company_encode = $company_encode;
        }

        $model->taxpayer_number = $param->taxpayer_number;
        $model->reg_sort = $param->reg_sort;
        $model->reg_date = $param->reg_date;
        $model->company_sort = $param->company_sort;
        $model->credit_code = $param->credit_code;
        $model->area_id = $param->area_id;
        $model->company_address = $param->company_address;
        $model->scope_business = $param->scope_business;
        $model->legal_person = $param->legal_person;
        $model->legal_personphone = $param->legal_personphone;
        $model->finance_person = $param->finance_person;
        $model->finance_personphone = $param->finance_personphone;
        $model->company_person = $param->company_person;
        $model->company_personphone = $param->company_personphone;
        $model->taxpayer_rights = $param->taxpayer_rights;
        $model->taxpayer_rank = $param->taxpayer_rank;
        $model->registered_capital = $param->registered_capital;
        $model->paidup_capital = $param->paidup_capital;

        $updated_at = now()->toDateTimeString();
        $model->updated_at = $updated_at;

        if ($model->save()) {
            $new_id = $model->id;

            /*if($do == 'insert'){
                // 处理新增事相关初始化操作  转移至初次更新账套时处理
                //$AccountSubject = AccountSubject::companySubjects($model->id);
                //$SubjectBalance = SubjectBalance::subjectBalanceNew($model, $model->used_year.'-'.$model->used_month.'01');
                //$Tax = Tax::initConfig($model);
            }*/
            $result = ['status' => true, 'msg' => $msg, 'id' => $new_id];
        } else {
            $result = ['status' => false, 'msg' => '操作失败。', 'id' => ''];
        }

        return $result;

    }

    /**
     * 新版编辑公司账套信息
     * @param $param
     * @return array
     * @throws \Exception
     * @throws \Throwable
     */
    public static function Save_Edit_Account_Info($param)
    {
        $id = $param->id;
        $subject_len = $param->subject_len;
        //$model = Company::find($id);
        $model = Company::query()->where('id', $id)->first();
        $data = $param->all();
        //$level_set = count(explode(",", $model->level_set));

        $rules = [
            "used_year" => 'required|string',
            "used_month" => 'required|string',
            "accounting_system" => 'required|string',
        ];
        $messages = [
            "used_year.required" => '启用年份必填！',
            "used_month.required" => '启用月份必填！',
            "accounting_system.required" => '会计制度必填！',
        ];
        $validator = Validator::make($data, $rules, $messages, []);
        if ($validator->fails()) {
            $result = ['status' => false, 'msg' => $validator->messages()->first()];
            return $result;
        }

        $model->used_year = $param->used_year;
        $model->used_month = $param->used_month;
        $model->standard_money = $param->standard_money;
        $model->accounting_trade = $param->accounting_trade;

        // 会计制度 不可随便更改
        $old_accounting_system = $model->accounting_system;
        //$new_accounting_system = $param->accounting_system;
        /*if (empty($param->accounting_system)) {
            $model->accounting_system = $param->accounting_system;
        }*/
        $model->accounting_system = $param->accounting_system;

        // 科目长度只能增加不能减少
        $level_set2 = $param->level_set;
        $old_level_set = $model->level_set;
        if ($subject_len <= count($level_set2)) {
            $level_set2 = @join(",", $level_set2);
            $model->level_set = $level_set2;
        } else {
            //$model->level_set = '4,2,2';
            $result = ['status' => false, 'msg' => '保存失败，科目长度不能回调。'];
            return $result;
        }

        $msg = '更新企业账套信息成功。';
        if ($model->save()) {

            // 如果 是初次更新账套信息 相关初始操作
            if (strlen($old_accounting_system) < 1) {
                //\Log::info('初次更新账套相关操作：'.$model->company_name);

                //根据公司表账套里的年月构建初始期间
                $used_year = $model->used_year;
                $used_month = $model->used_month;
                if (strlen($used_month) == 1) {
                    $used_month = '0' . $used_month;
                }
                $fiscal_period = $used_year . '-' . $used_month . '-01';
                AccountSubject::companySubjects($model->id, $model->accounting_system);
                SubjectBalance::subjectBalanceNew($model, $fiscal_period);
                Tax::initConfig($model);
                AccountClose::initializeAccountClose($model->id, $fiscal_period, AccountClose::CLOSE_STATUS_NO);

                $msg = '更新企业账套信息成功；账套相关初始化操作成功。';
            }

            if ($old_level_set != $model['level_set'])
                AccountSubject::updateNumberByLevel($model->id, $old_level_set, $model['level_set']);

            $result = ['status' => true, 'msg' => $msg];
        } else {
            $result = ['status' => false, 'msg' => '操作失败。'];
        }

        return $result;
    }

    /**
     * 删除公司 更新deleted_at时间   不实际删除
     * @param $param
     * @return int
     * @throws \Exception
     */
    public static function delCompany($param)
    {
        try {
            $company_id = $param->id;
            $deleted_at = Carbon::now();
            $data = ['deleted_at' => $deleted_at];
            Company::query()->where('id', $company_id)->update($data);
            return true;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * 获取会计准则
     * @return array
     */
    public static function getAccountingRules()
    {
        return [
            0 => 'company',
            1 => 'littlecompany',
            2 => 'nonprofit',
        ];
    }
}