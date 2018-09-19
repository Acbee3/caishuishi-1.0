<?php

namespace App\Entity;

use App\Models\Accounting\BalanceSheet as BalanceSheetModel;
use App\Models\Pinyin;


/**
 * 资产负债表
 * Class BalanceSheet
 * @package App\Entity
 */
class BalanceSheet
{

    public $company_id;
    public $fiscal_period;
    public $data_tpl = [
        '流动资产' => [
            '货币资金' => ['1001', '1002', '1012'],
            '短期投资' => ['1101'],
            '应收票据' => ['1121'],
            '应收账款' => ['1122'],
            '预付账款' => ['1123'],
            '应收股利' => ['1131'],
            '应收利息' => ['1132'],
            '其他应收款' => ['1221'],
            '存货' => [],
            '原材料' => ['1403', '1413'],
            '在产品' => ['4101', '4001', '4002'],
            '库存商品' => ['1405', '1407'],
            '周转材料' => [],
            '其他流动资产' => [],
            '流动资产合计' => [
                '1001', '1002', '1012', '1101', '1121', '1122', '1123', '1131', '1132', '1221',
                '1403', '1413', '4101', '4001', '4002', '1405', '1407',
            ],
        ],
        '非流动资产' => [
            '长期债券投资' => [],
            '长期股权投资' => ['1511'],
            '固定资产原价' => ['1601'],
            '减：累计折旧' => ['1602'],
            '固定资产账面价值' => ['1601', '-1602'],
            '在建工程' => ['1604'],
            '工程物资' => ['1605'],
            '固定资产清理' => ['1606'],
            '生产性生物资产' => [],
            '无形资产' => ['1701'],
            '开发支出' => [],
            '长期待摊费用' => ['1801'],
            '其他非流动资产' => ['1901'],
            '非流动资产合计' => [
                '1511', '1601', '-1602', '1604', '1605', '1606', '1701', '1801', '1901',
            ],
            '资产合计' => [
                '1001', '1002', '1012', '1101', '1121', '1122', '1123', '1131', '1132', '1221',
                '1403', '1413', '4101', '4001', '4002', '1405', '1407', '1511', '1601', '-1602',
                '1604', '1605', '1606', '1701', '1801', '1901',
            ],
        ],

        '流动负债' => [
            '短期借款' => ['2001'],
            '应付票据' => ['2201'],
            '应付账款' => ['2202'],
            '预收帐款' => ['2203'],
            '应付职工薪酬' => ['2211'],
            '应交税费' => ['2221'],
            '应付利息' => [],
            '应付利润' => [],
            '其他应付款' => ['2241'],
            '其他流动负债' => [],
            '流动负债合计' => [
                '2001', '2201', '2202', '2203', '2211', '2221', '2241',
            ],
        ],
        '非流动负债' => [
            '长期借款' => ['2501'],
            '长期应付款' => ['2701'],
            '递延收益' => [],
            '其他非流动负债' => [],
            '非流动负债合计' => [
                '2501', '2701',
            ],
            '负债合计' => [
                '2001', '2201', '2202', '2203', '2211', '2221', '2241', '2501', '2701',
            ],
        ],

        '所有者权益' => [
            '实收资本 （或股本）' => ['3001'],
            '资本公积' => ['3002'],
            '盈余公积' => ['3101'],
            '未分配利润' => ['3103', '3104'],
            '所有者权益合计' => [
                '3001', '3002', '3101', '3103', '3104',
            ],
            '负债和所有者权益合计' => [
                '2001', '2201', '2202', '2203', '2211', '2221', '2241', '2501', '2701',
                '3001', '3002', '3101', '3103', '3104',
            ],
        ],

    ];

    /**
     * BalanceSheet constructor.
     * @param array $attr
     * @throws \Exception
     */
    public function __construct(Array $attr)
    {

        if (empty($attr['company_id']) || empty($attr['fiscal_period']))
            throw new \Exception('缺少初始化参数');

        !empty($attr['company_id']) && $this->company_id = $attr['company_id'];
        !empty($attr['fiscal_period']) && $this->fiscal_period = $attr['fiscal_period'];
    }

