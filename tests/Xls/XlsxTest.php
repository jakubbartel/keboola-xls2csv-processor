<?php

namespace Keboola\Xls2CsvProcessor\Tests\Xls;

use Keboola\Csv\CsvFile;
use Keboola\Xls2CsvProcessor\Exception\InvalidSheetIndexException;
use Keboola\Xls2CsvProcessor\Exception\InvalidXlsFileException;
use Keboola\Xls2CsvProcessor\Xls;
use Keboola\Xls2CsvProcessor\Xlsx;
use PHPUnit\Framework\TestCase;

class XlsxTest extends TestCase
{

    public function testConvertValidXls() : void
    {
        $xls = new Xlsx(__DIR__.'/fixtures/valid.xlsx');

        $assertData = [];

        $csv = new CsvFile(__DIR__ . '/fixtures/valid.csv');
        foreach($csv as $row) {
            $assertData[] = $row;
        }

        $this->assertEquals($xls->toArray(0), $assertData);
    }

    public function testConvertValidXlsWithFormulas() : void
    {
        $xls = new Xlsx(__DIR__ . '/fixtures/valid_formulas.xlsx');

        $assertData = [];

        $csv = new CsvFile(__DIR__ . '/fixtures/valid_formulas_0.csv');
        foreach($csv as $row) {
            $assertData[] = $row;
        }

        $this->assertEquals($xls->toArray(0), $assertData);
    }

    public function testConvertValidXlsWithFormulasSecondSheet() : void
    {
        $xls = new Xlsx(__DIR__ . '/fixtures/valid_formulas.xlsx');

        $assertData = [];

        $csv = new CsvFile(__DIR__ . '/fixtures/valid_formulas_1.csv');
        foreach($csv as $row) {
            $assertData[] = $row;
        }

        $this->assertEquals($xls->toArray(1), $assertData);
    }

    public function testInvalidXls() : void
    {
        $this->expectException(InvalidXlsFileException::class);

        $xls = new Xlsx(__DIR__ . '/fixtures/hello.pdf');
    }

    public function testInvalidSheetIndex() : void
    {
        $xls = new Xlsx(__DIR__.'/fixtures/valid.xlsx');

        $this->expectException(InvalidSheetIndexException::class);

        $xls->toArray(666);
    }

    public function testFormatDates() : void
    {
        $xls = new Xlsx(__DIR__.'/fixtures/format_dates.xlsx');

        $data = $xls->toArray(0);

        $stringsOnly = array_reduce($data, function($rowCarry, $row) {
            return $rowCarry && array_reduce($row, function($carry, $item) {
                    return $carry && (is_string($item) || is_int($item) || is_float($item) || is_bool($item));
                }, true);
        }, true);

        $this->assertEquals(true, $stringsOnly, 'Data contains some items that are not strings');
    }

    public function testVariableColumnsNum() : void
    {
        $xls = new Xlsx(__DIR__.'/fixtures/variable_columns_num.xlsx');

        $arr = $xls->toArray(0);

        foreach($arr as $row) {
            $this->assertEquals(3, count($row));
        }
    }

}
