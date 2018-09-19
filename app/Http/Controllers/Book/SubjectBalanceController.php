<?php

namespace App\Http\Controllers\Book;

use App\Entity\SubjectBalance;
use App\Entity\SubjectBalance as SubjectBalanceEntity;
use App\Http\Controllers\Controller;
use App\Models\Common;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SubjectBalanceController extends Controller
{
    /**
     * 科目余额列表
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function subjectBalanceList(Request $request)
    {
        $list = SubjectBalanceEntity::subjectBalanceList($request);
        //导出Excel
        if ($request->export) {
            $name = $request->startkjqj == $request->endkjqj ? $request->startkjqj . '科目余额表' : $request->startkjqj . '-' . $request->endkjqj . '科目余额表';
            Excel::create($name, function ($excel) use ($list) {
                $excel->sheet('New sheet', function ($sheet) use ($list) {
                    $sheet->loadView('book.bookAccount.kmyexport', ['list' => $list]);
                });
            })->download('xls');
        } else {
            if ($request->ajax()) return Common::apiSuccess($list);
            return view('book.bookAccount.kmye');
        }
    }

    /**
     * 科目余额更新
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function subjectBalanceEdit(Request $request)
    {
        if (SubjectBalanceEntity::isClosed()) return Common::apiFail('已有结账数据，无法修改');
        $result = SubjectBalanceEntity::subjectBalanceEdit($request);
        return $result ? Common::apiSuccess() : Common::apiFail();
    }

    /**
     * 科目余额数据初始化-财务
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function subjectBalanceFirst(Request $request)
    {
        $list = SubjectBalanceEntity::subjectBalanceFirst();
        if ($request->ajax()) return Common::apiSuccess($list);
        return view('book.initData.account');
    }

    /**
     * 导入 建账期初科目余额
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function import(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'company_id' => 'required',
                'fiscal_period' => 'required',
                'file' => 'required',
            ], [
                'company_id.required' => '缺少参数公司id:company_id',
                'fiscal_period.required' => '缺少参数会计期间:fiscal_period',
                'file.required' => '缺少参数数据文件:file',
            ]);

            if ($validator->fails())
                throw new \Exception($validator->getMessageBag()->first());

            $file = $request->file('file');
            //dd($file);
            if (empty($file))
                throw new \Exception('文件不能为空');

            if (!Common::checkFile($file, ['xls', 'xlsx']))
                throw new \Exception('数据文件格式有误，请确认文件后缀名为 xls 或 xlsx');

            if (!SubjectBalance::checkInit($request['company_id'], $request['fiscal_period']))
                throw new \Exception('当前数据无法导入，原因：已生成凭证 或 当前不是初始会计期间');

            $excel_file_path = $file->path();
            Excel::load($excel_file_path, function ($reader) use (&$res) {
                $reader = $reader->getSheet(0);
                $res = $reader->toArray();
            });

            $param = [];

            DB::transaction(function () use ($request, $res) {
                SubjectBalanceEntity::import($request['company_id'], $res);
            }, 5);

            return Common::apiSuccess();

        } catch (\Exception $e) {
            //throw $e;
            return Common::apiFail($e->getMessage());
        }
    }


    /**
     * 检测是否可以编辑 初始科目余额表
     * @param Request $request
     */
    public function checkInit(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'company_id' => 'required',
                'fiscal_period' => 'required',
            ], [
                'company_id.required' => '缺少参数公司id:company_id',
                'fiscal_period.required' => '缺少参数会计期间:fiscal_period',
            ]);

            if ($validator->fails())
                throw new \Exception($validator->getMessageBag()->first());

            $res = SubjectBalance::checkInit($request['company_id'], $request['fiscal_period']);
            $data = ['allow' => intval($res)];
            return Common::apiSuccess($data);

        } catch (\Exception $e) {
            //throw $e;
            return Common::apiFail($e->getMessage());
        }
    }

}
