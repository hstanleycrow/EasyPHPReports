<?php

namespace hstanleycrow\EasyPHPReports;

use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CellFormatter
{

    public function __construct(
        private array $reportHeadersSetup,
        private array $reportHeaders,
        private array $reportData,
        private Worksheet $sheet,
        private DataValidator $reportFormatValidator,
    ) {
    }

    public function setHeaderStyle(): void
    {
        $column = 1;
        $row = 1;
        foreach ($this->reportHeaders as $header) :
            $header = mb_convert_encoding($header, "UTF8");
            $this->sheet->setCellValue($this->getCellNumber($column, $row), $header);

            $this->sheet->getStyle($this->getCellNumber($column, $row))->applyFromArray(ReportConfig::$styleArray);

            $column++;
        endforeach;
    }

    public function fillCells(): void
    {

        $column = 1;
        $row = ReportConfig::INITIAL_DATA_ROW;

        foreach ($this->reportData as $exportData) :
            foreach ($exportData as $value) :
                if ($this->reportFormatValidator->isDateFormat($column)) :
                    $fecha = \DateTime::createFromFormat('Y-m-d', $value);
                    $fecha_formateada = $fecha->format(ReportConfig::DATE_FORMAT);
                    $fecha_excel = \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($fecha_formateada);
                    $this->sheet->setCellValue($this->getCellNumber($column, $row), $fecha_excel)->getStyle($this->getCellNumber($column, $row))->getNumberFormat()->setFormatCode('DD/MM/YYYY');
                else :
                    if ($this->reportFormatValidator->isDatetimeFormat($column)) :
                        $fecha = \DateTime::createFromFormat('Y-m-d H:i:s', $value);
                        $fecha_formateada = $fecha->format(ReportConfig::DATETIME_FORMAT);
                        $fecha_excel = \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($fecha_formateada);
                        $this->sheet->setCellValue($this->getCellNumber($column, $row), $fecha_excel)->getStyle($this->getCellNumber($column, $row))->getNumberFormat()->setFormatCode('DD/MM/YYYY HH:MM:SS');
                    else :
                        #$value = utf8_encode($value);
                        $value = mb_convert_encoding($value, "UTF8");
                        if (strlen($value) > ReportConfig::MAX_LEN_WORDWRAP) {
                            $this->sheet->getStyle($this->getCellNumber($column, $row))->getAlignment()->setWrapText(true);
                        }
                        $this->sheet->setCellValue($this->getCellNumber($column, $row), "$value");
                    endif;
                endif;
                $column++;
            endforeach;
            $this->sheet->getColumnDimension(Coordinate::stringFromColumnIndex($column))->setAutoSize(true);
            $column = 1;
            $row++;
        endforeach;
    }

    public function setCellsStyle(): void
    {
        $column = 1;
        foreach ($this->reportHeadersSetup as $columnSetup) :
            $format = strtoupper($columnSetup['format']);
            $calculate = strtoupper($columnSetup['calculate']);
            if ($this->isValidFormat($format)) :
                if ($format <> "DATE") :
                    $this->setCellStyle(Coordinate::stringFromColumnIndex($column), $format);
                endif;
            endif;
            if ($calculate) :
                $this->setCalculatedValue(Coordinate::stringFromColumnIndex($column), $format);

            endif;
            $column++;
        endforeach;
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '00000000'],
                ],
            ],
        ];
        $this->sheet->getStyle('A1:' . $this->getCellNumber(count($this->reportHeaders), count($this->reportData) + 1))->applyFromArray($styleArray);
        foreach (range('A', Coordinate::stringFromColumnIndex((count($this->reportHeaders)))) as $column) :
            foreach (range(2, count($this->reportData)) as $row) :
                $col = $this->sheet->getCell($column . $row);
                if (strlen($this->sheet->getCell($column . $row)->getValue()) > ReportConfig::MAX_LEN_WORDWRAP) :
                    $col->getStyle()->getAlignment()->setWrapText(true);
                    $this->sheet->getColumnDimension($column)->setWidth(25);
                    $this->sheet->getRowDimension($row)->setRowHeight(30);
                else :
                    $this->sheet->getColumnDimension($column)->setAutoSize(true);
                endif;
                $col->getStyle()->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            endforeach;
        endforeach;
        $this->sheet->calculateColumnWidths();
    }

    private function getCellNumber(int $column, int $row): string
    {
        return Coordinate::stringFromColumnIndex($column) . "$row";
    }

    private function setCellStyle(string $column, string $format): void
    {
        $this->sheet->getStyle($column . "2:$column" . (count($this->reportData) + 1))
            ->getNumberFormat()
            ->setFormatCode(ReportConfig::$excel_cells_format[$format]['format']);
    }

    private function isValidFormat(string $format): bool
    {
        return (array_key_exists($format, ReportConfig::$excel_cells_format));
    }

    private function setCalculatedValue(string $column, string $format): void
    {
        $calculatedRow = $column . (count($this->reportData) + 2);
        if (DataValidator::isTextFormat($format)) :
            $this->sheet->setCellValue($calculatedRow, '=' . ReportConfig::$excel_cells_format[$format]['calculate'] . '(' . $column . '2:' . $column . (count($this->reportData) + 1) . ', "*")');
        else :
            $this->sheet->setCellValue($calculatedRow, '=' . ReportConfig::$excel_cells_format[$format]['calculate'] . '(' . $column . '2:' . $column . (count($this->reportData) + 1) . ')');
        endif;
        $this->sheet->getStyle($calculatedRow)->getNumberFormat()->setFormatCode(ReportConfig::$excel_cells_format[$format]['format']);
        $styleArray = [
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
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '00000000'],
                ],
            ],
        ];
        $this->sheet->getStyle($calculatedRow)->applyFromArray($styleArray);
    }
}
