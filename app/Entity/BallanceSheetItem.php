<?php

namespace App\Entity;

class BallanceSheetItem
{
    public $belong;
    public $name;
    public $row_num;
    public $begin_of_year;
    public $end_of_period;

    /**
     * BallanceSheetItem constructor.
     * @param $param
     * @throws \Exception
     */
    public function __construct($param)
    {
        if (!is_array($param))
            throw new \Exception('$param 为数组类型');

        if (empty($param['belong']) || empty($param['name']) || empty($param['row_num']))
            throw  new \Exception('缺少参数');

        $this->belong = $param['belong'];
        $this->name = $param['name'];
        $this->row_num = $param['row_num'];
        $this->begin_of_year = !empty($param['begin_of_year']) ? $param['begin_of_year'] : 0;
        $this->end_of_period = !empty($param['end_of_period']) ? $param['end_of_period'] : 0;
    }

    /**
     * 转化为数组
     * @return array
     */
    public function toArray()
    {
        return [
            $this->name,
            $this->row_num,
            $this->end_of_period,
            $this->begin_of_year,
        ];
    }

}