<?php

namespace hstanleycrow\EasyPHPReports;

final class PHPReport
{

    public const SCREEN = "SCREEN";
    public const EXCEL = "EXCEL";
    public const PDF = "PDF";
    public const CSV = "CSV";

    protected ReportInterface $report;

    private static array $types = [];

    public function __construct(
        private string $type,
        private array $reportHeadersSetup,
        private array $reportData,
        private ?string $filepath = null,
    ) {
    }

    public static function registerType(string $type, string $className): void
    {
        self::$types[$type] = $className;
    }

    public static function initialize(): void
    {
        self::registerType(self::SCREEN, ScreenReport::class);
        self::registerType(self::EXCEL, ExcelReport::class);
        self::registerType(self::PDF, PDFReport::class);
        self::registerType(self::CSV, CSVReport::class);
    }

    public function generate(): string
    {
        if (!isset(self::$types[$this->type])) {
            throw new \InvalidArgumentException("Invalid report type: $this->type");
        }

        $className = self::$types[$this->type];
        $this->report = $className::create($this->reportHeadersSetup, $this->reportData, $this->filepath);

        return $this->report->generate();
    }

    public function isDownloadable(): bool
    {
        return $this->report->isDownloadable();
    }
}
