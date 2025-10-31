<?php

require_once 'Report.php';

class CsvReport implements Report
{
    public function __construct(
        private string $title,
        private array $data
    ) {}
    
    public function generate(): string
    {
        $content = "=== CSV REPORT ===\n";
        $content .= "Title: {$this->title}\n";
        $content .= "Format: Comma Separated Values\n";
        $content .= "Records: " . count($this->data) . "\n\n";
        $content .= "Data:\n";
        
        if (!empty($this->data)) {
            $headers = array_keys($this->data[0]);
            $content .= "  " . implode(',', $headers) . "\n";
            
            foreach ($this->data as $row) {
                $content .= "  " . implode(',', $row) . "\n";
            }
        }
        
        return $content;
    }
    
    public function getFormat(): string
    {
        return 'CSV';
    }
}
