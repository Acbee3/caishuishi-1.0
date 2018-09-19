<?php

namespace Tests\Unit;

use App\Models\Common;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class CommonTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCheckFile()
    {
        $file = UploadedFile::fake()->create('1.png', 10 * 1024);
        $this->assertFalse(Common::checkFile($file, 'xls'));
        $this->assertFalse(Common::checkFile($file, 'xlsx'));
        $this->assertFalse(Common::checkFile($file, ['xls', 'xlsx']));

        $file = UploadedFile::fake()->create('1.xls', 10 * 1024);
        $this->assertTrue(Common::checkFile($file, 'xls'));
        $this->assertFalse(Common::checkFile($file, 'xlsx'));
        $this->assertTrue(Common::checkFile($file, ['xls', 'xlsx']));

        $file = UploadedFile::fake()->create('1.xlsx', 10 * 1024);
        $this->assertFalse(Common::checkFile($file, 'xls'));
        $this->assertTrue(Common::checkFile($file, 'xlsx'));
        $this->assertTrue(Common::checkFile($file, ['xls', 'xlsx']));
    }
}
