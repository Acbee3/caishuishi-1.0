<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Accounting\Asset
 *
 * @property int $id
 * @property int $company_id 代账公司id
 * @property string $zcmc 资产名称
 * @property string $zclb 资产类别(房屋、建筑物……)
 * @property int $num 数量
 * @property string $rzrq 入账日期
 * @property int $zjff 折旧方法(平均年限法……)
 * @property int $zjqx 折旧期限（月）
 * @property string $yzkm 原值科目
 * @property string $ljzjkm 累计折旧科目
 * @property int $yzkm_id 原值科目id
 * @property int $ljzjkm_id 累计折旧科目id
 * @property int $cbfykm_id 成本费用科目id
 * @property string $cbfykm 成本费用科目
 * @property float $yz 原值
 * @property float $cz 残值
 * @property string $remark 备注
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Asset whereCbfykm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Asset whereCbfykmId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Asset whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Asset whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Asset whereCz($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Asset whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Asset whereLjzjkm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Asset whereLjzjkmId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Asset whereNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Asset whereRemark($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Asset whereRzrq($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Asset whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Asset whereYz($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Asset whereYzkm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Asset whereYzkmId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Asset whereZclb($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Asset whereZcmc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Asset whereZjff($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Asset whereZjqx($value)
 * @mixin \Eloquent
 */
class Asset extends Model
{
    protected $table = 'asset';
    protected $guarded = [];

    //relation
    public function voucher()
    {
        return $this->hasOne(Voucher::class, 'id', 'voucher_id');
    }
}
