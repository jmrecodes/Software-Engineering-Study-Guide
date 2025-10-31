<?php

declare(strict_types=1);

/**
 * Hash Map Implementation from Scratch
 * 
 * Collision Handling Strategy: Chaining with Linked Lists
 * 
 * Time Complexity (Average Case):
 * - Insert: O(1)
 * - Delete: O(1)
 * - Search: O(1)
 * 
 * Time Complexity (Worst Case - all keys hash to same bucket):
 * - Insert: O(n)
 * - Delete: O(n)
 * - Search: O(n)
 * 
 * Space Complexity: O(n)
 * 
 * Load Factor: n / capacity (when > 0.75, resize)
 */

/**
 * Node for chaining in hash table
 */
class HashNode
{
    public function __construct(
        public string $key,
        public mixed $value,
        public ?HashNode $next = null
    ) {}
}

class HashMap
{
    private array $buckets;
    private int $capacity;
    private int $size = 0;
    private float $loadFactorThreshold = 0.75;

    public function __construct(int $initialCapacity = 16)
    {
        $this->capacity = $initialCapacity;
        // Initialize buckets array with null values
        $this->buckets = array_fill(0, $initialCapacity, null);
    }

    /**
     * Hash function using PHP's built-in hash
     * 
     * Good hash function properties:
     * 1. Deterministic (same key -> same hash)
     * 2. Uniform distribution
     * 3. Fast to compute
     */
    private function hash(string $key): int
    {
        // Use CRC32 hash and modulo to fit within bucket size
        // Bitwise AND with PHP_INT_MAX to ensure positive value
        $hashCode = crc32($key) & PHP_INT_MAX;
        return $hashCode % $this->capacity;
    }

    /**
     * Insert or update key-value pair
     * Time: O(1) average, O(n) worst case
     */
    public function put(string $key, mixed $value): void
    {
        // Check if we need to resize
        if ($this->size / $this->capacity > $this->loadFactorThreshold) {
            $this->resize();
        }

        $index = $this->hash($key);
        $node = $this->buckets[$index];

        // If bucket is empty, create new node
        if ($node === null) {
            $this->buckets[$index] = new HashNode($key, $value);
            $this->size++;
            return;
        }

        // Traverse the chain to find key or reach end
        $prev = null;
        while ($node !== null) {
            // If key exists, update value
            if ($node->key === $key) {
                $node->value = $value;
                return;
            }
            $prev = $node;
            $node = $node->next;
        }

        // Key doesn't exist, add to end of chain
        $prev->next = new HashNode($key, $value);
        $this->size++;
    }

    /**
     * Get value by key
     * Time: O(1) average, O(n) worst case
     */
    public function get(string $key): mixed
    {
        $index = $this->hash($key);
        $node = $this->buckets[$index];

        // Traverse chain to find key
        while ($node !== null) {
            if ($node->key === $key) {
                return $node->value;
            }
            $node = $node->next;
        }

        // Key not found
        throw new OutOfBoundsException("Key not found: $key");
    }

    /**
     * Check if key exists
     * Time: O(1) average, O(n) worst case
     */
    public function has(string $key): bool
    {
        $index = $this->hash($key);
        $node = $this->buckets[$index];

        while ($node !== null) {
            if ($node->key === $key) {
                return true;
            }
            $node = $node->next;
        }

        return false;
    }

    /**
     * Remove key-value pair
     * Time: O(1) average, O(n) worst case
     */
    public function delete(string $key): bool
    {
        $index = $this->hash($key);
        $node = $this->buckets[$index];

        if ($node === null) {
            return false;
        }

        // Special case: key is in first node
        if ($node->key === $key) {
            $this->buckets[$index] = $node->next;
            $this->size--;
            return true;
        }

        // Find key in chain
        $prev = $node;
        $node = $node->next;
        
        while ($node !== null) {
            if ($node->key === $key) {
                // Remove node by updating previous node's next pointer
                $prev->next = $node->next;
                $this->size--;
                return true;
            }
            $prev = $node;
            $node = $node->next;
        }

        return false;
    }

    /**
     * Resize hash table when load factor exceeds threshold
     * This maintains O(1) average time complexity
     * Time: O(n)
     */
    private function resize(): void
    {
        $oldBuckets = $this->buckets;
        $this->capacity *= 2; // Double the capacity
        $this->buckets = array_fill(0, $this->capacity, null);
        $this->size = 0;

        // Rehash all existing entries
        foreach ($oldBuckets as $node) {
            while ($node !== null) {
                $this->put($node->key, $node->value);
                $node = $node->next;
            }
        }
    }

    /**
     * Get all keys
     */
    public function keys(): array
    {
        $keys = [];
        foreach ($this->buckets as $node) {
            while ($node !== null) {
                $keys[] = $node->key;
                $node = $node->next;
            }
        }
        return $keys;
    }

    /**
     * Get all values
     */
    public function values(): array
    {
        $values = [];
        foreach ($this->buckets as $node) {
            while ($node !== null) {
                $values[] = $node->value;
                $node = $node->next;
            }
        }
        return $values;
    }

    /**
     * Get current size
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * Get current capacity
     */
    public function getCapacity(): int
    {
        return $this->capacity;
    }

    /**
     * Get current load factor
     */
    public function getLoadFactor(): float
    {
        return $this->size / $this->capacity;
    }

    /**
     * Debug: Show distribution of elements across buckets
     */
    public function getDistribution(): array
    {
        $distribution = [];
        foreach ($this->buckets as $index => $node) {
            $count = 0;
            $current = $node;
            while ($current !== null) {
                $count++;
                $current = $current->next;
            }
            if ($count > 0) {
                $distribution[$index] = $count;
            }
        }
        return $distribution;
    }
}

// Example usage and collision demonstration
if (basename(__FILE__) === basename($_SERVER['PHP_SELF'] ?? '')) {
    echo "=== Hash Map Basic Operations ===\n";
    $map = new HashMap(4); // Small capacity to demonstrate resizing
    
    $map->put("name", "John");
    $map->put("age", 30);
    $map->put("city", "New York");
    $map->put("country", "USA");
    
    echo "Name: " . $map->get("name") . "\n";
    echo "Age: " . $map->get("age") . "\n";
    echo "Size: " . $map->getSize() . "\n";
    echo "Capacity: " . $map->getCapacity() . "\n";
    echo "Load Factor: " . round($map->getLoadFactor(), 2) . "\n";
    
    echo "\n=== Collision Handling Demo ===\n";
    $map->put("email", "john@example.com");
    $map->put("phone", "123-456-7890");
    
    echo "Distribution across buckets: " . json_encode($map->getDistribution()) . "\n";
    echo "All keys: " . json_encode($map->keys()) . "\n";
    
    echo "\n=== Update and Delete ===\n";
    $map->put("age", 31); // Update
    echo "Updated age: " . $map->get("age") . "\n";
    
    $map->delete("city");
    echo "Has city: " . ($map->has("city") ? "Yes" : "No") . "\n";
    echo "Final size: " . $map->getSize() . "\n";
}
