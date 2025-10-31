<?php

declare(strict_types=1);

/**
 * Merge Sort Implementation
 * 
 * Algorithm Type: Divide and Conquer
 * 
 * Time Complexity: O(n log n) - ALL CASES (best, average, worst)
 * Space Complexity: O(n) - requires temporary arrays
 * 
 * Characteristics:
 * - Stable: maintains relative order of equal elements
 * - Not in-place: requires extra space
 * - Predictable: always O(n log n)
 * 
 * Best for:
 * - Linked lists (can be done in O(1) space)
 * - Large datasets where worst-case performance matters
 * - When stability is required
 */

class MergeSort
{
    /**
     * Main merge sort function
     * 
     * Strategy:
     * 1. Divide array into two halves
     * 2. Recursively sort each half
     * 3. Merge the sorted halves
     */
    public static function sort(array $arr): array
    {
        // Base case: array of 0 or 1 element is already sorted
        if (count($arr) <= 1) {
            return $arr;
        }

        // Divide: find middle and split array
        $mid = (int)(count($arr) / 2);
        $left = array_slice($arr, 0, $mid);
        $right = array_slice($arr, $mid);

        // Conquer: recursively sort both halves
        $left = self::sort($left);
        $right = self::sort($right);

        // Combine: merge the sorted halves
        return self::merge($left, $right);
    }

    /**
     * Merge two sorted arrays into one sorted array
     * Time: O(n) where n = total elements in both arrays
     */
    private static function merge(array $left, array $right): array
    {
        $result = [];
        $i = 0; // Pointer for left array
        $j = 0; // Pointer for right array

        // Compare elements from both arrays and add smaller one
        while ($i < count($left) && $j < count($right)) {
            if ($left[$i] <= $right[$j]) {
                $result[] = $left[$i];
                $i++;
            } else {
                $result[] = $right[$j];
                $j++;
            }
        }

        // Add remaining elements from left array (if any)
        while ($i < count($left)) {
            $result[] = $left[$i];
            $i++;
        }

        // Add remaining elements from right array (if any)
        while ($j < count($right)) {
            $result[] = $right[$j];
            $j++;
        }

        return $result;
    }

    /**
     * In-place merge sort (optimized for space)
     * Still uses O(n) space but modifies original array
     */
    public static function sortInPlace(array &$arr, int $left = 0, ?int $right = null): void
    {
        if ($right === null) {
            $right = count($arr) - 1;
        }

        if ($left < $right) {
            $mid = $left + (int)(($right - $left) / 2);

            // Sort first and second halves
            self::sortInPlace($arr, $left, $mid);
            self::sortInPlace($arr, $mid + 1, $right);

            // Merge the sorted halves
            self::mergeInPlace($arr, $left, $mid, $right);
        }
    }

    private static function mergeInPlace(array &$arr, int $left, int $mid, int $right): void
    {
        // Create temporary arrays
        $leftArr = array_slice($arr, $left, $mid - $left + 1);
        $rightArr = array_slice($arr, $mid + 1, $right - $mid);

        $i = 0;
        $j = 0;
        $k = $left;

        // Merge back into original array
        while ($i < count($leftArr) && $j < count($rightArr)) {
            if ($leftArr[$i] <= $rightArr[$j]) {
                $arr[$k] = $leftArr[$i];
                $i++;
            } else {
                $arr[$k] = $rightArr[$j];
                $j++;
            }
            $k++;
        }

        // Copy remaining elements
        while ($i < count($leftArr)) {
            $arr[$k] = $leftArr[$i];
            $i++;
            $k++;
        }

        while ($j < count($rightArr)) {
            $arr[$k] = $rightArr[$j];
            $j++;
            $k++;
        }
    }
}

/**
 * Quicksort Implementation
 * 
 * Algorithm Type: Divide and Conquer
 * 
 * Time Complexity:
 * - Best: O(n log n) - pivot divides array evenly
 * - Average: O(n log n)
 * - Worst: O(n²) - pivot is always smallest/largest (rare with random pivot)
 * 
 * Space Complexity: O(log n) - recursion stack
 * 
 * Characteristics:
 * - Not stable: may change relative order of equal elements
 * - In-place: requires minimal extra space
 * - Cache-friendly: better locality of reference than merge sort
 * 
 * Best for:
 * - Average case performance matters more than worst case
 * - Memory is limited
 * - Arrays (not linked lists)
 */

class QuickSort
{
    /**
     * Main quicksort function
     * 
     * Strategy:
     * 1. Choose a pivot element
     * 2. Partition: rearrange so elements < pivot are left, > pivot are right
     * 3. Recursively sort left and right partitions
     */
    public static function sort(array &$arr, int $left = 0, ?int $right = null): void
    {
        if ($right === null) {
            $right = count($arr) - 1;
        }

        if ($left < $right) {
            // Partition and get pivot index
            $pivotIndex = self::partition($arr, $left, $right);

            // Recursively sort elements before and after partition
            self::sort($arr, $left, $pivotIndex - 1);
            self::sort($arr, $pivotIndex + 1, $right);
        }
    }

