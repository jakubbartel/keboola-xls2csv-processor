<?php

namespace Keboola\Xls2CsvProcessor;

use Box\Spout;

class Xlsx
{

    /**
     * @var Spout\Reader\ReaderInterface
     */
    private $spreadsheet;

    /**
     * @var Spout\Reader\XLSX\Sheet|null
     */
    private $sheet;

    /**
     * Xls constructor.
     *
     * @param string $spreadsheetPath
     * @throws Exception\SheetReaderException
     * @throws Exception\InvalidXlsFileException
     */
    public function __construct(string $spreadsheetPath)
    {
        try {
            $reader = Spout\Reader\ReaderFactory::create(Spout\Common\Type::XLSX);
        } catch(Spout\Common\Exception\UnsupportedTypeException $e) {
            throw new Exception\SheetReaderException('Wrong type init');
        }

        $reader->setShouldFormatDates(true);

        try {
            $reader->open($spreadsheetPath);
        } catch(Spout\Common\Exception\IOException $e) {
            throw new Exception\InvalidXlsFileException('Cannot create XLSX reader');
        }

        $this->spreadsheet = $reader;
    }

    /**
     * @param int $sheetIndex
     * @return Xlsx
     * @throws Exception\InvalidSheetIndexException
     */
    private function setSheetIndex(int $sheetIndex): self
    {
        $this->sheet = null;

        try {
            foreach($this->spreadsheet->getSheetIterator() as $sheet) {
                if($sheet->getIndex() === $sheetIndex) {
                    $this->sheet = $sheet;
                }
            }
        } catch(Spout\Reader\Exception\ReaderNotOpenedException $e) {
            throw new Exception\InvalidSheetIndexException('Cannot get sheets list from the XLSX');
        }

        if($this->sheet === null) {
            throw new Exception\InvalidSheetIndexException(sprintf('Cannot set active sheet to %d', $sheetIndex));
        }

        return $this;
    }

    /**
     * @param int $sheetIndex
     * @return array
     * @throws Exception\InvalidSheetIndexException
     * @throws Exception\InvalidSheetException
     */
    public function toArray(int $sheetIndex): array
    {
        $this->setSheetIndex($sheetIndex);

        $arr = [];

        // just for static code checkers - a sheet should always be set after setSheetIndex (if no exception thrown)
        if($this->sheet !== null) {
            try {
                foreach($this->sheet->getRowIterator() as $row) {
                    $arr[] = $row;
                }
            } catch(Spout\Common\Exception\SpoutException $e) {
                throw new Exception\InvalidSheetException('Cannot read XLS file row');
            }
        }

        return $arr;
    }

}
