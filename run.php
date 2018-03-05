<?php

date_default_timezone_set("UTC");

require __DIR__ . '/vendor/autoload.php';

try {
    $processor = new \Keboola\Xls2CsvProcessor\Processor();
    $processor->run();
} catch(Exception $e) {
    // TODO handle user and system errors
    error_log($e->getMessage());
    exit(1);
} catch(Throwable $e) {
    // TODO handle user and system errors
    error_log('Fatal error');
    error_log($e->getMessage());
    exit(1);
}

exit(0);
