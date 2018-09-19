<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Accounting\Cost
 *
 * @property int $id
 * @property int $company_id 代账公司id
 * @property int $voucher_id 凭证id
 * @property float $total_money 总金额
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Cost whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Cost whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Cost whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Cost whereTotalMoney($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Cost whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Cost whereVoucherId($value)
 * @mixin \Eloquent
 */
class Cost extends Model
{
    protected $table = 'cost';
    protected $guarded = [];

    //外键关联费用明细记录
    public function costItem()
    {
        return $this->hasMany(CostItem::class, 'cost_id', 'id');
    }

    //外键关联凭证
    public function voucher()
    {
        return $this->hasOne(Voucher::class, 'id', 'voucher_id');
    }

}
