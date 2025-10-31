<?php

declare(strict_types=1);

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

class TreeNode
{
    public function __construct(
        public int $value,
        public ?TreeNode $left = null,
        public ?TreeNode $right = null
    ) {}
}

class BinarySearchTree
{
    private ?TreeNode $root = null;
    private int $size = 0;

    /**
     * Insert a value into the BST
     * Time: O(log n) average, O(n) worst case
     */
    public function insert(int $value): void
    {
        $this->root = $this->insertRecursive($this->root, $value);
        $this->size++;
    }

    /**
     * Recursive helper for insert
     * Returns the modified subtree
     */
    private function insertRecursive(?TreeNode $node, int $value): TreeNode
    {
        // Base case: found the position to insert
        if ($node === null) {
            return new TreeNode($value);
        }

        // Traverse left or right based on value comparison
        if ($value < $node->value) {
            $node->left = $this->insertRecursive($node->left, $value);
        } elseif ($value > $node->value) {
            $node->right = $this->insertRecursive($node->right, $value);
        }
        // If value equals node->value, we don't insert duplicates

        return $node;
    }

    /**
     * Search for a value in the BST
     * Time: O(log n) average, O(n) worst case
     */
    public function find(int $value): bool
    {
        return $this->findRecursive($this->root, $value);
    }

