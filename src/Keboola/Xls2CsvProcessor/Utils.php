<?php

namespace Keboola\Xls2CsvProcessor;

class Utils
{

    /**
     * @param array $data
     * @param int $columnsNum
     * @return array
     */
    public static function enforceColumnsNum(array $data, int $columnsNum): array
    {
        foreach($data as $i => $row) {
            $missingCols = $columnsNum-count($row);

            if($missingCols > 0) {
                $data[$i] = array_merge($row, array_fill(0, $missingCols, ""));
            }
        }

        return $data;
    }

}
