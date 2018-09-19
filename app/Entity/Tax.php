<?php


namespace App\Entity;

use App\models\Accounting\Tax as TaxModel;
use App\Models\Accounting\TaxConfig;
use Illuminate\Support\Facades\Schema;

/**
 * 税金计算
 * Class Tax
 * @package App\Entity
 */
class Tax
{
    public static function rate()
    {
        return [
            0.3, 0.17,
        ];
    }

    /**
     * 新建账簿时初始化税金配置
     * @param $company
     * @return bool
     */
    public static function initConfig($company)
    {

        $exist = TaxConfig::query()->where('company_id', $company->id)->count();
        if ($exist != 0) return false;

        $data = [
            1 => [
                'tax_id' => 1, 'tax_name' => '应交城市维护建设税',
                'debit_number' => '5403', 'debit_name' => '税金及附加',
                'credit_number' => '222102', 'credit_name' => '应交城市维护建设税',
            ],
            2 => [
                'tax_id' => 2, 'tax_name' => '应交教育附加',
                'debit_number' => '5403', 'debit_name' => '税金及附加',
                'credit_number' => '222103', 'credit_name' => '应交教育费附加',
            ],
            3 => [
                'tax_id' => 3, 'tax_name' => '应交地方教育费附加',
                'debit_number' => '5403', 'debit_name' => '税金及附加',
                'credit_number' => '222104', 'credit_name' => '应交地方教育费附加',
            ],
            4 => [
                'tax_id' => 4, 'tax_name' => '计提企业所得税',
                'debit_number' => '5403', 'debit_name' => '税金及附加',
                'credit_number' => '222105', 'credit_name' => '应交企业所得税',
            ],
            5 => [
                'tax_id' => 5, 'tax_name' => '计提印花税',
                'debit_number' => '5602', 'debit_name' => '管理费用',
                'credit_number' => '222107', 'credit_name' => '应交印花税',
            ],
            6 => [
                'tax_id' => 6, 'tax_name' => '小规模增值税减免',
                'debit_number' => '222101', 'debit_name' => '应交增值税',
                'credit_number' => '53010101', 'credit_name' => '政府补助_征税收入',
            ],
            7 => [
                'tax_id' => 7, 'tax_name' => '防伪税控费实际抵减',
                'debit_number' => '', 'debit_name' => '',
                'credit_number' => '', 'credit_name' => '',
            ],
        ];

        $tax = TaxModel::all();
        foreach ($tax as $item) {
            //dd($item);
            TaxConfig::query()->updateOrCreate([
                'company_id' => $company->id,
                'tax_id' => $item['id'],
                'tax_name' => $item['name'],
            ], array_merge(['company_id' => $company->id], $data[$item['id']]));
        }
        return true;
    }

    /**
     * 获取企业税金配置
     * @param $company_id
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public static function config($company_id)
    {
        //self::initConfig(Company::sessionCompany());
        return $list = TaxConfig::query()->where('company_id', $company_id)->get();
    }

    /**
     * 保存企业税金配置
     * 有则更新否则新增
     * @param $param
     * @return \Illuminate\Database\Eloquent\Model
     */
    public static function save($param)
    {
        $param = self::filterColumn($param);
        //dd($param);
        empty($param['id']) && $param['id'] = null;
        return TaxConfig::query()->updateOrCreate(['id' => $param['id']], $param);
    }

    /**
     * 过滤非表结构字段
     * @param $param
     */
    private static function filterColumn(Array $param)
    {
        $table = (new TaxConfig())->getTable();
        //dd($param);
        foreach ($param as $key => $item) {
            if (!Schema::hasColumn($table, $key)) {
                unset($param[$key]);
            }
        }

        return $param;
    }

    /**
     * 获取当期增值税
     * @param $company_id
     * @param string $fiscal_period
     */
    public static function zengZhiShui($company_id = '', $fiscal_period = '')
    {
        $company_id == '' && $company_id = Company::sessionCompany()->id;
        $fiscal_period == '' && $fiscal_period = Period::currentPeriod();

        $number = '222101';
        $balance = SubjectBalance::get($company_id, $number, $fiscal_period);

        return $balance;
    }

    /**
     * 计算 城建税
     * @param $money
     * @param string $company_id
     * @param string $fiscal_period
     */
    public static function jsChengJian($company_id, $fiscal_period)
    {

    }

    /**
     * 计算 教育税
     * @param $money
     * @param string $company_id
     * @param $fiscal_period
     */
    public static function jsJiaoYu($company_id = '', $fiscal_period)
    {
    }

    /**
     * 计算 地方教育税
     * @param $money
     * @param $company_id
     * @param $fiscal_period
     */
    public static function jsJiaoYuDiFang($company_id, $fiscal_period)
    {
    }

    /**
     * 计算 企业所得税
     * @param $money
     * @param $company_id
     * @param $fiscal_period
     */
    public static function jsQiYeSuoDe($company_id, $fiscal_period)
    {
    }

    /**
     * 计算 印花税
     * @param $money
     * @param $company_id
     * @param $fiscal_period
     */
    public static function jsYingHua($company_id, $fiscal_period)
    {
    }
}