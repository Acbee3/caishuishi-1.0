<?php

namespace Tests\Unit;

use App\Models\AccountSubject;
use Tests\TestCase;

class AccountSubjectTest extends TestCase
{

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testgetParentNumber()
    {
        $this->assertEquals('', AccountSubject::getParentNumber('2221'));
        $this->assertEquals('2221', AccountSubject::getParentNumber('222101'));
        $this->assertEquals('2221', AccountSubject::getParentNumber('222103'));
        $this->assertEquals('222101', AccountSubject::getParentNumber('22210101'));
        $this->assertEquals('222101', AccountSubject::getParentNumber('22210102'));
        $this->assertEquals('1122', AccountSubject::getParentNumber('112201'));
    }

    public function testgetDirection()
    {
        $this->assertEquals('借', AccountSubject::getDirection(1001));
        $this->assertEquals('贷', AccountSubject::getDirection(2221));
        $this->assertEquals('贷', AccountSubject::getDirection(222101));
        $this->assertEquals('贷', AccountSubject::getDirection(22210101));
        $this->assertEquals('贷', AccountSubject::getDirection(222199));
        $this->assertEquals('贷', AccountSubject::getDirection(22210199));
        $this->assertEquals('借', AccountSubject::getDirection(112201));
    }

    public function testgetType()
    {
        $this->assertEquals('资产类', AccountSubject::getType(1001));
        $this->assertEquals('负债类', AccountSubject::getType(2221));
        $this->assertEquals('负债类', AccountSubject::getType(222101));
        $this->assertEquals('负债类', AccountSubject::getType(22210101));
        $this->assertEquals('负债类', AccountSubject::getType(222199));
        $this->assertEquals('负债类', AccountSubject::getType(22210199));
        $this->assertEquals('资产类', AccountSubject::getType(112201));
    }

    public function testgetLevel()
    {
        $this->assertEquals(false, AccountSubject::getLevel(100000000000, '4,2,2'));
        $this->assertEquals(false, AccountSubject::getLevel('asdasdasdasdasdasd', '4,2,2'));
        $this->assertEquals(false, AccountSubject::getLevel(1001010101, '4,2,2'));
        $this->assertEquals(0, AccountSubject::getLevel(1001, '4,2,2'));
        $this->assertEquals(1, AccountSubject::getLevel(100101, '4,2,2'));
        $this->assertEquals(2, AccountSubject::getLevel(10010101, '4,2,2'));
        $this->assertEquals(3, AccountSubject::getLevel(1001010101, '4,2,2,2'));

        $this->assertEquals(1, AccountSubject::getLevel(10010001, '4,4,4'));
        $this->assertEquals(2, AccountSubject::getLevel(100100010001, '4,4,4'));
    }

    public function testfillNumber()
    {
        $this->assertEquals(false, AccountSubject::fillNumber('1001', '2'));
        $this->assertEquals('1001', AccountSubject::fillNumber('1001', '4'));
        $this->assertEquals('001001', AccountSubject::fillNumber('1001', '6'));
        $this->assertEquals('00001001', AccountSubject::fillNumber('1001', '8'));
    }

