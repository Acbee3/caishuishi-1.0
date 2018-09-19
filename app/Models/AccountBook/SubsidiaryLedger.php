<?php
/**
 * FileName: SubsidiaryLedger.php
 * Created by PhpStorm.
 * User: Administrator
 * DateTime: 2018/7/27-14:41
 * E_mail: newsboy9248@163.com
 */

namespace App\Models\AccountBook;

use App\Entity\Salary;
use App\Models\Accounting\Voucher;
use App\Models\Accounting\VoucherItem;
use App\Models\AccountSubject;
use Illuminate\Database\Eloquent\Model;
use App\Entity\Company;
use App\Models\Company as CompanyModel;
use App\Models\AccountBook\Ledger as LedgerModel;

/**
 * 明细账
 * Class SubsidiaryLedger
 * @package App\Models\AccountBook
 */
class SubsidiaryLedger extends Model
{
    /**
     * 页面加载 返回数据
     * @param $param
     * @return array
     * @throws \Exception
     */
    public static function Get_List($param)
    {
        new Company();
        $company_id = Company::$company->id;

        // 会计科目 普通列表
        $data['as'] = self::Get_Account_Subjects_List($company_id);

        // 会计科目 options列表
        //$data['options'] = self::Get_Account_Subjects_Options_List($company_id);

        // 会计科目 树形列表
        $data['tree'] = self::Get_Account_Subjects_Tree_List($company_id);

        // 当前会计期间
        $belong_time = Salary::Get_Belong_Time_Type_A();
        $data['belong_time'] = $belong_time;

        // 会计期间选择下拉数据
        $data['qj_options'] = self::Get_Month_Arr();

        // 会计科目初始列表数据
        $number = $param->km_code;//科目编码
        if (empty($number) || $number == null) {
            $number = 1001;// 1001 库存现金
        }
        $km_id = self::Get_Km_Id_By_Number($number);

        // 检查当前科目编码 当期总账表里是否已存在
        LedgerModel::Check_Add_User_KmToLedger($number);

        // 返回当前选中科目编码及名称字串
        $data['text'] = self::Get_Km_Str_By_Id($km_id);

        //$data['km'] = self::Get_Merge_AS_List($km_id, $belong_time, $belong_time);
        $data['km'] = array();

        $data['km_id'] = $km_id;

        $result = array('status' => true, 'msg' => 'loading', 'data' => $data);
        return $result;
    }

    /**
     * 会计科目 普通列表
     * @param $company_id
     * @return array|string
     */
    public static function Get_Account_Subjects_List($company_id)
    {
        $list = AccountSubject::query()->where('company_id', $company_id)->where('status', 1)->get();

        if (count($list) > 0) {
            $new_list = array();
            foreach ($list as $key => $v) {
                $new_list[$key]['id'] = $v->id;
                $new_list[$key]['name'] = $v->name;
                $new_list[$key]['number'] = $v->number;
            }
        } else {
            $new_list = '';
        }

        return $new_list;
    }

    /**
     * 会计科目 树形列表
     * @param $company_id
     * @return array|string
     */
    public static function Get_Account_Subjects_Tree_List($company_id)
    {
        // 取公司会计科目一级  pid:0
        $list = AccountSubject::query()->where('company_id', $company_id)->where('status', 1)->get();

        if (count($list) > 0) {
            $new_list = array();
            foreach ($list as $key => $v) {
                /*if (in_array($v->id, $voucher_items_arr)) {

                }*/

                $new_list[$key]['id'] = $v->id;
                $new_list[$key]['name'] = $v->number . ' ' . $v->name;
                $new_list[$key]['number'] = $v->number;
                $new_list[$key]['pId'] = $v->pid;
                $new_list[$key]['open'] = true;
                $new_list[$key]['vid'] = $v->id;
                if ($v->pid == 0) {
                    $new_list[$key]['isParent'] = true;
                }
            }
        } else {
            $new_list = '';
        }

        return $new_list;
    }

