<?php

namespace hstanleycrow\EasyPHPReports;

use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use hstanleycrow\EasyPHPReports\ReportFileExtensionManager;

final class ExcelReport extends PHPOfficeReport implements ReportInterface
{

    const EXTENSION = ".xlsx";

    public function __construct(
        protected array $reportHeaders,
        protected array $reportData,
        protected string $filepath,
    ) {
        parent::__construct($reportHeaders, $reportData);
        $this->filepath = (new ReportFileExtensionManager($this->filepath, self::EXTENSION))->setFilename();
    }

    public static function create(array $reportHeadersSetup, array $reportData, ?string $filepath): self
    {
        if ($filepath === null) {
            throw new \InvalidArgumentException("Filepath is required for ExcelReport");
        }

        return new self($reportHeadersSetup, $reportData, $filepath);
    }

    public function generate(): string
    {
        parent::generate();
        $this->save();
        return $this->filepath;
    }

    public function isDownloadable(): bool
    {
        return true;
    }

    private function save(): void
    {
        $writer = new Xlsx($this->spreadsheet);

        $writer->save($this->filepath);
    }
}
