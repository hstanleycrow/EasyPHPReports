<?php

namespace hstanleycrow\EasyPHPReports;

class ReportFileExtensionManager
{

    public function __construct(
        private string $filepath,
        private string $fileExtension,
    ) {
    }

    public function setFilename(): string
    {
        if (!$this->hasExtension()) :
            $this->filepath = $this->filepath . $this->fileExtension;
        else :
            $this->filepath = $this->filepath;
        endif;
        return $this->filepath;
    }

    private function hasExtension(): bool
    {
        return (stripos($this->filepath, $this->fileExtension));
    }
}