    /**
     * 构建科目下拉   解决返回纯json数组前台和layer下拉冲突的问题
     * @param $company_id
     * @return string
     */
    public static function Get_Account_Subjects_Options_List($company_id)
    {
        $list = AccountSubject::query()->where('company_id', $company_id)->where('status', 1)->get();
        $list_arr = AccountSubject::tree($list);
        if (count($list_arr) > 0) {
            $data_options = '<option value="">请选择</option>';
            foreach ($list_arr as $key => $v) {
                $id = $v['id'];
                $number = $v['number'];
                $name = $v['name'];
                $data_options .= '<option value="' . $id . '" >' . $number . ' ' . $name . '</option>';

            }
        } else {
            $data_options = '<option value="" >请选择</option>';
        }

        return $data_options;
    }

    /**
     * 获取某一个会计科目的明细账
     * @param $param
     * @return array
     */
    public static function Get_Account_Subjects_Info_list($param)
    {
        // 传入会计科目ID
        $km_id = $param->as_id;
        $km_id = str_replace("treeLeft_", "", $km_id);
        $km_id = str_replace("_span", "", $km_id);

        $start = $param->start;//2018年第06期
        $end = $param->end;//2018年第07期

        $data['items'] = self::Get_Merge_AS_List($km_id, $start, $end);
        $data['num'] = count($data['items']);

        // 取科目编码及名称 给前端页面
        //$as_info = AccountSubject::find($km_id);
        $as_info = AccountSubject::query()->where('id', $km_id)->first();
        $data['text'] = $as_info->number . ' ' . $as_info->name;

        if ($data['num'] > 0) {
            $result = array('status' => true, 'msg' => '...', 'data' => $data);
        } else {
            $result = array('status' => true, 'msg' => '当前会计科目下无数据！', 'data' => $data);
        }

        return $result;
    }

    /**
     * 获取 选中的会计科目 树形ID
     * @param $param
     * @return array
     */
    public static function Get_Select_Tree_Id($param)
    {
        // 传入会计科目 树形ID
        $km_id = $param->as_id;
        $km_id = str_replace("treeLeft_", "", $km_id);
        $km_id = str_replace("_span", "", $km_id);
        $km_id = str_replace("_ico", "", $km_id);
        $km_id = str_replace("_a", "", $km_id);

        // 将传入序号转为 取出对应的科目ID
        new Company();
        $company_id = Company::$company->id;
        $list = AccountSubject::query()->where('company_id', $company_id)->where('status', 1)->get();

        $num = $km_id - 1;
        $list_arr = AccountSubject::tree($list);
        $km_id = $list_arr[$num]['id'];

        //$km_id = $list[$num]['id'];

        if (is_numeric($km_id)) {
            $data['as_id'] = $km_id;
            $result = array('status' => true, 'msg' => '数据获取成功。', 'data' => $data);
        } else {
            $result = array('status' => false, 'msg' => '数据获取失败。', 'data' => '');
        }

        return $result;
    }

    /**
     * 通过科目编码反查 对应的科目ID
     * @param $number
     * @return mixed
     */
    public static function Get_Km_Id_By_Number($number)
    {
        new Company();
        $company_id = Company::$company->id;

        $km_id = AccountSubject::query()->where('company_id', $company_id)->where('number', $number)->where('status', 1)->value('id');
        return $km_id;
    }

    /**
     * 通过科目ID取科目编码及名称组合字串
     * @param $id
     * @return string
     */
    public static function Get_Km_Str_By_Id($id)
    {
        //$km_info = AccountSubject::find($id);
        $km_info = AccountSubject::query()->where('id', $id)->where('status', 1)->first();
        if ($km_info) {
            $str = $km_info->number . ' ' . $km_info->name;
        } else {
            $str = "数据异常！";
        }

        return $str;
    }

