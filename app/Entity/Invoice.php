<?php

namespace App\Entity;

use App\Entity\BusinessDataConfig\BusinessConfig;
use App\Models\Accounting\AssetAlter;
use App\Models\Accounting\InvoiceItem;
use App\Models\BussinessData;
use App\Models\Common;
use App\Models\Company;
use Illuminate\Support\Facades\Schema;

/**
 * 发票类
 * Class Invoice
 * @property int $id
 * @property int $company_id 代账公司id
 * @property string $fpdm 发票代码
 * @property string $fphm 发票号码
 * @property string $kprq 开票日期
 * @property int $type 发票大类(进项、销项)
 * @property int $sub_type 发票细分类型(增值税专用发票、增值税普通发票…)
 * @package App\Entity
 */
class Invoice
{

    //发票大类
    const TYPE_IMPORT = 1;
    const TYPE_EXPORT = 2;

    //抵扣状态
    const DKZT_BQDK = 1;//本期抵扣
    const DKZT_NO = 0;//不予抵扣

    //抵扣方式
    const DKFS_YBXM = 1;//一般项目
    const DKFS_JZJT = 2;//即征即退

    //结算状态
    const JSZT_YES = 1; //已结算
    const JSZT_NO = 0; //未结算

    //发票-进项发票细分类型
    const SUB_TYPE_IMPORT_ZZSZYFP = 11; //增值税专用发票
    const SUB_TYPE_IMPORT_HGJKZZS = 12; //海关进口增值税专用缴款通知书
    const SUB_TYPE_IMPORT_NCPFP = 13; //农产品发票
    const SUB_TYPE_IMPORT_QTFP = 14; //其他发票
    const SUB_TYPE_IMPORT_QTFP_KDK = 15; //其他发票（可抵扣）

    //发票-销项发票细分类型
    const SUB_TYPE_EXPORT_ZZSZYFP = 21; //增值税专用发票
    const SUB_TYPE_EXPORT_ZZSPTFP = 22; //增值税普通发票
    const SUB_TYPE_EXPORT_HWYSYZZS = 23; //货物运输业增值税专用发票
    const SUB_TYPE_EXPORT_JDCXSTYFP = 24; //机动车销售统一发票
    const SUB_TYPE_EXPORT_GSTYJDFP = 25; //国税通用机打发票
    const SUB_TYPE_EXPORT_GSQTFP = 26; //国税其他发票
    const SUB_TYPE_EXPORT_DSFP = 27; //地税发票
    const SUB_TYPE_EXPORT_WXXSFP = 28; //外销形式发票
    const SUB_TYPE_EXPORT_WPSR_ZZS = 29; //无票收入（增值税）
    const SUB_TYPE_EXPORT_WPSR_YYS = 30; //无票收入（营业税）

    private $id;
    private $companyId;
    private $fpdm;
    private $fphm;
    private $kprq;
    private $type;
    private $sub_type;
    private $jszt;

    public static $typeLabels = [
        self::TYPE_IMPORT => '进项发票',
        self::TYPE_EXPORT => '销项发票',
    ];

    public static $dkztLabels = [
        self::DKZT_BQDK => '本期抵扣',
        self::DKZT_NO => '不予抵扣',
    ];

    public static $dkfsLabels = [
        self::DKFS_YBXM => '一般项目',
        self::DKFS_JZJT => '即征即退',
    ];

    public static $jsztLabels = [
        self::JSZT_YES => '已结算',
        self::JSZT_NO => '未结算',
    ];

