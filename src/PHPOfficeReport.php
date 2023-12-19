<?php

namespace hstanleycrow\EasyPHPReports;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PHPOfficeReport
{

    protected Spreadsheet $spreadsheet;
    protected Worksheet $sheet;
    private CellFormatter $cellFormatter;
    private DataValidator $dataValidator;
    private ReportBuilder $reportBuilder;
    private ReportDataPreparer $dataPreparer;
    protected array $reportHeaders;
    protected string $content;

    public function __construct(
        private array $reportHeadersSetup,
        private array $reportData,
    ) {
        $this->spreadsheet = new Spreadsheet();
        $this->sheet = $this->spreadsheet->getActiveSheet();

        $this->dataValidator = new DataValidator($this->reportHeadersSetup);

        $this->dataPreparer = new ReportDataPreparer(
            $this->reportHeadersSetup,
            $this->reportData,
        );

        $this->reportHeaders = $this->dataPreparer->createColumnsHeaders();

        $this->cellFormatter = new CellFormatter(
            $this->reportHeadersSetup,
            $this->reportHeaders,
            $this->reportData,
            $this->sheet,
            $this->dataValidator,
        );

        $this->reportBuilder = new ReportBuilder(
            $this->dataPreparer,
            $this->cellFormatter,
            $this->reportHeaders,
            $this->reportData,
        );
    }

    public function generate()
    {
        $this->reportBuilder->generate();
    }
}
