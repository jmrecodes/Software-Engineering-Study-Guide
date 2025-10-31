<?php

require_once 'Report.php';

abstract class ReportFactory
{
    // Factory Method - subclasses must implement this
    abstract protected function createReport(string $title, array $data): Report;
    
    // Template method that uses the factory method
    public function generate(string $title, array $data): string
    {
        $report = $this->createReport($title, $data);
        return $report->generate();
    }
    
    public function getReportFormat(string $title, array $data): string
    {
        $report = $this->createReport($title, $data);
        return $report->getFormat();
    }
}
