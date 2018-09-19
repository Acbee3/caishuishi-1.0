<?php

namespace App\Http\Controllers\Book;

use App\Entity\Invoice;
use App\Entity\Period;
use App\Http\Controllers\Controller;
use App\Models\Common;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

/**
 * 发票操作控制器
 * Class InvoiceController
 * @package App\Http\Controllers\Book
 */
class InvoiceController extends Controller
{
    /**
     * 进项发票列表 page
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function import(Request $request)
    {
        //ini_set('serialize_precision', 16);

        $sessionCompany = \App\Entity\Company::sessionCompany();
        $company = Company::query()->whereKey($sessionCompany->id)->first();
        $list = (new Invoice())->list($company, array_merge($request->all(), [
            'type' => Invoice::TYPE_IMPORT,
            'fiscal_period' => Period::currentPeriod(),
        ]));
        return view('book.invoice.import', [
            'list' => $list,
        ]);
    }

    /**
     * 新增进项发票 page
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function AddImport(Request $request)
    {
        return view('book.invoice.add_import');
    }

    /**
     * 编辑进项发票 page
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     */
    public function editImport(Request $request)
    {
        ini_set('serialize_precision', 16);
        $invoce = Invoice::detail($request->route('id'));

        return view('book.invoice.edit_import', [
            'invoice' => $invoce,
        ]);
    }

    /**
     * 销项发票列表 page
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function export(Request $request)
    {
        $sessionCompany = \App\Entity\Company::sessionCompany();
        $company = Company::query()->whereKey($sessionCompany->id)->first();
        $list = (new Invoice())->list($company, array_merge($request->all(), [
            'type' => Invoice::TYPE_EXPORT,
            'fiscal_period' => Period::currentPeriod(),
        ]));
        return view('book.invoice.export', [
            'list' => $list,
        ]);
    }

    /**
     * 新增销项发票 page
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function AddExport(Request $request)
    {
        //dd((new BusinessConfig(2))->getData());
        return view('book.invoice.add_export');
    }

    /**
     * 编辑进项发票 page
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editExport(Request $request)
    {
        return view('book.invoice.edit_export');
    }

    /**
     * 发票汇总
     */
    public function summary(Request $request)
    {
        $summary_list = (new Invoice())->summary(\App\Entity\Company::sessionCompany(), $request->all());
        return view('book.invoice.summary', [
            'list' => $summary_list,
        ]);
    }

    /**
     * 导出进项excel
     * @param Request $request
     */
    public function importExcel(Request $request)
    {
        $sessionCompany = \App\Entity\Company::sessionCompany();
        $company = Company::query()->whereKey($sessionCompany->id)->first();

        $param = array_merge($request->all(), [
            'type' => Invoice::TYPE_IMPORT,
            'fiscal_period' => Period::currentPeriod(),
        ]);
        (new Invoice())->importExcel($company, $param);
    }

    /**
     * 导出销项excel
     * @param Request $request
     */
    public function exportExcel(Request $request)
    {
        $sessionCompany = \App\Entity\Company::sessionCompany();
        $company = Company::query()->whereKey($sessionCompany->id)->first();

        $param = array_merge($request->all(), [
            'type' => Invoice::TYPE_EXPORT,
            'fiscal_period' => Period::currentPeriod(),
        ]);
        (new Invoice())->exportExcel($company, $param);
    }

