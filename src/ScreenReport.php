<?php

namespace hstanleycrow\EasyPHPReports;

use DOMDocument;
use PhpOffice\PhpSpreadsheet\Writer\Html;

final class ScreenReport extends PHPOfficeReport implements ReportInterface
{

    public function __construct(
        protected array $reportHeaders,
        protected array $reportData,
    ) {
        parent::__construct($reportHeaders, $reportData);
    }

    public static function create(array $reportHeadersSetup, array $reportData, ?string $filepath): self
    {
        return new self($reportHeadersSetup, $reportData, $filepath);
    }

    public function generate(): string
    {
        parent::generate();
        $this->save();
        return $this->content;
    }

    public function isDownloadable(): bool
    {
        return false;
    }

    private function save(): void
    {
        $writer = new Html($this->spreadsheet);
        $hdr = $writer->generateHTMLHeader();
        $hdr = "";
        $sty = $writer->generateStyles(false); // do not write <style> and </style>
        $html = $hdr;
        $html .= $writer->generateSheetData();
        $this->content = $html;
        $this->prepareContent();
    }

    private function prepareContent(): void
    {
        $doc = new DOMDocument();
        $doc->encoding = 'utf-8';
        $doc->loadHTML(mb_convert_encoding($this->content, 'ISO-8859-1', 'UTF-8'));
        $tabla = $doc->getElementById('sheet0');
        $tabla->setAttribute('class', $tabla->getAttribute('class') . ' table table-bordered table-responsive');
        $this->content = $doc->saveHTML($tabla);
    }
}
