<?php

namespace Keboola\Xls2CsvProcessor\Tests\Xls;

use Keboola\Xls2CsvProcessor\Utils;
use PHPUnit\Framework\TestCase;

class UtilsTest extends TestCase
{

    public function testAddSome() : void
    {
        $arr = [
            ["aaa", "bbb"],
            ["ccc", "ddd", "eee", "fff"],
            ["ggg"],
        ];

        $out = Utils::enforceColumnsNum($arr, 4);

        $assert = [
            ["aaa", "bbb", "", ""],
            ["ccc", "ddd", "eee", "fff"],
            ["ggg", "", "", ""],
        ];

        $this->assertEquals($assert, $out);
    }

    public function testNoChange() : void
    {
        $arr = [
            ["aaa", "bbb"],
            ["ccc", "ddd"],
        ];

        $out = Utils::enforceColumnsNum($arr, 2);

        $this->assertEquals($arr, $out);
    }

    public function testEmptyRows() : void
    {
        $arr = [
            [],
            []
        ];

        $out = Utils::enforceColumnsNum($arr, 0);

        $this->assertEquals($arr, $out);
    }

}
