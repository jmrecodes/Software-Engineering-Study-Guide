# PHP & JavaScript Algorithm & Data Structure Study Pack

A comprehensive, production-ready collection of algorithms and data structures implemented in both **PHP 8.3** and **JavaScript**, with detailed explanations and complexity analysis.

## 📚 Table of Contents

1. [Overview](#overview)
2. [Directory Structure](#directory-structure)
3. [Data Structures](#data-structures)
4. [Algorithms](#algorithms)
5. [LeetCode Problems](#leetcode-problems)
6. [How to Use](#how-to-use)
7. [Running the Code](#running-the-code)
8. [Learning Path](#learning-path)

## 🎯 Overview

This study pack contains:
- **Well-commented, optimized code** for both PHP 8.3 and JavaScript
- **Time and space complexity analysis** for every implementation
- **Line-by-line explanations** of complex logic
- **Real-world use cases** and practical examples
- **Multiple approaches** (brute force vs optimal) where applicable

## 📁 Directory Structure

```
algorithm-study-pack/
├── data-structures/
│   ├── php/
│   │   ├── LinkedList.php          (Singly & Doubly)
│   │   ├── Stack.php               (Array & Linked implementations)
│   │   ├── Queue.php               (Array, Linked & Circular)
│   │   ├── HashMap.php             (From scratch with collision handling)
│   │   └── BinarySearchTree.php    (Insert, Delete, Find, Traversals)
│   └── js/
│       ├── LinkedList.js
│       ├── Stack.js
│       ├── Queue.js
│       ├── HashMap.js
│       └── BinarySearchTree.js
├── algorithms/
│   ├── php/
│   │   ├── Sorting.php             (Merge Sort & Quicksort)
│   │   └── GraphSearch.php         (BFS & DFS)
│   └── js/
│       ├── Sorting.js
│       └── GraphSearch.js
└── leetcode-problems/
    └── php/
        ├── 01-TwoSum.php
        ├── 02-ValidParentheses.php
        ├── 03-ReverseLinkedList.php
        ├── 04-MaximumSubarray.php
        └── 05-MergeTwoSortedLists.php
```

## 🗂️ Data Structures

### 1. Linked List (Singly and Doubly)

**Files:** `data-structures/php/LinkedList.php`, `data-structures/js/LinkedList.js`

**Features:**
- Singly Linked List with head and tail pointers
- Doubly Linked List with bidirectional traversal
- Insert at head/tail: O(1)
- Delete: O(n) search, O(1) removal
- Practical examples included

**Key Concepts:**
- Pointer manipulation
- Edge case handling (empty list, single node)
- Memory efficiency vs functionality trade-offs

### 2. Stack

**Files:** `data-structures/php/Stack.php`, `data-structures/js/Stack.js`

**Implementations:**
- Array-based stack
- Linked list-based stack

**Operations:**
- Push: O(1)
- Pop: O(1)
- Peek: O(1)

**Practical Examples:**
- Balanced parentheses checker
- Expression evaluation use cases

### 3. Queue

**Files:** `data-structures/php/Queue.php`, `data-structures/js/Queue.js`

**Implementations:**
- Array-based queue (with optimization)
- Linked list-based queue
- Circular queue (fixed size)

**Operations:**
- Enqueue: O(1)
- Dequeue: O(1)
- Peek: O(1)

### 4. Hash Map (From Scratch)

**Files:** `data-structures/php/HashMap.php`, `data-structures/js/HashMap.js`

**Features:**
- Custom hash function implementation
- **Collision handling via chaining** (linked lists)
- Automatic resizing when load factor > 0.75
- Distribution analysis tools

**Operations:**
- Insert: O(1) average, O(n) worst
- Search: O(1) average, O(n) worst
- Delete: O(1) average, O(n) worst

**Educational Value:**
- Understanding hash functions
- Collision resolution strategies
- Load factor and performance optimization

### 5. Binary Search Tree

**Files:** `data-structures/php/BinarySearchTree.php`, `data-structures/js/BinarySearchTree.js`

**Features:**
- Insert, delete, find operations
- In-order, pre-order, post-order traversals
- Height calculation
- Balance checking

**Operations:**
- Insert: O(log n) average, O(n) worst
- Delete: O(log n) average, O(n) worst
- Search: O(log n) average, O(n) worst

**Key Concepts:**
- Three deletion cases (leaf, one child, two children)
- Inorder successor for deletion
- Tree balancing awareness

## 🔧 Algorithms

### 1. Merge Sort

**Files:** `algorithms/php/Sorting.php`, `algorithms/js/Sorting.js`

**Characteristics:**
- Time: O(n log n) - ALL cases (best, average, worst)
- Space: O(n)
- Stable sorting algorithm
- Divide and conquer approach

**Implementations:**
- Standard merge sort (creates new arrays)
- In-place merge sort (modifies original)

**Use Cases:**
- Linked list sorting
- External sorting (large datasets)
- When stability is required

### 2. Quicksort

**Files:** `algorithms/php/Sorting.php`, `algorithms/js/Sorting.js`

**Characteristics:**
- Time: O(n log n) average, O(n²) worst
- Space: O(log n) - recursion stack
- Not stable
- In-place sorting

**Implementations:**
- Standard quicksort (Lomuto partition)
- Random pivot optimization
- 3-way partition (for duplicates)

**Use Cases:**
- General-purpose sorting
- Memory-constrained environments
- Arrays (not linked lists)

### 3. Breadth-First Search (BFS)

**Files:** `algorithms/php/GraphSearch.php`, `algorithms/js/GraphSearch.js`

**Features:**
- Graph traversal using adjacency list
- Shortest path in unweighted graph
- Level-by-level exploration
- Uses queue (FIFO)

**Time Complexity:** O(V + E)
**Space Complexity:** O(V)

**Use Cases:**
- Shortest path problems
- Web crawling
- Social network analysis
- Level-order operations

### 4. Depth-First Search (DFS)

**Files:** `algorithms/php/GraphSearch.php`, `algorithms/js/GraphSearch.js`

**Implementations:**
- Recursive DFS
- Iterative DFS (explicit stack)
- Path finding
- Cycle detection

**Time Complexity:** O(V + E)
**Space Complexity:** O(V)

**Use Cases:**
- Pathfinding
- Topological sorting
- Cycle detection
- Maze solving

## 💡 LeetCode Problems

All problems include:
- Problem statement and examples
- Brute force approach (where applicable)
- Optimal solution with detailed explanation
- **Line-by-line code walkthrough**
- Visual examples and step-by-step execution
- Test cases

### Problem 1: Two Sum
**File:** `leetcode-problems/php/01-TwoSum.php`
- **Difficulty:** Easy
- **Optimal Time:** O(n)
- **Optimal Space:** O(n)
- **Technique:** Hash Map
- **Key Concept:** Complement lookup

### Problem 2: Valid Parentheses
**File:** `leetcode-problems/php/02-ValidParentheses.php`
- **Difficulty:** Easy
- **Time:** O(n)
- **Space:** O(n)
- **Technique:** Stack (LIFO)
- **Key Concept:** Matching pairs in reverse order

### Problem 3: Reverse Linked List
**File:** `leetcode-problems/php/03-ReverseLinkedList.php`
- **Difficulty:** Easy
- **Time:** O(n)
- **Space:** O(1) iterative, O(n) recursive
- **Techniques:** Iterative (3 pointers), Recursive
- **Key Concept:** Pointer reversal

### Problem 4: Maximum Subarray (Kadane's Algorithm)
**File:** `leetcode-problems/php/04-MaximumSubarray.php`
- **Difficulty:** Medium
- **Time:** O(n)
- **Space:** O(1)
- **Technique:** Dynamic Programming
- **Key Concept:** Extend vs start fresh decision

### Problem 5: Merge Two Sorted Lists
**File:** `leetcode-problems/php/05-MergeTwoSortedLists.php`
- **Difficulty:** Easy
- **Time:** O(n + m)
- **Space:** O(1) iterative, O(n + m) recursive
- **Techniques:** Iterative (dummy node), Recursive
- **Key Concept:** Two-pointer merge

## 🚀 How to Use

### Prerequisites

**For PHP:**
- PHP 8.3 or higher
- Command line access

**For JavaScript:**
- Node.js 14 or higher

### Running the Code

#### PHP Examples

Each PHP file can be run standalone to see demonstrations:

```bash
# Run data structure demos
php data-structures/php/LinkedList.php
php data-structures/php/Stack.php
php data-structures/php/Queue.php
php data-structures/php/HashMap.php
php data-structures/php/BinarySearchTree.php

# Run algorithm demos
php algorithms/php/Sorting.php
php algorithms/php/GraphSearch.php

# Run LeetCode solutions
php leetcode-problems/php/01-TwoSum.php
php leetcode-problems/php/02-ValidParentheses.php
php leetcode-problems/php/03-ReverseLinkedList.php
php leetcode-problems/php/04-MaximumSubarray.php
php leetcode-problems/php/05-MergeTwoSortedLists.php
```

#### JavaScript Examples

```bash
# Run data structure demos
node data-structures/js/LinkedList.js
node data-structures/js/Stack.js
node data-structures/js/Queue.js
node data-structures/js/HashMap.js
node data-structures/js/BinarySearchTree.js

# Run algorithm demos
node algorithms/js/Sorting.js
node algorithms/js/GraphSearch.js
```

### Importing in Your Code

#### PHP

```php
<?php
require_once 'data-structures/php/Stack.php';

$stack = new Stack();
$stack->push(10);
echo $stack->pop(); // 10
```

#### JavaScript

```javascript
const { Stack } = require('./data-structures/js/Stack.js');

const stack = new Stack();
stack.push(10);
console.log(stack.pop()); // 10
```

## 📖 Learning Path

### Beginner Path

1. **Start with Data Structures:**
   - Stack → Queue → Linked List
   - Understand basic operations and time complexities

2. **Simple Problems:**
   - Two Sum (HashMap application)
   - Valid Parentheses (Stack application)

3. **Basic Algorithms:**
   - Learn how sorting works (start with Merge Sort)
   - Understand recursion through examples

### Intermediate Path

1. **Advanced Data Structures:**
   - HashMap implementation (understand collision handling)
   - Binary Search Tree

2. **Graph Algorithms:**
   - BFS and DFS
   - Understand adjacency list representation

3. **Algorithm Problems:**
   - Reverse Linked List (pointer manipulation)
   - Maximum Subarray (dynamic programming)

### Advanced Path

1. **Optimize Everything:**
   - Compare brute force vs optimal solutions
   - Understand space-time trade-offs

2. **Pattern Recognition:**
   - When to use Stack (LIFO problems)
   - When to use Queue (FIFO, BFS)
   - When to use HashMap (O(1) lookup)
   - When to use Two Pointers
   - When to use Dynamic Programming

3. **Master Complexity Analysis:**
   - Practice calculating time/space complexity
   - Understand Big O notation deeply

## 🎓 Study Tips

1. **Don't Just Read - Code Along:**
   - Type out the code yourself
   - Modify examples and see what happens

2. **Understand, Don't Memorize:**
   - Focus on WHY solutions work
   - Draw diagrams for complex operations

3. **Practice Active Recall:**
   - After studying, try implementing from scratch
   - Explain concepts out loud

4. **Compare Languages:**
   - Notice similarities and differences between PHP and JS
   - Understand how language features affect implementation

5. **Run and Debug:**
   - Execute all examples
   - Add print statements to trace execution
   - Use debuggers to step through code

## 📊 Complexity Cheat Sheet

| Data Structure | Access | Search | Insert | Delete | Space |
|---------------|--------|--------|--------|--------|-------|
| Array         | O(1)   | O(n)   | O(n)   | O(n)   | O(n)  |
| Linked List   | O(n)   | O(n)   | O(1)*  | O(1)*  | O(n)  |
| Stack         | O(n)   | O(n)   | O(1)   | O(1)   | O(n)  |
| Queue         | O(n)   | O(n)   | O(1)   | O(1)   | O(n)  |
| Hash Map      | N/A    | O(1)†  | O(1)†  | O(1)†  | O(n)  |
| Binary Tree   | O(n)   | O(n)   | O(n)   | O(n)   | O(n)  |
| BST (balanced)| O(log n)| O(log n)| O(log n)| O(log n)| O(n)  |

\* With direct node access  
† Average case; worst case O(n)

| Algorithm     | Best       | Average    | Worst      | Space     |
|---------------|------------|------------|------------|-----------|
| Merge Sort    | O(n log n) | O(n log n) | O(n log n) | O(n)      |
| Quicksort     | O(n log n) | O(n log n) | O(n²)      | O(log n)  |
| BFS           | O(V + E)   | O(V + E)   | O(V + E)   | O(V)      |
| DFS           | O(V + E)   | O(V + E)   | O(V + E)   | O(V)      |

## 🏆 Next Steps

After mastering this study pack:

1. **LeetCode Practice:**
   - Solve 100+ problems
   - Focus on patterns you learned here

2. **Advanced Topics:**
   - AVL Trees / Red-Black Trees
   - Heaps and Priority Queues
   - Trie data structure
   - Dynamic Programming patterns
   - Graph algorithms (Dijkstra, Bellman-Ford)

3. **System Design:**
   - Apply data structures to real systems
   - Understand when to use which structure

4. **Interview Preparation:**
   - Practice whiteboard coding
   - Explain complexity analysis
   - Discuss trade-offs

## 📝 License

This study pack is created for educational purposes. Feel free to use, modify, and share!

---

**Happy Learning! 🚀**

*Master the fundamentals, and the complex becomes simple.*
