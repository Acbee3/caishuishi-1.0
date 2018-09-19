<?php

namespace App\Http\Controllers\Book;

use App\Entity\Company;
use App\Entity\Tax;
use App\Http\Controllers\Controller;
use App\Models\Common;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

/**
 * 税金配置
 * Class TaxConfigController
 * @package App\Http\Controllers\Book
 */
class TaxConfigController extends Controller
{
    /**
     * 税金配置 api
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function list(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'company_id' => 'required',
            ], [
                'company_id.required' => '缺少参数公司id:company_id',
            ]);

            if ($validator->fails())
                throw new \Exception($validator->getMessageBag()->first());

            Tax::initConfig(Company::sessionCompany());
            $data = Tax::config($request->input('company_id'));
            return Common::apiSuccess($data);

        } catch (\Exception $e) {
            //throw $e;
            return Common::apiFail($e->getMessage());
        }

    }

    /**
     * 保存税金配置
     * 有则更新否则新增
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function save(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'company_id' => 'required',
                'tax_id' => 'required',
                'tax_name' => 'required',
            ], [
                'company_id.required' => '缺少参数公司id:company_id',
                'tax_id.required' => '缺少参数税目id:tax_id',
                'tax_name.required' => '缺少参数税目名称:tax_name',
            ]);

            if ($validator->fails())
                throw new \Exception($validator->getMessageBag()->first());

            $param = $request->all();

            DB::transaction(function () use ($param, &$data) {
                $data = Tax::save($param);
            }, 5);

            return Common::apiSuccess($data);

        } catch (\Exception $e) {
            //throw $e;
            return Common::apiFail($e->getMessage());
        }
    }

}
