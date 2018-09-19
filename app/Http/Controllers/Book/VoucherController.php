<?php

namespace App\Http\Controllers\Book;

use App\Entity\Period;
use App\Entity\Profit;
use App\Entity\SubjectBalance;
use App\Entity\Voucher;
use App\Http\Controllers\Controller;
use App\Models\Common;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

/**
 * 凭证操作控制器
 * Class VoucherController
 * @package App\Http\Controllers\Book
 */
class VoucherController extends Controller
{

    /**
     * 凭证列表
     * @param Request $request
     * @return string
     */
    public function index(Request $request)
    {
        $data = Voucher::search($request);

        $source = \App\Models\Accounting\Voucher::$voucherSourceLabels;
        $voucherSource = [];
        foreach ($source as $k => $v) {
            $voucherSource[] = [
                'key' => $k,
                'value' => $v,
            ];
        }
        $status = \App\Models\Accounting\Voucher::$auditStatusLabels;

        $period = Period::currentPeriod();
        $y = date("Y", strtotime($period));
        $Y = date("Y");
        if ($y < $Y) {
            $n = 12;
        } else {
            $n = date("n", strtotime($period));
        }
        $fiscal_period = [];
        for ($i = 1; $i <= $n; $i++) {
            $fiscal_period [] = [
                'label' => $y . "年第" . $i . "期",
                'value' => $y . "-" . $i,
            ];
        }


        return view("book.credentials.index",[
            'data' => $data,
            'voucher_source' => $voucherSource,
            'audit_status' => $status,
            'period' => $fiscal_period,
        ]);
    }

    /**
     * 新增凭证 page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function add(Request $request)
    {
        if ($request->ajax()) {
            $d = \App\Entity\AccountSubject::subsetList();
            $period = Period::currentPeriod();
            $voucherNum = Voucher::getCurrentMaxVoucherNum($period);
            //$date = date("Y-m-d", (strtotime($period . "+1 month -1 day")));

            $voucher_date = Carbon::now();
            $period_now = date("Y-m-01", strtotime($voucher_date));
            if ($period == $period_now) {
                $date = date("Y-m-d", strtotime($voucher_date));
            } else {
                $date = date("Y-m-d", strtotime($period . "+1 month -1 day"));
            }

            $period = date("Y年第n期", strtotime($period));
            return response()->json([
                'kuaijikemu' => $d,
                'period' => $period,
                'date' => $date,
                'voucherNum' => $voucherNum,
            ]);

        }
        return view('book.voucher.add');
    }

    /**
     * api 预览凭证
     * @param Request $request
     */
    public function preview(Request $request)
    {
        try {
            if (!empty($request->type)) {
                $return = Voucher::preview($request);
            } else {
                $return = Voucher::showVoucher($request);
            }
            return $return ? Common::apiSuccess($return) : Common::apiFail();
        } catch (\Exception $e) {
            return Common::apiFail($e->getMessage());
        }
    }

    /**
     * api 生成凭证
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function make(Request $request)
    {
        try {
            \DB::beginTransaction();
            $voucher = Voucher::saveVoucher($request);
            \DB::commit();
            return $voucher ? Common::apiSuccess($voucher) : Common::apiFail();
        } catch (\Exception $e) {
            \DB::rollBack();
            return Common::apiFail($e->getMessage());
        }
    }

    /**
     * 凭证审核
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function audit(Request $request)
    {
        try {
            $return = Voucher::audit($request);
            return $return ? Common::apiSuccess() : Common::apiFail();
        } catch (\Exception $e) {
            return Common::apiFail($e->getMessage());
        }
    }

    /**
     * 凭证删除
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function del(Request $request)
    {
        try {
            \DB::beginTransaction();
            $voucher = \App\Models\Accounting\Voucher::where("id", $request->id)->with("voucherItem")->first();
            SubjectBalance::subjectBalanceDelForVoucher($voucher);

            $return = Voucher::del($request);
            \DB::commit();
            return $return ? Common::apiSuccess() : Common::apiFail();
        } catch (\Exception $e) {
            \DB::rollBack();
            return Common::apiFail($e->getMessage());
        }
    }

    public function addEditor(Request $request)
    {
        return view("book.voucher.addEditor");
    }

    public function addKeep(Request $request)
    {
        return view("book.voucher.addKeep");
    }

    public function invoiceSh(Request $request)
    {
        return view("book.voucher.invoiceSh");
    }

    /**
     * 编辑凭证页面
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request)
    {
        return view("book.voucher.edit", compact('request'));
    }

    /**
     * 编辑凭证页  获取凭证页数据
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function api_get_voucher(Request $request)
    {
        $result = Voucher::GetVoucherList($request);
        return response()->json($result);
    }

    /**
     * 获取简化凭证数据
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function api_get_simple_voucher(Request $request)
    {
        $result = Voucher::GetSimpleVoucherInfo($request);
        if ($result['status'] == 'success') {
            return Common::apiSuccess($result);
        } else {
            return Common::apiFail($result);
        }
    }

    /**
     * 展示pdf格式凭证
     * @param Request $request
     */
    public function pdf(Request $request)
    {
        $data = Voucher::pdfList();
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('book.voucher.pdf', ['list' => $data]);
        return $pdf->stream();
    }
}