    /**
     * Partition using Lomuto partition scheme
     * 
     * Process:
     * 1. Choose rightmost element as pivot
     * 2. Keep track of smaller elements
     * 3. Swap elements to partition around pivot
     * 
     * Returns: final position of pivot
     */
    private static function partition(array &$arr, int $left, int $right): int
    {
        $pivot = $arr[$right]; // Choose last element as pivot
        $i = $left - 1; // Index of smaller element

        // Move all elements smaller than pivot to left
        for ($j = $left; $j < $right; $j++) {
            if ($arr[$j] <= $pivot) {
                $i++;
                // Swap arr[i] and arr[j]
                [$arr[$i], $arr[$j]] = [$arr[$j], $arr[$i]];
            }
        }

        // Place pivot in correct position
        [$arr[$i + 1], $arr[$right]] = [$arr[$right], $arr[$i + 1]];
        
        return $i + 1;
    }

    /**
     * Optimized quicksort with random pivot
     * Reduces chance of worst-case O(n²)
     */
    public static function sortRandomPivot(array &$arr, int $left = 0, ?int $right = null): void
    {
        if ($right === null) {
            $right = count($arr) - 1;
        }

        if ($left < $right) {
            $pivotIndex = self::randomPartition($arr, $left, $right);
            self::sortRandomPivot($arr, $left, $pivotIndex - 1);
            self::sortRandomPivot($arr, $pivotIndex + 1, $right);
        }
    }

    private static function randomPartition(array &$arr, int $left, int $right): int
    {
        // Choose random pivot and swap with last element
        $randomIndex = rand($left, $right);
        [$arr[$randomIndex], $arr[$right]] = [$arr[$right], $arr[$randomIndex]];
        
        return self::partition($arr, $left, $right);
    }

    /**
     * Three-way partition (handles duplicates efficiently)
     * Good when array has many duplicate elements
     */
    public static function sort3Way(array &$arr, int $left = 0, ?int $right = null): void
    {
        if ($right === null) {
            $right = count($arr) - 1;
        }

        if ($left >= $right) {
            return;
        }

        $pivot = $arr[$left];
        $lt = $left;      // arr[left..lt-1] < pivot
        $gt = $right;     // arr[gt+1..right] > pivot
        $i = $left + 1;   // arr[lt..i-1] == pivot

        while ($i <= $gt) {
            if ($arr[$i] < $pivot) {
                [$arr[$lt], $arr[$i]] = [$arr[$i], $arr[$lt]];
                $lt++;
                $i++;
            } elseif ($arr[$i] > $pivot) {
                [$arr[$i], $arr[$gt]] = [$arr[$gt], $arr[$i]];
                $gt--;
            } else {
                $i++;
            }
        }

        self::sort3Way($arr, $left, $lt - 1);
        self::sort3Way($arr, $gt + 1, $right);
    }
}

// Example usage and comparison
if (basename(__FILE__) === basename($_SERVER['PHP_SELF'] ?? '')) {
    echo "=== Merge Sort Demo ===\n";
    $arr1 = [64, 34, 25, 12, 22, 11, 90];
    echo "Original: " . json_encode($arr1) . "\n";
    $sorted1 = MergeSort::sort($arr1);
    echo "Sorted: " . json_encode($sorted1) . "\n";

    echo "\n=== Merge Sort In-Place ===\n";
    $arr2 = [38, 27, 43, 3, 9, 82, 10];
    echo "Original: " . json_encode($arr2) . "\n";
    MergeSort::sortInPlace($arr2);
    echo "Sorted: " . json_encode($arr2) . "\n";

    echo "\n=== Quicksort Demo ===\n";
    $arr3 = [10, 7, 8, 9, 1, 5];
    echo "Original: " . json_encode($arr3) . "\n";
    QuickSort::sort($arr3);
    echo "Sorted: " . json_encode($arr3) . "\n";

    echo "\n=== Quicksort with Random Pivot ===\n";
    $arr4 = [3, 6, 8, 10, 1, 2, 1];
    echo "Original: " . json_encode($arr4) . "\n";
    QuickSort::sortRandomPivot($arr4);
    echo "Sorted: " . json_encode($arr4) . "\n";

    echo "\n=== 3-Way Quicksort (many duplicates) ===\n";
    $arr5 = [4, 9, 4, 4, 1, 9, 4, 4, 9, 4, 4, 1, 4];
    echo "Original: " . json_encode($arr5) . "\n";
    QuickSort::sort3Way($arr5);
    echo "Sorted: " . json_encode($arr5) . "\n";
}