    /**
     * 获取 当前公司 的会计期间数组
     * @return array
     */
    public static function Get_Month_Arr()
    {
        new Company();
        $company_id = Company::$company->id;
        $created_at = CompanyModel::query()->where('id', $company_id)->value('created_at');

        $start = $created_at;
        $start = Salary::Get_Belong_Time_FirstDay($start);

        $end = now();
        $end_dd = date('Y-m-01', strtotime($end));

        $st = strtotime($start);
        $et = strtotime("$end_dd +1 month -1 day");

        // 记账期间早于系统生成公司时间
        $info = LedgerModel::query()->where('company_id', $company_id)->orderBy('fiscal_period', 'ASC')->first();
        if ($info) {
            $first_fiscal_period = $info->fiscal_period;
            $st_f = strtotime($first_fiscal_period);
            if ($st_f < $st) {
                $st = $st_f;
            }
        }

        $t = $st;
        $i = 0;
        $d = array();
        while ($t <= $et) {
            $d[$i]['label'] = trim(date('Y年第n期', $t), ' ');
            $d[$i]['value'] = trim(date('Y-m', $t), ' ');
            $t += strtotime('+1 month', $t) - $t;
            $i++;
        }

        return $d;
    }

    /**
     * 将文本式会计期间转换为系统标准会计期间时间 类型
     * 传入：2018年第06期
     * 目标：2018-06
     * @param $val
     * @return mixed
     */
    public static function Change_To_Belong_Time_Type($val)
    {
        $val = str_replace("年第", "-", $val);
        $val = str_replace("期", "", $val);
        return $val;
    }

    /**
     * 获取期间组合字串
     * @param $begin
     * @param $end
     * @return string
     */
    public static function Change_To_Belong_Time_QJ($begin, $end)
    {
        if ($begin == $end) {
            $val = $begin;
        } else {
            $val = $begin . '-' . $end;
        }
        return $val;
    }

    /**
     * 金额格式化  保留2位小数
     * @param $money
     * @return string
     */
    public static function Format_Money_Type($money)
    {
        if (is_numeric($money)) {
            $money = sprintf("%.2f", $money);
        } else {
            $money = '0.00';
        }
        return $money;
    }

    /**
     * 获取会计科目当前分类ID及子级孙级ID数组
     * @param $id
     * @return array|string
     */
    public static function Get_AS_Ids_Arr($id)
    {
        $list = AccountSubject::query()->where('pid', $id)->where('status', 1)->get();

        if (count($list) > 0) {
            $parent_list = $id;
            $child_list = '';
            $s_child_list = '';
            foreach ($list as $key => $v) {
                $child_list .= $v->id . ',';
                if (!empty(self::Get_AS_Child_Ids_Arr($v->id))) {
                    $s_child_list .= self::Get_AS_Child_Ids_Arr($v->id);
                }
            }
            $v_list = $parent_list . ',' . $child_list . ',' . $s_child_list;

            // 清除空数组
            $v_list = array_filter(explode(",", $v_list));
        } else {
            $v_list = array($id);
        }

        return $v_list;
    }

    /**
     * 获取孙级会计科目ID拼接字串
     * @param $id
     * @return string
     */
    public static function Get_AS_Child_Ids_Arr($id)
    {
        $list = AccountSubject::query()->where('pid', $id)->where('status', 1)->get();

        if (count($list) > 0) {
            $child_list = '';
            foreach ($list as $key => $v) {
                $child_list .= $v->id . ',';
            }
        } else {
            $child_list = '';
        }

        return $child_list;
    }