    public static $subTypeLabels = [
        self::SUB_TYPE_IMPORT_ZZSZYFP => '增值税专用发票',
        self::SUB_TYPE_IMPORT_HGJKZZS => '海关进口增值税专用缴款通知书',
        self::SUB_TYPE_IMPORT_NCPFP => '农产品发票',
        self::SUB_TYPE_IMPORT_QTFP => '其他发票',
        self::SUB_TYPE_IMPORT_QTFP_KDK => '其他发票（可抵扣）',

        self::SUB_TYPE_EXPORT_ZZSZYFP => '增值税专用发票',
        self::SUB_TYPE_EXPORT_ZZSPTFP => '增值税普通发票',
        self::SUB_TYPE_EXPORT_HWYSYZZS => '货物运输业增值税专用发票',
        self::SUB_TYPE_EXPORT_JDCXSTYFP => '机动车销售统一发票',
        self::SUB_TYPE_EXPORT_GSTYJDFP => '国税通用机打发票',
        self::SUB_TYPE_EXPORT_GSQTFP => '国税其他发票',
        self::SUB_TYPE_EXPORT_DSFP => '地税发票',
        self::SUB_TYPE_EXPORT_WXXSFP => '外销形式发票',
        self::SUB_TYPE_EXPORT_WPSR_ZZS => '无票收入（增值税）',
        self::SUB_TYPE_EXPORT_WPSR_YYS => '无票收入（营业税）',
    ];

    public static $subTypeLabelsImport = [
        self::SUB_TYPE_IMPORT_ZZSZYFP => '增值税专用发票',
        self::SUB_TYPE_IMPORT_HGJKZZS => '海关进口增值税专用缴款通知书',
        self::SUB_TYPE_IMPORT_NCPFP => '农产品发票',
        self::SUB_TYPE_IMPORT_QTFP => '其他发票',
        self::SUB_TYPE_IMPORT_QTFP_KDK => '其他发票（可抵扣）',
    ];

    public static $subTypeLabelsExport = [
        self::SUB_TYPE_EXPORT_ZZSZYFP => '增值税专用发票',
        self::SUB_TYPE_EXPORT_ZZSPTFP => '增值税普通发票',
        self::SUB_TYPE_EXPORT_HWYSYZZS => '货物运输业增值税专用发票',
        self::SUB_TYPE_EXPORT_JDCXSTYFP => '机动车销售统一发票',
        self::SUB_TYPE_EXPORT_GSTYJDFP => '国税通用机打发票',
        self::SUB_TYPE_EXPORT_GSQTFP => '国税其他发票',
        self::SUB_TYPE_EXPORT_DSFP => '地税发票',
        self::SUB_TYPE_EXPORT_WXXSFP => '外销形式发票',
        self::SUB_TYPE_EXPORT_WPSR_ZZS => '无票收入（增值税）',
        self::SUB_TYPE_EXPORT_WPSR_YYS => '无票收入（营业税）',
    ];


    /**
     * 候选单位
     * @param $company_id
     * @return BussinessData[]|array|\Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
     */
    public static function dwList()
    {
        $list = BussinessData::bussinessdataList(['company_id' => \App\Entity\Company::sessionCompany()->id])->toArray();
        $list = array_map(function ($v) {
            return [
                'id' => $v['id'],
                'value' => $v['name'],
                'label' => BussinessData::getType()[$v['type']],
            ];
        }, (array)$list);

        return $list;
    }

