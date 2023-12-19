<?php

namespace hstanleycrow\EasyPHPReports;

class ReportDataPreparer
{

    public function __construct(
        private array $reportHeadersSetup,
        private array $reportData,
    ) {
    }

    public function createColumnsHeaders(): array
    {
        if (count($this->reportHeadersSetup) == 0) :
            throw new \Exception("You must define the columns headers setup before");
        endif;

        $tableHeaders = array();
        foreach ($this->reportHeadersSetup as $column) :
            $tableHeaders[] = mb_convert_encoding($column['header'], "UTF8");
        endforeach;
        return $tableHeaders;
    }
}
