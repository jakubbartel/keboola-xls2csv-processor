<?php

namespace Keboola\Xls2CsvProcessor\Tests\Xls;

use Keboola\Csv\CsvFile;
use Keboola\Xls2CsvProcessor\Xls;
use PHPUnit\Framework\TestCase;

class XlsTest extends TestCase
{

    public function testConvertValidXls() : void
    {
        $xls = new Xls(__DIR__ . '/fixtures/valid.xls');

        $assertData = [];

        $csv = new CsvFile(__DIR__ . '/fixtures/valid.csv');
        foreach($csv as $row) {
            $assertData[] = $row;
        }

        $this->assertEquals($xls->toArray(0), $assertData);
    }

    public function testConvertValidXlsWithFormulas() : void
    {
        $xls = new Xls(__DIR__ . '/fixtures/valid_formulas.xlsx');

        $assertData = [];

        $csv = new CsvFile(__DIR__ . '/fixtures/valid_formulas_0.csv');
        foreach($csv as $row) {
            $assertData[] = $row;
        }

        $this->assertEquals($xls->toArray(0), $assertData);
    }

    public function testConvertValidXlsWithFormulasSecondSheet() : void
    {
        $xls = new Xls(__DIR__ . '/fixtures/valid_formulas.xlsx');

        $assertData = [];

        $csv = new CsvFile(__DIR__ . '/fixtures/valid_formulas_1.csv');
        foreach($csv as $row) {
            $assertData[] = $row;
        }

        $this->assertEquals($xls->toArray(1), $assertData);
    }

}
