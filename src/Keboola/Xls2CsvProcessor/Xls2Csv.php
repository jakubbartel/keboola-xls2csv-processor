<?php

namespace Keboola\Xls2CsvProcessor;

use PhpOffice;

class Xls2Csv
{

    /**
     * @param string $inputPath
     * @param string $outputPath
     * @param int $sheetIndex
     * @throws Exception\InvalidInputFile
     */
    public static function convert(string $inputPath, string $outputPath, int $sheetIndex): void
    {
        $spreadsheet = PhpOffice\PhpSpreadsheet\IOFactory::load($inputPath);

        $writer = new PhpOffice\PhpSpreadsheet\Writer\Csv($spreadsheet);
        $writer->setDelimiter(',');
        $writer->setEnclosure('"');
        $writer->setSheetIndex($sheetIndex);

        if( ! file_exists(dirname($outputPath))) {
            mkdir(dirname($outputPath), 0777, true);
        }
        $writer->save($outputPath);
    }

}
