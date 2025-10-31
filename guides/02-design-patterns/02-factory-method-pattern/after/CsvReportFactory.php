<?php

require_once 'ReportFactory.php';
require_once 'CsvReport.php';

class CsvReportFactory extends ReportFactory
{
    protected function createReport(string $title, array $data): Report
    {
        return new CsvReport($title, $data);
    }
}
