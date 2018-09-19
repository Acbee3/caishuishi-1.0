<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Accounting\AssetAlter
 *
 * @property int $id
 * @property int $company_id 代账公司id
 * @property string $zclx 资产类型(固定资产……)
 * @property string $zcmc 资产名称
 * @property string $zclb 资产类别(房屋、建筑物……)
 * @property int $bdlx 变动类型(购入、卖出……)
 * @property string $dbx 变动项(原值……)
 * @property float $bdje 变动金额
 * @property int $voucher_id 凭证id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\AssetAlter whereBdje($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\AssetAlter whereBdlx($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\AssetAlter whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\AssetAlter whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\AssetAlter whereDbx($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\AssetAlter whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\AssetAlter whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\AssetAlter whereVoucherId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\AssetAlter whereZclb($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\AssetAlter whereZclx($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\AssetAlter whereZcmc($value)
 * @mixin \Eloquent
 */
class AssetAlter extends Model
{
    protected $table = 'asset_alter';
    protected $guarded = [];

    //relation
    public function voucher()
    {
        return $this->hasOne(Voucher::class, 'id', 'voucher_id');
    }
}
