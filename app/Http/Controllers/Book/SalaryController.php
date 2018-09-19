<?php

namespace App\Http\Controllers\Book;

use App\Entity\Salary AS SalaryEntity;
use App\Http\Controllers\Controller;
use App\Models\Accounting\SalaryEmployee;
use Illuminate\Http\Request;
use App\Models\Accounting\Salary;
use App\Entity\Voucher;
use App\Entity\Profit;
use App\Models\Common;

/**
 * 薪酬操作控制器
 * Class SalaryController
 * @package App\Http\Controllers\Book
 */
class SalaryController extends Controller
{
    /**
     * 薪酬 主列表
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function list(Request $request)
    {
        //薪酬类型
        $salary_options = Salary::SalaryTypeOptions();
        //当前公司已开的银行账户
        $bank_options = Salary::BankOptions();
        //支付方式
        $pay_options = Salary::PayOptions();
        //公司类型
        $company_sort_options = Salary::CompanySortOptions();
        //征收方式
        $zsfs_options = Salary::ZsfsOptions();

        $paymentOptions = SalaryEntity::$Salary_Labels;

        $pageSize = SalaryEntity::pageSize;
        $data = Salary::pager($request, $pageSize);

        //$belong_time = SalaryEntity::Get_Belong_Time();
        //\Log::info($belong_time);

        return view('book.salary.list', compact('salary_options', 'bank_options', 'pay_options', 'company_sort_options', 'zsfs_options', 'request', 'paymentOptions', 'data'));
    }

    /**
     * 获取薪酬列表数据
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function api_get_salary(Request $request)
    {
        $pageSize = SalaryEntity::pageSize;
        $data['items'] = Salary::SalaryJsonList($request, $pageSize);
        $data['show'] = Salary::Check_KM_Config();// 检查凭证科目配置窗口是否需要显示

        if ($data['items']) {
            return response()->json(['status' => 'success', 'msg' => 'data success！', 'data' => $data]);
        } else {
            return response()->json(['status' => 'err', 'msg' => 'data err！', 'data' => '']);
        }
    }

    /**
     * 添加薪酬 弹出窗口   因采用子页面弹窗取数据异常   放弃此页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function add()
    {
        //薪酬类型
        $salary_options = Salary::SalaryTypeOptions();
        //当前公司已开的银行账户
        $bank_options = Salary::BankOptions();
        //支付方式
        $pay_options = Salary::PayOptions();
        return view('book.salary.add', compact('salary_options', 'bank_options', 'pay_options'));
    }

    /**
     * 新增薪酬、编辑薪酬 保存操作并返回状态
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function api_add_salary(Request $request)
    {
        $result = SalaryEntity::SaveSalary($request);
        //$result = array('status'=>true, 'msg'=>'...');
        if ($result['status']) {
            return response()->json(['status' => 'success', 'code' => 200, 'msg' => $result['msg']]);
        } else {
            return response()->json(['status' => 'error', 'code' => 400, 'msg' => $result['msg']]);
        }
    }

    /**
     * 获取新增弹窗相关数据   暂未使用
     * @return \Illuminate\Http\JsonResponse
     */
    public function api_get_info()
    {
        //薪酬类型
        $salary_options = Salary::SalaryTypeOptions();
        //当前公司已开的银行账户
        $bank_options = Salary::BankOptions();
        //支付方式
        $pay_options = Salary::PayOptions();

        // 以下数据提供给前台 后期优化使用
        $data['salary_options'] = $salary_options;
        $data['bank_options'] = $bank_options;
        $data['pay_options'] = $pay_options;

        $result = array('status' => true, 'msg' => '...', 'data' => $data);
        if ($result['status']) {
            return response()->json(['status' => 'success', 'code' => 200, 'msg' => $result['msg'], 'data' => $result['data']]);
        } else {
            return response()->json(['status' => 'error', 'code' => 400, 'msg' => $result['msg'], 'data' => $result['data']]);
        }
    }

