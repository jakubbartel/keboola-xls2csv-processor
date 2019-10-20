<?php

namespace Keboola\Xls2CsvProcessor\Tests\Xls;

use Keboola\Xls2CsvProcessor\Xls;
use PHPUnit\Framework\TestCase;

class XlsTest extends TestCase
{

    public function testExport() : void
    {
        $xls = new Xls(__DIR__.'/fixtures/valid.xls');

        $data = $xls->toArray(0);

        $this->assertGreaterThan(0, count($data), 'Empty data output');
    }

    public function testVariableColumnsNum() : void
    {
        $xls = new Xls(__DIR__.'/fixtures/variable_columns_num.xls');

        $arr = $xls->toArray(0);

        foreach($arr as $row) {
            $this->assertEquals(3, count($row));
        }
    }

}