    /**
     * 删除发票
     * @param $id
     * @return bool
     * @throws \Exception
     */
    public static function delete($id)
    {
        try {
            $voucher_id = \App\Models\Accounting\Invoice::query()->whereKey($id)->value('voucher_id');

            //删除发票数据
            \App\Models\Accounting\Invoice::query()->whereKey($id)->delete();
            InvoiceItem::query()->where('invoice_id', $id)->delete();

            //删除凭证
            !empty($voucher_id) && \App\Models\Accounting\Voucher::query()->where('id', $voucher_id)->delete();
            !empty($voucher_id) && \App\Models\Accounting\VoucherItem::query()->where('voucher_id', $voucher_id)->delete();

            return true;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * 构造新增方法参数
     * @param $param
     */
    public static function makeAddParam($param)
    {
        unset($param['_token']);

        $items = json_decode($param['items'], true);
        $invoice_columns = Schema::getColumnListing((new \App\Models\Accounting\Invoice())->getTable());
        $invoice_item_columns = Schema::getColumnListing((new InvoiceItem())->getTable());

        $invoice_columns = array_diff($invoice_columns, ['id', 'created_at', 'updated_at']);
        $invoice_item_columns = array_diff($invoice_item_columns, ['id', 'created_at', 'updated_at']);

        foreach ($invoice_columns as $column) {

            empty($param[$column]) &&
            $param[$column] = (Schema::getColumnType((new \App\Models\Accounting\Invoice())->getTable(), $column) == 'boolean'
                || Schema::getColumnType((new \App\Models\Accounting\Invoice())->getTable(), $column) == 'integer'
                || Schema::getColumnType((new \App\Models\Accounting\Invoice())->getTable(), $column) == 'decimal'
            ) ? 0 : '';
        }

        foreach ($invoice_item_columns as $column) {
            foreach ($items as &$item) {
                empty($item[$column]) &&
                $item[$column] = (Schema::getColumnType((new \App\Models\Accounting\InvoiceItem())->getTable(), $column) == 'boolean'
                    || Schema::getColumnType((new \App\Models\Accounting\InvoiceItem())->getTable(), $column) == 'integer'
                    || Schema::getColumnType((new \App\Models\Accounting\InvoiceItem())->getTable(), $column) == 'decimal'
                ) ? 0 : '';
            }

        }
        $param['items'] = $items;
        //dd($param);
        return $param;
    }

    /**
     * 构造新增方法参数
     * @param $param
     */
    public static function makeUpdateParam($param)
    {
        unset($param['_token']);

        $items = json_decode($param['items'], true);
        $invoice_columns = Schema::getColumnListing((new \App\Models\Accounting\Invoice())->getTable());
        $invoice_item_columns = Schema::getColumnListing((new InvoiceItem())->getTable());

        $invoice_columns = array_diff($invoice_columns, ['created_at', 'updated_at']);
        $invoice_item_columns = array_diff($invoice_item_columns, ['created_at', 'updated_at']);

        foreach ($invoice_columns as $column) {

            empty($param[$column]) &&
            $param[$column] = (Schema::getColumnType((new \App\Models\Accounting\Invoice())->getTable(), $column) == 'boolean'
                || Schema::getColumnType((new \App\Models\Accounting\Invoice())->getTable(), $column) == 'integer'
                || Schema::getColumnType((new \App\Models\Accounting\Invoice())->getTable(), $column) == 'decimal'
            ) ? 0 : '';
        }

        foreach ($invoice_item_columns as $column) {
            foreach ($items as &$item) {
                empty($item[$column]) &&
                $item[$column] = (Schema::getColumnType((new \App\Models\Accounting\InvoiceItem())->getTable(), $column) == 'boolean'
                    || Schema::getColumnType((new \App\Models\Accounting\InvoiceItem())->getTable(), $column) == 'integer'
                    || Schema::getColumnType((new \App\Models\Accounting\InvoiceItem())->getTable(), $column) == 'decimal'
                ) ? 0 : '';
            }

        }
        $param['items'] = $items;
        //dd($param);
        return $param;
    }

    /**
     * 新增发票
     * @param $param
     * param 为发票类相关参数
     * param['items'] 发票的明细项目
     * @throws \Exception
     */
    public static function add($param)
    {
        try {
            $invoice = \App\Models\Accounting\Invoice::forceCreate([
                'company_id' => $param['company_id'],
                'fpdm' => $param['fpdm'],
                'fphm' => $param['fphm'],
                'kprq' => $param['kprq'],
                'type' => $param['type'],
                'sub_type' => $param['sub_type'],
                'gfdw_id' => intval($param['gfdw_id']),
                'gfdw_name' => $param['gfdw_name'],
                'gfdw_nsrsbh' => $param['gfdw_nsrsbh'],
                'gfdw_yhzh' => $param['gfdw_yhzh'],
                'gfdw_dzdh' => $param['gfdw_dzdh'],
                'xfdw_id' => intval($param['xfdw_id']),
                'xfdw_name' => $param['xfdw_name'],
                'xfdw_nsrsbh' => $param['xfdw_nsrsbh'],
                'xfdw_yhzh' => $param['xfdw_yhzh'],
                'xfdw_dzdh' => $param['xfdw_dzdh'],
                'dkzt' => intval($param['dkzt']),
                'dkfs' => $param['dkfs'],
                'fpzs' => $param['fpzs'],
                'voucher_id' => intval($param['voucher_id']),
                'jszt' => intval($param['jszt']),
                'wbhs' => intval($param['wbhs']),
                'wbhs_wbbz' => $param['wbhs_wbbz'],
                'wbhs_sjhl' => $param['wbhs_sjhl'],
                'remark' => $param['remark'],
                'kplx' => $param['kplx'],
                'zsfs' => $param['zsfs'],
                'cezs' => $param['cezs'],
                'fiscal_period' => date('Y-m-d', strtotime(Period::currentPeriod())),
            ]);

            $total_fee_tax_money = 0;

            foreach ($param['items'] as $item) {
                $total_fee_tax_money = $total_fee_tax_money + $item['fee_tax_sum'];
                $account_id = self::get_account_id($param['company_id'], $item['account_number']);
                InvoiceItem::forceCreate([
                    'invoice_id' => $invoice['id'],
                    'company_id' => $param['company_id'],
                    'ywlx_id' => $item['ywlx_id'],
                    'ywlx_name' => $item['ywlx_name'],
                    'kpxm_id' => intval($item['kpxm_id']),
                    'kpxm_name' => $item['kpxm_name'],
                    'ggxh' => $item['ggxh'],
                    'dw' => $item['dw'],
                    'num' => $item['num'],
                    'money' => $item['money'],
                    'tax_id' => $item['tax_id'],
                    'tax_name' => $item['tax_name'],
                    'tax_rate' => $item['tax_rate'],
                    'tax_money' => $item['tax_money'],
                    'fee_tax_sum' => $item['fee_tax_sum'],
                    'account_number' => $item['account_number'],
                    'account_name' => $item['account_name'],
                    'fiscal_period' => date('Y-m-d', strtotime(Period::currentPeriod())),
                    'account_id' => $account_id,
                ]);

                //新增固定资产变动
                if ($item['account_number'] == '1601' && !empty($item['kpxm_id'])) {
                    $asset = \App\Models\Accounting\Asset::query()->whereKey($item['kpxm_id'])->firstOrFail();
                    AssetAlter::create([
                        'company_id' => $asset['company_id'],
                        'fiscal_period' => $invoice['fiscal_period'],
                        'asset_id' => $asset['id'],
                        'zclx' => $asset['zclx'],
                        'zcmc' => $asset['zcmc'],
                        'zclb' => $asset['zclb'],
                        'bdlx' => '购入',
                        'dbx' => '原值',
                        'bdje' => $item['fee_tax_sum'],
                    ]);
                }
            }

            self::update_total_fee_tax_money($invoice['id']);
            return $invoice;

        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * 获取会计科目对应的id
     * @param $company_id
     * @param $account_number
     */
    public static function get_account_id($company_id, $account_number)
    {
        return $account_id = \App\Models\AccountSubject::query()
            ->where('company_id', $company_id)
            ->where('number', $account_number)
            ->value('id');
    }

    /**
     * 更新 单条发票的 total_fee_tax_money
     * @param $invoice_id
     * @return bool
     */
    private static function update_total_fee_tax_money($invoice_id)
    {
        $sum = InvoiceItem::query()->where('invoice_id', $invoice_id)->sum('fee_tax_sum');
        return \App\Models\Accounting\Invoice::where('id', $invoice_id)->update([
            'total_fee_tax_money' => $sum,
        ]);

    }

    /**
     * 新增发票
     * @param $param
     * param 为发票类相关参数
     * param['items'] 发票的明细项目
     * @throws \Exception
     */
    public static function update($param)
    {
        try {
            $invoice = \App\Models\Accounting\Invoice::whereKey($param['id'])->update([
                'company_id' => $param['company_id'],
                'fpdm' => $param['fpdm'],
                'fphm' => $param['fphm'],
                'kprq' => $param['kprq'],
                'type' => $param['type'],
                'sub_type' => $param['sub_type'],
                'gfdw_id' => intval($param['gfdw_id']),
                'gfdw_name' => $param['gfdw_name'],
                'gfdw_nsrsbh' => $param['gfdw_nsrsbh'],
                'gfdw_yhzh' => $param['gfdw_yhzh'],
                'gfdw_dzdh' => $param['gfdw_dzdh'],
                'xfdw_id' => intval($param['xfdw_id']),
                'xfdw_name' => $param['xfdw_name'],
                'xfdw_nsrsbh' => $param['xfdw_nsrsbh'],
                'xfdw_yhzh' => $param['xfdw_yhzh'],
                'xfdw_dzdh' => $param['xfdw_dzdh'],
                'dkzt' => intval($param['dkzt']),
                'dkfs' => $param['dkfs'],
                'fpzs' => $param['fpzs'],
                'voucher_id' => intval($param['voucher_id']),
                'jszt' => intval($param['jszt']),
                'wbhs' => intval($param['wbhs']),
                'wbhs_wbbz' => $param['wbhs_wbbz'],
                'wbhs_sjhl' => $param['wbhs_sjhl'],
                'remark' => $param['remark'],
                'kplx' => $param['kplx'],
                'zsfs' => $param['zsfs'],
                'cezs' => $param['cezs'],
            ]);

            foreach ($param['items'] as &$item) {
                $account_id = self::get_account_id($param['company_id'], $item['account_number']);
                $attr = [
                    'invoice_id' => $param['id'],
                    'company_id' => $param['company_id'],
                    'ywlx_id' => $item['ywlx_id'],
                    'ywlx_name' => $item['ywlx_name'],
                    'kpxm_id' => intval($item['kpxm_id']),
                    'kpxm_name' => $item['kpxm_name'],
                    'ggxh' => $item['ggxh'],
                    'dw' => $item['dw'],
                    'num' => $item['num'],
                    'money' => $item['money'],
                    'tax_id' => $item['tax_id'],
                    'tax_name' => $item['tax_name'],
                    'tax_rate' => $item['tax_rate'],
                    'tax_money' => $item['tax_money'],
                    'fee_tax_sum' => $item['fee_tax_sum'],
                    'account_number' => $item['account_number'],
                    'account_name' => $item['account_name'],
                    'account_id' => $account_id,
                ];

                if (empty($item['id'])) {
                    $tmp_item = InvoiceItem::forceCreate($attr);
                    $item['id'] = $tmp_item['id'];
                    //dd($tmp_item);
                } else {
                    InvoiceItem::whereKey($item['id'])->update($attr);
                }
            }

            $item_ids = array_column($param['items'], 'id');

            InvoiceItem::query()->where('invoice_id', $param['id'])
                ->whereNotIn('id', $item_ids)->delete();

            self::update_total_fee_tax_money($invoice['id']);

            return $invoice;

        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * 获取单个发票的信息
     * @param $id
     * @return \App\Models\Accounting\Invoice|\Illuminate\Database\Eloquent\Model|null|object
     * @throws \Exception
     */
    public static function detail($id)
    {
        $invoice = \App\Models\Accounting\Invoice::whereKey($id)->first();
        if (empty($invoice))
            throw new \Exception("发票信息{$id}不存在");

        $invoice['items'] = $invoice->invoiceItem;
        return $invoice;
    }

    /**
     * 业务类型候选id
     * @param $type
     */
    public static function ywItem($type)
    {
        return $data = (new BusinessConfig($type))->getData();
    }

    /**
     * 发票汇总
     * @param  $company
     * @param array $param
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function summary($company, $param = [])
    {
        $query = \App\Models\Accounting\InvoiceItem::query()
            ->leftJoin('invoice', 'invoice.id', '=', 'invoice_item.invoice_id')
            ->where('invoice.company_id', $company->id);
        $query = $query->where('invoice.type', $param['type']);

        $sub_types = $query->select('invoice.sub_type')->distinct()->pluck('sub_type');
        $data = [];

        foreach ($sub_types as $sub_type) {
            $data[$sub_type] = [
                'label' => self::$subTypeLabels[$sub_type],
                'dkzt' => '不可抵扣',
                'fpzs_sum' => self::subTypeSum($company, $param, $sub_type, 'fpzs'),
                'money_sum' => self::subTypeSum($company, $param, $sub_type, 'money'),
                'tax_money_sum' => self::subTypeSum($company, $param, $sub_type, 'tax_money'),
                'fee_tax_money_sum' => self::subTypeSum($company, $param, $sub_type, 'fee_tax_sum'),
            ];
        }
        return $data;
    }

    /**
     * 获取分类总价
     * @param $company
     * @param $param
     * @param $sub_type
     * @param $column
     * @return mixed
     */
    private static function subTypeSum($company, $param, $sub_type, $column)
    {
        $query = \App\Models\Accounting\InvoiceItem::query()
            ->leftJoin('invoice', 'invoice.id', '=', 'invoice_item.invoice_id')
            ->where('invoice.company_id', $company->id);
        $query = $query->where('invoice.type', $param['type']);
        return $query->where('invoice.sub_type', $sub_type)->sum($column);
    }

    /**
     * 发票列表
     * @param Company $company
     * @param array $param
     */
    public function list(Company $company, $param = [], $pagesize = 10)
    {
        $query = \App\Models\Accounting\Invoice::query();
        $query = $query->where('invoice.company_id', '=', $company->id);
        //搜索内容
        $query = $this->search($query, $param, $company);

        $list = $query->paginate($pagesize);
        foreach ($list as &$item) {
            $item['jszt'] = Invoice::$jsztLabels[$item->jszt];
            $item['invoice_items'] = $item->invoiceItem;

            $item['total_tax_money'] = array_reduce(array_column($item->invoiceItem->toArray(), 'tax_money'), function ($v1, $v2) {
                return $v1 + $v2;
            }, 0);

            $item['total_money'] = array_reduce(array_column($item->invoiceItem->toArray(), 'money'), function ($v1, $v2) {
                return $v1 + $v2;
            }, 0);

            $item['total_fee_tax_sum'] = array_reduce(array_column($item->invoiceItem->toArray(), 'fee_tax_sum'), function ($v1, $v2) {
                return $v1 + $v2;
            }, 0);

            $item['voucher_num'] = !empty($item->voucher->voucher_num) ? '记-' . $item->voucher->voucher_num : '';
        }

        return $list;
    }

    /**
     * 导出进项excel
     * @param Company $company
     * @param array $param
     */
    public function importExcel(Company $company, $param = [])
    {
        $query = \App\Models\Accounting\Invoice::query();
        $query = $query->where('invoice.company_id', '=', $company->id);
        //搜索内容
        isset($param['type']) && $query = $query->where('type', $param['type']);

        $query = $this->search($query, $param, $company);
        $invoice_ids = $query->pluck('id');

        $item_query = InvoiceItem::query()
            ->leftJoin('invoice', 'invoice.id', '=', 'invoice_item.invoice_id')
            ->whereIn('invoice_id', $invoice_ids);

        $header = [
            '序号', '发票类型', '发票代码', '发票号码', '销方名称', '销方税号',
            '开票项目', '规格型号', '计量单位', '数量', '金额', '税率',
            '税额', '价税合计', '开票日期', '备注', '抵扣状态', '抵扣方式',
        ];

        $column = [
            'invoice_item.id', 'invoice.sub_type', 'fpdm', 'fphm', 'xfdw_name', 'xfdw_nsrsbh',
            'kpxm_name', 'ggxh', 'dw', 'num', 'money', 'tax_rate',
            'tax_money', 'fee_tax_sum', 'kprq', 'remark', 'dkzt', 'dkfs',
        ];

        $item_query = $item_query->select($column);
        $data = $item_query->get()->toArray();

        foreach ($data as &$datum) {
            $datum['sub_type'] = self::$subTypeLabels[$datum['sub_type']];
            $datum['dkzt'] = self::$dkztLabels[$datum['dkzt']];
            $datum['dkfs'] = self::$dkfsLabels[$datum['dkfs']];
        }

        array_unshift($data, $header);
        Common::exportExcel($data, '发票' . date('YmdHis'));
    }

    /**
     * 导出销项excel
     * @param Company $company
     * @param array $param
     */
    public function exportExcel(Company $company, $param = [])
    {
        $query = \App\Models\Accounting\Invoice::query();
        $query = $query->where('invoice.company_id', '=', $company->id);
        //搜索内容
        isset($param['type']) && $query = $query->where('type', $param['type']);

        $query = $this->search($query, $param, $company);
        $invoice_ids = $query->pluck('id');

        $item_query = InvoiceItem::query()
            ->leftJoin('invoice', 'invoice.id', '=', 'invoice_item.invoice_id')
            ->whereIn('invoice_id', $invoice_ids);

        $header = [
            '序号', '发票类型', '发票代码', '发票号码', '购方名称', '购方税号',
            '开票项目', '规格型号', '计量单位', '数量', '金额', '税率',
            '税额', '价税合计', '开票日期', '备注', '征收方式',
        ];

        $column = [
            'invoice_item.id', 'invoice.sub_type', 'fpdm', 'fphm', 'xfdw_name', 'xfdw_nsrsbh',
            'kpxm_name', 'ggxh', 'dw', 'num', 'money', 'tax_rate',
            'tax_money', 'fee_tax_sum', 'kprq', 'remark', 'zsfs',
        ];

        $item_query = $item_query->select($column);
        $data = $item_query->get()->toArray();

        foreach ($data as &$datum) {
            $datum['sub_type'] = self::$subTypeLabels[$datum['sub_type']];
            $datum['zsfs'] = implode(',', json_decode($datum['zsfs']));
        }

        array_unshift($data, $header);
        Common::exportExcel($data, '发票' . date('YmdHis'));
    }

    /**
     * 处理搜索
     * @param $query
     * @param $param
     * @param $company
     */
    private function search($query, $param, $company)
    {
        //发票大类
        isset($param['type']) && $query = $query->where('type', $param['type']);

        //筛选会计区间
        isset($param['fiscal_period']) && $query = $query->where('fiscal_period', date('Y-m-d', strtotime($param['fiscal_period'])));

        //凭证状态
        isset($param['pzzt']) && $param['pzzt'] == 1 && $query = $query->where('voucher_id', '!=', '');
        isset($param['pzzt']) && $param['pzzt'] == 0 && $query = $query->where('voucher_id', '=', '0');

        //税率
        !empty($param['tax_rate']) &&
        $query = $query->where(function ($q) use ($param, $company) {
            $invoice_ids = InvoiceItem::query()->where('company_id', $company->id)
                ->where('tax_rate', $param['tax_rate'])->pluck('invoice_id');
            $q->whereIn('id', $invoice_ids);
        });

        //发票号码
        !empty($param['fphm']) && $query = $query->where('fphm', 'like', '%' . $param['fphm'] . '%');

        //是否结算
        isset($param['jszt']) && $query = $query->where('jszt', $param['jszt']);

        //销方名称
        !empty($param['xfdw_name']) && $query = $query->where('xfdw_name', 'like', '%' . $param['xfdw_name'] . '%');

        //购方名称
        !empty($param['gfdw_name']) && $query = $query->where('gfdw_name', 'like', '%' . $param['gfdw_name'] . '%');

        //备注
        !empty($param['remark']) && $query = $query->where('remark', 'like', '%' . $param['remark'] . '%');

        //总金额
        !empty($param['money']) && $query = $query->where('total_fee_tax_money', $param['money']);

        return $query;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * 获取类型名称
     * @return string 进项|销项
     */
    public function getTypeLabel(): string
    {
        return self::$typeLabels[$this->type];
    }

    /**
     * @param int $type
     * @return Invoice
     */
    public function setType(int $type): Invoice
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getFphm(): string
    {
        return $this->fphm;
    }

    /**
     * @param string $fphm
     */
    public function setFphm(string $fphm)
    {
        $this->fphm = $fphm;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getCompanyId()
    {
        return $this->companyId;
    }

    /**
     * @param mixed $companyId
     */
    public function setCompanyId($companyId)
    {
        $this->companyId = $companyId;
    }

    /**
     * @return mixed
     */
    public function getFpdm()
    {
        return $this->fpdm;
    }

    /**
     * @return mixed
     */
    public function getKprq()
    {
        return $this->kprq;
    }

    /**
     * @param mixed $kprq
     */
    public function setKprq($kprq)
    {
        $this->kprq = $kprq;
    }

    /**
     * @return mixed
     */
    public function getSubType()
    {
        return $this->sub_type;
    }

    /**
     * 获取发票明细分类 增值税普通发票|增值税专用发票|...
     * @return mixed
     */
    public function getSubTypeLabel()
    {
        return $this->sub_type;
    }

    /**
     * @param mixed $sub_type
     */
    public function setSubType($sub_type)
    {
        return self::$typeLabels[$this->type];
    }

    /**
     * 获取结算状态中文
     * @return mixed
     */
    public function getJsztLabel()
    {
        return self::$jsztLabels[$this->jszt];
    }

}