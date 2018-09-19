<?php
/**
 * 部门人员
 */

namespace App\Entity;


class Department
{

    static  $session_all;
    static  $session_company;
    static  $company_id;

    public function __construct()
    {
        self::$session_all = session();
        self::$session_company = (object)(session('companyInfo'));
    }

    public function List()
    {
        $query = \App\Models\Department::query();
        $query = $query->where('company_id', '=', '1');
        $list = $query->get();
        return $list;
    }
}