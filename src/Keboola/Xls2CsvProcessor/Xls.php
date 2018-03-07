<?php

namespace Keboola\Xls2CsvProcessor;

use ErrorException;
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
     * @param string $sheetPath
     * @throws Exception\InvalidXlsFileException
     */
    public function __construct(string $sheetPath)
    {
        // PhpOffice\PhpSpreadsheet requires a timezone to be set
        date_default_timezone_set("UTC");

        try {
            $this->spreadsheet = PhpOffice\PhpSpreadsheet\IOFactory::load($sheetPath);
        } catch(PhpOffice\PhpSpreadsheet\Reader\Exception | ErrorException $e) {
            echo $sheetPath;

            echo $e->getTraceAsString();

            throw new Exception\InvalidXlsFileException(
                sprintf('Unable to process "%s" file: "%s"', $sheetPath, $e->getMessage()),
                0,
                $e
            );
        }
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
