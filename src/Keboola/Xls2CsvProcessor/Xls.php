<?php

namespace Keboola\Xls2CsvProcessor;

use PhpOffice;

class Xls
{

    /**
     * @var PhpOffice\PhpSpreadsheet\SpreadSheet
     */
    private $spreadsheet;

    public function __construct($sheetPath)
    {
        $this->spreadsheet = PhpOffice\PhpSpreadsheet\IOFactory::load($sheetPath);
    }

    /**
     * @param int $sheetIndex
     * @return array
     */
    public function toArray(int $sheetIndex): array
    {
        $this->spreadsheet->setActiveSheetIndex($sheetIndex);

        return $this->spreadsheet->getActiveSheet()->toArray();
    }

}
