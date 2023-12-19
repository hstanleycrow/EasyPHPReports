<?php

namespace hstanleycrow\EasyPHPReports;

use PhpOffice\PhpSpreadsheet\Writer\Csv;

final class CSVReport extends PHPOfficeReport implements ReportInterface
{

    const EXTENSION = ".csv";

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
            throw new \InvalidArgumentException("Filepath is required for CSV Report");
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
        $writer = new Csv($this->spreadsheet);
        $writer->setDelimiter(',');
        $writer->setEnclosure('"');
        $writer->setLineEnding("\r\n");
        $writer->setSheetIndex(0);

        $writer->save($this->filepath);
    }
}
