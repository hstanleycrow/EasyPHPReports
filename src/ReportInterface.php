<?php

namespace hstanleycrow\EasyPHPReports;

interface ReportInterface
{
    public static function create(array $reportHeadersSetup, array $reportData, ?string $filepath): self;
    public function generate(): string;
    public function isDownloadable(): bool;
}
