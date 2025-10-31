<?php

declare(strict_types=1);

/**
 * PROBLEM 5: Merge Two Sorted Lists
 * 
 * Difficulty: Easy
 * 
 * Problem Statement:
 * You are given the heads of two sorted linked lists list1 and list2.
 * Merge the two lists into one sorted list. The list should be made by 
 * splicing together the nodes of the first two lists.
 * 
 * Return the head of the merged linked list.
 * 
 * Example 1:
 * Input: list1 = [1,2,4], list2 = [1,3,4]
 * Output: [1,1,2,3,4,4]
 * 
 * Example 2:
 * Input: list1 = [], list2 = []
 * Output: []
 * 
 * Example 3:
 * Input: list1 = [], list2 = [0]
 * Output: [0]
 * 
 * Constraints:
 * - The number of nodes in both lists is in range [0, 50]
 * - -100 <= Node.val <= 100
 * - Both list1 and list2 are sorted in non-decreasing order
 */

class ListNode2
{
    public function __construct(
        public int $val,
        public ?ListNode2 $next = null
    ) {}
}

class MergeTwoSortedLists
{
    /**
     * ITERATIVE APPROACH
     * 
     * Time Complexity: O(n + m) where n, m are lengths of two lists
     * Space Complexity: O(1) - only pointers, no extra data structures
     * 
     * Strategy: Use dummy node and two pointers
     * Compare values from both lists and attach smaller one
     */
    public static function mergeIterative(?ListNode2 $list1, ?ListNode2 $list2): ?ListNode2
    {
        // Line 1: Create dummy node to simplify edge cases
        // This avoids special handling for the first node
        // We'll return dummy->next as the actual head
        $dummy = new ListNode2(0);
        
        // Line 2: Current pointer tracks where to attach next node
        // Starts at dummy, will move forward as we build merged list
        $current = $dummy;
        
        // Line 3: Loop while both lists have nodes
        while ($list1 !== null && $list2 !== null) {
            // Line 4: Compare values from both lists
            if ($list1->val <= $list2->val) {
                // Line 5: list1 has smaller value
                // Attach list1's current node to merged list
                $current->next = $list1;
                
                // Line 6: Move list1 pointer forward
                $list1 = $list1->next;
            } else {
                // Line 7: list2 has smaller value
                // Attach list2's current node to merged list
                $current->next = $list2;
                
                // Line 8: Move list2 pointer forward
                $list2 = $list2->next;
            }
            
            // Line 9: Move current pointer forward
            // Ready to attach next node in next iteration
            $current = $current->next;
        }
        
        // Line 10: One list is exhausted, attach remainder of other list
        // At most one of these will be non-null
        // Since lists are already sorted, we can attach rest directly
        if ($list1 !== null) {
            $current->next = $list1;
        } else {
            $current->next = $list2;
        }
        
        // Line 11: Return merged list (skip dummy node)
        return $dummy->next;
    }

    /**
     * RECURSIVE APPROACH
     * 
     * Time Complexity: O(n + m)
     * Space Complexity: O(n + m) - recursion call stack
     * 
     * Strategy: Recursively build merged list
     * Each call handles one node and recurses for the rest
     */
    public static function mergeRecursive(?ListNode2 $list1, ?ListNode2 $list2): ?ListNode2
    {
        // Line 1: Base case - if one list is empty, return the other
        // This handles both initial empty lists and end of recursion
        if ($list1 === null) {
            return $list2;
        }
        if ($list2 === null) {
            return $list1;
        }
        
        // Line 2: Compare current nodes from both lists
        if ($list1->val <= $list2->val) {
            // Line 3: list1 node should come first
            // Recursively merge list1->next with list2
            // Attach result to current list1 node
            $list1->next = self::mergeRecursive($list1->next, $list2);
            
            // Line 4: Return list1 as head of merged portion
            return $list1;
        } else {
            // Line 5: list2 node should come first
            // Recursively merge list1 with list2->next
            // Attach result to current list2 node
            $list2->next = self::mergeRecursive($list1, $list2->next);
            
            // Line 6: Return list2 as head of merged portion
            return $list2;
        }
    }

    /**
     * Helper: Create linked list from array
     */
    public static function createList(array $values): ?ListNode2
    {
        if (empty($values)) {
            return null;
        }
        
        $head = new ListNode2($values[0]);
        $current = $head;
        
        for ($i = 1; $i < count($values); $i++) {
            $current->next = new ListNode2($values[$i]);
            $current = $current->next;
        }
        
        return $head;
    }

