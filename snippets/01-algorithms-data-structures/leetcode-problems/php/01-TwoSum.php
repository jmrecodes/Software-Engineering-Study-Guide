<?php

declare(strict_types=1);

/**
 * PROBLEM 1: Two Sum
 * 
 * Difficulty: Easy
 * 
 * Problem Statement:
 * Given an array of integers nums and an integer target, return indices of the 
 * two numbers such that they add up to target.
 * 
 * You may assume that each input would have exactly one solution, and you may 
 * not use the same element twice.
 * 
 * Example:
 * Input: nums = [2, 7, 11, 15], target = 9
 * Output: [0, 1]
 * Explanation: nums[0] + nums[1] = 2 + 7 = 9
 * 
 * Constraints:
 * - 2 <= nums.length <= 10^4
 * - -10^9 <= nums[i] <= 10^9
 * - -10^9 <= target <= 10^9
 * - Only one valid answer exists
 */

class TwoSum
{
    /**
     * BRUTE FORCE APPROACH
     * 
     * Time Complexity: O(n²) - nested loops
     * Space Complexity: O(1) - no extra space
     * 
     * Strategy: Check every pair of numbers
     */
    public static function bruteForceSolution(array $nums, int $target): array
    {
        $n = count($nums);
        
        // Line 1: Outer loop - iterate through each number
        for ($i = 0; $i < $n; $i++) {
            // Line 2: Inner loop - check all numbers after current
            for ($j = $i + 1; $j < $n; $j++) {
                // Line 3: If sum equals target, found the pair
                if ($nums[$i] + $nums[$j] === $target) {
                    return [$i, $j];
                }
            }
        }
        
        return []; // No solution found
    }

    /**
     * OPTIMAL APPROACH: Hash Map
     * 
     * Time Complexity: O(n) - single pass through array
     * Space Complexity: O(n) - hash map to store values and indices
     * 
     * Strategy: Use hash map to store complements
     * For each number, check if its complement (target - num) exists in map
     */
    public static function optimalSolution(array $nums, int $target): array
    {
        // Line 1: Initialize hash map to store value -> index mapping
        // This allows O(1) lookup to find if complement exists
        $map = [];
        
        // Line 2: Iterate through array once
        foreach ($nums as $i => $num) {
            // Line 3: Calculate complement (the number we need to find)
            // If current number is 2 and target is 9, complement is 7
            $complement = $target - $num;
            
            // Line 4: Check if complement exists in our map
            // isset() is O(1) operation for arrays in PHP
            if (isset($map[$complement])) {
                // Line 5: Found the pair! Return indices
                // map[$complement] gives us the index where we saw the complement
                // $i is current index
                return [$map[$complement], $i];
            }
            
            // Line 6: Store current number and its index in map
            // This makes it available for future iterations
            $map[$num] = $i;
        }
        
        // Line 7: No solution found (shouldn't happen per constraints)
        return [];
    }
}

/**
 * WALKTHROUGH EXAMPLE:
 * 
 * Input: nums = [2, 7, 11, 15], target = 9
 * 
 * Iteration 1: i=0, num=2
 *   - complement = 9 - 2 = 7
 *   - map is empty, so 7 not found
 *   - map = [2 => 0]
 * 
 * Iteration 2: i=1, num=7
 *   - complement = 9 - 7 = 2
 *   - map has key 2 with value 0
 *   - FOUND! Return [0, 1]
 */

// Test cases
if (basename(__FILE__) === basename($_SERVER['PHP_SELF'] ?? '')) {
    echo "=== Problem 1: Two Sum ===\n\n";
    
    $testCases = [
        [[2, 7, 11, 15], 9],      // Expected: [0, 1]
        [[3, 2, 4], 6],            // Expected: [1, 2]
        [[3, 3], 6],               // Expected: [0, 1]
        [[-1, -2, -3, -4, -5], -8] // Expected: [2, 4]
    ];
    
    foreach ($testCases as $i => $test) {
        [$nums, $target] = $test;
        echo "Test Case " . ($i + 1) . ":\n";
        echo "Input: nums = " . json_encode($nums) . ", target = $target\n";
        
        $resultBrute = TwoSum::bruteForceSolution($nums, $target);
        $resultOptimal = TwoSum::optimalSolution($nums, $target);
        
        echo "Brute Force: " . json_encode($resultBrute) . "\n";
        echo "Optimal (Hash Map): " . json_encode($resultOptimal) . "\n";
        echo "\n";
    }
}