    /**
     * 组装拼接 某一个 科目的列表信息
     * @param $km_id
     * @param $start
     * @param $end
     * @return array
     */
    public static function Get_Merge_AS_List($km_id, $start, $end)
    {
        new Company();
        $company_id = Company::$company->id;

        $start = self::Change_To_Belong_Time_Type($start);//如： 2018-07
        $end = self::Change_To_Belong_Time_Type($end);//如： 2018-07
        $start = Salary::Get_Belong_Time_FirstDay($start);//如： 2018-07-01
        $end = Salary::Get_Belong_Time_LastDay($end);//如： 2018-07-31

        // 当前分类、子分类、孙分类
        $km_id_arr = self::Get_AS_Ids_Arr($km_id);

        // 取相关凭证表数据
        // ->whereBetween('fiscal_period',[$start, $end])// 起止时间需要转化处理再应用whereBetween
        $list = VoucherItem::query()->where('company_id', $company_id)->whereIn('kuaijikemu_id', $km_id_arr)->whereBetween('fiscal_period', [$start, $end])->orderBy('created_at', 'ASC')->get();

        if (count($list) > 0) {
            $items = array();
            foreach ($list as $key => $v) {
                $items[$key]['date'] = self::Get_Voucher_List_Date($v->created_at, $v->fiscal_period, $v->voucher_id);//$v->created_at  $v->fiscal_period
                $items[$key]['voucher_id'] = $v->voucher_id;
                $items[$key]['marks'] = '记-' . self::Get_Voucher_Code_By_Id($v->voucher_id);
                $items[$key]['zy'] = $v->zhaiyao;
                $items[$key]['debit'] = $v->debit_money;
                $items[$key]['credit'] = $v->credit_money;
                if ($v->debit_money > $v->credit_money) {
                    $direction = '借';
                } else {
                    $direction = '贷';
                }
                $items[$key]['direction'] = $direction;
                $items[$key]['ye'] = '0.00';
                $items[$key]['px'] = 'B';
            }
        } else {
            $items = array();
        }

        // 取科目余额表数据（关联 期初余额表 ledgers）
        $ledgers_items = self::Get_Ledgers_Arr($company_id, $km_id, $start, $end, $km_id_arr);

        // 合并数组
        $items = array_merge($items, $ledgers_items);

        // 组合排序 并 计算余额
        $items = self::Do_Auto_Order_JS_List($items, $km_id);

        // 最后一行本年累计  借方、贷方、余额汇总
        // 如果需要读取库里的汇总信息   注释以下8行内容
        /*$last_info = self::Do_Js_Last_Row_info($items);
        if($last_info['status']){
            $total_row_num = count($items);
            $last_row = $total_row_num-1;
            //$items[$last_row]['debit'] = self::Format_Money_Type($last_info['debit']);
            //$items[$last_row]['credit'] = self::Format_Money_Type($last_info['credit']);
            //$items[$last_row]['ye'] = self::Format_Money_Type($last_info['ye']);
            $items[$last_row]['ye'] = $items[$last_row-1]['ye'];
        }*/

        return $items;
    }

    /**
     * 获取期初相关信息
     * @param $company_id
     * @param $km_id
     * @param $start
     * @param $end
     * @param $km_id_arr
     * @return array
     */
    public static function Get_Ledgers_Arr($company_id, $km_id, $start, $end, $km_id_arr)
    {
        $ledgers = Ledger::query()->where('company_id', $company_id)->where('account_subject_id', $km_id)->whereBetween('fiscal_period', [$start, $end])->orderBy('fiscal_period', 'ASC')->get();
        if (count($ledgers) > 0) {
            $ledgers_items = array();
            foreach ($ledgers as $key => $v) {
                // 期初余额
                $ledgers_items[$key][0]['date'] = date('Y-m-01', strtotime($v->fiscal_period));
                $ledgers_items[$key][0]['voucher_id'] = '';
                $ledgers_items[$key][0]['marks'] = '';
                $ledgers_items[$key][0]['zy'] = '期初余额';
                $ledgers_items[$key][0]['debit'] = $v->qcye_j;
                $ledgers_items[$key][0]['credit'] = $v->qcye_d;
                $ledgers_items[$key][0]['direction'] = $v->balance_direction;
                $ledgers_items[$key][0]['ye'] = $v->qcye;
                $ledgers_items[$key][0]['px'] = 'A';

                // 本期合计
                $ledgers_items[$key][1]['date'] = date('Y-m-t', strtotime($v->fiscal_period));
                $ledgers_items[$key][1]['voucher_id'] = '';
                $ledgers_items[$key][1]['marks'] = '';
                $ledgers_items[$key][1]['zy'] = '本期合计';
                $ledgers_items[$key][1]['debit'] = self::Js_BQHJ_Total($company_id, $v->fiscal_period, 'debit', $km_id_arr);
                $ledgers_items[$key][1]['credit'] = self::Js_BQHJ_Total($company_id, $v->fiscal_period, 'credit', $km_id_arr);
                $ledgers_items[$key][1]['direction'] = $v->balance_direction;
                $ledgers_items[$key][1]['ye'] = $v->bqhj;
                $ledgers_items[$key][1]['px'] = 'Y';

                // 本年累计
                $ledgers_items[$key][2]['date'] = date('Y-m-t', strtotime($v->fiscal_period));
                $ledgers_items[$key][2]['voucher_id'] = '';
                $ledgers_items[$key][2]['marks'] = '';
                $ledgers_items[$key][2]['zy'] = '本年累计';
                $ledgers_items[$key][2]['debit'] = self::Js_BNLJ_Total($company_id, $v->fiscal_period, 'debit', $km_id_arr);
                $ledgers_items[$key][2]['credit'] = self::Js_BNLJ_Total($company_id, $v->fiscal_period, 'credit', $km_id_arr);
                $ledgers_items[$key][2]['direction'] = $v->balance_direction;
                $ledgers_items[$key][2]['ye'] = $v->bnlj;//$v->bnlj
                $ledgers_items[$key][2]['px'] = 'Z';
            }

            $ledgers_items = self::Do_Ledgers_Merge_Arr($ledgers_items);
        } else {
            $ledgers_items = array();
        }

        return $ledgers_items;
    }

