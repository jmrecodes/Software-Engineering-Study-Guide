/**
 * Binary Search Tree (BST) Implementation
 * 
 * Properties:
 * - Left subtree contains values less than node
 * - Right subtree contains values greater than node
 * - Both subtrees are also BSTs
 * 
 * Time Complexity (Average - balanced tree):
 * - Insert: O(log n)
 * - Delete: O(log n)
 * - Search: O(log n)
 * 
 * Time Complexity (Worst - unbalanced/skewed tree):
 * - Insert: O(n)
 * - Delete: O(n)
 * - Search: O(n)
 * 
 * Space Complexity: O(n)
 */

class TreeNode {
    constructor(value, left = null, right = null) {
        this.value = value;
        this.left = left;
        this.right = right;
    }
}

class BinarySearchTree {
    constructor() {
        this.root = null;
        this.size = 0;
    }

    /**
     * Insert a value into the BST
     * Time: O(log n) average, O(n) worst case
     */
    insert(value) {
        this.root = this.insertRecursive(this.root, value);
        this.size++;
    }

    /**
     * Recursive helper for insert
     * Returns the modified subtree
     */
    insertRecursive(node, value) {
        // Base case: found the position to insert
        if (node === null) {
            return new TreeNode(value);
        }

        // Traverse left or right based on value comparison
        if (value < node.value) {
            node.left = this.insertRecursive(node.left, value);
        } else if (value > node.value) {
            node.right = this.insertRecursive(node.right, value);
        }
        // If value equals node.value, we don't insert duplicates

        return node;
    }

    /**
     * Search for a value in the BST
     * Time: O(log n) average, O(n) worst case
     */
    find(value) {
        return this.findRecursive(this.root, value);
    }

    /**
     * Recursive helper for find
     */
    findRecursive(node, value) {
        // Base case: reached null or found value
        if (node === null) {
            return false;
        }

        if (value === node.value) {
            return true;
        }

        // Search left or right subtree
        if (value < node.value) {
            return this.findRecursive(node.left, value);
        } else {
            return this.findRecursive(node.right, value);
        }
    }

    /**
     * Delete a value from the BST
     * Time: O(log n) average, O(n) worst case
     * 
     * Three cases to handle:
     * 1. Node has no children (leaf) - simply remove
     * 2. Node has one child - replace with child
     * 3. Node has two children - replace with inorder successor
     */
    delete(value) {
        const initialSize = this.size;
        this.root = this.deleteRecursive(this.root, value);
        return this.size < initialSize;
    }

    /**
     * Recursive helper for delete
     * Returns the modified subtree
     */
    deleteRecursive(node, value) {
        if (node === null) {
            return null;
        }

        // Find the node to delete
        if (value < node.value) {
            node.left = this.deleteRecursive(node.left, value);
        } else if (value > node.value) {
            node.right = this.deleteRecursive(node.right, value);
        } else {
            // Found the node to delete
            this.size--;

            // Case 1: No children (leaf node)
            if (node.left === null && node.right === null) {
                return null;
            }

            // Case 2: One child
            if (node.left === null) {
                return node.right;
            }
            if (node.right === null) {
                return node.left;
            }

            // Case 3: Two children
            // Find inorder successor (smallest in right subtree)
            const successor = this.findMin(node.right);
            // Replace node's value with successor's value
            node.value = successor.value;
            // Delete the successor from right subtree
            node.right = this.deleteRecursive(node.right, successor.value);
            this.size++; // Compensate for the decrement above
        }

        return node;
    }

    /**
     * Find minimum value node in a subtree
     * (leftmost node)
     */
    findMin(node) {
        while (node.left !== null) {
            node = node.left;
        }
        return node;
    }

    /**
     * Find maximum value in the tree
     */
    findMax() {
        if (this.root === null) {
            return null;
        }

        let node = this.root;
        while (node.right !== null) {
            node = node.right;
        }
        return node.value;
    }

    /**
     * In-order traversal (Left -> Root -> Right)
     * Returns values in sorted order
     * Time: O(n)
     */
    inOrderTraversal() {
        const result = [];
        this.inOrderRecursive(this.root, result);
        return result;
    }

    inOrderRecursive(node, result) {
        if (node === null) {
            return;
        }

        this.inOrderRecursive(node.left, result);
        result.push(node.value);
        this.inOrderRecursive(node.right, result);
    }

    /**
     * Pre-order traversal (Root -> Left -> Right)
     * Time: O(n)
     */
    preOrderTraversal() {
        const result = [];
        this.preOrderRecursive(this.root, result);
        return result;
    }

    preOrderRecursive(node, result) {
        if (node === null) {
            return;
        }

        result.push(node.value);
        this.preOrderRecursive(node.left, result);
        this.preOrderRecursive(node.right, result);
    }

    /**
     * Post-order traversal (Left -> Right -> Root)
     * Time: O(n)
     */
    postOrderTraversal() {
        const result = [];
        this.postOrderRecursive(this.root, result);
        return result;
    }

    postOrderRecursive(node, result) {
        if (node === null) {
            return;
        }

        this.postOrderRecursive(node.left, result);
        this.postOrderRecursive(node.right, result);
        result.push(node.value);
    }

    /**
     * Get height of the tree
     * Time: O(n)
     */
    getHeight() {
        return this.getHeightRecursive(this.root);
    }

    getHeightRecursive(node) {
        if (node === null) {
            return -1; // Height of empty tree is -1
        }

        const leftHeight = this.getHeightRecursive(node.left);
        const rightHeight = this.getHeightRecursive(node.right);

        return 1 + Math.max(leftHeight, rightHeight);
    }

    /**
     * Check if tree is balanced
     * (height difference between left and right subtrees <= 1)
     */
    isBalanced() {
        return this.isBalancedRecursive(this.root) !== -1;
    }

    isBalancedRecursive(node) {
        if (node === null) {
            return 0;
        }

        const leftHeight = this.isBalancedRecursive(node.left);
        if (leftHeight === -1) return -1;

        const rightHeight = this.isBalancedRecursive(node.right);
        if (rightHeight === -1) return -1;

        if (Math.abs(leftHeight - rightHeight) > 1) {
            return -1;
        }

        return 1 + Math.max(leftHeight, rightHeight);
    }

    getSize() {
        return this.size;
    }
}

// Example usage
if (require.main === module) {
    console.log("=== Binary Search Tree Demo ===");
    const bst = new BinarySearchTree();
    
    // Insert values
    const values = [50, 30, 70, 20, 40, 60, 80];
    values.forEach(val => bst.insert(val));
    
    console.log("In-order (sorted):", bst.inOrderTraversal());
    console.log("Pre-order:", bst.preOrderTraversal());
    console.log("Post-order:", bst.postOrderTraversal());
    
    console.log("\n=== Search Operations ===");
    console.log("Find 40:", bst.find(40) ? "Found" : "Not found");
    console.log("Find 100:", bst.find(100) ? "Found" : "Not found");
    console.log("Max value:", bst.findMax());
    
    console.log("\n=== Delete Operations ===");
    console.log("Deleting 20 (leaf node)");
    bst.delete(20);
    console.log("In-order:", bst.inOrderTraversal());
    
    console.log("Deleting 30 (node with two children)");
    bst.delete(30);
    console.log("In-order:", bst.inOrderTraversal());
    
    console.log("\n=== Tree Properties ===");
    console.log("Height:", bst.getHeight());
    console.log("Is balanced:", bst.isBalanced() ? "Yes" : "No");
    console.log("Size:", bst.getSize());
}

module.exports = { BinarySearchTree, TreeNode };
