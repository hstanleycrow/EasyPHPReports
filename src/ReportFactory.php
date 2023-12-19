<?php

namespace hstanleycrow\EasyPHPReports;

class ReportFactory
{
    private static array $types = [];

    public static function registerType(string $type, string $className): void
    {
        self::$types[$type] = $className;
    }

    public static function createReport(string $type, array $reportHeadersSetup, array $reportData, ?string $filepath = null): ReportInterface
    {
        if (!isset(self::$types[$type])) {
            throw new \InvalidArgumentException("Invalid report type: $type");
        }

        $className = self::$types[$type];
        return new $className($reportHeadersSetup, $reportData, $filepath);
    }
}
