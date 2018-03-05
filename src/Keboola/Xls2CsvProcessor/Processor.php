<?php declare(strict_types = 1);

namespace Keboola\Xls2CsvProcessor;

use RecursiveDirectoryIterator;
use RecursiveTreeIterator;

class Processor
{

    /**
     * @var Component
     */
    private $keboolaComponent;

    /**
     * Extractor constructor.
     */
    public function __construct()
    {
        $this->keboolaComponent = new Component();
    }

    private function processDir(string $inFilesDirPath, string $outFilesDirPath): void
    {
        $it = new RecursiveTreeIterator(
            new RecursiveDirectoryIterator($inFilesDirPath, RecursiveDirectoryIterator::SKIP_DOTS)
        );

        // remove visual elements of path tree
        $it->setPrefixPart(0 ,'');
        $it->setPrefixPart(1 ,'');
        $it->setPrefixPart(2 ,'');
        $it->setPrefixPart(3 ,'');
        $it->setPrefixPart(4 ,'');
        $it->setPrefixPart(5 ,'');

        foreach($it as $path) {
            if(is_file($path) && preg_match('/\.(xls|xlsx)$/', $path)) {
                $inputPath = $path;
                $outputPath = preg_replace(
                    '/^' . preg_quote($inFilesDirPath, '/') . '/',
                    $outFilesDirPath,
                    preg_replace('/\.(xls|xlsx)$/', '.csv', $path)
                );
                $sheetIndex = $this->keboolaComponent->getConfig()->getValue(['parameters', 'sheet_index']);

                Xls2Csv::convert($inputPath, $outputPath, $sheetIndex);
            }
        }
    }

    /**
     *
     */
    public function run() : void
    {
        $this->processDir(
            sprintf('%s%s', $this->keboolaComponent->getDataDir(), '/in/files'),
            sprintf('%s%s', $this->keboolaComponent->getDataDir(), '/out/files')
        );
    }

}
