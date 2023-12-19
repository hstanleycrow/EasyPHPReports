<?php

namespace hstanleycrow\EasyPHPReports;

use PhpOffice\PhpSpreadsheet\Style\NumberFormat;


class ReportConfig
{
    const DATE_FORMAT = 'd/m/Y';

    const DATETIME_FORMAT = 'd/m/Y H:i:s';

    const MAX_LEN_WORDWRAP = 20;

    const INITIAL_DATA_ROW = 2;

    static $excel_cells_format = [
        "TEXT" => ["format" => NumberFormat::FORMAT_GENERAL, "calculate" => "COUNTIF"],
        "NUMBER" => ["format" => NumberFormat::FORMAT_NUMBER, "calculate" => "SUM"],
        "DECIMAL" => ["format" => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1, "calculate" => "SUM"],
        "MONEY" => ["format" => NumberFormat::FORMAT_CURRENCY_USD, "calculate" => "SUM"],
        "DATE" => ["format" => NumberFormat::FORMAT_DATE_DDMMYYYY, "calculate" => "COUNTIF"],
        "DATETIME" => ["format" => NumberFormat::FORMAT_DATE_DATETIME, "calculate" => "COUNTIF"]
    ];

    static $styleArray = [
        'font' => [
            'bold' => true,
        ],
        'alignment' => [
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
        ],
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => [
                'rgb' => 'EFEFEF',
            ],
        ],
    ];
}
