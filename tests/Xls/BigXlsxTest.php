<?php

namespace Keboola\Xls2CsvProcessor\Tests\Xls;

use Box\Spout;
use Keboola\Csv\CsvFile;
use Keboola\Xls2CsvProcessor\Xlsx;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

class BigXlsxTest extends TestCase
{

    private function generateRandomXls(int $numRows, int $numCols, string $filePath): void {
        $writer = Spout\Writer\WriterFactory::create(Spout\Common\Type::XLSX);

        $writer->openToFile($filePath); // write data to a file or to a PHP stream

        for($i = 0; $i < $numRows; $i++) {
            $row = [];

            for($j = 0; $j < $numCols; $j++) {
                $row[] = str_repeat("a", rand(8,32));
            }

            $writer->addRow($row);
        }

        $writer->close();
    }

    public function testBigXls() : void
    {
//        //$fileSystem = vfsStream::setup();
//
          $numRows = 500000;
//        $numCols = 20;
//        //$filePath = $fileSystem->url() . '/big.xls'; // Spout stream wrapper does not support this
          $filePath = __DIR__ . '/fixtures/big.xls';

//        $this->generateRandomXls($numRows, $numCols, $filePath);

        $xls = new Xlsx($filePath);

        $data = $xls->toArray(0);

        $this->assertEquals(count($data), $numRows);
    }

}
