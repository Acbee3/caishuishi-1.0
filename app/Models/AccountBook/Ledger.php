<?php

namespace App\Models\AccountBook;

use App\Entity\Company;
use App\Entity\Salary;
use App\Models\AccountSubject;
use Illuminate\Database\Eloquent\Model;
use App\Models\AccountBook\SubsidiaryLedger as SubLedgerModel;
use App\Models\AccountBook\Ledger as LedgerModel;
use App\Models\MaatExcel;
use App\Models\Accounting\VoucherItem;

/**
 * 总账类
 * Class Ledger
 * @package App\Models\AccountBook
 */
class Ledger extends Model
{
    public $table = 'ledgers';

    /**
     * 页面加载 返回数据
     * @param $param
     * @return array
     * @throws \Exception
     */
    public static function Get_List($param)
    {
        // 当前会计期间
        $belong_time = Salary::Get_Belong_Time_Type_A();
        $data['belong_time'] = $belong_time;

        // 会计期间选择下拉数据
        $data['qj_options'] = SubLedgerModel::Get_Month_Arr();

        // 获取主体列表数据
        $main_list = self::Get_Merge_List($param, $belong_time, $belong_time);
        $data['list'] = $main_list;
        $data['km_option'] = self::Get_Ledger_KM_Options($main_list);
        if (count($data['list']) > 0) {
            $result = array('status' => true, 'msg' => 'loading', 'data' => $data);
        } else {
            $result = array('status' => true, 'msg' => '加载数据失败！', 'data' => $data);
        }

        return $result;
    }

    /**
     * 获取 主体列表数据
     * @param $param
     * @param $start
     * @param $end
     * @return array
     * @throws \Exception
     */
    public static function Get_Merge_List($param, $start, $end)
    {
        new Company();
        $company_id = Company::$company->id;

        if(empty($param->_token)){
            $no_items = array();
            return $no_items;
        }

        $start = SubLedgerModel::Change_To_Belong_Time_Type($start);//如： 2018-07
        $end = SubLedgerModel::Change_To_Belong_Time_Type($end);//如： 2018-07
        $start = Salary::Get_Belong_Time_FirstDay($start);//如： 2018-07-01
        $end = Salary::Get_Belong_Time_LastDay($end);//如： 2018-07-31

        $voucher_items_list = VoucherItem::query()->where('company_id', $company_id)->whereBetween('fiscal_period', [$start, $end])->get();
        if (count($voucher_items_list) > 0) {
            // 本会计期间内有凭证信息
            $voucher_items = array();
            foreach ($voucher_items_list as $key => $v) {
                $voucher_items[] = $v->kuaijikemu_id;
            }
            $voucher_items_arr = array_unique($voucher_items);

            // 如果当前数组中的会计科目有父级及祖父+级，关联进父级 祖父级
            $voucher_items_arr = self::GetNowArrParentArr($voucher_items_arr);

            $list = LedgerModel::query()->where('company_id', $company_id)->whereBetween('fiscal_period', [$start, $end])->orderBy('fiscal_period', 'ASC')->orderBy('account_subject_number', 'ASC')->get();
            if (count($list) > 0) {
                //对总账表里没有的客户新添加的新科目进行 检测和追加
                $check_member_km = self::Add_Ledger_Member_KM_Rows($voucher_items_arr);

                $items = array();
                if($check_member_km){
                    foreach ($list as $key => $v) {
                        // 取有凭证的组装数据
                        if (in_array($v->account_subject_id, $voucher_items_arr)) {
                            $items[$key]['id'] = $v->id;
                            $items[$key]['code'] = $v->account_subject_number;
                            $items[$key]['name'] = $v->account_subject_name;
                            $items[$key]['ledgerItem'] = self::Get_Ledger_Items($company_id, $v->id);
                            $items[$key]['km_id'] = $v->account_subject_id;
                            $items[$key]['v_code'] = 'v_' . $v->account_subject_number;
                        }
                    }
                }
            } else {
                // 初始化科目余额表
                $result = self::Initialize_Ledger_Period();
                //\Log::info("初始化总账表");

                if ($result) {
                    $list_n = LedgerModel::query()->where('company_id', $company_id)->whereBetween('fiscal_period', [$start, $end])->orderBy('fiscal_period', 'ASC')->get();
                    $items_n = array();
                    foreach ($list_n as $key => $v) {
                        // 取有凭证的组装数据
                        if (in_array($v->account_subject_id, $voucher_items_arr)) {
                            $items_n[$key]['id'] = $v->id;
                            $items_n[$key]['code'] = $v->account_subject_number;
                            $items_n[$key]['name'] = $v->account_subject_name;
                            $items_n[$key]['ledgerItem'] = self::Get_Ledger_Items($company_id, $v->id);
                            $items_n[$key]['km_id'] = $v->account_subject_id;
                            $items_n[$key]['v_code'] = 'v_' . $v->account_subject_number;
                        }
                    }
                    $items = $items_n;
                } else {
                    $items = array();
                }
            }
        } else {
            // 本会计期间内无凭证信息
            $items = array();
        }

        return $items;
    }

