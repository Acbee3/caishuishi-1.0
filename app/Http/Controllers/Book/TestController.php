<?php

namespace App\Http\Controllers\book;

use App\Entity\AccountClose;
use App\Entity\Company;
use App\Entity\Period;
use App\Http\Controllers\Controller;

class TestController extends Controller
{
    public function index()
    {

        //dd(Period::lastPeriod('2018-1-1'));

        AccountClose::jiTiShuiJin(Company::sessionCompany());
        dd(123);
        return view('');
    }
}
