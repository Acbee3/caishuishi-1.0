<?php

namespace App\Entity;

class Company
{
    static $company; //公司
    static $level_set; //公司科目层级
    static $level_detail = []; //详细科目设置

    public function __construct()
    {
        self::$company = (object)(session('companyInfo'));
        self::$level_detail = [];
        !empty(session('companyInfo')->level_set) && self::$level_detail = explode(',', session('companyInfo')->level_set);
        self::$level_set = count(self::$level_detail);
    }

    /**
     * @return \stdClass 公司账户对象信息
     */
    public static function sessionCompany()
    {
        new self();
        return self::$company;
    }

    /**
     * 公司会计期间列表信息
     * @param $company_id
     * @return array
     */
    public static function periodList($company_id)
    {
        $company = self::sessionCompany();
        $years = [2018, 2019];
        $ret = [];

        $beginPeriod = date('Y-m-d', strtotime("{$company->used_year}-{$company->used_month}"));

        foreach ($years as $year) {
            $tmp = [];
            for ($i = 1; $i <= 12; $i++) {
                $period = date('Y-m-d', strtotime("{$year}-{$i}"));

                //判断该会计区间是否可以点击
                //初始会计期间 可点击
                //非初始会计期间 如果上一期已结账 可点击
                //其他情况 不可点击
                $lstPeriodClose = AccountClose::checkClose(['company_id' => $company_id, 'fiscal_period' => Period::lastPeriod($period)]);
                $cannot_click = ($period == Period::first($company_id) || $lstPeriodClose) ? 0 : 1;

                $tmp[$i] = [
                    'cannot_click' => $cannot_click,
                    'close_status' => intval(AccountClose::checkClose(['company_id' => $company_id, 'fiscal_period' => $period])),
                    'url' => route('book.home', [
                        'id' => $company_id, 'company_encode' => $company->company_encode, 'fiscal_period' => $period,
                    ], false),
                ];
            }
            $ret[$year] = $tmp;
        }
        return $ret;
    }
}