    /**
     * 获取总账 一行转三行数组数据
     * @param $company_id
     * @param $id
     * @return array
     */
    public static function Get_Ledger_Items($company_id, $id)
    {
        $items = array();

        //$info = LedgerModel::find($id);
        $info = LedgerModel::query()->where('id',$id)->first();
        if ($info) {
            $balance_direction = $info->balance_direction;
            $fiscal_period = $info->fiscal_period;
            $fiscal_period = mb_substr($fiscal_period, 0, 7, 'utf-8');

            $km_id = $info->account_subject_id;

            // 当前分类、子分类、孙分类
            $km_id_arr = SubLedgerModel::Get_AS_Ids_Arr($km_id);

            // 计算相关数据
            $qc = self::Js_Ledger_QCYE($company_id, $km_id, $fiscal_period);// 期初余额

            $bq_arr = self::Js_Ledger_BQ($company_id, $fiscal_period, $balance_direction, $km_id_arr);
            $bq_j = SubLedgerModel::Format_Money_Type($bq_arr['bq_j']);// 本期 借
            $bq_d = SubLedgerModel::Format_Money_Type($bq_arr['bq_d']);// 本期 贷
            $bq = SubLedgerModel::Format_Money_Type($bq_arr['bq']);// 本期余额

            $bn_arr = self::Js_Ledger_BN($company_id, $fiscal_period, $balance_direction, $km_id_arr);
            $bn_j = SubLedgerModel::Format_Money_Type($bn_arr['bn_j']);// 本年 借
            $bn_d = SubLedgerModel::Format_Money_Type($bn_arr['bn_d']);// 本年 贷

            //计算本年余额
            $bn = SubLedgerModel::Format_Money_Type($bn_arr['bn']);// 本年余额
            /*if ($balance_direction == "借") {
                $arr_bn_j = array($qc, $bq_j, -$bq_d);
                $bn = array_sum($arr_bn_j);
            } else if ($balance_direction == "贷") {
                $arr_bn_d = array($qc, -$bq_j, $bq_d);
                $bn = array_sum($arr_bn_d);
            } else {
                $bn = '0.00';
            }
            $bn = SubLedgerModel::Format_Money_Type($bn);*/

            // 更新期初余额表相关数据
            $param['id'] = $id;
            $param['company_id'] = $company_id;
            $param['qcye'] = $qc;
            $param['qcye_j'] = '0.00';
            $param['qcye_d'] = '0.00';
            $param['bqhj'] = $bq;
            $param['bqhj_j'] = $bq_j;
            $param['bqhj_d'] = $bq_d;
            $param['bnlj'] = $bn;
            $param['bnlj_j'] = $bn_j;
            $param['bnlj_d'] = $bn_d;

            // 更新总账表相关信息（后期性能优化时，可在凭证生成时触发相关更新，再注释以下1行和以上相关11行）
            self::Update_Ledger_Row($param);

            // 期初余额
            $items[0]['date'] = $fiscal_period;
            $items[0]['abstract'] = "期初余额";
            $items[0]['borrower'] = self::Format_Money($info->qcye_j);
            $items[0]['lender'] = self::Format_Money($info->qcye_d);
            $items[0]['direction'] = $balance_direction;
            $items[0]['balance'] = $qc;//self::Format_Money($info->qcye)

            // 本期合计
            $items[1]['date'] = $fiscal_period;
            $items[1]['abstract'] = "本期合计";
            $items[1]['borrower'] = $bq_j;//self::Format_Money($info->bqhj_j)
            $items[1]['lender'] = $bq_d;//self::Format_Money($info->bqhj_d)
            $items[1]['direction'] = $balance_direction;
            $items[1]['balance'] = $bq;//self::Format_Money($info->bqhj)

            // 本年累计
            $items[2]['date'] = $fiscal_period;
            $items[2]['abstract'] = "本年累计";
            $items[2]['borrower'] = $bn_j;//self::Format_Money($info->bnlj_j)
            $items[2]['lender'] = $bn_d;//self::Format_Money($info->bnlj_d)
            $items[2]['direction'] = $balance_direction;
            $items[2]['balance'] = $bn;//self::Format_Money($info->bnlj)
        }

        return $items;
    }