    /**
     * 删除薪酬类型
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function api_del(Request $request)
    {
        if (empty($request->id)) {
            return response()->json(['status' => 'error', 'code' => 400, 'msg' => '删除失败。']);
        }

        $salary_number = SalaryEntity::Get_Belong_Time_Salary_Num($request->id);
        if($salary_number == 0){
            $result = SalaryEntity::del($request->id);
            return response()->json(['status' => !$result ? 'error' : 'success', 'code' => 200, 'msg' => '删除成功。']);
        }else{
            return response()->json(['status' => 'error', 'code' => 400, 'msg' => '此薪酬类型已濑员工薪酬，请先删除员工薪酬后再执行此操作。']);
        }
    }

    /**
     * 生成记账凭证
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function api_create_voucher(Request $request)
    {
        if (empty($request->id)) {
            return response()->json(['status' => 'error', 'code' => 400, 'msg' => '参数异常，生成记账凭证失败。']);
        }

        $result = SalaryEntity::CreateVoucher($request);
        if ($result['status']) {
            return response()->json(['status' => 'success', 'code' => 200, 'msg' => $result['msg'], 'data' => $result['data']]);
        } else {
            return response()->json(['status' => 'error', 'code' => 400, 'msg' => $result['msg'], 'data' => $result['data']]);
        }
    }

    /**
     * 复制往期薪酬
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function api_copy_salary(Request $request)
    {
        $result = SalaryEntity::CopyOldSalary($request);
        if ($result['status']) {
            return response()->json(['status' => 'success', 'code' => 200, 'msg' => $result['msg']]);
        } else {
            return response()->json(['status' => 'error', 'code' => 400, 'msg' => $result['msg']]);
        }
    }

    /**
     * 凭证科目配置
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function salary_km_config(Request $request)
    {
        $ac_options = Salary::GetAccountSubjectOptions();
        return view('book.salary.km_config', compact('request', 'ac_options'));
    }

    /**
     * 获取会计科目封装数组数据
     * @return \Illuminate\Http\JsonResponse
     */
    public function api_account_list()
    {
        $result = SalaryEntity::GetAccountSubjectList();
        $data['items'] = $result['items'];
        $data['info'] = SalaryEntity::GetSalaryConfigRowInfo();
        $data['type'] = SalaryEntity::GetSalaryCostType();

        $data['costs'] = SalaryEntity::GetSalaryCostConfigRows();

        if ($result['status']) {
            return response()->json(['status' => 'success', 'code' => 200, 'msg' => $result['msg'], 'data' => $data]);
        } else {
            return response()->json(['status' => 'error', 'code' => 400, 'msg' => $result['msg'], 'data' => $data]);
        }
    }

