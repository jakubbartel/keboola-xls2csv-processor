<?php declare(strict_types = 1);

namespace Keboola\Xls2CsvProcessor;

use Keboola\Csv\CsvFile;
use League\Flysystem;

class Processor
{

    /**
     * @var Flysystem\Filesystem
     */
    private $fileSystem;

    /**
     * @var int
     */
    private $sheetIndex;

    /**
     * @var string
     */
    private $fileSystemPath;

    /**
     * Extractor constructor.
     *
     * @param Flysystem\Filesystem $fileSystem
     * @param string $fileSystemPath
     * @param int $sheetIndex
     */
    public function __construct(Flysystem\Filesystem $fileSystem, string $fileSystemPath, int $sheetIndex)
    {
        $this->fileSystem = $fileSystem;
        $this->fileSystemPath = $fileSystemPath;
        $this->sheetIndex = $sheetIndex;
    }

    /**
     * @return string[]
     */
    private function getSupportedExtensions(): array {
        return [
            'xls',
            'xlsx',
        ];
    }

    /**
     * @param string $inFilesDirPath
     * @param string $outFilesDirPath
     * @return InOut[]
     */
    private function getFilesToProcess(string $inFilesDirPath, string $outFilesDirPath): array {
        /** @var InOut[] $inOuts */
        $inOuts = [];

        foreach($this->fileSystem->listContents($inFilesDirPath, true) as $object) {
            if($object['type'] != 'file') {
                continue;
            }

            if( ! in_array($object['extension'], $this->getSupportedExtensions())) {
                continue;
            }

            // replace inputDir path prefix with outDir prefix
            $outFilePath = preg_replace(
                '/^' . preg_quote($inFilesDirPath, '/') . '/',
                $outFilesDirPath,
                sprintf('%s/%s.csv', $object['dirname'], $object['filename'])
            );

            $inOuts[] = new InOut(
                $object['path'],
                $outFilePath
            );
        }

        return $inOuts;
    }

    /**
     * @param string $inFilesDirPath
     * @param string $outFilesDirPath
     */
    public function processDir(string $inFilesDirPath, string $outFilesDirPath): void
    {
        $inOuts = $this->getFilesToProcess($inFilesDirPath, $outFilesDirPath);

        $fileSystemPath = $this->fileSystemPath;
        $getFullFileSystemPath = function($path) use ($fileSystemPath) {
            return sprintf('%s%s', $this->fileSystemPath, $path);
        };

        foreach($inOuts as $inOut) {
            $xls = new Xls($getFullFileSystemPath($inOut->getIn()));
            $xlsArray = $xls->toArray($this->sheetIndex);

            $csv = new CsvFile($inOut->getOut());
            foreach($xlsArray as $xlsRow) {
                $csv->writeRow($xlsRow);
            }
        }
    }

}
