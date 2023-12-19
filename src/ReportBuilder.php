<?php

namespace hstanleycrow\EasyPHPReports;

class ReportBuilder
{
    public function __construct(
        private ReportDataPreparer $dataPreparer,
        private CellFormatter $cellFormatter,
        private array $reportHeaders,
        private array $reportData,
    ) {
    }

    public function generate()
    {
        $this->dataPreparer->createColumnsHeaders();
        $this->cellFormatter->setHeaderStyle();
        $this->cellFormatter->fillCells();
        $this->cellFormatter->setCellsStyle();
    }
}
