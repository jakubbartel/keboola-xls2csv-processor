<?php

namespace Keboola\Xls2CsvProcessor;

use Keboola\Component\BaseComponent;
use Keboola\Component\UserException as ComponentUserException;
use Keboola\Csv\Exception;
use Keboola\Xls2CsvProcessor\Exception\InvalidSheetException;
use Keboola\Xls2CsvProcessor\Exception\InvalidSheetIndexException;
use Keboola\Xls2CsvProcessor\Exception\InvalidXlsFileException;
use Keboola\Xls2CsvProcessor\Exception\SheetReaderException;
use Keboola\Xls2CsvProcessor\Exception\UserException;

class Component extends BaseComponent
{

    /**
     * Component constructor.
     *
     * @throws UserException
     */
    public function __construct()
    {
        try {
            parent::__construct();
        } catch(ComponentUserException $e) {
            throw new UserException($e);
        }
    }

    /**
     * @return string
     */
    protected function getConfigDefinitionClass(): string
    {
        return ConfigDefinition::class;
    }

    /**
     * The source of life.
     *
     * @throws UserException
     * @throws Exception
     */
    public function run() : void
    {
        $processor = new Processor($this->getConfig()->getValue(['parameters', 'sheet_index']));

        try {
            $processor->processDir(
                sprintf('%s%s', $this->getDataDir(), '/in/files'),
                sprintf('%s%s', $this->getDataDir(), '/out/files')
            );
        } catch(InvalidXlsFileException|InvalidSheetException|SheetReaderException $e) {
            throw new UserException(sprintf('Invalid xls file: %s', $e->getMessage()), 0, $e);
        } catch(InvalidSheetIndexException $e) {
            throw new UserException(sprintf('Invalid sheet index: %s', $e->getMessage()), 0, $e);
        }
    }

}
