<?php
/**
 * FileName: LedgerController.php
 * Created by PhpStorm.
 * User: Administrator
 * DateTime: 2018/7/31-13:54
 * E_mail: newsboy9248@163.com
 */

namespace App\Http\Controllers\Book;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AccountBook\Ledger AS LedgerModel;
use App\Entity\Company;

/**
 * 总账 控制器
 * Class LedgerController
 * @package App\Http\Controllers\Book
 */
class LedgerController extends Controller
{
    /**
     * 总账页面
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function list(Request $request)
    {
        new Company();
        $company_id = Company::$company->id;

        return view('book.bookAccount.ledger', compact('request', 'company_id'));
    }

    /**
     * 页面加载 返回数据
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function api_list(Request $request)
    {
        $result = LedgerModel::Get_List($request);
        if ($result['status']) {
            return response()->json(['status' => 'success', 'msg' => $result['msg'], 'data' => $result['data']]);
        } else {
            return response()->json(['status' => 'error', 'msg' => $result['msg'], 'data' =>$result['data']]);
        }
    }

    /**
     * 刷新数据
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function api_change_list(Request $request)
    {
        $result = LedgerModel::Get_Change_List($request);
        if ($result['status']) {
            return response()->json(['status' => 'success', 'msg' => $result['msg'], 'data' => $result['data']]);
        } else {
            return response()->json(['status' => 'error', 'msg' => $result['msg'], 'data' =>$result['data']]);
        }
    }

    /**
     * 导出
     * @param Request $request
     */
    public function export(Request $request)
    {
        LedgerModel::Export_Ledger($request);
    }

    /**
     * 打印
     * @param Request $request
     * @return mixed
     */
    public function print(Request $request)
    {
        $result = LedgerModel::Print_Ledger($request);

        $company_name = $result['company_name'];
        $period = $result['period'];
        $list = $result['items'];
        $status = $result['status'];

        if($status == 'success'){
            $name = $company_name.'_'.$period.'_总账_pdf文件.pdf';

            // 方式一   本地开发正常  测试环境没有引入h4cc包不可用 转换成方式二； 方式一效果更佳
            //$pdf = \App::make('snappy.pdf.wrapper');
            //$pdf = $pdf->loadView('book.bookAccount.ledger_pdf', compact('company_name','period','list'));
            //return $pdf->setPaper('a4')->setOption('margin-bottom', '20')->inline($name);


            // 方式二
            $pdf = \App::make('dompdf.wrapper');
            $pdf->loadView('book.bookAccount.ledger_dompdf', compact('company_name','period','list'));
            return $pdf->stream($name);
        }else{
            return false;
        }
    }



}