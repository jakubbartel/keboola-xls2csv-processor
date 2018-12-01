<?php

namespace Keboola\Xls2CsvProcessor;

use PhpOffice;

class Xls
{

    /**
     * @var PhpOffice\PhpSpreadsheet\Spreadsheet
     */
    private $spreadsheet;

    /**
     * Xls constructor.
     *
     * @param string $spreadsheetPath
     * @throws Exception\InvalidXlsFileException
     */
    public function __construct(string $spreadsheetPath)
    {
        // PhpOffice\PhpSpreadsheet requires a timezone to be set
        date_default_timezone_set("UTC");

        try {
            $this->spreadsheet = PhpOffice\PhpSpreadsheet\IOFactory::load($spreadsheetPath);
        } catch(PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
            throw new Exception\InvalidXlsFileException(
                sprintf('Unable to process "%s" file: "%s"', $spreadsheetPath, $e->getMessage()),
                0,
                $e
            );
        }
    }

    /**
     * @param int $sheetIndex
     * @return array
     * @throws PhpOffice\PhpSpreadsheet\Exception
     */
    public function toArray(int $sheetIndex): array
    {
        $this->spreadsheet->setActiveSheetIndex($sheetIndex);

        return $this->spreadsheet->getActiveSheet()->toArray();
    }

}
