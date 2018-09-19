<?php
/**
 * Created by PhpStorm V.2018.
 * User: Administrator - Newsboy9248@163.com
 * Date: 2018/6/6 - 15:49
 */

namespace App\Http\Controllers\Agent;

use App\Entity\Period;
//use App\Entity\Salary;
use App\Entity\SubjectBalance;
use App\Entity\Tax;
use App\Http\Controllers\Controller;
use App\Models\AccountSubject;
use App\Models\Common;
use App\Models\Company;
use Illuminate\Http\Request;
use Carbon\Carbon;


class CompanyController extends Controller
{
    /**
     * 取客户信息列表
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        //$can = Priv::CFS_Priv('company_manage');
        //if(!$can){return redirect()->route('forbidden');}

        $data = Company::search($request, $set_status = 'yes', $pageSize = 15);

        return view("agent.company.index", ['data' => $data, 'request' => $request, 'cid' => '']);
    }

    /**
     * 取禁用客户信息列表
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index_freez(Request $request)
    {
        //$can = Priv::CFS_Priv('company_manage');
        //if(!$can){return redirect()->route('forbidden');}

        $data = Company::search($request, $set_status = 'no', $pageSize = 15);

        return view("agent.company.index_freez", ['data' => $data, 'request' => $request, 'cid' => '']);
    }

    /**
     * 新增代账公司客户信息   已弃用 不要删除(新增见create方法)
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     * @throws \Exception
     */
    public function create_old(Request $request)
    {
        //$can = Priv::CFS_Priv('company_add');
        //if(!$can){return redirect()->route('forbidden');}

        // 取代账公司id
        //$agent_id = Auth::user()->agent_id;
        $agent_id = Common::loginUser()->agent_id;
        if (empty($agent_id)) {
            return redirect()->route('forbidden');
        }

        if (Common::isPost($request)) {
            $this->validate($request,
                [
                    "company_name" => 'required|string|unique:company',
                    "taxpayer_number" => 'required|string|unique:company',
                    "company_address" => 'required|string',
                    "finance_person" => 'required|string',
                    "finance_personphone" => 'required|string',
                    "taxpayer_rights" => 'required|string',
                ],
                [
                    "company_name.required" => '公司名称必填！',
                    "company_name.unique:company" => '此公司名称系统里已经存在！',
                    "taxpayer_number.required" => '纳税人识别号必填！',
                    "taxpayer_number.unique:company" => '此纳税人识别号系统里已经存在！',
                    "company_address.required" => '营业地址必填！',
                    "finance_person.required" => '财务联系人必填！',
                    "finance_personphone.required" => '财务联系人联系方式必填！',
                    "taxpayer_rights.required" => '纳税人资格必填！',
                ]
            );

            $model = new Company();
            $model->company_name = $request->company_name;

            $company_code = Company::Create_Company_Code();
            $model->company_code = $company_code;

            $company_encode = Company::Create_Company_Encode($company_code);
            $model->company_encode = $company_encode;

            $model->taxpayer_number = $request->taxpayer_number;
            $model->reg_sort = $request->reg_sort;
            $model->reg_date = $request->reg_date;
            $model->company_sort = $request->company_sort;
            $model->credit_code = $request->credit_code;
            $model->area_id = $request->area_id;
            $model->company_address = $request->company_address;
            $model->scope_business = $request->scope_business;
            $model->legal_person = $request->legal_person;
            $model->legal_personphone = $request->legal_personphone;
            $model->finance_person = $request->finance_person;
            $model->finance_personphone = $request->finance_personphone;
            $model->company_person = $request->company_person;
            $model->company_personphone = $request->company_personphone;
            $model->taxpayer_rights = $request->taxpayer_rights;
            $model->taxpayer_rank = $request->taxpayer_rank;
            $model->registered_capital = $request->registered_capital;
            $model->paidup_capital = $request->paidup_capital;

            $model->agent_id = $agent_id;

            if ($model->save()) {
                AccountSubject::companySubjects($model->id);
                SubjectBalance::subjectBalanceNew($model->id, $model->used_year . '-' . $model->used_month);
                Tax::initConfig($model);
                return redirect()->route('agent.companies');
            }
        }

        $cid = '';
        return view("agent.company.create", compact('cid'));
    }

