<?php

declare(strict_types=1);

/**
 * Queue Implementation using Array
 * 
 * FIFO (First In, First Out) data structure
 * 
 * Time Complexity:
 * - Enqueue: O(1)
 * - Dequeue: O(1) amortized
 * - Peek: O(1)
 * 
 * Space Complexity: O(n)
 * 
 * Use Cases:
 * - Task scheduling
 * - BFS in graphs
 * - Request handling
 * - Print queue
 */
class Queue
{
    private array $items = [];
    private int $front = 0; // Track front index to avoid array_shift overhead

    /**
     * Add element to back of queue
     * Time: O(1)
     */
    public function enqueue(mixed $item): void
    {
        $this->items[] = $item;
    }

    /**
     * Remove and return front element
     * Time: O(1) amortized (periodically resets array)
     */
    public function dequeue(): mixed
    {
        if ($this->isEmpty()) {
            throw new UnderflowException("Queue is empty");
        }
        
        $item = $this->items[$this->front];
        unset($this->items[$this->front]);
        $this->front++;
        
        // Periodically reset array to prevent memory waste
        if ($this->front > 100 && $this->front > count($this->items)) {
            $this->items = array_values($this->items);
            $this->front = 0;
        }
        
        return $item;
    }

    /**
     * Return front element without removing it
     * Time: O(1)
     */
    public function peek(): mixed
    {
        if ($this->isEmpty()) {
            throw new UnderflowException("Queue is empty");
        }
        
        return $this->items[$this->front];
    }

    /**
     * Check if queue is empty
     * Time: O(1)
     */
    public function isEmpty(): bool
    {
        return !isset($this->items[$this->front]);
    }

    /**
     * Get current size of queue
     * Time: O(1)
     */
    public function getSize(): int
    {
        return count($this->items) - $this->front;
    }

    /**
     * Clear all elements
     */
    public function clear(): void
    {
        $this->items = [];
        $this->front = 0;
    }

    /**
     * Convert to array (front to back)
     */
    public function toArray(): array
    {
        return array_values(array_slice($this->items, $this->front));
    }
}

/**
 * Queue Implementation using Linked List
 * 
 * More efficient for frequent dequeue operations
 * No need for periodic array resets
 */
class QueueNode
{
    public function __construct(
        public mixed $data,
        public ?QueueNode $next = null
    ) {}
}

class LinkedQueue
{
    private ?QueueNode $front = null;
    private ?QueueNode $rear = null;
    private int $size = 0;

    /**
     * Add element to back of queue
     * Time: O(1)
     */
    public function enqueue(mixed $data): void
    {
        $newNode = new QueueNode($data);
        
        if ($this->rear === null) {
            // Empty queue: new node is both front and rear
            $this->front = $this->rear = $newNode;
        } else {
            // Link current rear to new node, update rear
            $this->rear->next = $newNode;
            $this->rear = $newNode;
        }
        
        $this->size++;
    }

    /**
     * Remove and return front element
     * Time: O(1)
     */
    public function dequeue(): mixed
    {
        if ($this->isEmpty()) {
            throw new UnderflowException("Queue is empty");
        }
        
        $data = $this->front->data;
        $this->front = $this->front->next;
        
        // If queue becomes empty, update rear
        if ($this->front === null) {
            $this->rear = null;
        }
        
        $this->size--;
        return $data;
    }

    /**
     * Peek at front element
     * Time: O(1)
     */
    public function peek(): mixed
    {
        if ($this->isEmpty()) {
            throw new UnderflowException("Queue is empty");
        }
        
        return $this->front->data;
    }

    public function isEmpty(): bool
    {
        return $this->front === null;
    }

    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * Convert to array (front to back)
     */
    public function toArray(): array
    {
        $result = [];
        $current = $this->front;
        while ($current !== null) {
            $result[] = $current->data;
            $current = $current->next;
        }
        return $result;
    }
}

/**
 * Circular Queue Implementation
 * 
 * Fixed-size queue that wraps around
 * Useful when size limit is known beforehand
 */
class CircularQueue
{
    private array $items;
    private int $front = 0;
    private int $rear = -1;
    private int $size = 0;
    private int $capacity;

    public function __construct(int $capacity)
    {
        $this->capacity = $capacity;
        $this->items = array_fill(0, $capacity, null);
    }

    /**
     * Add element to queue
     * Time: O(1)
     */
    public function enqueue(mixed $item): void
    {
        if ($this->isFull()) {
            throw new OverflowException("Queue is full");
        }
        
        // Move rear pointer circularly
        $this->rear = ($this->rear + 1) % $this->capacity;
        $this->items[$this->rear] = $item;
        $this->size++;
    }

    /**
     * Remove element from queue
     * Time: O(1)
     */
    public function dequeue(): mixed
    {
        if ($this->isEmpty()) {
            throw new UnderflowException("Queue is empty");
        }
        
        $item = $this->items[$this->front];
        $this->items[$this->front] = null;
        // Move front pointer circularly
        $this->front = ($this->front + 1) % $this->capacity;
        $this->size--;
        
        return $item;
    }

    public function peek(): mixed
    {
        if ($this->isEmpty()) {
            throw new UnderflowException("Queue is empty");
        }
        return $this->items[$this->front];
    }

    public function isEmpty(): bool
    {
        return $this->size === 0;
    }

    public function isFull(): bool
    {
        return $this->size === $this->capacity;
    }

    public function getSize(): int
    {
        return $this->size;
    }
}

// Example usage
if (basename(__FILE__) === basename($_SERVER['PHP_SELF'] ?? '')) {
    echo "=== Basic Queue Operations ===\n";
    $queue = new Queue();
    $queue->enqueue("First");
    $queue->enqueue("Second");
    $queue->enqueue("Third");
    echo "Queue: " . json_encode($queue->toArray()) . "\n";
    echo "Dequeue: " . $queue->dequeue() . "\n"; // "First"
    echo "After dequeue: " . json_encode($queue->toArray()) . "\n";
    
    echo "\n=== Linked Queue Demo ===\n";
    $linkedQueue = new LinkedQueue();
    $linkedQueue->enqueue(10);
    $linkedQueue->enqueue(20);
    $linkedQueue->enqueue(30);
    echo "Queue: " . json_encode($linkedQueue->toArray()) . "\n";
    echo "Peek: " . $linkedQueue->peek() . "\n";
    $linkedQueue->dequeue();
    echo "After dequeue: " . json_encode($linkedQueue->toArray()) . "\n";
    
    echo "\n=== Circular Queue Demo ===\n";
    $circularQueue = new CircularQueue(3);
    $circularQueue->enqueue("A");
    $circularQueue->enqueue("B");
    $circularQueue->enqueue("C");
    echo "Dequeue: " . $circularQueue->dequeue() . "\n";
    $circularQueue->enqueue("D");
    echo "Dequeue: " . $circularQueue->dequeue() . "\n";
    echo "Dequeue: " . $circularQueue->dequeue() . "\n";
    echo "Dequeue: " . $circularQueue->dequeue() . "\n";
}
