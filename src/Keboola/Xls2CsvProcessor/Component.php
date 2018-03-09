<?php

namespace Keboola\Xls2CsvProcessor;

use Keboola\Component\BaseComponent;

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
     */
    public function run() : void
    {
        $processor = new Processor($this->getConfig()->getValue(['parameters', 'sheet_index']));

        $processor->processDir(
            sprintf('%s%s', $this->getDataDir(), '/in/files'),
            sprintf('%s%s', $this->getDataDir(), '/out/files')
        );
    }

}