    /**
     * 发票详情 api
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function detail(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'id' => 'required',
            ], ['id.required' => '缺少参数:id']);

            if ($validator->fails())
                throw new \Exception($validator->getMessageBag()->first());

            ini_set('serialize_precision', 16);
            $invoce = Invoice::detail($request->input('id'));

            return Common::apiSuccess($invoce);

        } catch (\Exception $e) {
            return Common::apiFail($e->getMessage());
        }
    }

    /**
     * 删除发票 api
     * @param Request $request
     * @throws
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
                Invoice::delete($request->input('id'));
            }, 5);

            return Common::apiSuccess();

        } catch (\Exception $e) {
            return Common::apiFail($e->getMessage());
        }
    }

    /**
     * 批量删除发票 api
     * @param Request $request
     * @throws
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

            $ids = json_decode($request->input('ids'), true);

            DB::transaction(function () use ($ids) {
                foreach ($ids as $id) {
                    Invoice::delete($id);
                }
            }, 5);

            return Common::apiSuccess();

        } catch (\Exception $e) {
            return Common::apiFail($e->getMessage());
        }
    }

    /**
     * 新增发票 api
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function create(Request $request)
    {
        try {

            //请求验证开始
            if (!Common::isPost($request))
                throw new \Exception('当前接口仅支持post方法请求');

            $validator = Validator::make($request->all(), [
                //'id' => 'required',
                'company_id' => 'required',
                'kprq' => 'required',
                'type' => 'required',
                'sub_type' => 'required',
                //'dkzt' => 'required',
                //'dkfs' => 'required',
                'fpzs' => 'required',
                'items' => 'required',
            ], [
                'company_id.required' => '缺少参数:代账公司id',
                'kprq.required' => '缺少参数:开票日期kprq',
                'type.required' => '缺少参数:发票类型type',
                'sub_type.required' => '缺少参数:发票明细类型sub_type',
                //'dkzt.required' => '缺少参数:抵扣状态dkzt',
                //'dkfs.required' => '缺少参数:抵扣方式dkfs',
                'fpzs.required' => '缺少参数:发票张数fpzs',
                'items.required' => '缺少参数:发票明细items',
            ]);

            $items = json_decode($request->input('items'), true);

            if (empty($items))
                throw new \Exception('发票明细不可为空');

            foreach ($items as $item) {
                $validator_tmp = Validator::make($item, [
                    'ywlx_id' => 'required',
                    'ywlx_name' => 'required',
                    'money' => 'required',
                    'tax_rate' => 'required',
                    'tax_money' => 'required',
                    'fee_tax_sum' => 'required',
                ], [
                    'ywlx_id.required' => '缺少参数:业务类型ywlx_id',
                    'ywlx_name.required' => '缺少参数:业务类型名称ywlx_name',
                    'money.required' => '缺少参数:金额money',
                    'tax_rate.required' => '缺少参数:税率tax_rate',
                    'tax_money.required' => '缺少参数:税额tax_money',
                    'fee_tax_sum.required' => '缺少参数:价税合计fee_tax_sum',
                ]);

                if ($validator_tmp->fails())
                    throw new \Exception($validator_tmp->getMessageBag()->first());

                //销项发票税目必填
                if ($request->input('type') == Invoice::TYPE_EXPORT) {
                    $validator_tmp = Validator::make($item, [
                        //'tax_id' => 'required',
                        'tax_name' => 'required',
                    ]);
                }

                if ($validator_tmp->fails())
                    throw new \Exception($validator_tmp->getMessageBag()->first());
            }

            if ($validator->fails())
                throw new \Exception($validator->getMessageBag()->first());
            //请求验证结束

            //组装参数
            $param = Invoice::makeAddParam($request->all());
            $data = [];

            DB::transaction(function () use ($param, &$data) {
                $data = Invoice::add($param);
            }, 5);

            return Common::apiSuccess($data);

        } catch (\Exception $e) {
            throw $e;
            return Common::apiFail($e->getMessage());
        }
    }

    /**
     * 更新发票信息 api
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function update(Request $request)
    {
        try {

            //请求验证开始
            if (!Common::isPost($request))
                throw new \Exception('当前接口仅支持post方法请求');

            $validator = Validator::make($request->all(), [
                'id' => 'required',
                'company_id' => 'required',
                'kprq' => 'required',
                'type' => 'required',
                'sub_type' => 'required',
                //'dkzt' => 'required',
                //'dkfs' => 'required',
                'fpzs' => 'required',
                'items' => 'required',
            ], [
                'id.required' => '缺少参数:发票id',
                'company_id.required' => '缺少参数:代账公司id',
                'kprq.required' => '缺少参数:开票日期kprq',
                'type.required' => '缺少参数:发票类型type',
                'sub_type.required' => '缺少参数:发票明细类型sub_type',
                //'dkzt.required' => '缺少参数:抵扣状态dkzt',
                //'dkfs.required' => '缺少参数:抵扣方式dkfs',
                'fpzs.required' => '缺少参数:发票张数fpzs',
                'items.required' => '缺少参数:发票明细items',
            ]);

            $items = json_decode($request->input('items'), true);

            if (empty($items))
                throw new \Exception('发票明细不可为空');

            foreach ($items as $item) {
                $validator_tmp = Validator::make($item, [
                    //'id' => 'required',
                    'ywlx_id' => 'required',
                    'ywlx_name' => 'required',
                    'money' => 'required',
                    'tax_rate' => 'required',
                    'tax_money' => 'required',
                    'fee_tax_sum' => 'required',
                ], [
                    //'id.required' => '缺少参数:发票明细id',
                    'ywlx_id.required' => '缺少参数:业务类型ywlx_id',
                    'ywlx_name.required' => '缺少参数:业务类型名称ywlx_name',
                    'money.required' => '缺少参数:金额money',
                    'tax_rate.required' => '缺少参数:税率tax_rate',
                    'tax_money.required' => '缺少参数:税额tax_money',
                    'fee_tax_sum.required' => '缺少参数:价税合计fee_tax_sum',
                ]);

                if ($validator_tmp->fails())
                    throw new \Exception($validator_tmp->getMessageBag()->first());

                //销项发票税目必填
                if ($request->input('type') == Invoice::TYPE_EXPORT) {
                    $validator_tmp = Validator::make($item, [
                        //'tax_id' => 'required',
                        'tax_name' => 'required',
                    ]);
                }

                if ($validator_tmp->fails())
                    throw new \Exception($validator_tmp->getMessageBag()->first());
            }

            if ($validator->fails())
                throw new \Exception($validator->getMessageBag()->first());
            //请求验证结束

            //组装参数
            $param = Invoice::makeUpdateParam($request->all());

            DB::transaction(function () use ($param) {
                Invoice::update($param);
            }, 5);

            return Common::apiSuccess();

        } catch (\Exception $e) {
            return Common::apiFail($e->getMessage());
        }
    }

}
