<?php

declare(strict_types=1);

/**
 * PROBLEM 4: Maximum Subarray (Kadane's Algorithm)
 * 
 * Difficulty: Medium
 * 
 * Problem Statement:
 * Given an integer array nums, find the contiguous subarray (containing at least 
 * one number) which has the largest sum and return its sum.
 * 
 * A subarray is a contiguous part of an array.
 * 
 * Example 1:
 * Input: nums = [-2,1,-3,4,-1,2,1,-5,4]
 * Output: 6
 * Explanation: [4,-1,2,1] has the largest sum = 6
 * 
 * Example 2:
 * Input: nums = [1]
 * Output: 1
 * 
 * Example 3:
 * Input: nums = [5,4,-1,7,8]
 * Output: 23
 * 
 * Constraints:
 * - 1 <= nums.length <= 10^5
 * - -10^4 <= nums[i] <= 10^4
 */

class MaximumSubarray
{
    /**
     * BRUTE FORCE APPROACH
     * 
     * Time Complexity: O(n²) - nested loops
     * Space Complexity: O(1)
     * 
     * Strategy: Check all possible subarrays
     */
    public static function bruteForceSolution(array $nums): int
    {
        $maxSum = PHP_INT_MIN;
        $n = count($nums);
        
        // Try all possible starting points
        for ($i = 0; $i < $n; $i++) {
            $currentSum = 0;
            // Try all possible ending points from current start
            for ($j = $i; $j < $n; $j++) {
                $currentSum += $nums[$j];
                $maxSum = max($maxSum, $currentSum);
            }
        }
        
        return $maxSum;
    }

    /**
     * OPTIMAL APPROACH: Kadane's Algorithm
     * 
     * Time Complexity: O(n) - single pass
     * Space Complexity: O(1) - only two variables
     * 
     * Strategy: Dynamic Programming
     * At each position, decide: extend current subarray or start new one?
     * 
     * Key Insight:
     * If current_sum becomes negative, it will only decrease future sums,
     * so it's better to start fresh from next element.
     */
    public static function kadaneAlgorithm(array $nums): int
    {
        // Line 1: Initialize maxSum with first element
        // This handles the case where all numbers are negative
        // We must include at least one element
        $maxSum = $nums[0];
        
        // Line 2: Initialize currentSum with first element
        // Tracks the sum of current subarray we're building
        $currentSum = $nums[0];
        
        // Line 3: Iterate from second element to end
        for ($i = 1; $i < count($nums); $i++) {
            // Line 4: KEY DECISION POINT
            // Either extend current subarray by adding nums[i]
            // OR start new subarray from nums[i]
            // Choose whichever gives larger sum
            // 
            // If currentSum + nums[i] < nums[i], it means currentSum is negative
            // and dragging us down, so better to start fresh
            $currentSum = max($nums[$i], $currentSum + $nums[$i]);
            
            // Line 5: Update global maximum if current is better
            // Keep track of best subarray sum seen so far
            $maxSum = max($maxSum, $currentSum);
        }
        
        // Line 6: Return the maximum sum found
        return $maxSum;
    }

    /**
     * VARIATION: Return the actual subarray (not just sum)
     * 
     * Time Complexity: O(n)
     * Space Complexity: O(n) - to store result array
     */
    public static function maxSubarrayWithIndices(array $nums): array
    {
        $maxSum = $nums[0];
        $currentSum = $nums[0];
        
        // Track indices of best subarray
        $maxStart = 0;
        $maxEnd = 0;
        $currentStart = 0;
        
        for ($i = 1; $i < count($nums); $i++) {
            // If starting fresh, update start index
            if ($nums[$i] > $currentSum + $nums[$i]) {
                $currentSum = $nums[$i];
                $currentStart = $i;
            } else {
                $currentSum += $nums[$i];
            }
            
            // Update max if current is better
            if ($currentSum > $maxSum) {
                $maxSum = $currentSum;
                $maxStart = $currentStart;
                $maxEnd = $i;
            }
        }
        
        return [
            'sum' => $maxSum,
            'start' => $maxStart,
            'end' => $maxEnd,
            'subarray' => array_slice($nums, $maxStart, $maxEnd - $maxStart + 1)
        ];
    }
}