    /**
     * 新增代账公司客户信息  新版
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function create()
    {
        $agent_id = Common::loginUser()->agent_id;
        if (empty($agent_id)) {
            return redirect()->route('forbidden');
        }

        $cid = '';
        return view("agent.company.create_new", compact('cid'));
    }

    /**
     * 编辑代账公司客户公司信息  已弃用 不要删除
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function edit_old(Request $request)
    {
        $id = $request->id;
        $model = Company::find($id);

        // 科目长度
        $level_set = count(explode(",", $model->level_set));

        if (Common::isPost($request)) {
            $this->validate($request,
                [
                    "company_name" => 'required|string',
                    "taxpayer_number" => 'required|string',
                    "company_address" => 'required|string',
                    "finance_person" => 'required|string',
                    "finance_personphone" => 'required|string',
                    "taxpayer_rights" => 'required|string',
                ],
                [
                    "company_name.required" => '公司名称必填！',
                    "taxpayer_number.required" => '纳税人识别号必填！',
                    "company_address.required" => '营业地址必填！',
                    "finance_person.required" => '财务联系人必填！',
                    "finance_personphone.required" => '财务联系人联系方式必填！',
                    "taxpayer_rights.required" => '纳税人资格必填！',
                ]
            );

            $model->company_name = $request->company_name;

            // 如果公司编码为空，补充公司编码
            $company_code = '';
            if (empty($request->company_code)) {
                $company_code = Company::Create_Company_Code();
                $model->company_code = $company_code;
            }

            if (empty($request->company_encode)) {
                $company_encode = Company::Create_Company_Encode($company_code);
                $model->company_encode = $company_encode;
            }

            $model->taxpayer_number = $request->taxpayer_number;
            $model->reg_sort = $request->reg_sort;
            $model->reg_date = $request->reg_date;
            $model->company_sort = $request->company_sort;
            $model->credit_code = $request->credit_code;
            $model->area_id = $request->area_id;
            $model->company_address = $request->company_address;
            $model->scope_business = $request->scope_business;
            $model->legal_person = $request->legal_person;
            $model->legal_personphone = $request->legal_personphone;
            $model->finance_person = $request->finance_person;
            $model->finance_personphone = $request->finance_personphone;
            $model->company_person = $request->company_person;
            $model->company_personphone = $request->company_personphone;
            $model->taxpayer_rights = $request->taxpayer_rights;
            $model->taxpayer_rank = $request->taxpayer_rank;
            $model->registered_capital = $request->registered_capital;
            $model->paidup_capital = $request->paidup_capital;

            $updated_at = now()->toDateTimeString();
            $model->updated_at = $updated_at;

            if ($model->save()) {
                return redirect()->route('agent.companies');
            }
        }

        return view("agent.company.edit", ['model' => $model, 'level_set' => $level_set, 'cid' => $id]);
    }

    /**
     * 编辑公司信息新页面 新版
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     *
     */
    public function edit(Request $request)
    {
        $id = $request->id;
        $cid = $id;
        $model = Company::find($id);

        $come_from = $request->f;//追加参数  正常为空；从账薄过来为book

        //根据来源进行相关处理
        $prev = \URL::previous();
        if (strpos($prev, '/agent/companies/create') !== false) {
            $prev_label = 'create';
        } else {
            $prev_label = 'edit';
        }

        // 编辑页面触发查看账套标记
        if ($request->vzt == 'yes') {
            $prev_label = 'create';
        }
        //\Log::info($prev_label);

        // 科目长度
        $level_set = count(explode(",", $model->level_set));
        return view("agent.company.edit_new", compact('model', 'level_set', 'cid', 'come_from', 'prev_label'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function api_edit(Request $request)
    {
        $result = Company::Save_Edit_Base_Info($request);
        if ($result['status']) {
            return response()->json(['status' => 'success', 'msg' => $result['msg'], 'data' => '', 'id' => $result['id']]);
        } else {
            return response()->json(['status' => 'err', 'msg' => $result['msg'], 'data' => '', 'id' => $result['id']]);
        }
    }

    /**
     * 编辑公司账套信息   弃用  不要删除
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function editaccount(Request $request)
    {
        $id = $request->id;
        $subject_len = $request->subject_len;
        $model = Company::find($id);

        $level_set = count(explode(",", $model->level_set));

        if (Common::isPost($request)) {
            $this->validate($request,
                [
                    "used_year" => 'required|string',
                    "used_month" => 'required|string',
                    "accounting_system" => 'required|string',
                ],
                [
                    "used_year.required" => '启用年份必填！',
                    "used_month.required" => '启用月份必填！',
                    "accounting_system.required" => '会计制度必填！',
                ]
            );

            $model->used_year = $request->used_year;
            $model->used_month = $request->used_month;
            $model->standard_money = $request->standard_money;
            $model->accounting_trade = $request->accounting_trade;

            // 会计制度 不可随便更改
            if (empty($request->accounting_system)) {
                $model->accounting_system = $request->accounting_system;
            }

            // 科目长度只能增加不能减少
            $level_set2 = $request->level_set;
            if ($subject_len <= count($level_set2)) {
                $level_set2 = @join(",", $level_set2);
                $model->level_set = $level_set2;
            } else {
                $model->level_set = '4,2,2';
            }

            if ($model->save()) {
                return redirect()->route('agent.companies');
            }
        }

        return view("agent.company.edit", ['model' => $model, 'level_set' => $level_set, 'cid' => $id]);
    }

    /**
     * 编辑公司账套信息
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function api_edit_account(Request $request)
    {
        $result = Company::Save_Edit_Account_Info($request);
        if ($result['status']) {
            return response()->json(['status' => 'success', 'msg' => $result['msg'], 'data' => '']);
        } else {
            return response()->json(['status' => 'err', 'msg' => $result['msg'], 'data' => '']);
        }
    }

    /**
     * 查看公司
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function view(Request $request)
    {
        $id = $request->id;
        $model = Company::find($id);

        return view("agent.company.view", ['model' => $model, 'cid' => $id]);
    }

    /**
     * 设置公司状态
     * 停用  启用
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function setstatus(Request $request)
    {
        $id = $request->id;
        $model = Company::find($id);

        //dd($model->status);

        if ($model->status == 'yes') {
            $model->status = 'no';
        } else {
            $model->status = 'yes';
        }

        if ($model->save()) {
            return redirect()->route('agent.companies');
        }
    }

    /**
     * 停用
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function freez(Request $request)
    {
        $id = $request->id;
        $model = Company::find($id);

        $model->status = 'no';

        // 当前会计期间
        //$period = Period::currentPeriod_New();

        $period_date = Carbon::now();
        $period = date("Y-m", (strtotime($period_date)));
        $model->stop_using = $period;

        if ($model->save()) {
            return redirect()->route('agent.companies');
        }
    }

    /**
     * 启用
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function unfreez(Request $request)
    {
        $id = $request->id;
        $model = Company::find($id);

        $model->status = 'yes';
        $model->stop_using = '';

        if ($model->save()) {
            return redirect()->route('agent.companies.freezlist');
        }
    }

    /**
     * 删除公司 更新deleted_at时间   不实际删除
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function api_del(Request $request)
    {
        $status = Company::delCompany($request);
        if ($status) {
            return response()->json(['status' => 'success', 'msg' => '操作成功!']);
        } else {
            return response()->json(['status' => 'err', 'msg' => '操作失败!']);
        }
    }
}