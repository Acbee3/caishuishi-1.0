<?php

namespace App\Http\Controllers\Book;

use App\Models\AccountSubject;
use App\Models\BussinessData;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BussinessdataController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $type = $request->type;
        $list = BussinessData::bussinessdataList($request);
        $types = BussinessData::getType();
        $subjects = AccountSubject::subjectList($request)['list'];
        if ($request->ajax()) return $list;
        return view('book.setting.bussinessdata.bussinessdata', compact('list', 'type', 'types', 'subjects'));
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
        $result = BussinessData::createBussinessdata($data);
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
        $bussinessData = BussinessData::find($id);
        $result = BussinessData::editBussinessdata($bussinessData, $data);
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
        $result = BussinessData::del($request);
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
        $result = BussinessData::freeze($request);
        if ($result === true) {
            return response()->json(['success' => '操作成功']);
        } else {
            return response()->json(['success' => $result]);
        }
    }
}
