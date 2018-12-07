# Keboola Xls2Csv Processor

[![Build Status](https://travis-ci.org/jakubbartel/keboola-xls2csv-processor.svg?branch=master)](https://travis-ci.org/jakubbartel/keboola-xls2csv-processor)

Convert XLS/XLSX (Microsoft Excel) files to CSV files.

## Usage

The processor takes all files in the input directory `/data/in/files` (and all subdirectories), reads them as XLS
files and converts them to CSV files. A sheet within the XLS file can be selected by the `sheet_index` parameter.
If there is no `sheet_index` defined, the default value is `0` which is the first sheet.

## Configuration

Example processor configuration:
```
{
    "definition": {
        "component": "jakub-bartel.processor-xls2csv"
    }
}
```

Example processor configuration - process the third sheet of each file:
```
{
    "definition": {
        "component": "jakub-bartel.processor-xls2csv"
    },
    "parameters": {
        "sheet_index": 2
    }
}
```
