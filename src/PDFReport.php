<?php

namespace hstanleycrow\EasyPHPReports;

use PhpOffice\PhpSpreadsheet\IOFactory;

final class PDFReport extends PHPOfficeReport implements ReportInterface
{

    const EXTENSION = ".pdf";

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
            throw new \InvalidArgumentException("Filepath is required for PDF Report");
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
        $writer = IOFactory::createWriter($this->spreadsheet, 'Mpdf');
        $writer->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        $writer->save($this->filepath);

        #$writer->save('php://output');
        #exit;
    }
}
