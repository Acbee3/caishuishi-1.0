<?php

namespace App\Http\Controllers\Book;

use App\Entity\AccountClose;
use App\Entity\SubjectBalance;
use App\Entity\Voucher;
use App\Http\Controllers\Controller;
use App\Models\Common;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

/**
 * 结账控制器
 * Class AccountCloseController
 * @package App\Http\Controllers\Book
 */
class AccountCloseController extends Controller
{

    /**
     * 结账-检查
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function check(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'company_id' => 'required',
                'fiscal_period' => 'required',

            ], [
                'company_id.required' => '缺少参数公司id:company_id',
                'fiscal_period.required' => '缺少参数会计区间:fiscal_period',
            ]);

            if ($validator->fails())
                throw new \Exception($validator->getMessageBag()->first());

            $param = $request->all();
            $data = AccountClose::check($param);

            return Common::apiSuccess($data);

        } catch (\Exception $e) {
            //throw $e;
            return Common::apiFail($e->getMessage());
        }
    }

    /**
     * 结账-清单凭证-批量生成
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function makeVoucherByQingdan(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'company_id' => 'required',
                'fiscal_period' => 'required',

            ], [
                'company_id.required' => '缺少参数公司id:company_id',
                'fiscal_period.required' => '缺少参数会计区间:fiscal_period',
            ]);

            if ($validator->fails())
                throw new \Exception($validator->getMessageBag()->first());

            $data = AccountClose::makeVoucherByQingdan($request->input('company_id'), $request->input('fiscal_period'));
            return Common::apiSuccess($data);
        } catch (\Exception $e) {
            return Common::apiFail($e->getMessage());
        }
    }

    /**
     * 结账-税金计提-批量生成
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function makeVoucherByTax(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'company_id' => 'required',
                'fiscal_period' => 'required',
            ], [
                'company_id.required' => '缺少参数公司id:company_id',
                'fiscal_period.required' => '缺少参数会计区间:fiscal_period',
            ]);

            if ($validator->fails())
                throw new \Exception($validator->getMessageBag()->first());

            $param = $request->all();
            $data = [];
            DB::transaction(function () use ($param, &$data) {
                $data = AccountClose::jiTiShuiJin($param['company_id'], $param['fiscal_period']);
            }, 5);

            return Common::apiSuccess($data);
        } catch (\Exception $e) {
            return Common::apiFail($e->getMessage());
        }
    }

    /**
     * 结账-损益结转-批量生成
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function makeVoucherBySunyi(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'company_id' => 'required',
                'fiscal_period' => 'required',
            ], [
                'company_id.required' => '缺少参数公司id:company_id',
                'fiscal_period.required' => '缺少参数会计区间:fiscal_period',
            ]);

            if ($validator->fails())
                throw new \Exception($validator->getMessageBag()->first());

            $param = $request->all();
            $data = [];
            DB::transaction(function () use ($param, &$data) {
                AccountClose::sunYiJieZhuan($param['company_id'], $param['fiscal_period']);
            }, 5);

            return Common::apiSuccess();
        } catch (\Exception $e) {
            return Common::apiFail($e->getMessage());
        }
    }

    /**
     * 结账-直接结账-批量生成
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function run(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'company_id' => 'required',
                'fiscal_period' => 'required',
            ], [
                'company_id.required' => '缺少参数公司id:company_id',
                'fiscal_period.required' => '缺少参数会计区间:fiscal_period',
            ]);

            if ($validator->fails())
                throw new \Exception($validator->getMessageBag()->first());

            $param = $request->all();
            $data = [];
            DB::transaction(function () use ($param, &$data) {
                AccountClose::Run($param['company_id'], $param['fiscal_period']);
            }, 5);

            return Common::apiSuccess();
        } catch (\Exception $e) {
            //throw $e;
            return Common::apiFail($e->getMessage());
        }
    }

    /**
     * 结账-删除凭证
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function deleteJitiVoucher(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'company_id' => 'required',
                'fiscal_period' => 'required',
                'source' => 'required',
            ], [
                'company_id.required' => '缺少参数公司id:company_id',
                'fiscal_period.required' => '缺少参数会计区间:fiscal_period',
                'source.required' => '缺少参数凭证类型:source',
            ]);

            if ($validator->fails())
                throw new \Exception($validator->getMessageBag()->first());

            $param = $request->all();
            DB::transaction(function () use ($param) {
                Voucher::deleteBySource($param['source'], $param['company_id'], $param['fiscal_period']);
            }, 5);

            return Common::apiSuccess();
        } catch (\Exception $e) {
            return Common::apiFail($e->getMessage());
        }
    }

    /**
     * 检查当期是否结账
     * @param Request $request
     * @throws \Throwable
     */
    public function checkClose(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'company_id' => 'required',
                'fiscal_period' => 'required',
            ], [
                'company_id.required' => '缺少参数公司id:company_id',
                'fiscal_period.required' => '缺少参数会计区间:fiscal_period',
            ]);

            if ($validator->fails())
                throw new \Exception($validator->getMessageBag()->first());

            $param = $request->all();
            $data = ['close_status' => intval(AccountClose::checkClose($param))];

            return Common::apiSuccess($data);
        } catch (\Exception $e) {
            return Common::apiFail($e->getMessage());
        }
    }

    /**
     * 反结账
     * @param Request $request
     * @throws \Throwable
     */
    public function reverse(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'company_id' => 'required',
                'fiscal_period' => 'required',
            ], [
                'company_id.required' => '缺少参数公司id:company_id',
                'fiscal_period.required' => '缺少参数会计区间:fiscal_period',
            ]);

            if ($validator->fails())
                throw new \Exception($validator->getMessageBag()->first());

            $param = $request->all();
            DB::transaction(function () use ($param) {
                AccountClose::reverse($param['company_id'], $param['fiscal_period']);
            }, 5);

            return Common::apiSuccess();
        } catch (\Exception $e) {
            return Common::apiFail($e->getMessage());
        }
    }

}