    /**
     * 当前状态
     * @throws \Exception
     */
    public function active()
    {
        $ret = [];
        $row = 1;
        $balanceSheetModel = new BalanceSheetModel();

        //计算各个字段的数据
        //行数规整
        foreach ($this->data_tpl as $Key => $item) {

            if (!empty($item)) {
                foreach ($item as $k => $v) {

                    $field = Pinyin::utf8_to($k);
                    $balanceSheetModel->$field = SubjectBalance::getSum($v, $this->company_id, $this->fiscal_period);
                    $begin_of_year = SubjectBalance::getSumOfbeginOfYear($this->company_id, $v);
                    //$begin_of_year = '0';
                    //dd($v, $this->company_id, $this->fiscal_period);
                    $attr = [
                        'belong' => $Key,
                        'name' => $k,
                        'row_num' => $row,
                        'begin_of_year' => (string)$begin_of_year,
                        'end_of_period' => (string)$balanceSheetModel->$field,
                    ];

                    $ret[] = new BallanceSheetItem($attr);
                    $row++;
                }
            }
        }
        return $ret;
    }

    /**
     * 生成资产负债表 html table
     * @param $data
     * @throws \Exception
     */
    public static function makeTable($param)
    {
        $balance = (new BalanceSheet($param))->active();
        $table = [['资产', '行次', '期末数', '年初数']];
        $table[] = ['流动资产:', '', '', ''];

        for ($i = 0; $i < count($balance); $i++) {
            $table[] = $balance[$i]->toArray();
            if ($balance[$i]->name == '流动资产合计')
                $table[] = ['非流动资产:', '', '', ''];

            if ($balance[$i]->name == '资产合计') {
                $table[] = ['负债和所有者（或股东）权益', '行次', '期末数', '年初数'];
                $table[] = ['流动负债:', '', '', ''];
            }

            if ($balance[$i]->name == '流动负债合计')
                $table[] = ['非流动负债:', '', '', ''];

            if ($balance[$i]->name == '负债合计') {
                $table[] = ['所有者权益（或股东权益）:', '', '', ''];
            }

        }

        $table = self::fillBlankRow($table);
        //$table = self::filterArrayZeroToBlank($table);
        $table = self::cutTable($table);
        $tableHtml = self::convertArrayToTable($table);
        //dd($table);

        //dd(Company::sessionCompany());
        $ret = [
            'list' => $table,
            'html' => $tableHtml,
            'companyName' => Company::sessionCompany()->company_name,
            'period' => Period::currentPeriod(),
        ];
        //dd($table);

        return $ret;
    }

    /**
     * @param $table
     */
    private static function filterArrayZeroToBlank($table)
    {
        foreach ($table as &$item)
            foreach ($item as &$value)
                $value == '0' && $value = '';

        return $table;
    }

    /**
     * 将数组转化为html 表格
     * @param Array $arr 二维数组
     * @throws \Exception
     */
    private static function convertArrayToTable(Array $arr)
    {
        $str = '';
        foreach ($arr as $item) {
            $tr = '';
            if (is_array($item)) {
                foreach ($item as $value) {
                    is_numeric($value) && $value == 0 && $value = '';
                    $tr .= "<td>{$value}</td>";
                }
            }
            $str .= "<tr>{$tr}</tr>";
        }
        $str = "<table>{$str}</table>";
        return $str;
    }

    /**
     * 补充空白行
     * @param $table
     * @return array
     */
    private static function fillBlankRow($table)
    {
        $table = array_values($table);
        $rows = 0;
        $insert_flag = 0;
        foreach ($table as $key => $item) {
            if ($item[0] == '资产合计')
                $rows = $key;

            if ($item[0] == '负债合计')
                $insert_flag = $key + 1;
        }

        $count = count($table);

        $num = 2 * ($rows + 1) - $count;
//        dd($count);
//        dd($rows);
//        dd($num);
        while ($num-- > 0) {
            array_splice($table, $insert_flag, 0, [['', '', '', '']]);
        }

        //dd($table);
        return $table;
        //array_slice($table, $insert_flag, 0, '');
    }