    /**
     * 计算期初余额
     * @param $company_id
     * @param $fiscal_period
     * @param $km_id
     * @return mixed|string
     */
    public static function Js_Ledger_QCYE($company_id, $km_id, $fiscal_period)
    {
        $fiscal_period = Salary::Get_Belong_Time_FirstDay($fiscal_period);
        $last_wq = LedgerModel::query()->where('company_id', $company_id)->where('account_subject_id', $km_id)->where('fiscal_period', '<', $fiscal_period)->orderBy('fiscal_period', 'DESC')->first();
        if ($last_wq) {
            //$amount = $last_wq->bqhj;// 期初余额 ＝ 上个往期 本期合计
            if ($last_wq->balance_direction == '借') {
                $arr_j = array($last_wq->qcye, $last_wq->bqhj_j, -$last_wq->bqhj_d);
                $amount = array_sum($arr_j);
            } else {
                $arr_d = array($last_wq->qcye, -$last_wq->bqhj_j, $last_wq->bqhj_d);
                $amount = array_sum($arr_d);
            }
        } else {
            $amount = '0.00';
        }

        $amount = SubLedgerModel::Format_Money_Type($amount);
        return $amount;
    }

    /**
     * 计算本期相关数据
     * @param $company_id
     * @param $fiscal_period
     * @param $balance_direction
     * @param $km_id_arr
     * @return mixed
     */
    public static function Js_Ledger_BQ($company_id, $fiscal_period, $balance_direction, $km_id_arr)
    {
        $start = Salary::Get_Belong_Time_FirstDay($fiscal_period);
        $end = Salary::Get_Belong_Time_LastDay($fiscal_period);
        $fiscal_period_arr = [$start, $end];

        $voucher_item = VoucherItem::query()->where('company_id', $company_id)->whereIn('kuaijikemu_id', $km_id_arr)->whereBetween('fiscal_period', $fiscal_period_arr);
        if (count($voucher_item->get()) > 0) {
            $sum_arr = $voucher_item->first(
                array(
                    \DB::raw('SUM(debit_money) as total_debit'),
                    \DB::raw('SUM(credit_money) as total_credit')
                )
            )->toArray();
        } else {
            $sum_arr = array(
                'total_debit' => '0.00',
                'total_credit' => '0.00'
            );
        }

        $bq_arr['bq_j'] = $sum_arr['total_debit'];
        $bq_arr['bq_d'] = $sum_arr['total_credit'];
        if ($balance_direction == "借") {
            $arr = array($sum_arr['total_debit'], -$sum_arr['total_credit']);
            $bq_arr['bq'] = array_sum($arr);
        } else if ($balance_direction == "贷") {
            $arr = array(-$sum_arr['total_debit'], $sum_arr['total_credit']);
            $bq_arr['bq'] = array_sum($arr);
        } else {
            $bq_arr['bq'] = '0.00';
        }

        return $bq_arr;
    }