    /**
     * 合并期初相关数组
     * @param $arr
     * @return array
     */
    public static function Do_Ledgers_Merge_Arr($arr)
    {
        $result = [];
        array_map(function ($value) use (&$result) {
            $result = array_merge($result, array_values($value));
        }, $arr);

        return $result;
    }

    /**
     *  求和
     * @param $num_a
     * @param $num_b
     * @return float|int
     */
    public static function Do_Sum($num_a, $num_b)
    {
        if (is_numeric($num_a) && is_numeric($num_b)) {
            $num_arr = array($num_a, $num_b);
            $num = array_sum($num_arr);
        } else {
            $num = '??';
        }
        return $num;
    }

    /**
     * 求差   $num_a － $num_b
     * @param $num_a
     * @param $num_b
     * @return float|int|string
     */
    public static function Do_Difference($num_a, $num_b)
    {
        if (is_numeric($num_a) && is_numeric($num_b)) {
            $num_arr = array($num_a, -$num_b);
            $num = array_sum($num_arr);
        } else {
            $num = '??';
        }
        return $num;
    }

    /**
     * 计算本期合计
     * @param $arr
     * @return float|int
     */
    public static function Do_Js_Total_Period($arr)
    {
        $amount = array_sum($arr);
        return $amount;
    }

    /**
     * 判断输出累和 还是 单个数据
     * @param $px
     * @param $total
     * @param $unit
     * @return string
     */
    public static function Judge_Total_Unit($px, $total, $unit)
    {
        switch ($px) {
            case "A":
                // A:期初余额
                $amount = $unit;
                break;
            case "B":
                // B: 凭证数据
                $amount = $unit;
                break;
            case "Y":
                // Y:本期合计
                $amount = $total;
                //$amount = $unit;
                break;
            case "Z":
                // Z:本年累计
                $amount = $unit;
                break;
            default:
                $amount = '??';
                break;
        }

        $amount = self::Format_Money_Type($amount);

        return $amount;
    }

    /**
     * 计算本期合计
     * @param $company_id
     * @param $fiscal_period
     * @param $direction
     * @param $km_id_arr
     * @return mixed|string
     */
    public static function Js_BQHJ_Total($company_id, $fiscal_period, $direction, $km_id_arr)
    {
        $fiscal_period = mb_substr($fiscal_period, 0, 7, 'utf-8');
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

        if ($direction == 'debit') {
            // 借
            $amount = $sum_arr['total_debit'];
        } else if ($direction == 'credit') {
            // 贷
            $amount = $sum_arr['total_credit'];
        } else {
            // 其他 如  平
            $amount = '0.00';
        }
        return $amount;
    }

