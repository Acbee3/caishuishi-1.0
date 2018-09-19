<?php

namespace App\Http\Controllers\Book;

use App\Entity\Cost;
use App\Entity\Period;
use App\Http\Controllers\Controller;
use App\Models\Common;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

/**
 * 费用操作控制器
 * Class SalaryControllerer
 * @package App\Http\Controllers\Book
 */
class CostController extends Controller
{
    /**
     * 列表页 page
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $sessionCompany = \App\Entity\Company::sessionCompany();
        $company = Company::query()->whereKey($sessionCompany->id)->first();
        //dd(Period::currentPeriod());
        $list = Cost::list($company, array_merge($request->all(), [
            'fiscal_period' => Period::currentPeriod(),
        ]));
        //dd($list->toArray());

        return view('book.cost.index', [
            'list' => $list,
        ]);
    }

    /**
     * 导入excel页面 api
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function importExcel(Request $request)
    {
        try {

            if (!Common::isPost($request))
                throw new \Exception('当前接口仅支持post请求');

            if (!$request->hasFile('file'))
                throw new \Exception('请选择文件');

            //dd(123);
            $datas = Common::importDataByExcel($request);
            DB::transaction(function () use ($datas) {
                Cost::excelAdd($datas);
            }, 5);

            return Common::apiSuccess($datas);

        } catch (\Exception $e) {
            return Common::apiFail($e->getMessage());
        }

        Common::apiFail();
    }

    /**
     * 删除费用 api
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function delete(Request $request)
    {
        try {

            if (!Common::isPost($request))
                throw new \Exception('当前接口仅支持post方法请求');

            $validator = Validator::make($request->all(), [
                'id' => 'required',
            ]);

            if ($validator->fails())
                throw new \Exception($validator->getMessageBag()->first());

            DB::transaction(function () use ($request) {
                Cost::delete($request->input('id'));
            }, 5);

            return Common::apiSuccess();

        } catch (\Exception $e) {
            return Common::apiFail($e->getMessage());
        }
    }

    /**
     * 删除费用 api
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function deleteAll(Request $request)
    {
        try {

            if (!Common::isPost($request))
                throw new \Exception('当前接口仅支持post方法请求');

            $validator = Validator::make($request->all(), [
                'ids' => 'required',
            ]);

            if ($validator->fails())
                throw new \Exception($validator->getMessageBag()->first());

            DB::transaction(function () use ($request) {
                Cost::deleteAll($request->input('ids'));
            }, 5);

            return Common::apiSuccess();

        } catch (\Exception $e) {
            return Common::apiFail($e->getMessage());
        }
    }

    /**
     * 删除费用明细项 api
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function deleteItem(Request $request)
    {
        try {

            if (!Common::isPost($request))
                throw new \Exception('当前接口仅支持post方法请求');

            $validator = Validator::make($request->all(), [
                'id' => 'required',
            ]);

            if ($validator->fails())
                throw new \Exception($validator->getMessageBag()->first());

            DB::transaction(function () use ($request) {
                Cost::deleteItem($request->input('id'));
            }, 5);

            return Common::apiSuccess();

        } catch (\Exception $e) {
            return Common::apiFail($e->getMessage());
        }
    }

    /**
     * 新增费用 api
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function add(Request $request)
    {
        try {

            if (!Common::isPost($request))
                throw new \Exception('当前接口仅支持post方法请求');

            $validator = Validator::make($request->all(), [
                'expenseTable' => 'required',
            ], [
                'expenseTable.required' => '费用明细不能为空',
            ]);

            if ($validator->fails())
                throw new \Exception($validator->getMessageBag()->first());

            $param = Cost::makeAddParam($request->all());

            DB::transaction(function () use ($param) {
                Cost::add($param);
            }, 5);

            return Common::apiSuccess();

        } catch (\Exception $e) {
            return Common::apiFail($e->getMessage());
        }
    }

    /**
     * 修改费用 api
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function update(Request $request)
    {
        try {

            if (!Common::isPost($request))
                throw new \Exception('当前接口仅支持post方法请求');

            $validator = Validator::make($request->all(), [
                'id' => 'required',
                'expenseTable' => 'required',
            ]);

            if ($validator->fails())
                throw new \Exception($validator->getMessageBag()->first());

            $items = $request->input('expenseTable');
            if (empty($items))
                throw new \Exception('费用明细项不能为空');

            $param = Cost::makeUpdateParam($request);

            DB::transaction(function () use ($param) {
                Cost::update($param);
            }, 5);

            return Common::apiSuccess();

        } catch (\Exception $e) {
            throw  $e;
            return Common::apiFail($e->getMessage());
        }
    }

}
