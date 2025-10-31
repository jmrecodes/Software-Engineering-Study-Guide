/**
 * Node class for Singly Linked List
 */
class SinglyNode {
    constructor(data, next = null) {
        this.data = data;
        this.next = next;
    }
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
class SinglyLinkedList {
    constructor() {
        this.head = null;
        this.tail = null;
        this.size = 0;
    }

    /**
     * Insert at the beginning of the list
     * Time: O(1)
     */
    insertAtHead(data) {
        const newNode = new SinglyNode(data);
        
        if (this.head === null) {
            // Empty list: new node becomes both head and tail
            this.head = this.tail = newNode;
        } else {
            // Point new node to current head, then update head
            newNode.next = this.head;
            this.head = newNode;
        }
        
        this.size++;
    }

    /**
     * Insert at the end of the list
     * Time: O(1) - thanks to tail pointer
     */
    insertAtTail(data) {
        const newNode = new SinglyNode(data);
        
        if (this.tail === null) {
            // Empty list
            this.head = this.tail = newNode;
        } else {
            // Link current tail to new node, update tail
            this.tail.next = newNode;
            this.tail = newNode;
        }
        
        this.size++;
    }

    /**
     * Delete first occurrence of a value
     * Time: O(n)
     */
    delete(data) {
        if (this.head === null) {
            return false;
        }

        // Special case: deleting head
        if (this.head.data === data) {
            this.head = this.head.next;
            if (this.head === null) {
                this.tail = null; // List is now empty
            }
            this.size--;
            return true;
        }

        // Traverse to find the node before the one to delete
        let current = this.head;
        while (current.next !== null && current.next.data !== data) {
            current = current.next;
        }

        if (current.next === null) {
            return false; // Not found
        }

        // Remove the node by updating pointers
        const nodeToDelete = current.next;
        current.next = nodeToDelete.next;
        
        // Update tail if we deleted the last node
        if (nodeToDelete === this.tail) {
            this.tail = current;
        }
        
        this.size--;
        return true;
    }

    /**
     * Search for a value
     * Time: O(n)
     */
    search(data) {
        let current = this.head;
        while (current !== null) {
            if (current.data === data) {
                return true;
            }
            current = current.next;
        }
        return false;
    }

    /**
     * Convert list to array for easy viewing
     */
    toArray() {
        const result = [];
        let current = this.head;
        while (current !== null) {
            result.push(current.data);
            current = current.next;
        }
        return result;
    }

    getSize() {
        return this.size;
    }
}

/**
 * Node class for Doubly Linked List
 */
class DoublyNode {
    constructor(data, next = null, prev = null) {
        this.data = data;
        this.next = next;
        this.prev = prev;
    }
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
class DoublyLinkedList {
    constructor() {
        this.head = null;
        this.tail = null;
        this.size = 0;
    }

    /**
     * Insert at the beginning
     * Time: O(1)
     */
    insertAtHead(data) {
        const newNode = new DoublyNode(data);
        
        if (this.head === null) {
            // Empty list
            this.head = this.tail = newNode;
        } else {
            // Link new node to current head
            newNode.next = this.head;
            this.head.prev = newNode;
            this.head = newNode;
        }
        
        this.size++;
    }

    /**
     * Insert at the end
     * Time: O(1)
     */
    insertAtTail(data) {
        const newNode = new DoublyNode(data);
        
        if (this.tail === null) {
            // Empty list
            this.head = this.tail = newNode;
        } else {
            // Link current tail to new node
            this.tail.next = newNode;
            newNode.prev = this.tail;
            this.tail = newNode;
        }
        
        this.size++;
    }

    /**
     * Delete first occurrence of a value
     * Time: O(n) to find, O(1) to delete
     */
    delete(data) {
        let current = this.head;
        
        while (current !== null) {
            if (current.data === data) {
                // Found the node to delete
                
                if (current.prev !== null) {
                    // Not the head, link previous node to next
                    current.prev.next = current.next;
                } else {
                    // Deleting head
                    this.head = current.next;
                }
                
                if (current.next !== null) {
                    // Not the tail, link next node to previous
                    current.next.prev = current.prev;
                } else {
                    // Deleting tail
                    this.tail = current.prev;
                }
                
                this.size--;
                return true;
            }
            current = current.next;
        }
        
        return false; // Not found
    }

    /**
     * Traverse forward and return array
     */
    toArray() {
        const result = [];
        let current = this.head;
        while (current !== null) {
            result.push(current.data);
            current = current.next;
        }
        return result;
    }

    /**
     * Traverse backward and return array
     */
    toArrayReverse() {
        const result = [];
        let current = this.tail;
        while (current !== null) {
            result.push(current.data);
            current = current.prev;
        }
        return result;
    }

    getSize() {
        return this.size;
    }
}

// Example usage
if (require.main === module) {
    console.log("=== Singly Linked List Demo ===");
    const sll = new SinglyLinkedList();
    sll.insertAtTail(1);
    sll.insertAtTail(2);
    sll.insertAtHead(0);
    console.log("List:", sll.toArray()); // [0, 1, 2]
    console.log("Search 1:", sll.search(1) ? "Found" : "Not found");
    sll.delete(1);
    console.log("After deleting 1:", sll.toArray()); // [0, 2]
    
    console.log("\n=== Doubly Linked List Demo ===");
    const dll = new DoublyLinkedList();
    dll.insertAtTail(10);
    dll.insertAtTail(20);
    dll.insertAtHead(5);
    console.log("Forward:", dll.toArray()); // [5, 10, 20]
    console.log("Reverse:", dll.toArrayReverse()); // [20, 10, 5]
}

module.exports = { SinglyLinkedList, DoublyLinkedList, SinglyNode, DoublyNode };
