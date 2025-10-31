<?php

declare(strict_types=1);

/**
 * Node class for Singly Linked List
 */
class SinglyNode
{
    public function __construct(
        public mixed $data,
        public ?SinglyNode $next = null
    ) {}
}

/**
 * Singly Linked List Implementation
 * 
 * Time Complexity:
 * - Insert at head: O(1)
 * - Insert at tail: O(1) with tail pointer
 * - Delete: O(n)
 * - Search: O(n)
 * 
 * Space Complexity: O(n)
 */
class SinglyLinkedList
{
    private ?SinglyNode $head = null;
    private ?SinglyNode $tail = null;
    private int $size = 0;

    /**
     * Insert at the beginning of the list
     * Time: O(1)
     */
    public function insertAtHead(mixed $data): void
    {
        $newNode = new SinglyNode($data);
        
        if ($this->head === null) {
            // Empty list: new node becomes both head and tail
            $this->head = $this->tail = $newNode;
        } else {
            // Point new node to current head, then update head
            $newNode->next = $this->head;
            $this->head = $newNode;
        }
        
        $this->size++;
    }

    /**
     * Insert at the end of the list
     * Time: O(1) - thanks to tail pointer
     */
    public function insertAtTail(mixed $data): void
    {
        $newNode = new SinglyNode($data);
        
        if ($this->tail === null) {
            // Empty list
            $this->head = $this->tail = $newNode;
        } else {
            // Link current tail to new node, update tail
            $this->tail->next = $newNode;
            $this->tail = $newNode;
        }
        
        $this->size++;
    }

    /**
     * Delete first occurrence of a value
     * Time: O(n)
     */
    public function delete(mixed $data): bool
    {
        if ($this->head === null) {
            return false;
        }

        // Special case: deleting head
        if ($this->head->data === $data) {
            $this->head = $this->head->next;
            if ($this->head === null) {
                $this->tail = null; // List is now empty
            }
            $this->size--;
            return true;
        }

        // Traverse to find the node before the one to delete
        $current = $this->head;
        while ($current->next !== null && $current->next->data !== $data) {
            $current = $current->next;
        }

        if ($current->next === null) {
            return false; // Not found
        }

        // Remove the node by updating pointers
        $nodeToDelete = $current->next;
        $current->next = $nodeToDelete->next;
        
        // Update tail if we deleted the last node
        if ($nodeToDelete === $this->tail) {
            $this->tail = $current;
        }
        
        $this->size--;
        return true;
    }

    /**
     * Search for a value
     * Time: O(n)
     */
    public function search(mixed $data): bool
    {
        $current = $this->head;
        while ($current !== null) {
            if ($current->data === $data) {
                return true;
            }
            $current = $current->next;
        }
        return false;
    }

    /**
     * Convert list to array for easy viewing
     */
    public function toArray(): array
    {
        $result = [];
        $current = $this->head;
        while ($current !== null) {
            $result[] = $current->data;
            $current = $current->next;
        }
        return $result;
    }

    public function getSize(): int
    {
        return $this->size;
    }
}

/**
 * Node class for Doubly Linked List
 */
class DoublyNode
{
    public function __construct(
        public mixed $data,
        public ?DoublyNode $next = null,
        public ?DoublyNode $prev = null
    ) {}
}

/**
 * Doubly Linked List Implementation
 * 
 * Advantages over singly linked list:
 * - Can traverse backwards
 * - Easier deletion (no need to track previous node)
 * 
 * Time Complexity:
 * - Insert: O(1)
 * - Delete: O(n) to find, O(1) to delete once found
 * - Search: O(n)
 * 
 * Space Complexity: O(n) - uses more memory due to prev pointer
 */
class DoublyLinkedList
{
    private ?DoublyNode $head = null;
    private ?DoublyNode $tail = null;
    private int $size = 0;

    /**
     * Insert at the beginning
     * Time: O(1)
     */
    public function insertAtHead(mixed $data): void
    {
        $newNode = new DoublyNode($data);
        
        if ($this->head === null) {
            // Empty list
            $this->head = $this->tail = $newNode;
        } else {
            // Link new node to current head
            $newNode->next = $this->head;
            $this->head->prev = $newNode;
            $this->head = $newNode;
        }
        
        $this->size++;
    }

    /**
     * Insert at the end
     * Time: O(1)
     */
    public function insertAtTail(mixed $data): void
    {
        $newNode = new DoublyNode($data);
        
        if ($this->tail === null) {
            // Empty list
            $this->head = $this->tail = $newNode;
        } else {
            // Link current tail to new node
            $this->tail->next = $newNode;
            $newNode->prev = $this->tail;
            $this->tail = $newNode;
        }
        
        $this->size++;
    }

    /**
     * Delete first occurrence of a value
     * Time: O(n) to find, O(1) to delete
     */
    public function delete(mixed $data): bool
    {
        $current = $this->head;
        
        while ($current !== null) {
            if ($current->data === $data) {
                // Found the node to delete
                
                if ($current->prev !== null) {
                    // Not the head, link previous node to next
                    $current->prev->next = $current->next;
                } else {
                    // Deleting head
                    $this->head = $current->next;
                }
                
                if ($current->next !== null) {
                    // Not the tail, link next node to previous
                    $current->next->prev = $current->prev;
                } else {
                    // Deleting tail
                    $this->tail = $current->prev;
                }
                
                $this->size--;
                return true;
            }
            $current = $current->next;
        }
        
        return false; // Not found
    }

    /**
     * Traverse forward and return array
     */
    public function toArray(): array
    {
        $result = [];
        $current = $this->head;
        while ($current !== null) {
            $result[] = $current->data;
            $current = $current->next;
        }
        return $result;
    }

    /**
     * Traverse backward and return array
     */
    public function toArrayReverse(): array
    {
        $result = [];
        $current = $this->tail;
        while ($current !== null) {
            $result[] = $current->data;
            $current = $current->prev;
        }
        return $result;
    }

    public function getSize(): int
    {
        return $this->size;
    }
}

// Example usage
if (basename(__FILE__) === basename($_SERVER['PHP_SELF'] ?? '')) {
    echo "=== Singly Linked List Demo ===\n";
    $sll = new SinglyLinkedList();
    $sll->insertAtTail(1);
    $sll->insertAtTail(2);
    $sll->insertAtHead(0);
    echo "List: " . json_encode($sll->toArray()) . "\n"; // [0, 1, 2]
    echo "Search 1: " . ($sll->search(1) ? "Found" : "Not found") . "\n";
    $sll->delete(1);
    echo "After deleting 1: " . json_encode($sll->toArray()) . "\n"; // [0, 2]
    
    echo "\n=== Doubly Linked List Demo ===\n";
    $dll = new DoublyLinkedList();
    $dll->insertAtTail(10);
    $dll->insertAtTail(20);
    $dll->insertAtHead(5);
    echo "Forward: " . json_encode($dll->toArray()) . "\n"; // [5, 10, 20]
    echo "Reverse: " . json_encode($dll->toArrayReverse()) . "\n"; // [20, 10, 5]
}
