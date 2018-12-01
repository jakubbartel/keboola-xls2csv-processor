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
     * @throws Exception\InvalidSheetIndexException
     * @throws Exception\InvalidSheetException
     */
    public function toArray(int $sheetIndex): array
    {
        try {
            $this->spreadsheet->setActiveSheetIndex($sheetIndex);
        } catch(PhpOffice\PhpSpreadsheet\Exception $e) {
            throw new Exception\InvalidSheetIndexException(sprintf('Cannot set active sheet to %d', $sheetIndex));
        }

        try {
            $arr = $this->spreadsheet->getActiveSheet()->toArray();
        } catch(PhpOffice\PhpSpreadsheet\Exception $e) {
            throw new Exception\InvalidSheetException(sprintf('Cannot parse data array from sheet %d', $sheetIndex));
        }

        return $arr;
    }

}
