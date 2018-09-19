<?php

namespace App\Models\Accounting;

use App\Models\Company;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class AccountClose extends Model
{
    const CLOSE_STATUS_YES = 1;//已结账
    const CLOSE_STATUS_NO = 0;//未结账

    protected $table = 'account_close';
    protected $guarded = [];

    /**
     * 初次更新账套时初始化AccountClose操作
     * @param $company_id
     * @param $fiscal_period
     * @param $close_status
     * @return bool
     */
    public static function initializeAccountClose($company_id, $fiscal_period, $close_status)
    {
        $model = new AccountClose();
        $model->company_id = $company_id;
        $model->fiscal_period = $fiscal_period;
        $model->close_status = $close_status;
        if ($model->save()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 对没有初始化结账的公司进行补充初始化相关操作
     * @param $company_id
     * @return false|string
     */
    public static function checkNotInitializeAdd($company_id)
    {
        $close_status = self::CLOSE_STATUS_NO;
        $company_info = Company::query()->where('id', $company_id)->first();// find($company_id);

        if ($company_info) {
            $used_year = $company_info->used_year;
            $used_month = $company_info->used_month;
            if (strlen($used_month) == 1) {
                $used_month = '0' . $used_month;
            }
            $fiscal_period = $used_year . '-' . $used_month . '-01';

            $account_close_info = AccountClose::query()->where('company_id', $company_id)->where('fiscal_period', $fiscal_period)->get();
            if (count($account_close_info) == 0) {
                self::initializeAccountClose($company_id, $fiscal_period, $close_status);
            }

            $result = $fiscal_period;
        } else {
            $now_time = Carbon::now();
            $fiscal_period = date('Y-m-d', strtotime($now_time));

            $result = $fiscal_period;
        }

        return $result;
    }
}
