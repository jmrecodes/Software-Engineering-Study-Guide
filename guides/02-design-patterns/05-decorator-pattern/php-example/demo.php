<?php

/**
 * Decorator Pattern - PHP 8.3 Complete Example
 * 
 * Run: php demo.php
 */

// ============================================================================
// COMPONENT INTERFACE
// ============================================================================

interface LoggerInterface
{
    public function log(string $message): void;
}

// ============================================================================
// CONCRETE COMPONENT
// ============================================================================

class BasicLogger implements LoggerInterface
{
    public function log(string $message): void
    {
        echo $message . "\n";
    }
}

// ============================================================================
// BASE DECORATOR
// ============================================================================

abstract class LoggerDecorator implements LoggerInterface
{
    public function __construct(protected LoggerInterface $logger) {}
    
    public function log(string $message): void
    {
        $this->logger->log($message);
    }
}

// ============================================================================
// CONCRETE DECORATORS
// ============================================================================

class TimestampDecorator extends LoggerDecorator
{
    public function log(string $message): void
    {
        $timestamp = date('[Y-m-d H:i:s]');
        $this->logger->log("{$timestamp} {$message}");
    }
}

class ColorDecorator extends LoggerDecorator
{
    public function __construct(
        LoggerInterface $logger,
        private string $color = '32' // Default green
    ) {
        parent::__construct($logger);
    }
    
    public function log(string $message): void
    {
        $this->logger->log("\033[{$this->color}m{$message}\033[0m");
    }
}

class UppercaseDecorator extends LoggerDecorator
{
    public function log(string $message): void
    {
        $this->logger->log(strtoupper($message));
    }
}

class PrefixDecorator extends LoggerDecorator
{
    public function __construct(
        LoggerInterface $logger,
        private string $prefix
    ) {
        parent::__construct($logger);
    }
    
    public function log(string $message): void
    {
        $this->logger->log("[{$this->prefix}] {$message}");
    }
}

class JsonDecorator extends LoggerDecorator
{
    public function log(string $message): void
    {
        $json = json_encode([
            'timestamp' => time(),
            'message' => $message,
            'level' => 'info',
        ]);
        $this->logger->log($json);
    }
}

class FileDecorator extends LoggerDecorator
{
    public function __construct(
        LoggerInterface $logger,
        private string $filename
    ) {
        parent::__construct($logger);
    }
    
    public function log(string $message): void
    {
        file_put_contents($this->filename, $message . "\n", FILE_APPEND);
        $this->logger->log($message);
    }
}

// ============================================================================
// USAGE DEMONSTRATION
// ============================================================================

echo "╔══════════════════════════════════════════════════════════════╗\n";
echo "║            Decorator Pattern - Logger Demo                   ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n\n";

// 1. Basic logger
echo "1️⃣  Basic Logger:\n";
$logger = new BasicLogger();
$logger->log('This is a basic log message');

echo "\n" . str_repeat('-', 60) . "\n\n";

// 2. Logger with timestamp
echo "2️⃣  Logger with Timestamp Decorator:\n";
$logger = new TimestampDecorator(new BasicLogger());
$logger->log('This message has a timestamp');

echo "\n" . str_repeat('-', 60) . "\n\n";

// 3. Logger with color
echo "3️⃣  Logger with Color Decorator (Green):\n";
$logger = new ColorDecorator(new BasicLogger(), '32');
$logger->log('This message is colored');

echo "\n" . str_repeat('-', 60) . "\n\n";

// 4. Combining decorators: timestamp + color
echo "4️⃣  Combined: Timestamp + Color:\n";
$logger = new ColorDecorator(
    new TimestampDecorator(new BasicLogger()),
    '34' // Blue
);
$logger->log('Timestamp and color combined');

echo "\n" . str_repeat('-', 60) . "\n\n";

// 5. Multiple decorators
echo "5️⃣  Multiple Decorators: Prefix + Timestamp + Color + Uppercase:\n";
$logger = new UppercaseDecorator(
    new ColorDecorator(
        new TimestampDecorator(
            new PrefixDecorator(new BasicLogger(), 'ERROR')
        ),
        '31' // Red
    )
);
$logger->log('Critical system error detected');

echo "\n" . str_repeat('-', 60) . "\n\n";

// 6. JSON formatted logger
echo "6️⃣  JSON Decorator:\n";
$logger = new JsonDecorator(new BasicLogger());
$logger->log('User logged in successfully');

echo "\n" . str_repeat('-', 60) . "\n\n";

// 7. File logger (writes to file AND console)
echo "7️⃣  File Decorator (writes to file AND console):\n";
$logger = new FileDecorator(
    new TimestampDecorator(new BasicLogger()),
    'app.log'
);
$logger->log('This message is logged to file and console');

echo "\n" . str_repeat('-', 60) . "\n\n";

// 8. Dynamic decorator composition
echo "8️⃣  Dynamic Composition:\n";

function createLogger(array $features): LoggerInterface
{
    $logger = new BasicLogger();
    
    foreach ($features as $feature => $config) {
        $logger = match($feature) {
            'timestamp' => new TimestampDecorator($logger),
            'color' => new ColorDecorator($logger, $config ?? '32'),
            'uppercase' => new UppercaseDecorator($logger),
            'prefix' => new PrefixDecorator($logger, $config),
            'json' => new JsonDecorator($logger),
            'file' => new FileDecorator($logger, $config),
            default => $logger
        };
    }
    
    return $logger;
}

$features = [
    'prefix' => 'INFO',
    'timestamp' => true,
    'color' => '36', // Cyan
];

$logger = createLogger($features);
$logger->log('Dynamically composed logger');

echo "\n╔══════════════════════════════════════════════════════════════╗\n";
echo "║                    ✨ Demo Complete ✨                        ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n\n";

echo "Key Benefits Demonstrated:\n";
echo "  ✅ No class explosion - only " . (5 + 1) . " decorator classes\n";
echo "  ✅ Unlimited combinations without new classes\n";
echo "  ✅ Add/remove features at runtime\n";
echo "  ✅ Each decorator has single responsibility\n";
echo "  ✅ Order of decorators can be changed\n";

// Show log file if created
if (file_exists('app.log')) {
    echo "\n📄 Contents of app.log:\n";
    echo file_get_contents('app.log');
    unlink('app.log'); // Clean up
}
