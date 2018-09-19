<?php

namespace App\Models\Accounting;

use App\Entity\Company;
use Illuminate\Database\Eloquent\Model;
use App\Entity\Salary as SalaryEntity;
use DB;
use App\Models\AccountSubject as AccountSubjectModel;

/**
 * Class Salary
 * @package App\Models\Accounting
 */
class Salary extends Model
{
    protected $guarded = [];
    protected $table = 'salary';

    /**
     * 获取薪酬初始数据
     * @param $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function SalaryList($request)
    {
        //dd($request);
        new Company();
        $company_id = Company::$company->id;
        //$query = \App\Models\Accounting\Salary::query();

        $belong_time = SalaryEntity::Get_Belong_Time();
        $belong_time_year = SalaryEntity::Get_Belong_Time_Year();

        $belong_time_arr = array($belong_time, $belong_time_year);

        $query = self::query();
        $data = $query->where('company_id', '=', $company_id)->whereIn('belong_time', $belong_time_arr)->orderBy('id', 'ASC');

        return $data;
    }

    /**
     * 分页
     * @param $request
     * @param $pageSize
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function pager($request, $pageSize)
    {
        $data = self::SalaryList($request);
        return $data->paginate($pageSize);
    }

    /**
     * 格式化薪酬主表数据
     * @param $request
     * @param $pageSize
     * @return array|string
     */
    public static function SalaryJsonList($request, $pageSize)
    {
        $data = self::SalaryList($request);
        $data = $data->paginate($pageSize);

        if ($data) {
            $list_arr = array();
            foreach ($data as $key => $v) {
                $list_arr[$key]['id'] = $v->id;
                $list_arr[$key]['xclx'] = $v->xclx;
                $list_arr[$key]['compensationType'] = SalaryEntity::Change_Salary_Cn($v->xclx);
                $list_arr[$key]['compensationStart'] = SalaryEntity::Get_Belong_Time_Start($v->begin_date);
                $list_arr[$key]['compensationEnd'] = SalaryEntity::Get_Belong_Time_End($v->end_date);
                $list_arr[$key]['number'] = SalaryEntity::Get_Belong_Time_Salary_Num($v->id);
                $list_arr[$key]['payment'] = SalaryEntity::Change_Pay_Cn($v->pay_type_id);
                $list_arr[$key]['Certificate'] = SalaryEntity::Get_Certificate_Name_By_Id($v->voucher_id);
                $list_arr[$key]['Certificate_id'] = $v->voucher_id;

                // 编辑与查看状态
                if(empty($v->voucher_id)){
                    $list_arr[$key]['status'] = '0';
                    $list_arr[$key]['setUp'] = true;
                }else{
                    $list_arr[$key]['status'] = '1';
                    $list_arr[$key]['setUp'] = false;
                }

                // 附加
                $list_arr[$key]['pay_type_id'] = $v->pay_type_id;
                $list_arr[$key]['bank_account_id'] = $v->bank_account_id;
            }
        } else {
            return '';
        }

        return $list_arr;
    }

    /**
     * 支付方式options
     * @return string
     */
    public static function PayOptions()
    {
        $data = SalaryEntity::$PAY_Labels;

        $data_options = '<option value="">请选择</option>';
        foreach ($data as $key => $v) {
            $data_options .= '<option value="'.$key.'">'.$v.'</option>';
        }

        return $data_options;
    }

    /**
     * 薪酬类型options
     * @return string
     */
    public static function SalaryTypeOptions()
    {
        $data = SalaryEntity::$Salary_Labels;

        $data_options = '<option value="">请选择</option>';
        foreach ($data as $key => $v) {
            $data_options .= '<option value="'.$key.'">'.$v.'</option>';
        }

        return $data_options;
    }

    /**
     * 当前公司已开的银行账户options
     * @return string
     */
    public static function BankOptions()
    {
        new Company();
        $company_id = Company::$company->id;

        // 检索该公司银行账户组装相关数据
        $data = DB::table("bank_accounts")->where('company_id', $company_id)->where('status', 1)->orderBy('id', 'Asc')->get();

        if(count($data) > 0)
        {
            $data_options = '<option value="">请选择</option>';
            foreach ($data as $key => $v) {
                $data_options .= '<option value="'.$v->id.'">'.$v->name.'</option>';
            }
        } else {
            $data_options = '<option value="">请先添加银行账户</option>';
        }
        return $data_options;
    }

    /**
     * 公司类型options
     * @return string
     */
    public static function CompanySortOptions()
    {
        $data = SalaryEntity::$Company_Sort_Labels;
        $data_options = '<option value="">请选择</option>';
        foreach ($data as $key => $v) {
            $data_options .= '<option value="'.$key.'">'.$v.'</option>';
        }

        return $data_options;
    }

    /**
     * 征收方式options
     * @return string
     */
    public static function ZsfsOptions()
    {
        $data = SalaryEntity::$ZSFS_Labels;
        $data_options = '<option value="">请选择</option>';
        foreach ($data as $key => $v) {
            $data_options .= '<option value="'.$key.'">'.$v.'</option>';
        }

        return $data_options;
    }

    /**
     * 取会计科目列表options
     * @return string
     */
    public static function GetAccountSubjectOptions()
    {
        new Company();
        $company_id = Company::$company->id;
        $status = AccountSubjectModel::USED;
        //$company_id_arr = array(0, $company_id);

        $list = AccountSubjectModel::query()->where('company_id',$company_id)
            ->where('status',$status)->get();//->where("pid",0)

        $account_options = '<option value="">请选择</option>';
        foreach ($list as $key => $v) {
            $account_options .= '<option value="'.$v->id.'">'.$v->number.' '.$v->type.'_'.$v->name.'</option>';
        }

        return $account_options;
    }

    /**
     * 删除薪酬凭证操作
     * @param $param
     * @return bool
     */
    public static function Del_Salary_Voucher_Number($param)
    {
        // $param = [82,83];
        $list = array();
        foreach ($param as $key => $v) {
            $list[$key] = self::Update_Salary_Voucher_Number($v);
        }
        //\Log::info($list);

        return true;
    }

    /**
     * 更新薪酬ID
     * @param $id
     * @return bool
     */
    public static function Update_Salary_Voucher_Number($id)
    {
        $info = Salary::query()->where('voucher_id',$id)->first();
        if($info){
            $salary_id = $info->id;
            $data = array('voucher_id' => 0);
            Salary::query()->where('id',$salary_id)->update($data);
        }

        return true;
    }

    /**
     * 检查薪酬凭证科目配置是否已设置
     * @return bool
     */
    public static function Check_KM_Config()
    {
        $company = Company::sessionCompany();
        $company_id = $company->id;
        $salary_config = SalaryConfig::query()->where('company_id', $company_id)->whereIn('status',[0,1])->get();
        $salary_cost_config = SalaryCostConfig::query()->where('company_id', $company_id)->whereIn('status',[0,1])->get();
        if(count($salary_config) == 1 && count($salary_cost_config) == 1){
            return false;
        }else{
            return true;
        }
    }
}