    /**
     * @throws \Exception
     */
    public function testconvertAccountNumber()
    {
        // 1001 => 1001
        $this->assertEquals('1001', AccountSubject::convertAccountNumber('1001', '4,2,2', '4,4,2'));
        $this->assertEquals('1001', AccountSubject::convertAccountNumber('1001', '4,2,2', '4,4,4'));
        $this->assertEquals('1001', AccountSubject::convertAccountNumber('1001', '4,2,2', '4,4,4,2'));

        $this->assertEquals('1001', AccountSubject::convertAccountNumber('1001', '4,4,2', '4,4,4'));
        $this->assertEquals('1001', AccountSubject::convertAccountNumber('1001', '4,4,2', '4,4,4,2'));

        $this->assertEquals('1001', AccountSubject::convertAccountNumber('1001', '4,4,4', '4,4,4,2'));
        $this->assertEquals('1001', AccountSubject::convertAccountNumber('1001', '4,4,2,2', '4,4,2,4'));
        $this->assertEquals('1001', AccountSubject::convertAccountNumber('1001', '4,4,2,2', '4,4,4,4'));
        $this->assertEquals('1001', AccountSubject::convertAccountNumber('1001', '4,4,4,2', '4,4,4,4'));


        // 100101 => 10010001
        $this->assertEquals('10010001', AccountSubject::convertAccountNumber('100101', '4,2,2', '4,4,2'));
        $this->assertEquals('10010001', AccountSubject::convertAccountNumber('100101', '4,2,2', '4,4,4'));
        $this->assertEquals('10010001', AccountSubject::convertAccountNumber('100101', '4,2,2', '4,4,4,2'));

        $this->assertEquals('10010001', AccountSubject::convertAccountNumber('100101', '4,4,2', '4,4,4'));
        $this->assertEquals('10010001', AccountSubject::convertAccountNumber('100101', '4,4,2', '4,4,4,2'));

        $this->assertEquals('10010001', AccountSubject::convertAccountNumber('100101', '4,4,4', '4,4,4,2'));

        $this->assertEquals('10010001', AccountSubject::convertAccountNumber('100101', '4,4,2,2', '4,4,2,4'));
        $this->assertEquals('10010001', AccountSubject::convertAccountNumber('100101', '4,4,2,2', '4,4,4,4'));
        $this->assertEquals('10010001', AccountSubject::convertAccountNumber('100101', '4,4,4,2', '4,4,4,4'));


        // 10010101 => 1001000101
        $this->assertEquals('1001000101', AccountSubject::convertAccountNumber('10010101', '4,2,2', '4,4,2'));
        $this->assertEquals('100100010001', AccountSubject::convertAccountNumber('10010101', '4,2,2', '4,4,4'));
        $this->assertEquals('100100010001', AccountSubject::convertAccountNumber('10010101', '4,2,2', '4,4,4,2'));
        $this->assertEquals('10010101', AccountSubject::convertAccountNumber('10010101', '4,4,2', '4,4,4'));
        $this->assertEquals('100100010001', AccountSubject::convertAccountNumber('1001000101', '4,4,2', '4,4,4,2'));

        $this->assertEquals('100100010001', AccountSubject::convertAccountNumber('100100010001', '4,4,4', '4,4,4,2'));
        $this->assertEquals('1001000100010001', AccountSubject::convertAccountNumber('1001010101', '4,2,2,2', '4,4,4,4'));
        $this->assertEquals('1001000100010001', AccountSubject::convertAccountNumber('100101000101', '4,2,4,2', '4,4,4,4'));
        $this->assertEquals('1001000100010001', AccountSubject::convertAccountNumber('10010100010001', '4,2,4,4', '4,4,4,4'));
        $this->assertEquals('1001000100010001', AccountSubject::convertAccountNumber('100100010101', '4,4,2,2', '4,4,4,4'));
        $this->assertEquals('1001000100010001', AccountSubject::convertAccountNumber('10010001000101', '4,4,4,2', '4,4,4,4'));

    }

    public function testgetLevelSetByExcelData()
    {
        $data = [];
        $this->assertEquals(false, AccountSubject::getLevelSetByExcelData($data));

        $data = ['1001', '100101', '10010101', '123123123123123123', '223123123', '1123123123123123'];
        $this->assertEquals(false, AccountSubject::getLevelSetByExcelData($data));

        $data = ['10', '100101', '10010101', '123123123123123123', '223123123', '1123123123123123'];
        $this->assertEquals(false, AccountSubject::getLevelSetByExcelData($data));

        $data = ['1001', '100101', '10010101'];
        $this->assertEquals('4,2,2', AccountSubject::getLevelSetByExcelData($data));

        $data = ['1001', '10010001', '100100010001'];
        $this->assertEquals('4,4,4', AccountSubject::getLevelSetByExcelData($data));

        $data = ['1001', '10010001', '1001000101'];
        $this->assertEquals('4,4,2', AccountSubject::getLevelSetByExcelData($data));
    }

}
