<?php declare(strict_types = 1);

namespace Keboola\Xls2CsvProcessor;

use Keboola\Csv\CsvFile;
use League\Flysystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Filesystem\Filesystem;

class Processor
{

    /**
     * @var int
     */
    private $sheetIndex;

    /**
     * Extractor constructor.
     *
     * @param int $sheetIndex
     */
    public function __construct(int $sheetIndex)
    {
        $this->sheetIndex = $sheetIndex;
    }

    /**
     * Look up all xls(x) and return their path with desired output file.
     *
     * @param string $inFilesDirPath
     * @param string $outFilesDirPath
     * @return array
     * @throws Exception\InvalidFilePathException
     */
    private function getFilesToProcess(string $inFilesDirPath, string $outFilesDirPath): array
    {
        $inOuts = [];

        $finder = new Finder();
        $finder->files()->in($inFilesDirPath);

        foreach($finder as $file) {
            if($file->getRelativePath() === '') {
                $outFilePath = sprintf('%s/%s.csv',
                    $outFilesDirPath,
                    $file->getBasename(sprintf('.%s', $file->getExtension()))
                );
            } else {
                $outFilePath = sprintf('%s/%s/%s.csv',
                    $outFilesDirPath,
                    $file->getRelativePath(),
                    $file->getBasename(sprintf('.%s', $file->getExtension()))
                );
            }

            $inOuts[] = [
                'input' => $file->getPathname(),
                'output' => $outFilePath
            ];
        }

        return $inOuts;
    }

    /**
     * @param string $filePath
     * @return Processor
     */
    public function prepareOutputFile($filePath): self
    {
        $fileSystem = new FileSystem();

        $fileSystem->dumpFile($filePath, '');

        return $this;
    }

    /**
     * @param string $inFilePath
     * @param string $outFilePath
     * @return Processor
     */
    public function processFile(string $inFilePath, string $outFilePath): self
    {
        $xls = new Xls($inFilePath);
        $xlsArray = $xls->toArray($this->sheetIndex);

        $this->prepareOutputFile($outFilePath);

        $csv = new CsvFile($outFilePath);
        foreach($xlsArray as $xlsRow) {
            $csv->writeRow($xlsRow);
        }

        return $this;
    }

    /**
     * @param string $inFilesDirPath
     * @param string $outFilesDirPath
     * @return Processor
     */
    public function processDir(string $inFilesDirPath, string $outFilesDirPath): self
    {
        $inOuts = $this->getFilesToProcess($inFilesDirPath, $outFilesDirPath);

        foreach($inOuts as $inOut) {
            $this->processFile($inOut['input'], $inOut['output']);
        }

        return $this;
    }

}
