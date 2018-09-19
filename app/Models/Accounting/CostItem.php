<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Accounting\CostItem
 *
 * @property int $id
 * @property int $company_id 公司id
 * @property int $cost_id 费用id
 * @property string $fyrq 费用日期
 * @property string $fylx 费用类型
 * @property float $money 费用金额
 * @property int $dw_id 单位id
 * @property string $dw_name 单位名称
 * @property string $remark 备注
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\CostItem whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\CostItem whereCostId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\CostItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\CostItem whereDwId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\CostItem whereDwName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\CostItem whereFylx($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\CostItem whereFyrq($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\CostItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\CostItem whereMoney($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\CostItem whereRemark($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\CostItem whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CostItem extends Model
{
    protected $table = 'cost_item';
    protected $guarded = [];
}
