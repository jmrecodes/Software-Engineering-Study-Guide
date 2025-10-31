<?php

require_once 'ReportFactory.php';
require_once 'ExcelReport.php';

class ExcelReportFactory extends ReportFactory
{
    protected function createReport(string $title, array $data): Report
    {
        return new ExcelReport($title, $data);
    }
}
