/**
 * Queue Implementation using Array
 * 
 * FIFO (First In, First Out) data structure
 * 
 * Time Complexity:
 * - Enqueue: O(1)
 * - Dequeue: O(1)
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
class Queue {
    constructor() {
        this.items = [];
    }

    /**
     * Add element to back of queue
     * Time: O(1)
     */
    enqueue(item) {
        this.items.push(item);
    }

    /**
     * Remove and return front element
     * Time: O(1)
     */
    dequeue() {
        if (this.isEmpty()) {
            throw new Error("Queue is empty");
        }
        return this.items.shift();
    }

    /**
     * Return front element without removing it
     * Time: O(1)
     */
    peek() {
        if (this.isEmpty()) {
            throw new Error("Queue is empty");
        }
        return this.items[0];
    }

    /**
     * Check if queue is empty
     * Time: O(1)
     */
    isEmpty() {
        return this.items.length === 0;
    }

    /**
     * Get current size of queue
     * Time: O(1)
     */
    getSize() {
        return this.items.length;
    }

    /**
     * Clear all elements
     */
    clear() {
        this.items = [];
    }

    /**
     * Convert to array (front to back)
     */
    toArray() {
        return [...this.items];
    }
}

/**
 * Queue Implementation using Linked List
 * 
 * More efficient for frequent dequeue operations
 */
class QueueNode {
    constructor(data, next = null) {
        this.data = data;
        this.next = next;
    }
}

class LinkedQueue {
    constructor() {
        this.front = null;
        this.rear = null;
        this.size = 0;
    }

    /**
     * Add element to back of queue
     * Time: O(1)
     */
    enqueue(data) {
        const newNode = new QueueNode(data);
        
        if (this.rear === null) {
            // Empty queue: new node is both front and rear
            this.front = this.rear = newNode;
        } else {
            // Link current rear to new node, update rear
            this.rear.next = newNode;
            this.rear = newNode;
        }
        
        this.size++;
    }

    /**
     * Remove and return front element
     * Time: O(1)
     */
    dequeue() {
        if (this.isEmpty()) {
            throw new Error("Queue is empty");
        }
        
        const data = this.front.data;
        this.front = this.front.next;
        
        // If queue becomes empty, update rear
        if (this.front === null) {
            this.rear = null;
        }
        
        this.size--;
        return data;
    }

    /**
     * Peek at front element
     * Time: O(1)
     */
    peek() {
        if (this.isEmpty()) {
            throw new Error("Queue is empty");
        }
        return this.front.data;
    }

    isEmpty() {
        return this.front === null;
    }

    getSize() {
        return this.size;
    }

    /**
     * Convert to array (front to back)
     */
    toArray() {
        const result = [];
        let current = this.front;
        while (current !== null) {
            result.push(current.data);
            current = current.next;
        }
        return result;
    }
}

/**
 * Circular Queue Implementation
 * 
 * Fixed-size queue that wraps around
 * Useful when size limit is known beforehand
 */
class CircularQueue {
    constructor(capacity) {
        this.capacity = capacity;
        this.items = new Array(capacity).fill(null);
        this.front = 0;
        this.rear = -1;
        this.size = 0;
    }

    /**
     * Add element to queue
     * Time: O(1)
     */
    enqueue(item) {
        if (this.isFull()) {
            throw new Error("Queue is full");
        }
        
        // Move rear pointer circularly
        this.rear = (this.rear + 1) % this.capacity;
        this.items[this.rear] = item;
        this.size++;
    }

    /**
     * Remove element from queue
     * Time: O(1)
     */
    dequeue() {
        if (this.isEmpty()) {
            throw new Error("Queue is empty");
        }
        
        const item = this.items[this.front];
        this.items[this.front] = null;
        // Move front pointer circularly
        this.front = (this.front + 1) % this.capacity;
        this.size--;
        
        return item;
    }

    peek() {
        if (this.isEmpty()) {
            throw new Error("Queue is empty");
        }
        return this.items[this.front];
    }

    isEmpty() {
        return this.size === 0;
    }

    isFull() {
        return this.size === this.capacity;
    }

    getSize() {
        return this.size;
    }
}

// Example usage
if (require.main === module) {
    console.log("=== Basic Queue Operations ===");
    const queue = new Queue();
    queue.enqueue("First");
    queue.enqueue("Second");
    queue.enqueue("Third");
    console.log("Queue:", queue.toArray());
    console.log("Dequeue:", queue.dequeue()); // "First"
    console.log("After dequeue:", queue.toArray());
    
    console.log("\n=== Linked Queue Demo ===");
    const linkedQueue = new LinkedQueue();
    linkedQueue.enqueue(10);
    linkedQueue.enqueue(20);
    linkedQueue.enqueue(30);
    console.log("Queue:", linkedQueue.toArray());
    console.log("Peek:", linkedQueue.peek());
    linkedQueue.dequeue();
    console.log("After dequeue:", linkedQueue.toArray());
    
    console.log("\n=== Circular Queue Demo ===");
    const circularQueue = new CircularQueue(3);
    circularQueue.enqueue("A");
    circularQueue.enqueue("B");
    circularQueue.enqueue("C");
    console.log("Dequeue:", circularQueue.dequeue());
    circularQueue.enqueue("D");
    console.log("Dequeue:", circularQueue.dequeue());
    console.log("Dequeue:", circularQueue.dequeue());
    console.log("Dequeue:", circularQueue.dequeue());
}

module.exports = { Queue, LinkedQueue, QueueNode, CircularQueue };
