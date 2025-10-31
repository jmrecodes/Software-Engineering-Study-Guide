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

class MergeSort {
    /**
     * Main merge sort function
     * 
     * Strategy:
     * 1. Divide array into two halves
     * 2. Recursively sort each half
     * 3. Merge the sorted halves
     */
    static sort(arr) {
        // Base case: array of 0 or 1 element is already sorted
        if (arr.length <= 1) {
            return arr;
        }

        // Divide: find middle and split array
        const mid = Math.floor(arr.length / 2);
        const left = arr.slice(0, mid);
        const right = arr.slice(mid);

        // Conquer: recursively sort both halves
        const sortedLeft = this.sort(left);
        const sortedRight = this.sort(right);

        // Combine: merge the sorted halves
        return this.merge(sortedLeft, sortedRight);
    }

    /**
     * Merge two sorted arrays into one sorted array
     * Time: O(n) where n = total elements in both arrays
     */
    static merge(left, right) {
        const result = [];
        let i = 0; // Pointer for left array
        let j = 0; // Pointer for right array

        // Compare elements from both arrays and add smaller one
        while (i < left.length && j < right.length) {
            if (left[i] <= right[j]) {
                result.push(left[i]);
                i++;
            } else {
                result.push(right[j]);
                j++;
            }
        }

        // Add remaining elements from left array (if any)
        while (i < left.length) {
            result.push(left[i]);
            i++;
        }

        // Add remaining elements from right array (if any)
        while (j < right.length) {
            result.push(right[j]);
            j++;
        }

        return result;
    }

    /**
     * In-place merge sort (optimized for space)
     * Still uses O(n) space but modifies original array
     */
    static sortInPlace(arr, left = 0, right = arr.length - 1) {
        if (left < right) {
            const mid = left + Math.floor((right - left) / 2);

            // Sort first and second halves
            this.sortInPlace(arr, left, mid);
            this.sortInPlace(arr, mid + 1, right);

            // Merge the sorted halves
            this.mergeInPlace(arr, left, mid, right);
        }
    }

    static mergeInPlace(arr, left, mid, right) {
        // Create temporary arrays
        const leftArr = arr.slice(left, mid + 1);
        const rightArr = arr.slice(mid + 1, right + 1);

        let i = 0;
        let j = 0;
        let k = left;

        // Merge back into original array
        while (i < leftArr.length && j < rightArr.length) {
            if (leftArr[i] <= rightArr[j]) {
                arr[k] = leftArr[i];
                i++;
            } else {
                arr[k] = rightArr[j];
                j++;
            }
            k++;
        }

        // Copy remaining elements
        while (i < leftArr.length) {
            arr[k] = leftArr[i];
            i++;
            k++;
        }

        while (j < rightArr.length) {
            arr[k] = rightArr[j];
            j++;
            k++;
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

class QuickSort {
    /**
     * Main quicksort function
     * 
     * Strategy:
     * 1. Choose a pivot element
     * 2. Partition: rearrange so elements < pivot are left, > pivot are right
     * 3. Recursively sort left and right partitions
     */
    static sort(arr, left = 0, right = arr.length - 1) {
        if (left < right) {
            // Partition and get pivot index
            const pivotIndex = this.partition(arr, left, right);

            // Recursively sort elements before and after partition
            this.sort(arr, left, pivotIndex - 1);
            this.sort(arr, pivotIndex + 1, right);
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
    static partition(arr, left, right) {
        const pivot = arr[right]; // Choose last element as pivot
        let i = left - 1; // Index of smaller element

        // Move all elements smaller than pivot to left
        for (let j = left; j < right; j++) {
            if (arr[j] <= pivot) {
                i++;
                // Swap arr[i] and arr[j]
                [arr[i], arr[j]] = [arr[j], arr[i]];
            }
        }

        // Place pivot in correct position
        [arr[i + 1], arr[right]] = [arr[right], arr[i + 1]];
        
        return i + 1;
    }

    /**
     * Optimized quicksort with random pivot
     * Reduces chance of worst-case O(n²)
     */
    static sortRandomPivot(arr, left = 0, right = arr.length - 1) {
        if (left < right) {
            const pivotIndex = this.randomPartition(arr, left, right);
            this.sortRandomPivot(arr, left, pivotIndex - 1);
            this.sortRandomPivot(arr, pivotIndex + 1, right);
        }
    }

    static randomPartition(arr, left, right) {
        // Choose random pivot and swap with last element
        const randomIndex = left + Math.floor(Math.random() * (right - left + 1));
        [arr[randomIndex], arr[right]] = [arr[right], arr[randomIndex]];
        
        return this.partition(arr, left, right);
    }

    /**
     * Three-way partition (handles duplicates efficiently)
     * Good when array has many duplicate elements
     */
    static sort3Way(arr, left = 0, right = arr.length - 1) {
        if (left >= right) {
            return;
        }

        const pivot = arr[left];
        let lt = left;      // arr[left..lt-1] < pivot
        let gt = right;     // arr[gt+1..right] > pivot
        let i = left + 1;   // arr[lt..i-1] == pivot

        while (i <= gt) {
            if (arr[i] < pivot) {
                [arr[lt], arr[i]] = [arr[i], arr[lt]];
                lt++;
                i++;
            } else if (arr[i] > pivot) {
                [arr[i], arr[gt]] = [arr[gt], arr[i]];
                gt--;
            } else {
                i++;
            }
        }

        this.sort3Way(arr, left, lt - 1);
        this.sort3Way(arr, gt + 1, right);
    }
}

// Example usage and comparison
if (require.main === module) {
    console.log("=== Merge Sort Demo ===");
    const arr1 = [64, 34, 25, 12, 22, 11, 90];
    console.log("Original:", arr1);
    const sorted1 = MergeSort.sort([...arr1]);
    console.log("Sorted:", sorted1);

    console.log("\n=== Merge Sort In-Place ===");
    const arr2 = [38, 27, 43, 3, 9, 82, 10];
    console.log("Original:", arr2);
    MergeSort.sortInPlace(arr2);
    console.log("Sorted:", arr2);

    console.log("\n=== Quicksort Demo ===");
    const arr3 = [10, 7, 8, 9, 1, 5];
    console.log("Original:", arr3);
    QuickSort.sort(arr3);
    console.log("Sorted:", arr3);

    console.log("\n=== Quicksort with Random Pivot ===");
    const arr4 = [3, 6, 8, 10, 1, 2, 1];
    console.log("Original:", arr4);
    QuickSort.sortRandomPivot(arr4);
    console.log("Sorted:", arr4);

    console.log("\n=== 3-Way Quicksort (many duplicates) ===");
    const arr5 = [4, 9, 4, 4, 1, 9, 4, 4, 9, 4, 4, 1, 4];
    console.log("Original:", arr5);
    QuickSort.sort3Way(arr5);
    console.log("Sorted:", arr5);
}

module.exports = { MergeSort, QuickSort };