/**
 * WALKTHROUGH EXAMPLE:
 * 
 * Input: nums = [-2, 1, -3, 4, -1, 2, 1, -5, 4]
 * 
 * Initial:
 *   maxSum = -2
 *   currentSum = -2
 * 
 * i=1, nums[1]=1:
 *   currentSum = max(1, -2+1) = max(1, -1) = 1 (start fresh)
 *   maxSum = max(-2, 1) = 1
 * 
 * i=2, nums[2]=-3:
 *   currentSum = max(-3, 1+(-3)) = max(-3, -2) = -2 (extend)
 *   maxSum = max(1, -2) = 1
 * 
 * i=3, nums[3]=4:
 *   currentSum = max(4, -2+4) = max(4, 2) = 4 (start fresh)
 *   maxSum = max(1, 4) = 4
 * 
 * i=4, nums[4]=-1:
 *   currentSum = max(-1, 4+(-1)) = max(-1, 3) = 3 (extend)
 *   maxSum = max(4, 3) = 4
 * 
 * i=5, nums[5]=2:
 *   currentSum = max(2, 3+2) = max(2, 5) = 5 (extend)
 *   maxSum = max(4, 5) = 5
 * 
 * i=6, nums[6]=1:
 *   currentSum = max(1, 5+1) = max(1, 6) = 6 (extend)
 *   maxSum = max(5, 6) = 6
 * 
 * i=7, nums[7]=-5:
 *   currentSum = max(-5, 6+(-5)) = max(-5, 1) = 1 (extend)
 *   maxSum = max(6, 1) = 6
 * 
 * i=8, nums[8]=4:
 *   currentSum = max(4, 1+4) = max(4, 5) = 5 (extend)
 *   maxSum = max(6, 5) = 6
 * 
 * Result: 6 (subarray [4, -1, 2, 1])
 */

/**
 * WHY IT WORKS:
 * 
 * Kadane's algorithm is based on dynamic programming principle:
 * 
 * For each position i, we calculate:
 *   dp[i] = max sum of subarray ending at position i
 * 
 * We have two choices:
 *   1. Extend previous subarray: dp[i-1] + nums[i]
 *   2. Start new subarray: nums[i]
 * 
 * Choose whichever is larger:
 *   dp[i] = max(nums[i], dp[i-1] + nums[i])
 * 
 * We don't need array to store dp values, just track current!
 */

// Test cases
if (basename(__FILE__) === basename($_SERVER['PHP_SELF'] ?? '')) {
    echo "=== Problem 4: Maximum Subarray ===\n\n";
    
    $testCases = [
        [-2, 1, -3, 4, -1, 2, 1, -5, 4],  // Expected: 6
        [1],                               // Expected: 1
        [5, 4, -1, 7, 8],                 // Expected: 23
        [-1],                              // Expected: -1
        [-2, -1],                          // Expected: -1
        [1, 2, 3, 4, 5],                  // Expected: 15 (all positive)
    ];
    
    foreach ($testCases as $i => $test) {
        echo "Test Case " . ($i + 1) . ":\n";
        echo "Input: " . json_encode($test) . "\n";
        
        $resultBrute = MaximumSubarray::bruteForceSolution($test);
        $resultKadane = MaximumSubarray::kadaneAlgorithm($test);
        $resultWithIndices = MaximumSubarray::maxSubarrayWithIndices($test);
        
        echo "Brute Force: $resultBrute\n";
        echo "Kadane's Algorithm: $resultKadane\n";
        echo "With Subarray: sum={$resultWithIndices['sum']}, ";
        echo "subarray=" . json_encode($resultWithIndices['subarray']) . "\n";
        echo "\n";
    }
}
