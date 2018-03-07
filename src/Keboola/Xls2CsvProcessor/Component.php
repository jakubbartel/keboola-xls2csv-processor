<?php

namespace Keboola\Xls2CsvProcessor;

use Keboola\Component\BaseComponent;
use League\Flysystem;

class Component extends BaseComponent
{

    /**
     * @var Processor
     */
    private $processor;

    /**
     * Component constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $fileSystemAdapter = new Flysystem\Adapter\Local('/');
        $fileSystem = new Flysystem\Filesystem($fileSystemAdapter);
        $this->processor = new Processor($fileSystem, '/', $this->getConfig()->getValue(['parameters', 'sheet_index']));
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
     */
    public function run() : void
    {
        $this->processor->processDir(
            sprintf('%s%s', $this->getDataDir(), '/in/files'),
            sprintf('%s%s', $this->getDataDir(), '/out/files')
        );
    }

}
