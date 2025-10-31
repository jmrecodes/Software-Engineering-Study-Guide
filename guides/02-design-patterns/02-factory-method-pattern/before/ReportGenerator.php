<?php

/**
 * BEFORE: The Problem
 * 
 * This code demonstrates what happens WITHOUT the Factory Method Pattern.
 * Notice the tight coupling and conditional logic for object creation.
 */

// Product classes
class PdfReport
{
    public function __construct(private string $title, private array $data) {}
    
    public function generate(): string
    {
        return "PDF Report: {$this->title}\n" . 
               "Data: " . json_encode($this->data) . "\n" .
               "Format: Binary PDF\n";
    }
}

class ExcelReport
{
    public function __construct(private string $title, private array $data) {}
    
    public function generate(): string
    {
        return "Excel Report: {$this->title}\n" .
               "Data: " . json_encode($this->data) . "\n" .
               "Format: XLSX Spreadsheet\n";
    }
}

class CsvReport
{
    public function __construct(private string $title, private array $data) {}
    
    public function generate(): string
    {
        return "CSV Report: {$this->title}\n" .
               "Data: " . json_encode($this->data) . "\n" .
               "Format: Comma Separated Values\n";
    }
}

// Client code with problematic object creation
class ReportService
{
    public function createReport(string $type, string $title, array $data): object
    {
        // PROBLEM: Hardcoded instantiation with conditionals
        if ($type === 'pdf') {
            return new PdfReport($title, $data);
        } elseif ($type === 'excel') {
            return new ExcelReport($title, $data);
        } elseif ($type === 'csv') {
            return new CsvReport($title, $data);
        } else {
            throw new Exception("Unknown report type: {$type}");
        }
    }
    
    public function generateReport(string $type, string $title, array $data): string
    {
        $report = $this->createReport($type, $title, $data);
        return $report->generate();
    }
}

// Usage
$service = new ReportService();

echo "=== PROBLEMATIC APPROACH (Before Factory Method Pattern) ===\n\n";

$data = [
    ['Name' => 'John Doe', 'Sales' => 15000],
    ['Name' => 'Jane Smith', 'Sales' => 23000],
    ['Name' => 'Bob Johnson', 'Sales' => 18500],
];

echo $service->generateReport('pdf', 'Q1 Sales Report', $data);
echo "\n";
echo $service->generateReport('excel', 'Q1 Sales Report', $data);
echo "\n";
echo $service->generateReport('csv', 'Q1 Sales Report', $data);

/**
 * PROBLEMS WITH THIS APPROACH:
 * 
 * 1. Tight Coupling
 *    - ReportService directly depends on concrete PdfReport, ExcelReport, CsvReport classes
 *    - Changing any report class might require changes to ReportService
 * 
 * 2. Violation of Open/Closed Principle
 *    - Adding a new report type requires modifying ReportService
 *    - Can't extend without modification
 * 
 * 3. Violation of Single Responsibility Principle
 *    - ReportService knows too much about object creation
 *    - Mixed concerns: business logic + object instantiation
 * 
 * 4. No Common Interface
 *    - Report objects don't share a common interface
 *    - Can't guarantee they all have a generate() method
 *    - No type safety
 * 
 * 5. Hard to Test
 *    - Can't mock report creation
 *    - Must test with real report objects
 * 
 * 6. Code Duplication
 *    - Conditional logic repeated wherever reports are created
 * 
 * 7. String-Based Selection
 *    - Typos won't be caught until runtime
 *    - No IDE autocomplete support
 */
