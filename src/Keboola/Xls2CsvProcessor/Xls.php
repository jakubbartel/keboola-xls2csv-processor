<?php

namespace Keboola\Xls2CsvProcessor;

use PhpOffice\PhpSpreadsheet;
use Keboola\Xls2CsvProcessor\Exception;

class Xls
{

    /**
     * @var string
     */
    private $spreadsheetPath;

    /**
     * @var PhpSpreadsheet\Reader\Xls
     */
    private $spreadsheetReader;

    /**
     * @var string
     */
    private $sheetName;

    /**
     * @param string $spreadsheetPath
     */
    public function __construct(string $spreadsheetPath)
    {
        $this->spreadsheetPath = $spreadsheetPath;

        $this->spreadsheetReader = new PhpSpreadsheet\Reader\Xls();

        $this->spreadsheetReader->setReadDataOnly(true);
    }

    /**
     * @param int $sheetIndex
     * @return Xls
     * @throws Exception\InvalidSheetIndexException
     * @throws Exception\SheetReaderException
     */
    private function setSheetIndex(int $sheetIndex): self
    {
        try {
            $sheets = $this->spreadsheetReader->listWorksheetNames($this->spreadsheetPath);
        } catch(PhpSpreadsheet\Reader\Exception $e) {
            throw new Exception\SheetReaderException("Cannot list spreadsheet sheets");
        }

        if(!isset($sheets[$sheetIndex])) {
            throw new Exception\InvalidSheetIndexException(sprintf('Cannot get sheet with index %d', $sheetIndex));
        }

        $this->sheetName = $sheets[$sheetIndex];

        $this->spreadsheetReader->setLoadSheetsOnly($this->sheetName);

        return $this;
    }

    /**
     * @param int $sheetIndex
     * @return array
     * @throws Exception\SheetReaderException
     * @throws Exception\InvalidSheetIndexException
     * @throws Exception\InvalidSheetException
     */
    public function toArray(int $sheetIndex): array
    {
        $this->setSheetIndex($sheetIndex);

        try {
            $spreadsheet = $this->spreadsheetReader->load($this->spreadsheetPath);
        } catch(PhpSpreadsheet\Reader\Exception $e) {
            throw new Exception\SheetReaderException("Cannot load spreadsheet data");
        }

        try {
            $spreadsheet->setActiveSheetIndexByName($this->sheetName);
        } catch(PhpSpreadsheet\Exception $e) {
            throw new Exception\InvalidSheetIndexException('Cannot select the sheet');
        }

        try {
            $arr = $spreadsheet->getActiveSheet()->toArray();
        } catch(PhpSpreadsheet\Exception $e) {
            throw new Exception\InvalidSheetException('Cannot parse data array from the sheet');
        }

        return $arr;
    }

}
