<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HeaderController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function agent_header(Request $request)
    {
        $id = $request->id;

        $data = '.....';

        return response()->json(['status' => 1, 'msg' => '查询成功！', 'data' => $id]);
    }
}
