<?php

namespace Keboola\Xls2CsvProcessor\Tests\Component;

use Keboola\Xls2CsvProcessor\Component;
use Keboola\Xls2CsvProcessor\Exception\UserException;
use PHPUnit\Framework\TestCase;

class ComponentTest extends TestCase
{

//    Unfortunately does not work because vfsStream does not support php ext/zip which is used by phpoffice
//    public function testRun() : void
//    {
//        $config = [
//            'parameters' => [
//                'sheet_index' => 0,
//            ]
//        ];
//
//        $fileSystem = vfsStream::setup('root');
//        mkdir($fileSystem->url() . '/data');
//        vfsStream::copyFromFileSystem(__DIR__ . '/fixtures/data', $fileSystem->getChild('data'));
//
//        $file = vfsStream::url('root/data/config.json');
//        file_put_contents($file, json_encode($config));
//
//        putenv('KBC_DATADIR=' . $fileSystem->url() . '/data');
//
//        $component = new Component();
//        $component->run();
//
//        $this->assertTrue($fileSystem->hasChild('data/out/files/input.csv'));
//
//        $this->assertFileEquals(
//            __DIR__ . '/fixtures/data/in/files/output.csv',
//            $fileSystem->url() . '/data/files/out/input.csv'
//        );
//    }

    public static function setUpBeforeClass()
    {
        self::clearTestRuns();
    }

    public static function tearDownAfterClass()
    {
        self::clearTestRuns();
    }

    /**
     * clear environment because filesystem sandbox is not used
     */
    private static function clearTestRuns(): void
    {
        if(file_exists(__DIR__ . '/test_run_1/data/out/files/input.csv')) {
            unlink(__DIR__ . '/test_run_1/data/out/files/input.csv');
        }

        if(file_exists(__DIR__ . '/test_run_2/data/out/files/input.csv')) {
            unlink(__DIR__ . '/test_run_2/data/out/files/input.csv');
        }
    }

    public function testRun(): void
    {
        putenv('KBC_DATADIR=' . __DIR__ . '/test_run_1/data');

        $component = new Component();
        $component->run();

        $this->assertFileExists(__DIR__ . '/test_run_1/data/out/files/input.csv');

        $this->assertFileEquals(
            __DIR__.'/test_run_1/data/out/files/input_expected.csv',
            __DIR__ . '/test_run_1/data/out/files/input.csv'
        );
    }

    public function testRunPdf(): void
    {
        putenv('KBC_DATADIR=' . __DIR__ . '/test_run_2/data');

        $this->expectException(UserException::class);

        $component = new Component();
        $component->run();
    }

    public function testInvalidConfig(): void
    {
        putenv('KBC_DATADIR=' . __DIR__ . '/test_run_3/data');

        $this->expectException(UserException::class);

        $component = new Component();
        $component->run();
    }

}
