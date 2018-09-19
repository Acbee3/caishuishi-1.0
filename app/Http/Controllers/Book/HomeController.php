<?php

namespace App\Http\Controllers\Book;

use App\Entity\Company as CompanyEntity;
use App\Entity\Period;
use App\Http\Controllers\Controller;
use App\Models\AccountBook\Ledger;
use App\Models\Common;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
//use App\Entity\SubjectBalance as SubjectBalanceEntity;

class HomeController extends Controller
{

    /**
     *
     * 账簿 首页
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     * @throws \Exception
     */
    public function index(Request $request)
    {
        $id = $request->route('id');
        $company_encode = $request->route('company_encode');
        $fiscal_period = $request->route('fiscal_period');

        if (!empty($id) && !empty($company_encode) && strlen($company_encode) == 100) {
            $company_row = Company::Get_Company_info($id, $company_encode);
            if ($company_row) {
                session()->forget('companyInfo');
                session(['companyInfo' => $company_row]);

                //设置全局 会计期间 session
                Period::setGlobalSession($fiscal_period);

                // 检查当前账期科目余额信息是否已做过初始化  有则跳过  没有就生成
                //SubjectBalanceEntity::checkPeriodSubjectBalance();// 检查初始化当期 科目余额表
                Ledger::Initialize_Ledger_Period();// 检查初始化当期 总账表

                return view("book.home", ['data' => $company_row, 'request' => $request]);
            } else {
                return redirect()->route('forbidden');
            }
        } else {
            return redirect()->route('forbidden');
        }
    }

    /**
     * 获取 会计期间列表 信息
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function periodList(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'company_id' => 'required',
            ], ['company_id.required' => '缺少参数 公司id:company_id']);

            if ($validator->fails())
                throw new \Exception($validator->getMessageBag()->first());

            $data = CompanyEntity::periodList($request['company_id']);
            return Common::apiSuccess($data);
        } catch (\Exception $e) {
            return Common::apiFail($e->getMessage());
        }
    }
}
