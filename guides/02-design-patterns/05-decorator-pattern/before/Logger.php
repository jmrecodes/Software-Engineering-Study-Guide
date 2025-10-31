<?php

/**
 * BEFORE: The Problem
 * 
 * This code demonstrates what happens WITHOUT the Decorator Pattern.
 * Notice the class explosion when using inheritance for combinations.
 */

// Base class
class Logger
{
    public function log(string $message): void
    {
        echo $message . "\n";
    }
}

// PROBLEM: Need a class for every combination!

class TimestampLogger extends Logger
{
    public function log(string $message): void
    {
        $timestamp = date('[Y-m-d H:i:s]');
        echo "{$timestamp} {$message}\n";
    }
}

class ColorLogger extends Logger
{
    public function log(string $message): void
    {
        echo "\033[32m{$message}\033[0m\n"; // Green color
    }
}

class UppercaseLogger extends Logger
{
    public function log(string $message): void
    {
        echo strtoupper($message) . "\n";
    }
}

// What if we want timestamp AND color?
class TimestampColorLogger extends Logger
{
    public function log(string $message): void
    {
        $timestamp = date('[Y-m-d H:i:s]');
        echo "\033[32m{$timestamp} {$message}\033[0m\n";
    }
}

// What if we want timestamp AND uppercase?
class TimestampUppercaseLogger extends Logger
{
    public function log(string $message): void
    {
        $timestamp = date('[Y-m-d H:i:s]');
        echo "{$timestamp} " . strtoupper($message) . "\n";
    }
}

// What if we want color AND uppercase?
class ColorUppercaseLogger extends Logger
{
    public function log(string $message): void
    {
        echo "\033[32m" . strtoupper($message) . "\033[0m\n";
    }
}

// What if we want ALL THREE?
class TimestampColorUppercaseLogger extends Logger
{
    public function log(string $message): void
    {
        $timestamp = date('[Y-m-d H:i:s]');
        echo "\033[32m{$timestamp} " . strtoupper($message) . "\033[0m\n";
    }
}

// And what if we add a 4th feature? 5th? 6th?
// The number of classes EXPLODES exponentially!

echo "=== PROBLEMATIC APPROACH (Before Decorator Pattern) ===\n\n";

echo "Basic Logger:\n";
$logger1 = new Logger();
$logger1->log('Basic log message');

echo "\nTimestamp Logger:\n";
$logger2 = new TimestampLogger();
$logger2->log('Message with timestamp');

echo "\nColor Logger:\n";
$logger3 = new ColorLogger();
$logger3->log('Colored message');

echo "\nTimestamp + Color Logger:\n";
$logger4 = new TimestampColorLogger();
$logger4->log('Timestamp and color message');

echo "\nTimestamp + Color + Uppercase Logger:\n";
$logger5 = new TimestampColorUppercaseLogger();
$logger5->log('All features message');

/**
 * PROBLEMS WITH THIS APPROACH:
 * 
 * 1. Class Explosion
 *    - With n features, we need 2^n classes for all combinations
 *    - 3 features = 7 classes, 4 features = 15 classes, 5 features = 31 classes!
 * 
 * 2. Code Duplication
 *    - Each combination class duplicates logic from others
 *    - Hard to maintain when features change
 * 
 * 3. Violation of Open/Closed Principle
 *    - Adding a new feature requires creating many new classes
 *    - Can't extend without modification
 * 
 * 4. Violation of Single Responsibility Principle
 *    - Classes handling multiple concerns
 * 
 * 5. No Runtime Flexibility
 *    - Can't add/remove features at runtime
 *    - Must decide at instantiation time
 * 
 * 6. Hard to Test
 *    - Must test every combination
 *    - Exponential test cases
 * 
 * 7. Rigid Structure
 *    - Can't change the order of features
 *    - Can't conditionally apply features
 * 
 * 8. Maintenance Nightmare
 *    - Changing one feature means updating multiple classes
 *    - Bug fixes must be applied to many classes
 */
