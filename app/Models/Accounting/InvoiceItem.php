<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Accounting\InvoiceItem
 *
 * @property int $id
 * @property int $company_id 代账公司id
 * @property int $invoice_id 发票id
 * @property int $ywlx_id 业务类型id
 * @property string $ywlx_name 业务类型-名词
 * @property int $kpxm_id 开票项目id
 * @property string $kpxm_name 开票项目-名词
 * @property string $ggxh 规格型号
 * @property string $dw 单位
 * @property float $num 数量
 * @property float $money 金额
 * @property float $tax_rate 税率
 * @property float $tax_money 税额
 * @property float $fee_tax_sum 价税合计
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\InvoiceItem whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\InvoiceItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\InvoiceItem whereDw($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\InvoiceItem whereFeeTaxSum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\InvoiceItem whereGgxh($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\InvoiceItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\InvoiceItem whereInvoiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\InvoiceItem whereKpxmId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\InvoiceItem whereKpxmName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\InvoiceItem whereMoney($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\InvoiceItem whereNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\InvoiceItem whereTaxMoney($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\InvoiceItem whereTaxRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\InvoiceItem whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\InvoiceItem whereYwlxId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\InvoiceItem whereYwlxName($value)
 * @mixin \Eloquent
 */
class InvoiceItem extends Model
{
    protected $table = 'invoice_item';

    /**
     * 外键关联 发票
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function invoice()
    {
        return $this->hasOne(Invoice::class, 'id', 'invoice_id');
    }

}
