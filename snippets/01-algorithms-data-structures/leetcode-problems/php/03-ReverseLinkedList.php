<?php

declare(strict_types=1);

/**
 * PROBLEM 3: Reverse Linked List
 * 
 * Difficulty: Easy
 * 
 * Problem Statement:
 * Given the head of a singly linked list, reverse the list, and return the reversed list.
 * 
 * Example 1:
 * Input: head = [1,2,3,4,5]
 * Output: [5,4,3,2,1]
 * 
 * Example 2:
 * Input: head = [1,2]
 * Output: [2,1]
 * 
 * Example 3:
 * Input: head = []
 * Output: []
 * 
 * Constraints:
 * - The number of nodes in the list is in range [0, 5000]
 * - -5000 <= Node.val <= 5000
 */

class ListNode
{
    public function __construct(
        public int $val,
        public ?ListNode $next = null
    ) {}
}

class ReverseLinkedList
{
    /**
     * ITERATIVE APPROACH
     * 
     * Time Complexity: O(n) - visit each node once
     * Space Complexity: O(1) - only use 3 pointers
     * 
     * Strategy: Use three pointers to reverse links iteratively
     * - prev: points to previous node (starts as null)
     * - current: points to current node being processed
     * - next: temporarily stores next node before we break the link
     */
    public static function reverseIterative(?ListNode $head): ?ListNode
    {
        // Line 1: Initialize prev pointer to null
        // This will become the new tail of reversed list
        $prev = null;
        
        // Line 2: Start with current pointing to head
        $current = $head;
        
        // Line 3: Iterate until we reach end of list
        while ($current !== null) {
            // Line 4: Save next node before we break the link
            // We need this to continue traversing after reversing current link
            $next = $current->next;
            
            // Line 5: REVERSE THE LINK
            // Make current node point backwards to previous node
            // This is the key step that reverses the direction
            $current->next = $prev;
            
            // Line 6: Move prev forward to current node
            // This node will be the previous node in next iteration
            $prev = $current;
            
            // Line 7: Move current forward to next node
            // Continue processing the rest of the list
            $current = $next;
        }
        
        // Line 8: When loop ends, prev points to new head (old tail)
        // Current is null (past the end of original list)
        return $prev;
    }

    /**
     * RECURSIVE APPROACH
     * 
     * Time Complexity: O(n) - visit each node once
     * Space Complexity: O(n) - recursion call stack
     * 
     * Strategy: Reverse rest of list first, then fix current node
     */
    public static function reverseRecursive(?ListNode $head): ?ListNode
    {
        // Line 1: Base case - empty list or single node
        // Single node is already "reversed"
        if ($head === null || $head->next === null) {
            return $head;
        }
        
        // Line 2: Recursively reverse the rest of the list
        // This returns the new head (which was the old tail)
        // After this call, everything after current node is reversed
        $newHead = self::reverseRecursive($head->next);
        
        // Line 3: Fix the link for current node
        // head->next is now the last node in reversed portion
        // Make it point back to current node
        $head->next->next = $head;
        
        // Line 4: Current node is now the tail, so point to null
        // Break the old forward link
        $head->next = null;
        
        // Line 5: Return the new head (unchanged through recursion)
        return $newHead;
    }

    /**
     * Helper: Create linked list from array
     */
    public static function createList(array $values): ?ListNode
    {
        if (empty($values)) {
            return null;
        }
        
        $head = new ListNode($values[0]);
        $current = $head;
        
        for ($i = 1; $i < count($values); $i++) {
            $current->next = new ListNode($values[$i]);
            $current = $current->next;
        }
        
        return $head;
    }

    /**
     * Helper: Convert linked list to array
     */
    public static function toArray(?ListNode $head): array
    {
        $result = [];
        $current = $head;
        
        while ($current !== null) {
            $result[] = $current->val;
            $current = $current->next;
        }
        
        return $result;
    }
}

/**
 * WALKTHROUGH EXAMPLE (Iterative):
 * 
 * Input: 1 -> 2 -> 3 -> NULL
 * 
 * Initial state:
 *   prev = NULL
 *   current = 1
 * 
 * Iteration 1:
 *   next = 2
 *   1->next = NULL (reverse link)
 *   prev = 1
 *   current = 2
 *   State: NULL <- 1    2 -> 3 -> NULL
 * 
 * Iteration 2:
 *   next = 3
 *   2->next = 1 (reverse link)
 *   prev = 2
 *   current = 3
 *   State: NULL <- 1 <- 2    3 -> NULL
 * 
 * Iteration 3:
 *   next = NULL
 *   3->next = 2 (reverse link)
 *   prev = 3
 *   current = NULL
 *   State: NULL <- 1 <- 2 <- 3
 * 
 * Result: return prev (which is 3)
 *   3 -> 2 -> 1 -> NULL
 */

/**
 * WALKTHROUGH EXAMPLE (Recursive):
 * 
 * Input: 1 -> 2 -> 3 -> NULL
 * 
 * Call Stack:
 *   reverse(1)
 *     reverse(2)
 *       reverse(3)
 *         reverse(NULL) -> returns 3
 *       3->next->next = 3 means 3->next = 2
 *       return 3
 *     2->next->next = 2 means 3 -> 2
 *     return 3
 *   1->next->next = 1 means 2 -> 1
 *   return 3
 * 
 * Result: 3 -> 2 -> 1 -> NULL
 */

// Test cases
if (basename(__FILE__) === basename($_SERVER['PHP_SELF'] ?? '')) {
    echo "=== Problem 3: Reverse Linked List ===\n\n";
    
    $testCases = [
        [1, 2, 3, 4, 5],
        [1, 2],
        [1],
        []
    ];
    
    foreach ($testCases as $i => $test) {
        echo "Test Case " . ($i + 1) . ":\n";
        echo "Input: " . json_encode($test) . "\n";
        
        // Test iterative
        $list1 = ReverseLinkedList::createList($test);
        $reversed1 = ReverseLinkedList::reverseIterative($list1);
        echo "Iterative Output: " . json_encode(ReverseLinkedList::toArray($reversed1)) . "\n";
        
        // Test recursive
        $list2 = ReverseLinkedList::createList($test);
        $reversed2 = ReverseLinkedList::reverseRecursive($list2);
        echo "Recursive Output: " . json_encode(ReverseLinkedList::toArray($reversed2)) . "\n";
        echo "\n";
    }
}
