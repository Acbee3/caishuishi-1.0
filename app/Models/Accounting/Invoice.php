<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Accounting\Invoice
 *
 * @property int $id
 * @property int $company_id 代账公司id
 * @property string $fpdm 发票代码
 * @property string $fphm 发票号码
 * @property string $kprq 开票日期
 * @property int $type 发票大类(进项、销项)
 * @property int $sub_type 发票细分类型(增值税专用发票、增值税普通发票…)
 * @property string $gfdw_name 购方单位-名词
 * @property string $gfdw_nsrsbh 购方单位-纳税人识别号
 * @property string $gfdw_yhzh 购方单位-银行账号
 * @property string $gfdw_dzdh 购方单位-地址电话
 * @property int $gfdw_id 购方单位-id
 * @property int $xfdw_id 销方单位
 * @property string $xfdw_name 销方单位名词
 * @property string $xfdw_nsrsbh 销方单位-纳税人识别号
 * @property string $xfdw_yhzh 销方单位-银行账号
 * @property string $xfdw_dzdh 销方单位-地址电话
 * @property int $dkzt 抵扣状态
 * @property int $dkfs 抵扣方式
 * @property int $fpzs 发票张数
 * @property int $voucher_id 凭证id
 * @property int $jszt 结算状态
 * @property int $wbhs 外币核算
 * @property string $wbhs_wbbz 外币核算_外币币种
 * @property float $wbhs_sjhl 外币核算_实际汇率
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Invoice whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Invoice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Invoice whereDkfs($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Invoice whereDkzt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Invoice whereFpdm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Invoice whereFphm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Invoice whereFpzs($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Invoice whereGfdwDzdh($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Invoice whereGfdwId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Invoice whereGfdwName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Invoice whereGfdwNsrsbh($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Invoice whereGfdwYhzh($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Invoice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Invoice whereJszt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Invoice whereKprq($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Invoice whereSubType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Invoice whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Invoice whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Invoice whereVoucherId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Invoice whereWbhs($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Invoice whereWbhsSjhl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Invoice whereWbhsWbbz($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Invoice whereXfdwDzdh($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Invoice whereXfdwId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Invoice whereXfdwName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Invoice whereXfdwNsrsbh($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Invoice whereXfdwYhzh($value)
 * @mixin \Eloquent
 */
class Invoice extends Model
{
    protected $table = 'invoice';

    public function invoiceItem()
    {
        return $this->hasMany(InvoiceItem::class, 'invoice_id', 'id');
    }

    public function voucher()
    {
        return $this->hasOne(Voucher::class, 'id', 'voucher_id');
    }

}