    /**
     * 数组折叠
     * @param $table
     */
    private static function cutTable($table)
    {
        $count = count($table); //66
        $start = $count / 2; //33
        $i = 0;
        while ($i < $start) {
            $table[$i] = array_merge($table[$i], $table[$start + $i]);
            $i++;
        }

        foreach ($table as $key => $item) {
            if ($key >= $start)
                unset($table[$key]);
        }

        //dd($table);
        return $table;

    }

    /**
     * 固化表结构
     */
    public function save()
    {
    }

    public static function sum($model)
    {
        $model->liudongzichanheji = $model->huobizijin + $model->duanqitouzi + $model->yingshoupiaoju + $model->yingshouzhangkuan
            + $model->yufuzhangkuan + $model->yingshouguli + $model->yingshoulixi + $model->qitayingshoukuan + $model->cunhuo
            + $model->yuancailiao + $model->zaichanpin + $model->kucunshangpin + $model->zhouzhuancailiao + $model->qitaliudongzichan;
        $model->feiliudongzichanheji = $model->changqizhaiquantouzi + $model->changqiguquantouzi + $model->gudingzichanzhangmianjiazhi
            + $model->zaijiangongcheng + $model->gongchengwuzi + $model->gudingzichanqingli + $model->shengchanxingshengwuzichan
            + $model->wuxingzichan + $model->kaifazhichu + $model->changqidaitanfeiyong + $model->qitafeiliudongzichan;
        $model->liudongfuzhaiheji = '';
        $model->feiliudongfuzhaiheji = '';
        $model->suoyouzhequanyiheji = '';

        return $model;
    }

    /**
     * 处理合计字段
     * @return $this
     */
    public static function handleSumField($model)
    {
        $data = [
            '流动资产合计' => [
                '货币资金',
                '短期投资',
                '应收票据',
                '应收账款',
                '预付账款',
                '应收股利',
                '应收利息',
                '其他应收款',
                '存货',
                '原材料',
                '在产品',
                '库存商品',
                '周转材料',
                '其他流动资产',
            ],
            '非流动资产合计' => [
                '长期债券投资',
                '长期股权投资',
                '固定资产原价',
                '减：累计折旧',
                '固定资产账面价值',
                '在建工程',
                '工程物资',
                '固定资产清理',
                '生产性生物资产',
                '无形资产',
                '开发支出',
                '长期待摊费用',
                '其他非流动资产',
            ],
            '流动负债合计' => [
                '短期借款',
                '应付票据',
                '应付账款',
                '预收帐款',
                '应付职工薪酬',
                '应交税费',
                '应付利息',
                '应付利润',
                '其他应付款',
                '其他流动负债',
            ],
            '非流动负债合计' => [
                '长期借款',
                '长期应付款',
                '递延收益',
                '其他非流动负债',
            ],
            '所有者权益合计' => [
                '实收资本 （或股本）',
                '资本公积',
                '盈余公积',
                '未分配利润',
            ],

        ];
        foreach ($data as $key => $datum) {
            $sumField = Pinyin::utf8_to($key);
            if (is_array($datum)) {
                foreach ($datum as $item) {
                    $subField = Pinyin::utf8_to($item);
                    $model->$sumField += $model->$subField;
                }
            }
        }

        //非流动资产特殊处理
        $model->feiliudongzichanheji = $model->changqizhaiquantouzi + $model->changqiguquantouzi + $model->gudingzichanzhangmianjiazhi
            + $model->zaijiangongcheng + $model->gongchengwuzi + $model->gudingzichanqingli + $model->shengchanxingshengwuzichan
            + $model->wuxingzichan + $model->kaifazhichu + $model->changqidaitanfeiyong + $model->qitafeiliudongzichan;

        $model->zichanheji = $model->liudongzichanheji + $model->feiliudongzichanheji;
        $model->fuzhaiheji = $model->liudongfuzhaiheji + $model->feiliudongfuzhaiheji;
        //dd($model->fuzhaiheji, $model->suoyouzhequanyiheji);
        $model->fuzhaihesuoyouzhequanyiheji = $model->fuzhaiheji + $model->suoyouzhequanyiheji;

        return $model;
    }
}