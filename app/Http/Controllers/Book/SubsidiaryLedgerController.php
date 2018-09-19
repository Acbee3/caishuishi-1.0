<?php
/**
 * FileName: SubsidiaryLedgerController.php
 * Created by PhpStorm.
 * User: Administrator
 * DateTime: 2018/7/27-13:46
 * E_mail: newsboy9248@163.com
 */

namespace App\Http\Controllers\Book;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AccountBook\SubsidiaryLedger as SubLedgerModel;
use App\Entity\Company;

/**
 * 明细账 控制器
 * Class SubsidiaryLedgerController
 * @package App\Http\Controllers\Book
 */
class SubsidiaryLedgerController extends Controller
{
    /**
     * 明细账  页面
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function list(Request $request)
    {
        new Company();
        $company_id = Company::$company->id;
        $options = SubLedgerModel::Get_Account_Subjects_Options_List($company_id);

        // 传入页面会计科目编码 km_code
        $km_code = $request->km_code;
        //dd($km_code);

        return view('book.bookAccount.mxz', compact('request', 'options', 'km_code'));
    }

    /**
     * 明细账  取初始数据
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function api_list(Request $request)
    {
        $result = SubLedgerModel::Get_List($request);
        if ($result['status']) {
            return response()->json(['status' => 'success', 'msg' => $result['msg'], 'data' => $result['data']]);
        } else {
            return response()->json(['status' => 'error', 'msg' => $result['msg'], 'data' =>$result['data']]);
        }
    }

    /**
     * 获取某一个会计科目的明细账
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function api_get_list(Request $request)
    {
        $result = SubLedgerModel::Get_Account_Subjects_Info_list($request);
        if ($result['status']) {
            return response()->json(['status' => 'success', 'msg' => $result['msg'], 'data' => $result['data']]);
        } else {
            return response()->json(['status' => 'error', 'msg' => $result['msg'], 'data' =>$result['data']]);
        }
    }

    /**
     * 获取 选中的树形的ID
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function api_get_id(Request $request)
    {
        $result = SubLedgerModel::Get_Select_Tree_Id($request);
        if ($result['status']) {
            return response()->json(['status' => 'success', 'msg' => $result['msg'], 'data' => $result['data']]);
        } else {
            return response()->json(['status' => 'error', 'msg' => $result['msg'], 'data' =>'']);
        }
    }

    /**
     * 当前选择 会计科目明细账 打印
     * @param Request $request
     * @return mixed
     */
    public function print(Request $request)
    {
        $result = SubLedgerModel::Print_OneKm_SubLedger($request);

        $company_name = $result['company_name'];
        $period = $result['period'];
        $list = $result['items'];
        $status = $result['status'];
        $km_name = $result['km_name'];

        if($status == 'success'){
            $name = $company_name.'_'.$period.'_'.$km_name.'_明细账_pdf文件.pdf';

            // 有引入h4cc包推荐使用方式一   反之用方式二（推荐 方式一）
            // composer require h4cc/wkhtmltopdf-amd64 0.12.x
            // composer require h4cc/wkhtmltoimage-amd64 0.12.x

            // 有引入h4cc包推荐使用方式一   反之用方式二（推荐 方式一）
            /*$dir_path = base_path().'/vendor/h4cc';
            if(!is_dir($dir_path)){
                $warn_txt = "<b>当前打印PDF功能依赖h4cc包(功能已开发完成。)；</b><br><br>引用方法:<br>composer require h4cc/wkhtmltopdf-amd64 0.12.x<br>composer require h4cc/wkhtmltoimage-amd64 0.12.x";
                print_r($warn_txt);
                exit;
            }*/


            // 方式一   本地开发正常  功能正常
            //$pdf = \App::make('snappy.pdf.wrapper');
            //$pdf = $pdf->loadView('book.bookAccount.mxz_one_snappypdf', compact('company_name','period','list','km_name'));
            //return $pdf->setPaper('a4')->setOption('margin-bottom', '20')->inline($name);

            // 方式二
            $pdf = \App::make('dompdf.wrapper');
            $pdf->loadView('book.bookAccount.mxz_one_dompdf', compact('company_name','period','list','km_name'));
            return $pdf->stream($name);

        }else{
            //return false;
            $warn_txt = "请求数据失败，操作异常。";
            print_r($warn_txt);
            exit;
        }
    }

    /**
     * 连续打印 明细账
     * @param Request $request
     * @return mixed
     */
    public function print_all(Request $request)
    {
        $result = SubLedgerModel::Print_AllKm_SubLedger($request);

        $company_name = $result['company_name'];
        $period = $result['period'];
        $list = $result['items'];
        $status = $result['status'];

        if($status == 'success'){
            $name = $company_name.'_'.$period.'_明细账_pdf文件.pdf';

            // 有引入h4cc包推荐使用方式一   反之用方式二（推荐 方式一）
            /*$dir_path = base_path().'/vendor/h4cc';
            if(!is_dir($dir_path)){
                $warn_txt = "<b>当前打印PDF功能依赖h4cc包(功能已开发完成。)；</b><br><br>引用方法:<br>composer require h4cc/wkhtmltopdf-amd64 0.12.x<br>composer require h4cc/wkhtmltoimage-amd64 0.12.x";
                print_r($warn_txt);
                exit;
            }*/

            // 方式一
            //$pdf = \App::make('snappy.pdf.wrapper');
            //$pdf = $pdf->loadView('book.bookAccount.mxz_all_snappypdf', compact('company_name','period','list'));
            //$pdf = $pdf->setPaper('a4')->setOption('margin-bottom', '20')->inline($name);
            //return $pdf;

            // 方式二
            $pdf = \App::make('dompdf.wrapper');
            $pdf->loadView('book.bookAccount.mxz_all_dompdf2', compact('company_name','period','list'));
            return $pdf->stream($name);
        }else{
            //return false;
            $warn_txt = "请求数据失败，操作异常。";
            print_r($warn_txt);
            exit;
        }
    }

}