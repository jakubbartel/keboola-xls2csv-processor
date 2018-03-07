# Keboola Xls2Csv Processor

Convert xls/xlsx (Excel) files to csv files.

## Functionality

Processor takes all files in input directory `/data/in/files` (and all subdirectories) that have `.xsl` or `.xlsx`
extension and transfers them to `.csv` files. Sheet within the xls file can be selected by `sheet_index` parameter.
If there is no `sheet_index` defined, default value is `0` which is the first sheet.

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
