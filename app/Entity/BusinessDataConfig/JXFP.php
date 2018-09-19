<?php

namespace App\Entity\BusinessDataConfig;


use App\Entity\Company;
use App\Models\AccountSubject;

class JXFP
{
    const TYPE_1 = 1; //一般科目
    const TYPE_2 = 2; //成本费用
    const TYPE_3 = 3; //资产

    public $data = [
        ["type" => JXFP::TYPE_1, "number" => "1403", "name" => "原材料", "full_name" => "原材料", 'child' => []],
        ["type" => JXFP::TYPE_1, "number" => "1405", "name" => "库存商品", "full_name" => "库存商品", 'child' => []],
        ["type" => JXFP::TYPE_1, "number" => "1408", "name" => "委托加工物资", "full_name" => "委托加工物资", 'child' => []],
        ["type" => JXFP::TYPE_1, "number" => "1411", "name" => "周转材料", "full_name" => "周转材料", 'child' => []],
        ["type" => JXFP::TYPE_1, "number" => "1421", "name" => "消耗性生物资产", "full_name" => "消耗性生物资产", 'child' => []],
        ["type" => JXFP::TYPE_2, "number" => "", "name" => "成本/费用", "full_name" => "成本/费用", 'child' => []],
        ["type" => JXFP::TYPE_3, "number" => "1601", "name" => "购固定资产", "full_name" => "购固定资产", 'child' => []],
        ["type" => JXFP::TYPE_3, "number" => "1701", "name" => "购无形资产", "full_name" => "购无形资产", 'child' => []],
        ["type" => JXFP::TYPE_3, "number" => "1801", "name" => "待摊费用", "full_name" => "待摊费用", 'child' => []],
        ["type" => JXFP::TYPE_3, "number" => "1801", "name" => "长期待摊费用", "full_name" => "长期待摊费用", 'child' => []],
    ];

    //成本费用类数据
    public $fyData = [
        ["number" => "4001", "name" => "生产成本", "full_name" => "生产成本"],
        ["number" => "4002", "name" => "劳务成本", "full_name" => "劳务成本"],
        ["number" => "4101", "name" => "制造费用", "full_name" => "制造费用"],
        ["number" => "4301", "name" => "研发支出", "full_name" => "研发支出"],
        ["number" => "4401", "name" => "工程施工", "full_name" => "工程施工"],
        ["number" => "4403", "name" => "机械作业", "full_name" => "机械作业"],
        ["number" => "5401", "name" => "主营业务成本", "full_name" => "主营业务成本"],
        ["number" => "5402", "name" => "其他业务成本", "full_name" => "其他业务成本"],
        ["number" => "5601", "name" => "销售费用", "full_name" => "销售费用"],
        ["number" => "5602", "name" => "管理费用", "full_name" => "管理费用"],
        ["number" => "5603", "name" => "财务费用", "full_name" => "财务费用"],
        ["number" => "5711", "name" => "营业外支出", "full_name" => "营业外支出"],
    ];


    public function getBusinessData()
    {
        foreach ($this->data as $key => $arr) {
            $km = [];
            if ($arr['type'] == JXFP::TYPE_1) {
                $this->loopKM($arr, $km);
                unset($this->data[$key]);
            }

            if ($arr['type'] == JXFP::TYPE_2) {
                $fy = [];
                foreach ($this->fyData as $v) {
                    $this->loopFY($v, $fy, true);
                }
                $arr['child'] = $fy;
                unset($this->data[$key]);
                array_push($this->data, $arr);
            }

            if ($arr['type'] == JXFP::TYPE_3) {
                unset($this->data[$key]);
                array_push($this->data, $arr);
            }
            unset($this->data[$key]['full_name']);
        }

        $this->handleAsset($this->data);
        return array_values($this->data);
    }


    /**
     * 递归查询 科目分类
     * @param $arr
     * @param $kmAll
     */
    public function loopKM($arr, &$kmAll, $bm = false)
    {

        $company = Company::sessionCompany();
        $km = AccountSubject::where('number', $arr["number"])->where("company_id", $company->id)->first();
        !empty($km) && $km = $km->toArray();

        if ($km) {
            $kmList = AccountSubject::where('pid', $km['id'])->where("company_id", $company->id)->get();
            !empty($kmList) && $kmList = $kmList->toArray();

            if ($kmList) {
                foreach ($kmList as $v) {
                    $v['full_name'] = $arr['full_name'] . '_' . $v['name'];
                    $this->loopKM($v, $kmAll, $bm);
                }
            } else {
                $full_name = $bm ? $arr["number"] . "_" . $arr['full_name'] : $arr['full_name'];
                array_push($this->data, ["type" => JXFP::TYPE_1, "number" => $arr["number"], "name" => $full_name]);
            }
        }
    }

    /**
     * 递归查询 费用科目分类
     * @param $arr
     * @param $kmAll
     */
    public function loopFY($arr, &$kmAll, $bm = false)
    {

        $company = Company::sessionCompany();
        $km = AccountSubject::where('number', $arr["number"])->where("company_id", $company->id)->first();
        !empty($km) && $km = $km->toArray();

        if ($km) {
            $kmList = AccountSubject::where('pid', $km['id'])->where("company_id", $company->id)->get();
            !empty($kmList) && $kmList = $kmList->toArray();

            if ($kmList) {
                foreach ($kmList as $v) {
                    $v['full_name'] = $arr['full_name'] . '_' . $v['name'];
                    $this->loopFY($v, $kmAll, $bm);
                }
            } else {
                $full_name = $bm ? $arr["number"] . "_" . $arr['full_name'] : $arr['full_name'];

                array_push($kmAll, ["type" => JXFP::TYPE_1, "id" => $km['id'], "number" => $arr["number"], "name" => $full_name, "full_name" => $arr['full_name']]);
            }
        }
    }

    /**
     * 获取固定资产子选项
     * @param $data
     * @return void
     */
    public function handleAsset(&$data, $company_id = '')
    {
        $company_id == '' && $company_id = Company::sessionCompany()->id;
        foreach ($data as &$datum) {
            if ($datum['number'] == '1601' && $datum['type'] == JXFP::TYPE_3) {
                $assetList = \App\Models\Accounting\Asset::query()
                    ->where('company_id', $company_id)
                    ->get();
                foreach ($assetList as $item) {
                    $datum['child'][] = [
                        'number' => '',
                        'asset_id' => $item['id'],
                        'type' => JXFP::TYPE_3,
                        'name' => $item['zcmc'],
                        'full_name' => $item['zcmc'],
                        'child' => [],
                    ];
                }

            }
        }
    }

}
