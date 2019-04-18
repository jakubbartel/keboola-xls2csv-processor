<?php

namespace Keboola\Xls2CsvProcessor;

use Box\Spout;
use Keboola\Xls2CsvProcessor\Exception\InvalidXlsFileException;
use Keboola\Xls2CsvProcessor\Exception\SheetReaderException;

class Xls
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
     * @throws SheetReaderException
     * @throws InvalidXlsFileException
     */
    public function __construct(string $spreadsheetPath)
    {
        try {
            $reader = Spout\Reader\ReaderFactory::create(Spout\Common\Type::XLSX);
        } catch(Spout\Common\Exception\UnsupportedTypeException $e) {
            throw new SheetReaderException('Wrong type init');
        }

        $reader->setShouldFormatDates(true);

        try {
            $reader->open($spreadsheetPath);
        } catch(Spout\Common\Exception\IOException $e) {
            throw new InvalidXlsFileException('Cannot create XLS reader');
        }

        $this->spreadsheet = $reader;
    }

    /**
     * @param int $sheetIndex
     * @return Xls
     * @throws Exception\InvalidSheetIndexException
     * @throws InvalidXlsFileException
     */
    private function setSheetIndex(int $sheetIndex): self {
        $this->sheet = null;

        try {
            foreach($this->spreadsheet->getSheetIterator() as $sheet) {
                if($sheet->getIndex() === $sheetIndex) {
                    $this->sheet = $sheet;
                }
            }
        } catch(Spout\Reader\Exception\ReaderNotOpenedException $e) {
            throw new Exception\InvalidXlsFileException(sprintf('Cannot parse sheet %d from the XLS', $sheetIndex));
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
     * @throws InvalidXlsFileException
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
                throw new InvalidXlsFileException('Cannot read XLS file row');
            }
        }

        return $arr;
    }

}