    /**
     * 保存科目配置信息
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function api_save_config(Request $request)
    {
        $result = SalaryEntity::SaveAccountSubjectConfig($request);
        if ($result['status']) {
            return response()->json(['status' => 'success', 'code' => 200, 'msg' => $result['msg']]);
        } else {
            return response()->json(['status' => 'error', 'code' => 400, 'msg' => $result['msg']]);
        }

    }

    /**
     * 删除成本费用 行设置信息
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function api_del_cost_config(Request $request)
    {
        $result = SalaryEntity::DelSalaryCostConfig($request);
        if ($result['status']) {
            return response()->json(['status' => 'success', 'code' => 200, 'msg' => $result['msg']]);
        } else {
            return response()->json(['status' => 'error', 'code' => 400, 'msg' => $result['msg']]);
        }
    }

    /**
     * 复制工资条
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function api_copy_salary_bill(Request $request)
    {
        $result = SalaryEntity::CopyOldSalaryBill($request);
        if ($result['status']) {
            return response()->json(['status' => 'success', 'code' => 200, 'msg' => $result['msg']]);
        } else {
            return response()->json(['status' => 'error', 'code' => 400, 'msg' => $result['msg']]);
        }
    }

    /**
     * 自动创建并设置计提科目和成本费用科目
     * 没有初始会计科目表  系统将初始填充会计科目数据
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function api_auto_config()
    {
        $result = SalaryEntity::AutoSettingCostConfig();
        if ($result['status']) {
            return response()->json(['status' => 'success', 'code' => 200, 'msg' => $result['msg']]);
        } else {
            return response()->json(['status' => 'error', 'code' => 400, 'msg' => $result['msg']]);
        }
    }

    /**
     * 薪酬主表 点击编辑或查看 返回链接并处理新页面打开    可深入优化
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function api_get_link(Request $request)
    {
        if (empty($request->id)) {
            return response()->json(['status' => 'error', 'code' => 400, 'msg' => '参数异常!']);
        }

        $result = SalaryEntity::Get_Link($request);
        if ($result['status']) {
            return response()->json(['status' => 'success', 'code' => 200, 'msg' => $result['msg'], 'link' => $result['link']]);
        } else {
            return response()->json(['status' => 'error', 'code' => 400, 'msg' => $result['msg'], 'link' => $result['link']]);
        }
    }

    /**
     * 正常工资薪酬列表   处理中...
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function list_a(Request $request)
    {
        //薪酬类型表ID(salary_id)
        $id = $request->id;

        return view('book.salary.list_a', compact('request', 'id'));
    }

    // 临时工资薪金
    public function list_b(Request $request)
    {
        $id = $request->id;
        return view('book.salary.list_b', compact('request', 'id'));
    }

    //全年一次性奖金
    public function list_c(Request $request)
    {
        $id = $request->id;
        return view('book.salary.list_c', compact('request', 'id'));
    }

    //外籍人员正常工资薪金
    public function list_d(Request $request)
    {
        $id = $request->id;
        return view('book.salary.list_d', compact('request', 'id'));
    }

    //劳务报酬
    public function list_e(Request $request)
    {
        $id = $request->id;
        return view('book.salary.list_e', compact('request', 'id'));
    }

    //利息股息红利所得
    public function list_f(Request $request)
    {
        $id = $request->id;
        return view('book.salary.list_f', compact('request', 'id'));
    }

    //个人生产经营所得（核定）
    public function list_g(Request $request)
    {
        $id = $request->id;
        return view('book.salary.list_g', compact('request', 'id'));
    }

    //个人生产经营所得（查账）
    public function list_h(Request $request)
    {
        $id = $request->id;
        return view('book.salary.list_h', compact('request', 'id'));
    }

    /**
     * 获取该公司人员列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function api_get_employee(Request $request)
    {
        $result = SalaryEntity::Get_Company_Employee_List($request);
        if ($result['status']) {
            return response()->json(['status' => 'success', 'code' => 200, 'msg' => $result['msg'], 'data' => $result['data']]);
        } else {
            return response()->json(['status' => 'error', 'code' => 400, 'msg' => $result['msg'], 'data' => $result['data']]);
        }
    }

    /**
     * 保存员工薪酬至 员工薪酬表(salary_employee)
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function api_save_salary(Request $request)
    {
        $result = SalaryEntity::Save_Employee_Salary($request);
        if ($result['status']) {
            return response()->json(['status' => 'success', 'code' => 200, 'msg' => $result['msg'], 'id' => $result['id'], 'data' => $result['data']]);
        } else {
            return response()->json(['status' => 'error', 'code' => 400, 'msg' => $result['msg'], 'id' => $result['id'], 'data' => '']);
        }
    }

    /**
     * 删除员工薪酬
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function api_del_salary(Request $request)
    {
        if (empty($request->id)) {
            return response()->json(['status' => 'error', 'code' => 400, 'msg' => '删除失败。']);
        }

        $result = SalaryEntity::delSalaryEmployee($request->id);
        return response()->json(['status' => !$result ? 'error' : 'success', 'code' => 200, 'msg' => !$result ? '已生成凭证，不允许删除。' : '删除成功。']);
    }

    /**
     * 正常工资薪酬 列表a
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function api_get_salary_a(Request $request)
    {
        $data['items'] = SalaryEmployee::SalaryEmployeeJsonList_A($request);
        $data['total'] = SalaryEmployee::SumManyEmployeeSalaryList($request);
        $data['cost_list'] = SalaryEntity::GetSalaryCostTypeList();

        if (!empty($data['items'])) {
            return response()->json(['status' => 'success', 'msg' => 'data success！', 'data' => $data]);
        } else {
            return response()->json(['status' => 'err', 'msg' => 'data err！', 'data' => $data]);
        }
    }

    /**
     * 临时工资薪金 列表b
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function api_get_salary_b(Request $request)
    {
        $data['items'] = SalaryEmployee::SalaryEmployeeJsonList_B($request);
        $data['total'] = SalaryEmployee::SumManyEmployeeSalaryList($request);
        $data['cost_list'] = SalaryEntity::GetSalaryCostTypeList();

        if (!empty($data['items'])) {
            return response()->json(['status' => 'success', 'msg' => 'data success！', 'data' => $data]);
        } else {
            return response()->json(['status' => 'err', 'msg' => 'data err！', 'data' => $data]);
        }
    }

    /**
     * 全年一次性奖金 列表c
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function api_get_salary_c(Request $request)
    {
        $data['items'] = SalaryEmployee::SalaryEmployeeJsonList_C($request);
        $data['total'] = SalaryEmployee::SumManyEmployeeSalaryList($request);
        $data['cost_list'] = SalaryEntity::GetSalaryCostTypeList();

        if (!empty($data['items'])) {
            return response()->json(['status' => 'success', 'msg' => 'data success！', 'data' => $data]);
        } else {
            return response()->json(['status' => 'err', 'msg' => 'data err！', 'data' => $data]);
        }
    }

    // 外籍人员正常工资薪金  暂缓
    public function api_get_salary_d(Request $request)
    {
        $data['items'] = SalaryEmployee::SalaryEmployeeJsonList_D($request);
        $data['total'] = SalaryEmployee::SumManyEmployeeSalaryList($request);
        $data['cost_list'] = SalaryEntity::GetSalaryCostTypeList();

        if (!empty($data['items'])) {
            return response()->json(['status' => 'success', 'msg' => 'data success！', 'data' => $data]);
        } else {
            return response()->json(['status' => 'err', 'msg' => 'data err！', 'data' => $data]);
        }
    }

    /**
     * 劳务报酬  列表e
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function api_get_salary_e(Request $request)
    {
        $data['items'] = SalaryEmployee::SalaryEmployeeJsonList_E($request);
        $data['total'] = SalaryEmployee::SumManyEmployeeSalaryList($request);
        $data['cost_list'] = SalaryEntity::GetSalaryCostTypeList();
        //\Log::info($data);

        if (!empty($data['items'])) {
            return response()->json(['status' => 'success', 'msg' => 'data success！', 'data' => $data]);
        } else {
            return response()->json(['status' => 'err', 'msg' => 'data err！', 'data' => $data]);
        }
    }


    /**
     * 正常工资薪酬 导出
     * @param Request $request
     */
    public function export_a(Request $request)
    {
        SalaryEmployee::Export_SalaryEmployee_A($request);
    }

    /**
     * 最终  生成凭证
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function make_voucher(Request $request)
    {
        try {
            \DB::beginTransaction();
            $return = Voucher::saveVoucher($request);
            $voucher = \App\Models\Accounting\Voucher::where("id",$return->id)->with("voucherItem")->first();

            // 薪酬salary_id添加凭证ID
            $salary_id = $request->salary_id;
            $data = array('voucher_id' => $return->id);
            Salary::query()->where('id', $salary_id)->update($data);

            \DB::commit();
            return $return ? Common::apiSuccess($return) : Common::apiFail();
        } catch (\Exception $e) {
            \DB::rollBack();
            return Common::apiFail($e->getMessage());
        }
    }
}