    /**
     * 计算本年相关数据
     * @param $company_id
     * @param $fiscal_period
     * @param $balance_direction
     * @param $km_id_arr
     * @return mixed
     */
    public static function Js_Ledger_BN($company_id, $fiscal_period, $balance_direction, $km_id_arr)
    {
        $start = Salary::Get_Belong_Time_Year_FirstDay($fiscal_period);
        $end = Salary::Get_Belong_Time_LastDay($fiscal_period);
        $fiscal_period_arr = [$start, $end];

        $voucher_item = VoucherItem::query()->where('company_id', $company_id)->whereIn('kuaijikemu_id', $km_id_arr)->whereBetween('fiscal_period', $fiscal_period_arr);
        if (count($voucher_item->get()) > 0) {
            $sum_arr = $voucher_item->first(
                array(
                    \DB::raw('SUM(debit_money) as total_debit'),
                    \DB::raw('SUM(credit_money) as total_credit')
                )
            )->toArray();
        } else {
            $sum_arr = array(
                'total_debit' => '0.00',
                'total_credit' => '0.00'
            );
        }

        $bn_arr['bn_j'] = $sum_arr['total_debit'];
        $bn_arr['bn_d'] = $sum_arr['total_credit'];
        if ($balance_direction == "借") {
            $arr = array($sum_arr['total_debit'], -$sum_arr['total_credit']);
            $bn_arr['bn'] = array_sum($arr);
        } else if ($balance_direction == "贷") {
            $arr = array(-$sum_arr['total_debit'], $sum_arr['total_credit']);
            $bn_arr['bn'] = array_sum($arr);
        } else {
            $bn_arr['bn'] = '0.00';
        }
        //$bn_arr['bn'] = '0.00';

        return $bn_arr;
    }

    /**
     * 更新总账表
     * @param $param
     * @return int
     */
    public static function Update_Ledger_Row($param)
    {
        $id = $param['id'];
        $company_id = $param['company_id'];

        $qcye = $param['qcye'];
        $bqhj = $param['bqhj'];
        $bqhj_j = $param['bqhj_j'];
        $bqhj_d = $param['bqhj_d'];
        $bnlj = $param['bnlj'];
        $bnlj_j = $param['bnlj_j'];
        $bnlj_d = $param['bnlj_d'];

        $data = array(
            'qcye' => $qcye,
            'bqhj' => $bqhj,
            'bqhj_j' => $bqhj_j,
            'bqhj_d' => $bqhj_d,
            'bnlj' => $bnlj,
            'bnlj_j' => $bnlj_j,
            'bnlj_d' => $bnlj_d
        );
        $return = LedgerModel::query()->where('id', $id)->where('company_id', $company_id)->update($data);

        return $return;
    }

    /**
     * 0值金额设置为空
     * @param $val
     * @return string
     */
    public static function Format_Money($val)
    {
        if ($val == '0.00' || $val == '0') {
            $val = '';
        }
        return $val;
    }

    /**
     * 列表数据
     * @param $param
     * @return array
     * @throws \Exception
     */
    public static function Get_Change_List($param)
    {
        // 获取主体列表数据
        $start = $param->start;
        $end = $param->end;

        $main_list = self::Get_Merge_List($param, $start, $end);
        $data['list'] = $main_list;

        $data['km_option'] = self::Get_Ledger_KM_Options($main_list);

        $result = array('status' => true, 'msg' => 'loading', 'data' => $data);
        return $result;
    }