    /**
     * 计算本年累计
     * @param $company_id
     * @param $fiscal_period
     * @param $direction
     * @param $km_id_arr
     * @return mixed|string
     */
    public static function Js_BNLJ_Total($company_id, $fiscal_period, $direction, $km_id_arr)
    {
        $fiscal_period = mb_substr($fiscal_period, 0, 7, 'utf-8');
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

        if ($direction == 'debit') {
            // 借
            $amount = $sum_arr['total_debit'];
        } else if ($direction == 'credit') {
            // 贷
            $amount = $sum_arr['total_credit'];
        } else {
            // 其他 如  平
            $amount = '0.00';
        }
        return $amount;
    }

    /**
     * 对相关凭证表数据及科目余额组装数据进行排序及相关计算处理
     * @param $arr
     * @param $km_id
     * @return array
     */
    public static function Do_Auto_Order_JS_List($arr, $km_id)
    {
        // 取科目借贷方向
        $km_fx = AccountSubject::query()->whereKey($km_id)->value('balance_direction');

        array_multisort(array_column($arr, 'date'), SORT_ASC, array_column($arr, 'px'), SORT_ASC, $arr);

        $total_debit = '0.00';
        $total_credit = '0.00';
        $total_ye = '0.00';
        $items = array();
        if (count($arr) > 0) {
            foreach ($arr as $key => $v) {
                $items[$key]['date'] = $v['date'];
                $items[$key]['voucher_id'] = $v['voucher_id'];
                $items[$key]['marks'] = $v['marks'];
                $items[$key]['zy'] = $v['zy'];
                $items[$key]['direction'] = $v['direction'];
                $items[$key]['px'] = $v['px'];

                // 累计金额
                if ($v['px'] == 'B') {
                    // 借方
                    $total_debit += $v['debit'];
                    // 贷方
                    $total_credit += $v['credit'];
                }

                // 借
                //$items[$key]['debit'] = self::Judge_Total_Unit($v['px'], $total_debit, $v['debit']);
                $items[$key]['debit'] = $v['debit'];

                // 贷
                //$items[$key]['credit'] = self::Judge_Total_Unit($v['px'], $total_credit, $v['credit']);
                $items[$key]['credit'] = $v['credit'];

                // 累加金额 用于后续计算处理
                if (($key == 0 && $v['px'] == 'A') || $v['px'] == 'B') {
                    $total_ye += $v['ye'];
                }

                $items[$key]['ye'] = self::Do_Js_MXZ_Yu_Er($v['debit'], $v['credit'], $v['direction'], $v['px'], $v['ye'], $total_ye, $total_debit, $total_credit, $km_fx);
            }
        }

        return $items;
    }

