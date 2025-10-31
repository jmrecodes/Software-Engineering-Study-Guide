<?php

/**
 * Factory Method Pattern - PHP 8.3 Complete Example
 * 
 * Run: php demo.php
 */

// ============================================================================
// PRODUCT INTERFACE
// ============================================================================

interface Report
{
    public function generate(): string;
    public function getFormat(): string;
}

// ============================================================================
// CONCRETE PRODUCTS
// ============================================================================

class PdfReport implements Report
{
    public function __construct(
        private string $title,
        private array $data
    ) {}
    
    public function generate(): string
    {
        $content = "📄 === PDF REPORT ===\n";
        $content .= "Title: {$this->title}\n";
        $content .= "Format: Binary PDF\n";
        $content .= "Pages: " . ceil(count($this->data) / 20) . "\n\n";
        $content .= "Data:\n";
        
        foreach ($this->data as $row) {
            $content .= "  - " . implode(' | ', $row) . "\n";
        }
        
        return $content;
    }
    
    public function getFormat(): string
    {
        return 'PDF';
    }
}

class ExcelReport implements Report
{
    public function __construct(
        private string $title,
        private array $data
    ) {}
    
    public function generate(): string
    {
        $content = "📊 === EXCEL REPORT ===\n";
        $content .= "Title: {$this->title}\n";
        $content .= "Format: XLSX Spreadsheet\n";
        $content .= "Worksheets: 1\n";
        $content .= "Rows: " . count($this->data) . "\n\n";
        $content .= "Data:\n";
        
        foreach ($this->data as $index => $row) {
            $content .= "  Row " . ($index + 1) . ": " . implode(' | ', $row) . "\n";
        }
        
        return $content;
    }
    
    public function getFormat(): string
    {
        return 'XLSX';
    }
}

class CsvReport implements Report
{
    public function __construct(
        private string $title,
        private array $data
    ) {}
    
    public function generate(): string
    {
        $content = "📋 === CSV REPORT ===\n";
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

// ============================================================================
// CREATOR (Abstract Factory)
// ============================================================================

abstract class ReportFactory
{
    // Factory Method - subclasses must implement this
    abstract protected function createReport(string $title, array $data): Report;
    
    // Template method that uses the factory method
    public function generate(string $title, array $data): string
    {
        $report = $this->createReport($title, $data);
        echo "🏭 Creating {$report->getFormat()} report via " . static::class . "\n\n";
        return $report->generate();
    }
}

// ============================================================================
// CONCRETE CREATORS
// ============================================================================

class PdfReportFactory extends ReportFactory
{
    protected function createReport(string $title, array $data): Report
    {
        return new PdfReport($title, $data);
    }
}

class ExcelReportFactory extends ReportFactory
{
    protected function createReport(string $title, array $data): Report
    {
        return new ExcelReport($title, $data);
    }
}

class CsvReportFactory extends ReportFactory
{
    protected function createReport(string $title, array $data): Report
    {
        return new CsvReport($title, $data);
    }
}

// ============================================================================
// USAGE DEMONSTRATION
// ============================================================================

echo "╔══════════════════════════════════════════════════════════════╗\n";
echo "║        Factory Method Pattern - Report Generator Demo        ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n\n";

// Sample data
$salesData = [
    ['Name' => 'John Doe', 'Sales' => 15000, 'Region' => 'North'],
    ['Name' => 'Jane Smith', 'Sales' => 23000, 'Region' => 'South'],
    ['Name' => 'Bob Johnson', 'Sales' => 18500, 'Region' => 'East'],
    ['Name' => 'Alice Williams', 'Sales' => 31000, 'Region' => 'West'],
];

// Generate PDF Report
$pdfFactory = new PdfReportFactory();
echo $pdfFactory->generate('Q1 2024 Sales Report', $salesData);
echo "\n" . str_repeat('-', 60) . "\n\n";

// Generate Excel Report
$excelFactory = new ExcelReportFactory();
echo $excelFactory->generate('Q1 2024 Sales Report', $salesData);
echo "\n" . str_repeat('-', 60) . "\n\n";

// Generate CSV Report
$csvFactory = new CsvReportFactory();
echo $csvFactory->generate('Q1 2024 Sales Report', $salesData);
echo "\n" . str_repeat('-', 60) . "\n\n";

// Dynamic factory selection based on user preference
echo "🔄 Dynamic Factory Selection:\n\n";

function getReportFactory(string $format): ReportFactory
{
    return match(strtolower($format)) {
        'pdf' => new PdfReportFactory(),
        'excel', 'xlsx' => new ExcelReportFactory(),
        'csv' => new CsvReportFactory(),
        default => throw new Exception("Unsupported format: {$format}")
    };
}

$userPreference = 'excel'; // Could come from user input or config
$factory = getReportFactory($userPreference);
echo $factory->generate('User Requested Report', $salesData);

echo "\n╔══════════════════════════════════════════════════════════════╗\n";
echo "║                    ✨ Demo Complete ✨                        ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n\n";

echo "Key Benefits Demonstrated:\n";
echo "  ✅ Decoupled object creation from business logic\n";
echo "  ✅ Easy to add new report types without modifying existing code\n";
echo "  ✅ Each factory is responsible for one product type\n";
echo "  ✅ Type-safe with interfaces\n";
echo "  ✅ Testable - can mock factories easily\n";
