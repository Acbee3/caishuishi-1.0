<?php

namespace App\Http\Controllers\Book;

use App\Models\AccountSubject;
use App\Models\BankAccount;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BankAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $list = BankAccount::bankaccountList($request);
        $subjects = AccountSubject::subjectList($request)['list'];
        if ($request->ajax()) return $list;
        return view('book.setting.bussinessdata.bank_account', compact('list','subjects'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->input();
        $result = BankAccount::createBankaccount($data);
        if ($result === true) {
            return response()->json(['success'=>'操作成功']);
        } else {
            return response()->json(['success'=>$result]);
        }
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->input();
        $bankAccount = BankAccount::find($id);
        $result = BankAccount::editBankaccount($bankAccount, $data);
        if ($result === true) {
            return response()->json(['success'=>'操作成功']);
        } else {
            return response()->json(['success'=>$result]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function del(Request $request)
    {
        $result = BankAccount::del($request);
        if ($result === true) {
            return response()->json(['success' => '操作成功']);
        } else {
            return response()->json(['success' => $result]);
        }
    }

    /**
     * 禁用业务数据
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function freeze(Request $request)
    {
        $result = BankAccount::freeze($request);
        if ($result === true) {
            return response()->json(['success' => '操作成功']);
        } else {
            return response()->json(['success' => $result]);
        }
    }
}
