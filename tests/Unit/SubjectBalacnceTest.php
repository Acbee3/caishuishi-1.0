<?php

namespace Tests\Unit;

use App\Entity\SubjectBalance;
use Tests\TestCase;

class SubjectBalacnceTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testcheckExcelFormat()
    {
        $data1 = [
            [0 => '科目编码', 1 => '科目名称', 2 => '期初余额', 3 => null, 4 => '本年累计发生额', 5 => null,],
            [0 => null, 1 => null, 2 => '借方', 3 => '贷方', 4 => '借方', 5 => '贷方',],
        ];

        $data11 = [
            ['科目编码', '科目名称', '期初余额', null, '本年累计发生额', null,],
            [null, null, '借方', '贷方', '借方', '贷方',],
        ];

        $data2 = [
            0 => [0 => '科目编码', 1 => '科目名称', 2 => '期初余额', 3 => null, 4 => '本年累计发生额', 5 => null,],
            1 => [0 => null, 1 => null, 2 => '借方', 3 => '贷方', 4 => '借方', 5 => '贷方',],
        ];

        $data3 = [
            0 => [0 => '科目编码1', 1 => '科目名称', 2 => '期初余额', 3 => null, 4 => '本年累计发生额', 5 => null,],
            1 => [0 => null, 1 => null, 2 => '借方', 3 => '贷方', 4 => '借方', 5 => '贷方',],
        ];

        $data4 = [
            0 => [0 => '科目编码', 1 => '科目名称', 2 => '期初余额', 3 => null, 4 => '本年累计发生额', 5 => null,],
            1 => [0 => '', 1 => '', 2 => '借方', 3 => '贷方', 4 => '借方', 5 => '贷方',],
        ];

        $this->assertTrue(SubjectBalance::checkExcelFormat($data1));
        $this->assertTrue(SubjectBalance::checkExcelFormat($data11));
        $this->assertTrue(SubjectBalance::checkExcelFormat($data2));
        $this->assertFalse(SubjectBalance::checkExcelFormat($data3));
        $this->assertTrue(SubjectBalance::checkExcelFormat($data4));
    }
}