    /**
     * 导出总账信息
     * @param $param
     * @return bool
     */
    public static function Export_Ledger($param)
    {
        new Company();
        $company_id = Company::$company->id;

        $start = SubLedgerModel::Change_To_Belong_Time_Type($param->start);
        $end = SubLedgerModel::Change_To_Belong_Time_Type($param->end);

        //$belong_time = Salary::Get_Belong_Time();
        $begin = Salary::Get_Belong_Time_FirstDay($start);
        $end = Salary::Get_Belong_Time_LastDay($end);
        $belong_time_arr = array($begin, $end);

        if ($param->ids == 'all') {
            $num = count(LedgerModel::query()->where('company_id', $company_id)->whereBetween('fiscal_period', $belong_time_arr)->get());
            if ($num > 0) {
                LedgerModel::query()->where('company_id', $company_id)->whereBetween('fiscal_period', $belong_time_arr)->orderBy('fiscal_period', 'ASC')->orderBy('account_subject_number', 'ASC')->chunk(500, function ($lists) {
                    $cellData[] = array('0' => Company::$company->company_name . '_总账');
                    //$cellData[] = array('0' => '');
                    $cellData[] = array('0' => '');//$qj_txt
                    $cellData[] = ['科目编码', '科目名称', '期间', '摘要', '借方', '贷方', '方向', '余额'];
                    if (count($lists) > 0) {
                        foreach ($lists as $key => $v) {
                            // 排除未发生业务的相关行
                            if ($v->qcye_j == "0.00" && $v->qcye_d == "0.00" && $v->bqhj_j == "0.00" && $v->bqhj_d == "0.00" && $v->bnlj_j == "0.00" && $v->bnlj_d == "0.00") {
                                // 无业务数据行
                            } else {
                                $cellData[] = [
                                    $v->account_subject_number,
                                    $v->account_subject_name,
                                    mb_substr($v->fiscal_period, 0, 7, 'utf-8'),
                                    '期初余额',
                                    $v->qcye_j,
                                    $v->qcye_d,
                                    $v->balance_direction,
                                    $v->qcye
                                ];
                                $cellData[] = [
                                    $v->account_subject_number,
                                    $v->account_subject_name,
                                    mb_substr($v->fiscal_period, 0, 7, 'utf-8'),
                                    '本期合计',
                                    $v->bqhj_j,
                                    $v->bqhj_d,
                                    $v->balance_direction,
                                    $v->bqhj
                                ];
                                $cellData[] = [
                                    $v->account_subject_number,
                                    $v->account_subject_name,
                                    mb_substr($v->fiscal_period, 0, 7, 'utf-8'),
                                    '本年累计',
                                    $v->bnlj_j,
                                    $v->bnlj_d,
                                    $v->balance_direction,
                                    $v->bnlj
                                ];
                            }
                        }

                        MaatExcel::Export_Ledger($cellData, "xls");
                    }
                });
            } else {
                $cellData[] = array('0' => Company::$company->company_name . '_总账');
                $cellData[] = array('0' => '期间：' . Salary::Get_Belong_Time() . ' ');
                $cellData[] = ['科目编码', '科目名称', '期间', '摘要', '借方', '贷方', '方向', '余额'];

                MaatExcel::Export_Ledger($cellData, "xls");
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * 对总账表客户添加的科目进行追加
     * @param $param
     * @return bool
     * @throws \Exception
     */
    public static function Add_Ledger_Member_KM_Rows($param)
    {
        $belong_time = Salary::Get_Belong_Time();
        $start = Salary::Get_Belong_Time_FirstDay($belong_time);
        $end = Salary::Get_Belong_Time_LastDay($belong_time);

        $result = false;
        foreach ($param as $key => $v) {
            $info = LedgerModel::query()->where('account_subject_id', $v)->whereBetween('fiscal_period', [$start, $end])->first();
            if (!$info) {
                // 如果账表没有进行新增
                $as_info = AccountSubject::query()->where('id', $v)->first();
                $company_id = $as_info->company_id;
                $km_id = $v;
                $km_number = $as_info->number;
                $km_name = $as_info->name;
                $km_direction = $as_info->balance_direction;
                self::Initialize_Ledger_Rows($company_id, $km_id, $km_number, $km_name, $km_direction);
                //\Log::info("追加客户科目_".$v);
            }

            $result = true;
        }
        return $result;
    }

    /**
     * 检查并处理 初始化总账表
     * @return bool
     * @throws \Exception
     */
    public static function Initialize_Ledger_Period()
    {
        new Company();
        $company_id = Company::$company->id;

        $belong_time = Salary::Get_Belong_Time();
        $start = Salary::Get_Belong_Time_FirstDay($belong_time);
        $end = Salary::Get_Belong_Time_LastDay($belong_time);

        $list = LedgerModel::query()->where('company_id', $company_id)->whereBetween('fiscal_period', [$start, $end])->get();
        if (count($list) > 0) {
            return false;
        } else {
            $result = self::Initialize_Ledger($company_id);
            return $result;
        }
    }

    /**
     * 初始化总账表
     * @param $company_id
     * @return bool
     * @throws \Exception
     */
    public static function Initialize_Ledger($company_id)
    {
        //new Company();
        //$company_id = Company::$company->id;

        $km_list = AccountSubject::query()->where('company_id', $company_id)->where('status', 1)->get();
        foreach ($km_list as $key => $v) {
            self::Initialize_Ledger_Rows($company_id, $v->id, $v->number, $v->name, $v->balance_direction);
        }

        return true;
    }

    /**
     * 总账表 插入行信息
     * @param $company_id
     * @param $km_id
     * @param $km_number
     * @param $km_name
     * @param $km_direction
     * @throws \Exception
     */
    public static function Initialize_Ledger_Rows($company_id, $km_id, $km_number, $km_name, $km_direction)
    {
        $belong_time = Salary::Get_Belong_Time();
        $fiscal_period = Salary::Get_Belong_Time_FirstDay($belong_time);

        $ledger = new Ledger();
        $ledger->company_id = $company_id;
        $ledger->account_subject_id = $km_id;
        $ledger->account_subject_number = $km_number;
        $ledger->account_subject_name = $km_name;
        $ledger->balance_direction = $km_direction;
        $ledger->qcye = '0.00';
        $ledger->qcye_j = '0.00';
        $ledger->qcye_d = '0.00';
        $ledger->bqhj = '0.00';
        $ledger->bqhj_j = '0.00';
        $ledger->bqhj_d = '0.00';
        $ledger->bnlj = '0.00';
        $ledger->bnlj_j = '0.00';
        $ledger->bnlj_d = '0.00';
        $ledger->fiscal_period = $fiscal_period;

        $return = $ledger->save();
        if (!$return) {
            throw new \Exception("初始化总账表失败！");
        }
    }

    /**
     * 追加用户新增会计科目至当期总账表
     * @param $km_number
     * @return bool
     * @throws \Exception
     */
    public static function Check_Add_User_KmToLedger($km_number)
    {
        $belong_time = Salary::Get_Belong_Time();
        $fiscal_period = Salary::Get_Belong_Time_FirstDay($belong_time);

        $company = Company::sessionCompany();
        $company_id = $company->id;

        // 检查总账表当期有没有些科目 没有就新增
        $row_info = LedgerModel::query()->where('company_id', $company_id)->where('account_subject_number', $km_number)->where('fiscal_period', $fiscal_period)->first();
        if ($row_info) {
            // 已存在
            return false;
        } else {
            // 没有 需新增
            $as_row_info = AccountSubject::query()->where('number', $km_number)->where('company_id', $company_id)->first();
            if ($as_row_info) {
                $km_id = $as_row_info->id;
                $km_number = $as_row_info->number;
                $km_name = $as_row_info->name;
                $km_direction = $as_row_info->balance_direction;

                self::Initialize_Ledger_Rows($company_id, $km_id, $km_number, $km_name, $km_direction);
            }

            return true;
        }
    }

    /**
     * 总账包含科目（去重后数据）
     * @param $param
     * @return array|mixed
     */
    public static function Get_Ledger_KM_Options($param)
    {
        $list = array();
        foreach ($param as $key => $v) {
            $list[$key]['name'] = $v['name'];
            $list[$key]['num'] = $v['code'];
            $list[$key]['v_name'] = $v['code'] . ' ' . $v['name'];
        }

        $list = self::Second_Array_Unique_By_Key($list, 'name');
        return $list;
    }

    /**
     * 二维数组去重处理
     * @param $arr
     * @param $key
     * @return mixed
     */
    public static function Second_Array_Unique_By_Key($arr, $key)
    {
        $tmp_arr = array();
        foreach ($arr as $k => $v) {
            if (in_array($v[$key], $tmp_arr)) {
                unset($arr[$k]);
            } else {
                $tmp_arr[$k] = $v[$key];
            }
        }

        return $arr;
    }

    // 预处理打印总账数据
    public static function Print_Ledger($param)
    {
        $company = Company::sessionCompany();
        $company_id = $company->id;

        $start_to = SubLedgerModel::Change_To_Belong_Time_Type($param->start);
        $end_to = SubLedgerModel::Change_To_Belong_Time_Type($param->end);

        $begin = Salary::Get_Belong_Time_FirstDay($start_to);
        $end = Salary::Get_Belong_Time_LastDay($end_to);
        $belong_time_arr = array($begin, $end);

        if ($param->start != $param->end) {
            $period = $param->start . ' - ' . $param->end;
        } else {
            $period = $param->start;
        }

        $list['status'] = 'err';
        $list['company_name'] = $company->company_name;
        $list['period'] = $period;
        $list['items'] = '';

        if ($param->ids == 'all' && $company_id > 0) {
            $obj_list = LedgerModel::query()->where('company_id', $company_id)->whereBetween('fiscal_period', $belong_time_arr)->orderBy('fiscal_period', 'ASC')->orderBy('account_subject_number', 'ASC')->get();
            $num = count($obj_list);
            if ($num > 0) {
                foreach ($obj_list as $key => $v) {
                    if ($v->qcye_j == "0.00" && $v->qcye_d == "0.00" && $v->bqhj_j == "0.00" && $v->bqhj_d == "0.00" && $v->bnlj_j == "0.00" && $v->bnlj_d == "0.00") {
                        // 无业务数据行
                        unset($obj_list[$key]);
                    }
                }


                /*$list_items = $obj_list->toArray();
                $list['status'] = 'success';
                $list['items'] = $list_items;*/

                $group_items = $obj_list->chunk(10)->toArray();
                $list_items = array();
                $i = 1;
                $num = count($group_items);
                foreach ($group_items as $key => $v) {
                    $list_items[$key]['item'] = $v;
                    $num_i = $i++;
                    $list_items[$key]['page'] = '第' . $num_i . '页/共' . $num . '页';
                }

                $list['status'] = 'success';
                $list['items'] = $list_items;
            }
        }

        return $list;
    }

    /**
     * 在编辑科目时 更新总账科目名称 借贷方向
     * $as_id 会计科目表ID
     * $period 当前账期
     * @param $as_id
     * @param $period
     * @return bool|int
     */
    public static function EditLedgerKmName($as_id, $period)
    {
        $company = Company::sessionCompany();
        $company_id = $company->id;

        $as_info = AccountSubject::query()->where('id', $as_id)->first();
        if ($as_info) {
            $balance_direction = $as_info->balance_direction;
            $name = $as_info->name;

            $data = array('account_subject_name' => $name, 'balance_direction' => $balance_direction);
            $result = LedgerModel::query()->where('company_id', $company_id)->where('account_subject_id', $as_id)->where('fiscal_period', $period)->update($data);
            return $result;
        } else {
            return false;
        }
    }

    /**
     * 查询当前数组 会计科目的父级+ 并组成新的数组
     * @param $arr
     * @return mixed
     */
    public static function GetNowArrParentArr($arr)
    {
        $new_arr = array();
        foreach ($arr as $key => $v) {
            $new_arr[] = self::GetNowKmParentIds($v);
        }

        $new_arr = array_unique($new_arr);
        $new_arr = implode(",", $new_arr);
        $new_arr = explode(",",$new_arr);
        $new_arr = array_unique($new_arr);

        // 合并
        $last_arr = array_merge($arr,$new_arr);
        foreach($last_arr as $k=>$v){
            if( !$v )
                unset( $last_arr[$k] );
        }

        return $last_arr;
    }

    /**
     * 取当前科目ID的父级ID
     * @param $id
     * @return array
     */
    public static function GetNowKmParentIds($id)
    {
        $info = AccountSubject::query()->where('id',$id)->first();
        if($info){
            $pid = $info->pid;
            if($pid > 0){
                // 暂用  可优化  start
                //父1级
                $result[] = $pid;
                $one_pid = self::GetNowKmParentIds_C($pid);
                if($one_pid > 0 ){
                    //父2级
                    $result[] = $one_pid;
                    $two_pid = self::GetNowKmParentIds_C($one_pid);
                    if($two_pid > 0){
                        //父3级
                        $result[] = $two_pid;
                        $three_pid = self::GetNowKmParentIds_C($two_pid);
                        if($three_pid > 0){
                            //父4级
                            $result[] = $three_pid;
                            $four_pid = self::GetNowKmParentIds_C($three_pid);
                            if($four_pid > 0){
                                //父5级
                                $result[] = $four_pid;
                                $five_pid = self::GetNowKmParentIds_C($four_pid);
                                if($five_pid > 0){
                                    //父6级
                                    $result[] = $five_pid;

                                    //6级应该够业务需求了
                                }
                            }
                        }
                    }
                }
                // 暂用  可优化  end
            }else{
                $result = '';
            }
        }else{
            $result = '';
        }

        if(!empty($result)){
            $result = implode(',', $result);
        }

        return $result;
    }

    /**
     * 取当前科目的父级科目ID
     * @param $id
     * @return mixed|string
     */
    public static function GetNowKmParentIds_C($id)
    {
        $info = AccountSubject::query()->where('id',$id)->first();
        if($info){
            $pid = $info->pid;
            if($pid > 0){
                $result = $pid;
            }else{
                $result = '';
            }
        }else{
            $result = '';
        }

        return $result;
    }
}
