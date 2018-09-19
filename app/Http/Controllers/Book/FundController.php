<?php

namespace App\Http\Controllers\Book;

use App\Entity\BusinessDataConfig\BusinessConfig;
use App\Entity\Company;
use App\Entity\Fund as FundEntity;
use App\Entity\Period;
use App\Entity\SubjectBalance;
use App\Http\Controllers\Controller;
use App\Models\Accounting\Fund;
use App\Models\Accounting\FundItem;
use App\Models\Common;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

/**
 * 资金操作控制器
 * Class FundControllerer
 * @package App\Http\Controllers\Book
 */
class FundController extends Controller
{
    /**
     * 现金/票据 首页
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function index(Request $request)
    {
        $list = FundEntity::fundList($request);
        //dd($list);
        if ($request->ajax()) return $list;
        return view('book.fund.' . FundEntity::getChanneltype()[$request->channel_type], compact('list'));
    }

    /**
     * 新增/更新 资金-现金/票据
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $result = FundEntity::newFund($request);
        return $result === true ? Common::apiSuccess() : Common::apiFail($result);
    }

    /**
     * 删除 资金-现金/票据
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function del(Request $request)
    {
        $result = FundEntity::del($request);
        return $result === true ? Common::apiSuccess() : Common::apiFail($result);
    }

    /**
     * 业务类型
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function ywlxList(Request $request)
    {
        switch ($request->channel_type) {
            case FundEntity::BANK:
                $list = (new BusinessConfig(4))->getData();
                break;
            case FundEntity::CASH:
                $list = (new BusinessConfig(5))->getData();
                break;
            case FundEntity::BILL:
                $list = (new BusinessConfig(6))->getData();
                break;
        }
        return Common::apiSuccess($list);
    }

    /**
     * 资金 银行列表
     * @param Request $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function bankList(Request $request)
    {
        $list = FundEntity::bankList($request);
        if ($request->ajax()) return $list;
        return view('book.fund.bank', compact('list'));
    }

    /**
     * 银行收入支出统计
     * @return \Illuminate\Http\JsonResponse
     */
    public function bankFundCount()
    {
        $in = Fund::query()->where('company_id', Company::sessionCompany()->id)
            ->where('fiscal_period', Period::currentPeriod())
            ->where('fund_type', FundEntity::FUND_TYPE_IN)
            ->get()->sum('money');
        $out = Fund::where('company_id', Company::sessionCompany()->id)
            ->where('fiscal_period', Period::currentPeriod())
            ->where('fund_type', FundEntity::FUND_TYPE_OUT)
            ->get()->sum('money');
        $total = SubjectBalance::get(Company::sessionCompany()->id, '1002', Period::currentPeriod());
        return Common::apiSuccess(['in' => $in, 'out' => $out, 'total' => $total]);
    }

    /**
     * 新增/更新 资金-银行 废弃
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function newBank_bak(Request $request)
    {
        $result = FundEntity::newBank($request);
        return $result === true ? Common::apiSuccess() : Common::apiFail($result);
    }

    /**
     * 新增/更新 资金-银行
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function newBank(Request $request)
    {
        try {
            if (!Common::isPost($request))
                throw new \Exception('当前接口仅支持post方法请求');

            $validator = Validator::make($request->all(), [
                'fund_items' => 'required',
                'company_id' => 'required',
            ], [
                'fund_items.required' => '缺少参数资金明细项目:fund_items',
                'company_id.required' => '缺少参数公司id:company_id',
            ]);

            if ($validator->fails())
                throw new \Exception($validator->getMessageBag()->first());

            $funditem = $request->input('fund_items');

            foreach ($funditem as $k => $v) {
                $validator = Validator::make($v, [
                    'funditem_date' => 'required',
                    'money' => 'required',
                    'ywlx' => 'required',
                ], [
                    'funditem_date.required' => '请输入日期',
                    'money.required' => '请输入金额',
                    'ywlx.required' => '请选择业务类型',
                ]);
                if ($validator->fails())
                    throw new \Exception($validator->getMessageBag()->first());
            }

            $param = $request->all();

            //dd($param);

            $data = [];
            DB::transaction(function () use ($param, &$data) {
                $data = FundEntity::newBank($param);
            }, 5);

            return Common::apiSuccess($data);

        } catch (\Exception $e) {
            //throw $e;
            return Common::apiFail($e->getMessage());
        }
    }

    /**
     *
     * @param Request $request
     */
    public function convert(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'invoice_id' => 'required',
            ], [
                'invoice_id.required' => '缺少参数发票id:invoice_id',
            ]);

            if ($validator->fails())
                throw new \Exception($validator->getMessageBag()->first());

            $data = FundEntity::convertInvoice($request->input('invoice_id'), $request->input('channel_type'));
            return Common::apiSuccess($data);

        } catch (\Exception $e) {
            //throw $e;
            return Common::apiFail($e->getMessage());
        }
    }

    /**
     * 删除 资金-银行
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     * @throws \Throwable
     */
    public function delBank(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required',
            ], [
                'id.required' => '缺少参数银行资金id:id',
            ]);

            if ($validator->fails())
                throw new \Exception($validator->getMessageBag()->first());

            $result = null;
            DB::transaction(function () use ($request, &$result) {
                $result = FundEntity::delBank($request);
            }, 5);

            return $result === true ? Common::apiSuccess() : Common::apiFail($result);
        } catch (\Exception $e) {
            //throw $e;
            return Common::apiFail($e->getMessage());
        }
    }

    /**
     * 删除 资金-银行(单行)
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delBankItem(Request $request)
    {
        $result = FundEntity::delBankItem($request);
        return $result === true ? Common::apiSuccess() : Common::apiFail($result);
    }
}