    /**
     * 计算明细账 余额
     * @param $debit //借方金额
     * @param $credit //贷方金额
     * @param $direction //借贷方向
     * @param $px //A:期初余额  B: 凭证数据  Y:本期合计  Z:本年累计
     * @param $ye //计算前余额
     * @param $total_ye
     * @param $total_debit
     * @param $total_credit
     * @param $km_fx
     * @return float|int
     */
    public static function Do_Js_MXZ_Yu_Er($debit, $credit, $direction, $px, $ye, $total_ye, $total_debit, $total_credit, $km_fx)
    {
        switch ($px) {
            case "A":
                // A:期初余额
                $amount = $ye;
                break;
            case "B":
                // B: 凭证数据   可优化下面内容
                if ($direction == "借" && $debit != '0.00' && $credit == "0.00") {
                    // 借  记借
                    if ($km_fx == "借") {
                        $num_arr = array($total_ye, $total_debit, -$total_credit);
                        $amount = array_sum($num_arr);
                    } else {
                        $num_arr = array($total_ye, -$total_debit, $total_credit);
                        $amount = array_sum($num_arr);
                    }
                } else if ($direction == "借" && $debit == '0.00' && $credit != "0.00") {
                    // 借  记贷
                    //$amount = self::Do_Difference($total_ye, $total_credit);
                    //$amount = self::Do_Sum($amount, $total_debit);
                    if ($km_fx == "借") {
                        $num_arr = array($total_ye, $total_debit, -$total_credit);
                        $amount = array_sum($num_arr);
                    } else {
                        $num_arr = array($total_ye, -$total_debit, $total_credit);
                        $amount = array_sum($num_arr);
                    }
                } else if ($direction == "贷" && $debit != '0.00' && $credit == "0.00") {
                    // 贷  记借
                    //$amount = self::Do_Difference($total_ye, $total_debit);
                    //$amount = self::Do_Sum($amount, $total_credit);
                    if ($km_fx == "借") {
                        $num_arr = array($total_ye, -$total_debit, $total_credit);
                        $amount = array_sum($num_arr);
                    } else {
                        $num_arr = array($total_ye, $total_debit, -$total_credit);
                        $amount = array_sum($num_arr);
                    }
                } else if ($direction == "贷" && $debit == '0.00' && $credit != "0.00") {
                    // 贷  记贷
                    if ($km_fx == "借") {
                        $num_arr = array($total_ye, $total_debit, -$total_credit);
                        $amount = array_sum($num_arr);
                    } else {
                        $num_arr = array($total_ye, -$total_debit, $total_credit);
                        $amount = array_sum($num_arr);
                    }
                } else {
                    $amount = "??";
                }
                break;
            case "Y":
                // Y:本期合计
                //$amount = $total_ye+$total_debit-$total_credit;
                if ($direction == "借") {
                    $amount_arr = array($debit, -$credit);//$total_ye,
                } elseif ($direction == "贷") {
                    $amount_arr = array(-$debit, $credit);//$total_ye,
                } else {
                    $amount_arr = array($total_ye, $total_debit, -$total_credit);
                }
                $amount = self::Do_Js_Total_Period($amount_arr);
                break;
            case "Z":
                // Z:本年累计
                //$amount = $ye;
                if ($direction == "借") {
                    $amount_arr = array($total_ye, $total_debit, -$total_credit);
                } elseif ($direction == "贷") {
                    $amount_arr = array($total_ye, -$total_debit, $total_credit);
                } else {
                    $amount_arr = array($total_ye, $total_debit, -$total_credit);
                }
                $amount = self::Do_Js_Total_Period($amount_arr);
                break;
            default:
                $amount = '??';
                break;
        }

        $amount = self::Format_Money_Type($amount);

        return $amount;
    }

    /**
     * 计算最后一行相关汇总数据
     * @param $arr
     * @return array
     */
    public static function Do_Js_Last_Row_info($arr)
    {
        $new_arr = array('status' => false, 'debit' => '0.00', 'credit' => '0.00', 'ye' => '0.00');
        foreach ($arr as $key => $v) {
            if ($v['px'] == 'Y') {
                $new_arr['status'] = true;
                $new_arr['debit'] += $v['debit'];
                $new_arr['credit'] += $v['credit'];
                //$new_arr['ye'] = '??';
            } else {
                $new_arr['ye'] = $v['ye'];
            }
        }

        return $new_arr;
    }

    /**
     * 通过凭证序号ID取凭证编号
     * @param $id
     * @return mixed
     */
    public static function Get_Voucher_Code_By_Id($id)
    {
        $voucher_num = Voucher::find($id)->voucher_num;
        return $voucher_num;
    }

