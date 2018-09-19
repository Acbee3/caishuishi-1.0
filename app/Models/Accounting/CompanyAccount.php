<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Accounting\CompanyAccount
 *
 * @property int $id
 * @property int $company_id 代账公司id
 * @property int $type 账户类型（银行、现金、票据）
 * @property float $money 余额
 * @property int $bank_id 银行id
 * @property string $bank_name 银行名称
 * @property int $bank_bz_id 银行币种id
 * @property int $bank_bz_name 银行币种
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\CompanyAccount whereBankBzId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\CompanyAccount whereBankBzName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\CompanyAccount whereBankId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\CompanyAccount whereBankName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\CompanyAccount whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\CompanyAccount whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\CompanyAccount whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\CompanyAccount whereMoney($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\CompanyAccount whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\CompanyAccount whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CompanyAccount extends Model
{
    protected $table = 'company_account';
}
