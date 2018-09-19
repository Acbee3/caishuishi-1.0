<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Accounting\Fund
 *
 * @property int $id
 * @property int $company_id 代账公司id
 * @property string $fund_date 资金日期
 * @property int $fund_type 资金变动（入账、出账）
 * @property int $channel_type 变动形式（银行、现金、票据）
 * @property int $source_type 变动来源（手动、自动）
 * @property float $money 金额
 * @property int $ywlx_id 业务类型id
 * @property string $ywlx 业务类型
 * @property int $voucher_id 关联凭证号
 * @property int $dw_id 单位id
 * @property int $dw_name 单位名称
 * @property int $invoice_id 发票id
 * @property int $bank_id 银行id
 * @property int $bank_name 银行名称
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Fund whereBankId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Fund whereBankName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Fund whereChannelType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Fund whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Fund whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Fund whereDwId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Fund whereDwName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Fund whereFundDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Fund whereFundType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Fund whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Fund whereInvoiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Fund whereMoney($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Fund whereSourceType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Fund whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Fund whereVoucherId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Fund whereYwlx($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Fund whereYwlxId($value)
 * @mixin \Eloquent
 */
class Fund extends Model
{
    protected $table = 'fund';
    protected $guarded = [];

    //relation
    public function FundItems()
    {
        return $this->hasMany(FundItem::class, 'fund_id', 'id');
    }

    public function voucher()
    {
        return $this->hasOne(Voucher::class, 'id', 'voucher_id');
    }
}
