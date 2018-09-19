<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class IndexController extends Controller
{
    //
    public function index(){
        $reg_num = DB::table('users')->where('created_at',">=",date("Y-m-d"))->count();
        //$atv_num = DB::table('users')->where('login_at',">=",date("Y-m-d"))->count();
        $atv_num = 0;

        return view("admin.index.index",[
            'reg_num' => $reg_num,
            'atv_num' => $atv_num
        ]);
    }
}