    /**
     * 列表凭证日期
     * @param $created_time
     * @param $period_time
     * @param $voucher_id
     * @return false|string
     */
    public static function Get_Voucher_List_Date($created_time, $period_time, $voucher_id)
    {
        $voucher_info = Voucher::query()->where('id', $voucher_id)->first();

        /*$date_created_time = date('Y-m-d', strtotime($created_time));
        $date_period_time = date('Y-m-d', strtotime($period_time));
        if ($date_created_time == $date_period_time) {
            $date_time_a = date('Y-m-d', strtotime($created_time));
        } else {
            $date_d = date('d', strtotime($created_time));
            $date_time_a = date('Y-m-' . $date_d, strtotime($period_time));
        }

        if ($voucher_info) {
            $date_time_b = $voucher_info->voucher_date;
            if($date_time_a > $date_time_b){
                $date_time = $date_time_b;
            }else{
                $date_time = $date_time_a;
            }
        } else {
            $date_time = '';
        }*/


        if ($voucher_info) {
            $date_time = $voucher_info->voucher_date;
        } else {
            $date_created_time = date('Y-m-d', strtotime($created_time));
            $date_period_time = date('Y-m-d', strtotime($period_time));
            if ($date_created_time == $date_period_time) {
                $date_time = date('Y-m-d', strtotime($created_time));
            } else {
                $date_d = date('d', strtotime($created_time));
                $date_time = date('Y-m-' . $date_d, strtotime($period_time));
            }
        }

        return $date_time;
    }

    /**
     * 打印 当前选择会计科目 的明细账(单个打印)
     * @param $param
     * @return mixed
     */
    public static function Print_OneKm_SubLedger($param)
    {
        // 公司信息
        $company = Company::sessionCompany();

        // 所选明细账的期间
        if ($param->start != $param->end) {
            $period = $param->start . ' - ' . $param->end;
        } else {
            $period = $param->start;
        }

        $km_id = $param->id;

        $km_info = AccountSubject::query()->where('id', $km_id)->first();
        if ($km_info) {
            $km_name = $km_info->number . ' ' . $km_info->name;
            $items = self::Get_Merge_AS_List($km_id, $param->start, $param->end);
            $status = 'success';
        } else {
            $km_name = '';
            $items = '';
            $status = 'err';
        }

        $list['status'] = $status;
        $list['company_name'] = $company->company_name;
        $list['period'] = $period;
        $list['km_name'] = $km_name;
        $list['items'] = $items;

        return $list;
    }

    /**
     * 连续打印 明细账
     * @param $param
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public static function Print_AllKm_SubLedger($param)
    {
        // 公司信息
        $company = Company::sessionCompany();
        $company_id = $company->id;

        $start = self::Change_To_Belong_Time_Type($param->start);//如： 2018-07
        $end = self::Change_To_Belong_Time_Type($param->end);//如： 2018-07
        $start = Salary::Get_Belong_Time_FirstDay($start);//如： 2018-07-01
        $end = Salary::Get_Belong_Time_LastDay($end);//如： 2018-07-31

        // 所选明细账的期间
        if ($param->start != $param->end) {
            $period = $param->start . ' - ' . $param->end;
        } else {
            $period = $param->start;
        }

        // 取发生业务的会计科目
        $voucher_items_list = VoucherItem::query()->where('company_id', $company_id)->whereBetween('fiscal_period', [$start, $end])->get();
        if (count($voucher_items_list) > 0) {
            $items = array();
            $voucher_items = array();
            foreach ($voucher_items_list as $key => $v) {
                $voucher_items[] = $v->kuaijikemu_id;
            }

            $voucher_items_arr = array_unique($voucher_items);

            $list = LedgerModel::query()->where('company_id', $company_id)->whereBetween('fiscal_period', [$start, $end])->orderBy('fiscal_period', 'ASC')->orderBy('account_subject_number', 'ASC')->get();
            if (count($list) > 0){
                foreach ($list as $key => $v) {
                    // 取有凭证的组装数据
                    if (in_array($v->account_subject_id, $voucher_items_arr)) {
                        $items[$key]['items'] = self::Get_Merge_AS_List($v->account_subject_id, $param->start, $param->end);
                        $items[$key]['km_name'] = AccountSubject::getAccountSubjectStrById($v->account_subject_id);
                    }
                }
            }

            $status = 'success';
        }else{
            $items = '';
            $status = 'err';
        }

        $list['status'] = $status;
        $list['company_name'] = $company->company_name;
        $list['period'] = $period;
        $list['items'] = $items;

        return $list;

    }
}