    /**
     * Helper: Convert linked list to array
     */
    public static function toArray(?ListNode2 $head): array
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
 * Input: list1 = [1,2,4], list2 = [1,3,4]
 * 
 * Initial:
 *   dummy = [0]
 *   current = dummy
 *   list1 -> 1, list2 -> 1
 * 
 * Step 1: Compare 1 and 1
 *   1 <= 1, so attach list1's 1
 *   current->next = list1 (value 1)
 *   list1 moves to 2
 *   current moves to node with value 1
 *   Result so far: [0] -> [1]
 * 
 * Step 2: Compare 2 and 1
 *   2 > 1, so attach list2's 1
 *   current->next = list2 (value 1)
 *   list2 moves to 3
 *   current moves to node with value 1
 *   Result so far: [0] -> [1] -> [1]
 * 
 * Step 3: Compare 2 and 3
 *   2 <= 3, so attach list1's 2
 *   current->next = list1 (value 2)
 *   list1 moves to 4
 *   current moves to node with value 2
 *   Result so far: [0] -> [1] -> [1] -> [2]
 * 
 * Step 4: Compare 4 and 3
 *   4 > 3, so attach list2's 3
 *   current->next = list2 (value 3)
 *   list2 moves to 4
 *   current moves to node with value 3
 *   Result so far: [0] -> [1] -> [1] -> [2] -> [3]
 * 
 * Step 5: Compare 4 and 4
 *   4 <= 4, so attach list1's 4
 *   current->next = list1 (value 4)
 *   list1 moves to null
 *   current moves to node with value 4
 *   Result so far: [0] -> [1] -> [1] -> [2] -> [3] -> [4]
 * 
 * Step 6: list1 is null, attach rest of list2
 *   current->next = list2 (value 4)
 *   Result: [0] -> [1] -> [1] -> [2] -> [3] -> [4] -> [4]
 * 
 * Return dummy->next: [1] -> [1] -> [2] -> [3] -> [4] -> [4]
 */

/**
 * WALKTHROUGH EXAMPLE (Recursive):
 * 
 * Input: list1 = [1,2], list2 = [1,3]
 * 
 * Call Stack:
 *   merge([1,2], [1,3])
 *     1 <= 1, so:
 *     [1]->next = merge([2], [1,3])
 *       2 > 1, so:
 *       [1]->next = merge([2], [3])
 *         2 <= 3, so:
 *         [2]->next = merge([], [3])
 *           return [3]
 *         return [2,3]
 *       return [1,3,2] ... wait, that's wrong!
 * 
 * Let me trace correctly:
 *   merge([1,2], [1,3])
 *     1 <= 1, choose first 1
 *     first 1->next = merge([2], [1,3])
 *       2 > 1, choose second 1
 *       second 1->next = merge([2], [3])
 *         2 <= 3, choose 2
 *         2->next = merge([], [3])
 *           return [3]
 *         return [2] -> [3]
 *       return [1] -> [2] -> [3]
 *     return [1] -> [1] -> [2] -> [3]
 */

// Test cases
if (basename(__FILE__) === basename($_SERVER['PHP_SELF'] ?? '')) {
    echo "=== Problem 5: Merge Two Sorted Lists ===\n\n";
    
    $testCases = [
        [[1, 2, 4], [1, 3, 4]],    // Expected: [1,1,2,3,4,4]
        [[], []],                   // Expected: []
        [[], [0]],                  // Expected: [0]
        [[1, 3, 5], [2, 4, 6]],    // Expected: [1,2,3,4,5,6]
        [[1], [2]],                 // Expected: [1,2]
    ];
    
    foreach ($testCases as $i => $test) {
        [$arr1, $arr2] = $test;
        echo "Test Case " . ($i + 1) . ":\n";
        echo "Input: list1 = " . json_encode($arr1) . ", list2 = " . json_encode($arr2) . "\n";
        
        // Test iterative
        $list1 = MergeTwoSortedLists::createList($arr1);
        $list2 = MergeTwoSortedLists::createList($arr2);
        $merged1 = MergeTwoSortedLists::mergeIterative($list1, $list2);
        echo "Iterative Output: " . json_encode(MergeTwoSortedLists::toArray($merged1)) . "\n";
        
        // Test recursive
        $list3 = MergeTwoSortedLists::createList($arr1);
        $list4 = MergeTwoSortedLists::createList($arr2);
        $merged2 = MergeTwoSortedLists::mergeRecursive($list3, $list4);
        echo "Recursive Output: " . json_encode(MergeTwoSortedLists::toArray($merged2)) . "\n";
        echo "\n";
    }
}
