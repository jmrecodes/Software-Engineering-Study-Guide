<?php

require_once 'Report.php';

class PdfReport implements Report
{
    public function __construct(
        private string $title,
        private array $data
    ) {}
    
    public function generate(): string
    {
        $content = "=== PDF REPORT ===\n";
        $content .= "Title: {$this->title}\n";
        $content .= "Format: Binary PDF\n";
        $content .= "Pages: " . ceil(count($this->data) / 20) . "\n\n";
        $content .= "Data:\n";
        
        foreach ($this->data as $row) {
            $content .= "  - " . json_encode($row) . "\n";
        }
        
        return $content;
    }
    
    public function getFormat(): string
    {
        return 'PDF';
    }
}
