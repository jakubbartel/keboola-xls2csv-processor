<?php

namespace Keboola\Xls2CsvProcessor\Tests\Processor;

use Keboola\Xls2CsvProcessor;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

class ProcessorTest extends TestCase
{

    public function testProcessOneSheet() : void
    {
        $sheetIndex = 0;

        $processor = new Xls2CsvProcessor\Processor($sheetIndex);

        $fileSystem = vfsStream::setup();

        $processor->processFile(
            __DIR__ . '/fixtures/process_one_sheet/input.xls',
            $fileSystem->url() . '/output.csv'
        );

        $this->assertTrue($fileSystem->hasChild('output.csv'));

        $this->assertFileEquals(
            __DIR__ . '/fixtures/process_one_sheet/output.csv',
            $fileSystem->url() . '/output.csv'
        );
    }

    public function testProcessDirectory() : void
    {
        $sheetIndex = 0;

        $processor = new Xls2CsvProcessor\Processor($sheetIndex);

        $fileSystem = vfsStream::setup();

        $processor->processDir(
            __DIR__ . '/fixtures/process_directory',
            $fileSystem->url() . '/'
        );

        $this->assertTrue($fileSystem->hasChild('input.csv'));
        $this->assertTrue($fileSystem->hasChild('subdir_1/input_1.csv'));
        $this->assertTrue($fileSystem->hasChild('subdir_1/input_2.csv'));
        $this->assertTrue($fileSystem->hasChild('subdir_1/subsubdir/input.csv'));
        $this->assertTrue($fileSystem->hasChild('subdir_2/input.csv'));

        $this->assertFileEquals(
            __DIR__ . '/fixtures/process_directory/output.csv',
            $fileSystem->url() . '/input.csv'
        );

        $this->assertFileEquals(
            __DIR__ . '/fixtures/process_directory/subdir_1/output_1.csv',
            $fileSystem->url() . '/subdir_1/input_1.csv'
        );

        $this->assertFileEquals(
            __DIR__ . '/fixtures/process_directory/subdir_1/output_2.csv',
            $fileSystem->url() . '/subdir_1/input_2.csv'
        );

        $this->assertFileEquals(
            __DIR__ . '/fixtures/process_directory/subdir_1/subsubdir/output.csv',
            $fileSystem->url() . '/subdir_1/subsubdir/input.csv'
        );

        $this->assertFileEquals(
            __DIR__ . '/fixtures/process_directory/subdir_2/output.csv',
            $fileSystem->url() . '/subdir_2/input.csv'
        );
    }

}
