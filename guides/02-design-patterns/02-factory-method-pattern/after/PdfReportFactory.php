<?php

require_once 'ReportFactory.php';
require_once 'PdfReport.php';

class PdfReportFactory extends ReportFactory
{
    protected function createReport(string $title, array $data): Report
    {
        return new PdfReport($title, $data);
    }
}
