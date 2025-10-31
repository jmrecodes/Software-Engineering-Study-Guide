<?php

require_once 'Report.php';

class ExcelReport implements Report
{
    public function __construct(
        private string $title,
        private array $data
    ) {}
    
    public function generate(): string
    {
        $content = "=== EXCEL REPORT ===\n";
        $content .= "Title: {$this->title}\n";
        $content .= "Format: XLSX Spreadsheet\n";
        $content .= "Worksheets: 1\n";
        $content .= "Rows: " . count($this->data) . "\n\n";
        $content .= "Data:\n";
        
        foreach ($this->data as $index => $row) {
            $content .= "  Row " . ($index + 1) . ": " . json_encode($row) . "\n";
        }
        
        return $content;
    }
    
    public function getFormat(): string
    {
        return 'XLSX';
    }
}