    /**
     * Recursive helper for find
     */
    private function findRecursive(?TreeNode $node, int $value): bool
    {
        // Base case: reached null or found value
        if ($node === null) {
            return false;
        }

        if ($value === $node->value) {
            return true;
        }

        // Search left or right subtree
        if ($value < $node->value) {
            return $this->findRecursive($node->left, $value);
        } else {
            return $this->findRecursive($node->right, $value);
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
    public function delete(int $value): bool
    {
        $initialSize = $this->size;
        $this->root = $this->deleteRecursive($this->root, $value);
        return $this->size < $initialSize;
    }

    /**
     * Recursive helper for delete
     * Returns the modified subtree
     */
    private function deleteRecursive(?TreeNode $node, int $value): ?TreeNode
    {
        if ($node === null) {
            return null;
        }

        // Find the node to delete
        if ($value < $node->value) {
            $node->left = $this->deleteRecursive($node->left, $value);
        } elseif ($value > $node->value) {
            $node->right = $this->deleteRecursive($node->right, $value);
        } else {
            // Found the node to delete
            $this->size--;

            // Case 1: No children (leaf node)
            if ($node->left === null && $node->right === null) {
                return null;
            }

            // Case 2: One child
            if ($node->left === null) {
                return $node->right;
            }
            if ($node->right === null) {
                return $node->left;
            }

            // Case 3: Two children
            // Find inorder successor (smallest in right subtree)
            $successor = $this->findMin($node->right);
            // Replace node's value with successor's value
            $node->value = $successor->value;
            // Delete the successor from right subtree
            $node->right = $this->deleteRecursive($node->right, $successor->value);
            $this->size++; // Compensate for the decrement above
        }

        return $node;
    }

    /**
     * Find minimum value node in a subtree
     * (leftmost node)
     */
    private function findMin(TreeNode $node): TreeNode
    {
        while ($node->left !== null) {
            $node = $node->left;
        }
        return $node;
    }

    /**
     * Find maximum value in the tree
     */
    public function findMax(): ?int
    {
        if ($this->root === null) {
            return null;
        }

        $node = $this->root;
        while ($node->right !== null) {
            $node = $node->right;
        }
        return $node->value;
    }

    /**
     * In-order traversal (Left -> Root -> Right)
     * Returns values in sorted order
     * Time: O(n)
     */
    public function inOrderTraversal(): array
    {
        $result = [];
        $this->inOrderRecursive($this->root, $result);
        return $result;
    }

    private function inOrderRecursive(?TreeNode $node, array &$result): void
    {
        if ($node === null) {
            return;
        }

        $this->inOrderRecursive($node->left, $result);
        $result[] = $node->value;
        $this->inOrderRecursive($node->right, $result);
    }

    /**
     * Pre-order traversal (Root -> Left -> Right)
     * Time: O(n)
     */
    public function preOrderTraversal(): array
    {
        $result = [];
        $this->preOrderRecursive($this->root, $result);
        return $result;
    }

    private function preOrderRecursive(?TreeNode $node, array &$result): void
    {
        if ($node === null) {
            return;
        }

        $result[] = $node->value;
        $this->preOrderRecursive($node->left, $result);
        $this->preOrderRecursive($node->right, $result);
    }

    /**
     * Post-order traversal (Left -> Right -> Root)
     * Time: O(n)
     */
    public function postOrderTraversal(): array
    {
        $result = [];
        $this->postOrderRecursive($this->root, $result);
        return $result;
    }

    private function postOrderRecursive(?TreeNode $node, array &$result): void
    {
        if ($node === null) {
            return;
        }

        $this->postOrderRecursive($node->left, $result);
        $this->postOrderRecursive($node->right, $result);
        $result[] = $node->value;
    }

    /**
     * Get height of the tree
     * Time: O(n)
     */
    public function getHeight(): int
    {
        return $this->getHeightRecursive($this->root);
    }

    private function getHeightRecursive(?TreeNode $node): int
    {
        if ($node === null) {
            return -1; // Height of empty tree is -1
        }

        $leftHeight = $this->getHeightRecursive($node->left);
        $rightHeight = $this->getHeightRecursive($node->right);

        return 1 + max($leftHeight, $rightHeight);
    }

    /**
     * Check if tree is balanced
     * (height difference between left and right subtrees <= 1)
     */
    public function isBalanced(): bool
    {
        return $this->isBalancedRecursive($this->root) !== -1;
    }

    private function isBalancedRecursive(?TreeNode $node): int
    {
        if ($node === null) {
            return 0;
        }

        $leftHeight = $this->isBalancedRecursive($node->left);
        if ($leftHeight === -1) return -1;

        $rightHeight = $this->isBalancedRecursive($node->right);
        if ($rightHeight === -1) return -1;

        if (abs($leftHeight - $rightHeight) > 1) {
            return -1;
        }

        return 1 + max($leftHeight, $rightHeight);
    }

    public function getSize(): int
    {
        return $this->size;
    }
}

// Example usage
if (basename(__FILE__) === basename($_SERVER['PHP_SELF'] ?? '')) {
    echo "=== Binary Search Tree Demo ===\n";
    $bst = new BinarySearchTree();
    
    // Insert values
    $values = [50, 30, 70, 20, 40, 60, 80];
    foreach ($values as $val) {
        $bst->insert($val);
    }
    
    echo "In-order (sorted): " . json_encode($bst->inOrderTraversal()) . "\n";
    echo "Pre-order: " . json_encode($bst->preOrderTraversal()) . "\n";
    echo "Post-order: " . json_encode($bst->postOrderTraversal()) . "\n";
    
    echo "\n=== Search Operations ===\n";
    echo "Find 40: " . ($bst->find(40) ? "Found" : "Not found") . "\n";
    echo "Find 100: " . ($bst->find(100) ? "Found" : "Not found") . "\n";
    echo "Max value: " . $bst->findMax() . "\n";
    
    echo "\n=== Delete Operations ===\n";
    echo "Deleting 20 (leaf node)\n";
    $bst->delete(20);
    echo "In-order: " . json_encode($bst->inOrderTraversal()) . "\n";
    
    echo "Deleting 30 (node with two children)\n";
    $bst->delete(30);
    echo "In-order: " . json_encode($bst->inOrderTraversal()) . "\n";
    
    echo "\n=== Tree Properties ===\n";
    echo "Height: " . $bst->getHeight() . "\n";
    echo "Is balanced: " . ($bst->isBalanced() ? "Yes" : "No") . "\n";
    echo "Size: " . $bst->getSize() . "\n";
}
