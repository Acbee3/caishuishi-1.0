<?php

namespace App\Http\Controllers\Book;

use App\Entity\BusinessDataConfig\XJ;
use App\Entity\BusinessDataConfig\YH;
use App\Entity\Period;
use App\Models\AccountBook\Ledger;
use App\Models\AccountBook\SubjectBalance;
use App\Models\Accounting\VoucherItem;
use App\Models\AccountSubject;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class AccountSubjectController extends Controller
{
    /**
     * 会计科目首页
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = AccountSubject::subjectList($request);
        $status = AccountSubject::getStatus();
        if ($request->ajax()) {
            return $data['list'];
        }
//        \Log::info($data);
        return view('book.setting.account_subject', compact('data', 'status'));
    }

    /**
     * 新增会计科目
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        unset($data['_token']);
        $result = AccountSubject::createSubject($data);
        if ($result === true) {
            return response()->json(['success' => '操作成功']);
        } else {
            return response()->json(['success' => $result]);
        }
    }

    /**
     * 修改会计科目
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //判断是否关联凭证
        $first = VoucherItem::where('kuaijikemu_id', $id)->where('fiscal_period', Period::currentPeriod())->first();
        if ($first) return response()->json(['success' => '已关联凭证，无法修改']);
        $result = AccountSubject::editSubject($request, $id);
        if ($result === true) {
            // 更新当前修改科目当期 在总账表科目名称及借贷方向
            Ledger::EditLedgerKmName($id,Period::currentPeriod());

            return response()->json(['success' => '操作成功']);
        } else {
            return response()->json(['success' => $result]);
        }
    }

    /**
     * 删除会计科目
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $subjectchild = AccountSubject::subjectChild($id);
        //判断是否关联凭证
        $first = VoucherItem::where('kuaijikemu_id', $id)->first();
        if ($first) return response()->json(['success' => '已关联凭证，无法删除']);
        if (!$subjectchild->isEmpty()) return response()->json(['success' => '请先删除子科目']);
        \DB::beginTransaction();
        try{
            SubjectBalance::where('account_subject_id', $id)->delete();
            AccountSubject::find($id)->delete();
            \DB::commit();
            return response()->json(['success' => '操作成功']);
        } catch(\Exception $e) {
            \DB::rollBack();
            return response()->json(['success' => '操作失败']);
        }
    }

    /**
     * 禁用会计科目
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function freeze(Request $request)
    {
        $result = AccountSubject::freeze($request);
        if ($result === true) {
            return response()->json(['success' => '操作成功']);
        } else {
            return response()->json(['success' => $result]);
        }
    }
}
