<?php

namespace Keboola\Xls2CsvProcessor;

use Keboola\Component\BaseComponent;
use Keboola\Xls2CsvProcessor\Exception\InvalidXlsFileException;
use Keboola\Xls2CsvProcessor\Exception\UserException;

class Component extends BaseComponent
{

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
     * @throws \Keboola\Csv\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function run() : void
    {
        $processor = new Processor($this->getConfig()->getValue(['parameters', 'sheet_index']));

        try {
            $processor->processDir(
                sprintf('%s%s', $this->getDataDir(), '/in/files'),
                sprintf('%s%s', $this->getDataDir(), '/out/files')
            );
        } catch(InvalidXlsFileException $e) {
            throw new UserException(sprintf('Invalid xls file: %s', $e->getMessage()), 0, $e);
        }
    }

}
