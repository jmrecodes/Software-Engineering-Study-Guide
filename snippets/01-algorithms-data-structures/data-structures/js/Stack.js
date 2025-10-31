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
class Stack {
    constructor() {
        this.items = [];
    }

    /**
     * Add element to top of stack
     * Time: O(1)
     */
    push(item) {
        this.items.push(item);
    }

    /**
     * Remove and return top element
     * Time: O(1)
     */
    pop() {
        if (this.isEmpty()) {
            throw new Error("Stack is empty");
        }
        return this.items.pop();
    }

    /**
     * Return top element without removing it
     * Time: O(1)
     */
    peek() {
        if (this.isEmpty()) {
            throw new Error("Stack is empty");
        }
        return this.items[this.items.length - 1];
    }

    /**
     * Check if stack is empty
     * Time: O(1)
     */
    isEmpty() {
        return this.items.length === 0;
    }

    /**
     * Get current size of stack
     * Time: O(1)
     */
    getSize() {
        return this.items.length;
    }

    /**
     * Clear all elements from stack
     * Time: O(1)
     */
    clear() {
        this.items = [];
    }

    /**
     * Convert stack to array (bottom to top)
     */
    toArray() {
        return [...this.items];
    }
}

/**
 * Stack Implementation using Linked List
 * 
 * More memory efficient for large stacks as it doesn't need contiguous memory
 */
class StackNode {
    constructor(data, next = null) {
        this.data = data;
        this.next = next;
    }
}

class LinkedStack {
    constructor() {
        this.top = null;
        this.size = 0;
    }

    /**
     * Push element onto stack
     * Time: O(1)
     */
    push(data) {
        // Create new node and make it point to current top
        const newNode = new StackNode(data, this.top);
        // Update top to new node
        this.top = newNode;
        this.size++;
    }

    /**
     * Pop element from stack
     * Time: O(1)
     */
    pop() {
        if (this.isEmpty()) {
            throw new Error("Stack is empty");
        }
        
        // Get data from top node
        const data = this.top.data;
        // Move top pointer to next node
        this.top = this.top.next;
        this.size--;
        
        return data;
    }

    /**
     * Peek at top element
     * Time: O(1)
     */
    peek() {
        if (this.isEmpty()) {
            throw new Error("Stack is empty");
        }
        return this.top.data;
    }

    isEmpty() {
        return this.top === null;
    }

    getSize() {
        return this.size;
    }

    /**
     * Convert to array (top to bottom)
     */
    toArray() {
        const result = [];
        let current = this.top;
        while (current !== null) {
            result.push(current.data);
            current = current.next;
        }
        return result;
    }
}

/**
 * Practical Example: Check if parentheses are balanced
 */
function isBalanced(expression) {
    const stack = new Stack();
    const pairs = {'(': ')', '[': ']', '{': '}'};
    
    for (const char of expression) {
        // If opening bracket, push to stack
        if (pairs[char]) {
            stack.push(char);
        }
        // If closing bracket, check if it matches
        else if (Object.values(pairs).includes(char)) {
            if (stack.isEmpty()) {
                return false;
            }
            const top = stack.pop();
            if (pairs[top] !== char) {
                return false;
            }
        }
    }
    
    // Stack should be empty if balanced
    return stack.isEmpty();
}

// Example usage
if (require.main === module) {
    console.log("=== Basic Stack Operations ===");
    const stack = new Stack();
    stack.push(10);
    stack.push(20);
    stack.push(30);
    console.log("Stack:", stack.toArray()); // [10, 20, 30]
    console.log("Peek:", stack.peek()); // 30
    console.log("Pop:", stack.pop()); // 30
    console.log("After pop:", stack.toArray()); // [10, 20]
    
    console.log("\n=== Practical Example: Balanced Parentheses ===");
    const test1 = "{[()]}";
    const test2 = "{[(])}";
    console.log(`${test1} is ${isBalanced(test1) ? "balanced" : "not balanced"}`);
    console.log(`${test2} is ${isBalanced(test2) ? "balanced" : "not balanced"}`);
    
    console.log("\n=== Linked Stack Demo ===");
    const linkedStack = new LinkedStack();
    linkedStack.push("first");
    linkedStack.push("second");
    linkedStack.push("third");
    console.log("Stack (top to bottom):", linkedStack.toArray());
    console.log("Pop:", linkedStack.pop());
}

module.exports = { Stack, LinkedStack, StackNode, isBalanced };
