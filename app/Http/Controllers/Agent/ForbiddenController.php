<?php
/**
 * Created by PhpStorm V.2018.
 * User: Administrator - Newsboy9248@163.com
 * Date: 2018/5/31 - 10:25
 */

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;

class ForbiddenController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('forbidden');
    }
}