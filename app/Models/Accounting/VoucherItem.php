<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Accounting\VoucherItem
 *
 * @property int $id
 * @property int $company_id 代账公司id
 * @property string $zhaiyao 摘要
 * @property int $kuaijikemu_id 会计科目id
 * @property string $kuaijikemu 会计科目
 * @property float $debit_money 借方金额
 * @property float $credit_money 贷方金额
 * @property int|null $voucher_id 凭证id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\VoucherItem whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\VoucherItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\VoucherItem whereCreditMoney($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\VoucherItem whereDebitMoney($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\VoucherItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\VoucherItem whereKuaijikemu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\VoucherItem whereKuaijikemuId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\VoucherItem whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\VoucherItem whereVoucherId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\VoucherItem whereZhaiyao($value)
 * @mixin \Eloquent
 */
class VoucherItem extends Model
{
    protected $table = 'voucher_item';
    protected $guarded = [];

    /**
     * 保存凭着子项
     * @param $obj
     * @return bool
     */
    public static function createVoucherItem($obj){

        $model = new self();
        $model->company_id = $obj['company_id'];
        $model->zhaiyao = $obj['zhaiyao'];
        $model->kuaijikemu_id = $obj['kuaijikemu_id'];
        $model->kuaijikemu = $obj['kuaijikemu'];
        $model->kuaijibianhao = $obj['kuaijibianhao'];
        $model->debit_money = !empty($obj['debit_money'])?$obj['debit_money']:0;
        $model->credit_money = !empty($obj['credit_money'])?$obj['credit_money']:0;
        $model->voucher_id = $obj['voucher_id'];
        $model->fiscal_period = $obj['fiscal_period'];


        return $model->save();
    }

}
