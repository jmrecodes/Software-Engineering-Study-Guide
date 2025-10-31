<?php

declare(strict_types=1);

/**
 * Stack Implementation using Array
 * 
 * LIFO (Last In, First Out) data structure
 * 
 * Time Complexity:
 * - Push: O(1)
 * - Pop: O(1)
 * - Peek: O(1)
 * - Search: O(n)
 * 
 * Space Complexity: O(n)
 * 
 * Use Cases:
 * - Function call stack
 * - Undo mechanisms
 * - Expression evaluation
 * - Backtracking algorithms
 */
class Stack
{
    private array $items = [];
    private int $size = 0;

    /**
     * Add element to top of stack
     * Time: O(1)
     */
    public function push(mixed $item): void
    {
        $this->items[] = $item;
        $this->size++;
    }

    /**
     * Remove and return top element
     * Time: O(1)
     * 
     * @throws UnderflowException if stack is empty
     */
    public function pop(): mixed
    {
        if ($this->isEmpty()) {
            throw new UnderflowException("Stack is empty");
        }
        
        $this->size--;
        return array_pop($this->items);
    }

    /**
     * Return top element without removing it
     * Time: O(1)
     * 
     * @throws UnderflowException if stack is empty
     */
    public function peek(): mixed
    {
        if ($this->isEmpty()) {
            throw new UnderflowException("Stack is empty");
        }
        
        return $this->items[$this->size - 1];
    }

    /**
     * Check if stack is empty
     * Time: O(1)
     */
    public function isEmpty(): bool
    {
        return $this->size === 0;
    }

    /**
     * Get current size of stack
     * Time: O(1)
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * Clear all elements from stack
     * Time: O(1)
     */
    public function clear(): void
    {
        $this->items = [];
        $this->size = 0;
    }

    /**
     * Convert stack to array (bottom to top)
     */
    public function toArray(): array
    {
        return $this->items;
    }
}

/**
 * Stack Implementation using Linked List
 * 
 * More memory efficient for large stacks as it doesn't need contiguous memory
 */
class StackNode
{
    public function __construct(
        public mixed $data,
        public ?StackNode $next = null
    ) {}
}

class LinkedStack
{
    private ?StackNode $top = null;
    private int $size = 0;

    /**
     * Push element onto stack
     * Time: O(1)
     */
    public function push(mixed $data): void
    {
        // Create new node and make it point to current top
        $newNode = new StackNode($data, $this->top);
        // Update top to new node
        $this->top = $newNode;
        $this->size++;
    }

    /**
     * Pop element from stack
     * Time: O(1)
     */
    public function pop(): mixed
    {
        if ($this->isEmpty()) {
            throw new UnderflowException("Stack is empty");
        }
        
        // Get data from top node
        $data = $this->top->data;
        // Move top pointer to next node
        $this->top = $this->top->next;
        $this->size--;
        
        return $data;
    }

    /**
     * Peek at top element
     * Time: O(1)
     */
    public function peek(): mixed
    {
        if ($this->isEmpty()) {
            throw new UnderflowException("Stack is empty");
        }
        
        return $this->top->data;
    }

    public function isEmpty(): bool
    {
        return $this->top === null;
    }

    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * Convert to array (top to bottom)
     */
    public function toArray(): array
    {
        $result = [];
        $current = $this->top;
        while ($current !== null) {
            $result[] = $current->data;
            $current = $current->next;
        }
        return $result;
    }
}

// Example usage and practical applications
if (basename(__FILE__) === basename($_SERVER['PHP_SELF'] ?? '')) {
    echo "=== Basic Stack Operations ===\n";
    $stack = new Stack();
    $stack->push(10);
    $stack->push(20);
    $stack->push(30);
    echo "Stack: " . json_encode($stack->toArray()) . "\n"; // [10, 20, 30]
    echo "Peek: " . $stack->peek() . "\n"; // 30
    echo "Pop: " . $stack->pop() . "\n"; // 30
    echo "After pop: " . json_encode($stack->toArray()) . "\n"; // [10, 20]
    
    echo "\n=== Practical Example: Balanced Parentheses ===\n";
    function isBalanced(string $expression): bool {
        $stack = new Stack();
        $pairs = ['(' => ')', '[' => ']', '{' => '}'];
        
        for ($i = 0; $i < strlen($expression); $i++) {
            $char = $expression[$i];
            
            // If opening bracket, push to stack
            if (isset($pairs[$char])) {
                $stack->push($char);
            }
            // If closing bracket, check if it matches
            elseif (in_array($char, $pairs)) {
                if ($stack->isEmpty()) {
                    return false;
                }
                $top = $stack->pop();
                if ($pairs[$top] !== $char) {
                    return false;
                }
            }
        }
        
        // Stack should be empty if balanced
        return $stack->isEmpty();
    }
    
    $test1 = "{[()]}";
    $test2 = "{[(])}";
    echo "$test1 is " . (isBalanced($test1) ? "balanced" : "not balanced") . "\n";
    echo "$test2 is " . (isBalanced($test2) ? "balanced" : "not balanced") . "\n";
    
    echo "\n=== Linked Stack Demo ===\n";
    $linkedStack = new LinkedStack();
    $linkedStack->push("first");
    $linkedStack->push("second");
    $linkedStack->push("third");
    echo "Stack (top to bottom): " . json_encode($linkedStack->toArray()) . "\n";
    echo "Pop: " . $linkedStack->pop() . "\n";
}
