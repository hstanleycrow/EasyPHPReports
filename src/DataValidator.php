<?php

namespace hstanleycrow\EasyPHPReports;

class DataValidator
{

    public function __construct(
        private array $reportHeadersSetup,
    ) {
    }

    public function isDateFormat(int $column): bool
    {
        return ($this->reportHeadersSetup[$column - 1]['format'] == "DATE");
    }

    public function isDatetimeFormat(int $column): bool
    {
        return ($this->reportHeadersSetup[$column - 1]['format'] == "DATETIME");
    }

    public static function isTextFormat(string $format): bool
    {
        return ($format == "TEXT");
    }
}
