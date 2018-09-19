<?php
namespace App\Http\Controllers\Book;

use App\Http\Controllers\Controller;

class AccountBookController extends Controller
{
    /**
     * 账簿下的科目余额
     *
     *
     */
    public function index()
    {
        return view('book.bookAccount.kmye');
    }

    /**
     * 账簿下的总账
     *
     *
     */
    public function ledger()
    {
        return view('book.bookAccount.ledger');
    }
    /**
     * 账簿下的明细账
     *
     *
     */
    public function mxz()
    {
        return view('book.bookAccount.mxz');
    }
}