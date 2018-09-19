<?php

namespace App\Entity;

use App\Models\Accounting\AccountClose as AccountCloseModel;

/**
 * 会计区间实体类
 * Class Period
 * @package App\Entity
 */
class Period
{

    // session key
    const PERIOD_SESSION_KEY = 'fiscal_period';

    /**
     * 设置 会计期间全局session
     * @param $period
     * return void
     */
    public static function setGlobalSession($period)
    {
        /*empty($period) &&
        $period = AccountCloseModel::query()->where('company_id', Company::sessionCompany()->id)
            ->where('close_status', AccountCloseModel::CLOSE_STATUS_NO)
            ->orderBy('fiscal_period', 'asc')
            ->value('fiscal_period');*/

        if (empty($period)) {
            $period = AccountCloseModel::checkNotInitializeAdd(Company::sessionCompany()->id);
        }

        $period = date('Y-m', strtotime($period));
        $period = date('Y-m-d', strtotime($period));
        session([self::PERIOD_SESSION_KEY => $period]);
    }

    /**
     * 获取上个会计期间
     * @param string $period
     * @return false|string
     */
    public static function lastPeriod($period = '')
    {
        $period == '' && $period = self::currentPeriod();
        return date('Y-m-d', strtotime("{$period} -1 month "));
    }

    /**
     * 获取去年最后一期余额
     * @param string $period
     */
    public static function lastYearPeriod($period = '')
    {
        $period == '' && $period = self::currentPeriod();
        return date('Y-12-01', strtotime("{$period} -1 year "));
    }

    /**
     * 会计期间列表
     * @return array
     */
    public static function list()
    {
        $years = [2018, 2019];
        $periods = [];

        foreach ($years as $year) {
            for ($i = 1; $i <= 12; $i++) {
                $periods[] = date('Y-m-d', strtotime("{$year}-{$i}"));
            }
        }
        return $periods;
    }

    /**
     * 获取当前会计区间 例如 2018-7
     * @return string
     */
    public static function currentPeriod()
    {
        empty(session(self::PERIOD_SESSION_KEY)) && self::setGlobalSession(null);
        $period = (string)session(self::PERIOD_SESSION_KEY);

        return $period;
    }

    /**
     * 获取当前会计区间 例如 2018-7    新
     * @return string
     */
    public static function currentPeriod_New()
    {
        $period = self::currentPeriod();
        $period = mb_substr($period, 0, 7, 'utf-8');
        return $period;
    }

    /**
     * 获取当前会计区间 例如 2018年7
     * @return string
     */
    public static function currentPeriodToCN()
    {
        $period = date("Y年第n期", strtotime(self::currentPeriod()));
        return $period;
    }

    /**
     * 获取当前会计期间最后一天
     * @return false|string
     */
    public static function currentPeriodLastDay()
    {
        $firstday = self::currentPeriod();
        return $lastday = date('Y-m-d', strtotime("$firstday +1 month -1 day"));
    }

    /**
     * 获取初始会计区间
     * @param string $company_id
     * @return mixed
     */
    public static function first($company_id = '')
    {
        $company_id == '' && $company_id = Company::sessionCompany()->id;
        $account = AccountCloseModel::query()->where('company_id', $company_id)->orderBy('fiscal_period', 'asc')->first();
        return $account['fiscal_period'];
    }

}
