<?php

namespace App\Models\Accounting;

use App\Entity\Company;
use App\Entity\Period;
use App\Models\Common;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Accounting\Voucher
 *
 * @property int $id
 * @property int $company_id 代账公司id
 * @property string $voucher_num 记账号
 * @property int $attach 附件张数
 * @property string $voucher_date 记账日期
 * @property float $total_debit_money 借方总金额
 * @property float $total_credit_money 贷方总金额
 * @property string|null $total_cn 合计金额（中文大写）
 * @property int $creator_id 制作人id
 * @property string $creator_name 制作人名称
 * @property int $auditor_id 审核人id
 * @property string $auditor_name 审核人名称
 * @property int $audit_status 审核状态
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Voucher whereAttach($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Voucher whereAuditStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Voucher whereAuditorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Voucher whereAuditorName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Voucher whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Voucher whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Voucher whereCreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Voucher whereCreatorName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Voucher whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Voucher whereTotalCn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Voucher whereTotalCreditMoney($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Voucher whereTotalDebitMoney($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Voucher whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Voucher whereVoucherDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Voucher whereVoucherNum($value)
 * @mixin \Eloquent
 */
class Voucher extends Model
{
    protected $table = 'voucher';
    protected $guarded = [];

    const VOUCHER_SOURCE_QB = 1;
    const VOUCHER_SOURCE_JXFP = 2;
    const VOUCHER_SOURCE_XXFP = 3;
    const VOUCHER_SOURCE_FYFP = 4;
    const VOUCHER_SOURCE_PJ = 5;
    const VOUCHER_SOURCE_XJ = 6;
    const VOUCHER_SOURCE_YH = 7;
    const VOUCHER_SOURCE_ZC = 8;
    const VOUCHER_SOURCE_CBJZ = 9;
    const VOUCHER_SOURCE_SJJT = 10;
    const VOUCHER_SOURCE_QMJZ = 11;
    const VOUCHER_SOURCE_XZ = 12;
    const VOUCHER_SOURCE_NZJZ = 13;
    const VOUCHER_SOURCE_SGPZ = 14;
    const VOUCHER_SOURCE_HDSYJZ = 15;
    const VOUCHER_SOURCE_SYJZ = 16;

    public static $voucherSourceLabels = [
        self::VOUCHER_SOURCE_QB => '全部',
        self::VOUCHER_SOURCE_JXFP => '进项发票',
        self::VOUCHER_SOURCE_XXFP => '销项发票',
        self::VOUCHER_SOURCE_FYFP => '费用发票',
        self::VOUCHER_SOURCE_PJ => '票据',
        self::VOUCHER_SOURCE_XJ => '现金',
        self::VOUCHER_SOURCE_YH => '银行',
        self::VOUCHER_SOURCE_ZC => '资产',
        self::VOUCHER_SOURCE_CBJZ => '成本结转',
        self::VOUCHER_SOURCE_SJJT => '税金计提',
        self::VOUCHER_SOURCE_QMJZ => '期末结转',
        self::VOUCHER_SOURCE_XZ => '薪酬',
        self::VOUCHER_SOURCE_NZJZ => '年终结转',
        self::VOUCHER_SOURCE_SGPZ => '手工凭证',
        self::VOUCHER_SOURCE_HDSYJZ => '汇兑损益结转',
        self::VOUCHER_SOURCE_SYJZ => '损益结转',
    ];

    const AUDIT_STATUS_0 = 0;
    const AUDIT_STATUS_1 = 1;

    public static $auditStatusLabels = [
        self::AUDIT_STATUS_1 => '已审核',
        self::AUDIT_STATUS_0 => '未审核',
    ];

    /**
     * 凭证列表
     * @param $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function search($request)
    {
        $company = Company::sessionCompany();
        $period = Period::currentPeriod();

        $model = new self();
        $model = $model->where("company_id", $company->id);

        empty($request->fiscal_period_start) && empty($request->fiscal_period_end) && $model = $model->where('fiscal_period', $period);
        !empty($request->fiscal_period_start) && $model = $model->where('fiscal_period', ">=", date("Y-m-01",strtotime($request->fiscal_period_start)));
        !empty($request->fiscal_period_end) && $model = $model->where('fiscal_period', "<=", date("Y-m-01",strtotime($request->fiscal_period_end)));

        !empty($request->voucher_source) && $request->voucher_source != self::VOUCHER_SOURCE_QB && $model = $model->where('voucher_source', $request->voucher_source);

        !empty($request->voucher_num_min) && $model = $model->where('voucher_num', ">=", $request->voucher_num_min);
        !empty($request->voucher_num_max) && $model = $model->where('voucher_num', "<=", $request->voucher_num_max);

        !empty($request->zhaiyao) && $model = $model->where('voucherItem.zhaiyao', "<=", $request->zhaiyao);
        !empty($request->total_debit_money_min) && $model = $model->where('total_debit_money', ">=", $request->total_debit_money_min);
        !empty($request->total_debit_money_max) && $model = $model->where('total_debit_money', "<=", $request->total_debit_money_max);

        isset($request->audit_status) && $request->audit_status != '' && $model = $model->where('audit_status', $request->audit_status);

        if (!empty($request->kuaijikemu)) {
            $ids = VoucherItem::where("company_id", $company->id)->where("fiscal_period", $period)->where(function ($return) use ($request) {
                $return->where('kuaijibianhao', $request->kuaijikemu)->orWhere("kuaijikemu", $request->kuaijikemu);
            })->pluck("voucher_id");
            if (!empty($ids)) {
                $model = $model->whereIn('id', $ids);
            }
        }

        $return = $model->with('voucherItem')->paginate(Common::PAGE_SIZE);
        return $return;
    }

    /**
     * 外键关联凭证明细
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function voucherItem()
    {
        return $this->hasMany(VoucherItem::class, 'voucher_id', 'id');
    }

    /**
     * 创建凭证
     * @param $arr
     * @return bool
     */
    public static function createVoucher($arr)
    {
        $model = empty($arr->id) ? new Voucher() : Voucher::where('id', $arr->id)->where("company_id", $arr->company_id)->first();
        $model->company_id = $arr->company_id;
        $model->voucher_num = $arr->voucher_num;
        $model->attach = $arr->attach;
        $model->voucher_date = $arr->voucher_date;
        $model->total_debit_money = $arr->total_debit_money;
        $model->total_credit_money = $arr->total_credit_money;
        $model->total_cn = $arr->total_cn;
        $model->creator_id = $arr->creator_id;
        $model->creator_name = $arr->creator_name;
        //$model->auditor_id = $arr->auditor_id;
        //$model->auditor_name = $arr->auditor_name;
        $model->audit_status = $arr->audit_status;
        $model->fiscal_period = $arr->fiscal_period;
        $model->voucher_source = $arr->voucher_source;
        if ($model->save()) {
            return $model;
        }

    }

    /**
     * 凭证审核
     * @param $request
     * @return bool
     */
    public static function audit($request)
    {
        $attr = [
            'audit_status' => $request->audit_status,
            'auditor_id' => $request->auditor_id,
            'auditor_name' => $request->auditor_name,
        ];
        return Voucher::whereIn('id', $request->id)->update($attr);
    }

    /**
     * 删除
     * @param $request
     * @throws \Exception
     */
    public static function del($request)
    {
        $return = Voucher::whereIn('id', $request->id)->delete();
        if (!$return) {
            throw new \Exception("数据删除失败！");
        }
        $return = VoucherItem::whereIn("voucher_id", $request->id)->delete();
        if (!$return) {
            throw new \Exception("子项数据删除失败！");
        }

        // 如果是薪酬相关 更新薪酬表voucher_id
        $return = Salary::Del_Salary_Voucher_Number($request->id);
        if (!$return) {
            throw new \Exception("薪酬凭证更新失败！");
        }
        return true;
    }

}
