# Quick Reference Guide

## 🎯 At a Glance

This study pack contains **15 files** with production-ready implementations:

### Data Structures (10 files)
- **Linked Lists** (Singly & Doubly) - PHP & JS
- **Stack** (Array & Linked) - PHP & JS  
- **Queue** (Array, Linked & Circular) - PHP & JS
- **HashMap** (Custom implementation with collision handling) - PHP & JS
- **Binary Search Tree** (Full CRUD operations) - PHP & JS

### Algorithms (4 files)
- **Sorting** (Merge Sort & Quicksort with variations) - PHP & JS
- **Graph Search** (BFS & DFS with Graph class) - PHP & JS

### LeetCode Problems (5 files - PHP only)
1. Two Sum
2. Valid Parentheses
3. Reverse Linked List
4. Maximum Subarray
5. Merge Two Sorted Lists

---

## ⚡ Quick Start

### Test Everything Works

```bash
# PHP Tests
php ./algorithm-study-pack/leetcode-problems/php/01-TwoSum.php
php ./algorithm-study-pack/data-structures/php/Stack.php
php ./algorithm-study-pack/algorithms/php/Sorting.php

# JavaScript Tests
node ./algorithm-study-pack/data-structures/js/Stack.js
node ./algorithm-study-pack/algorithms/js/GraphSearch.js
```

---

## 📊 What Makes This Study Pack Special?

### 1. **Production Quality Code**
- Strict typing (PHP 8.3 declare strict_types)
- Modern syntax (PHP constructor property promotion)
- Proper encapsulation and OOP principles

### 2. **Educational Depth**
- Every algorithm has line-by-line explanations
- Visual walkthroughs of execution
- Multiple approaches (brute force → optimal)
- Real complexity analysis (not just Big O notation)

### 3. **Practical Focus**
- Real-world use cases for each structure
- Working examples you can run immediately
- Pattern recognition training
- Interview preparation material

### 4. **Comprehensive Coverage**

**Data Structures:**
```
LinkedList → Stack → Queue → HashMap → BST
  (Linear)      ↓       ↓       ↓      (Tree)
             LIFO    FIFO    O(1)    O(log n)
```

**Algorithms:**
```
Sorting: Merge Sort (stable) vs Quicksort (fast)
Search: BFS (shortest path) vs DFS (any path)
```

---

## 🎓 Learning Objectives Achieved

After studying this pack, you will understand:

✅ **When to use which data structure**
- Stack for LIFO problems (parentheses, undo)
- Queue for FIFO problems (BFS, scheduling)
- HashMap for O(1) lookups (Two Sum)
- BST for sorted data operations
- Linked List for efficient insertions/deletions

✅ **How to analyze complexity**
- Time complexity calculation
- Space complexity trade-offs
- Best/Average/Worst case analysis

✅ **Common algorithm patterns**
- Two pointers (Merge Two Lists)
- Hash map for complement lookup (Two Sum)
- Stack for matching pairs (Valid Parentheses)
- Dynamic programming (Maximum Subarray)
- Divide and conquer (Merge Sort, Quicksort)

✅ **Implementation techniques**
- Iterative vs recursive approaches
- Pointer manipulation
- Edge case handling
- Optimization strategies

---

## 🔥 Most Important Concepts

### 1. Time Complexity Hierarchy
```
O(1) < O(log n) < O(n) < O(n log n) < O(n²) < O(2ⁿ) < O(n!)
 ↑       ↑        ↑         ↑          ↑        ↑        ↑
Best  Binary   Linear   Efficient  Quadratic Exponential Factorial
     Search            Sort (MS/QS)  (bad)     (very bad)  (worst)
```

### 2. Data Structure Selection Matrix

| Need                        | Use              | Time    |
|----------------------------|------------------|---------|
| LIFO access                | Stack            | O(1)    |
| FIFO access                | Queue            | O(1)    |
| Fast lookup by key         | HashMap          | O(1)    |
| Sorted data                | BST              | O(log n)|
| Insert/delete in middle    | Linked List      | O(1)*   |
| Random access              | Array            | O(1)    |

*with node reference

### 3. Algorithm Selection Guide

**Sorting:**
- Need stability? → **Merge Sort**
- Limited memory? → **Quicksort**
- Many duplicates? → **3-Way Quicksort**

**Graph Search:**
- Shortest path? → **BFS**
- Any path/cycle detection? → **DFS**
- Memory constrained? → **DFS** (iterative)

---

## 💎 Code Highlights

### Most Elegant: Kadane's Algorithm
```php
$currentSum = max($nums[$i], $currentSum + $nums[$i]);
$maxSum = max($maxSum, $currentSum);
```
One decision point solves maximum subarray in O(n)!

### Most Educational: HashMap Collision Handling
See how professional hash tables work under the hood with chaining and resizing.

### Most Practical: Graph BFS/DFS
Real adjacency list implementation you'd use in production.

---

## 🎯 Interview Preparation Checklist

- [ ] Can implement Stack from scratch
- [ ] Can implement Queue from scratch
- [ ] Can explain HashMap collision handling
- [ ] Can write Merge Sort and Quicksort
- [ ] Can implement BFS and DFS
- [ ] Can solve Two Sum in O(n)
- [ ] Can validate parentheses using Stack
- [ ] Can reverse a linked list iteratively
- [ ] Can find maximum subarray (Kadane)
- [ ] Can merge sorted lists

---

## 📚 Study Schedule Suggestion

### Week 1: Foundations
- Day 1-2: Linked List, Stack, Queue
- Day 3-4: HashMap implementation
- Day 5-7: BST, practice problems

### Week 2: Algorithms
- Day 1-3: Sorting algorithms (Merge, Quick)
- Day 4-5: Graph representation, BFS
- Day 6-7: DFS, practice problems

### Week 3: LeetCode Problems
- Day 1: Two Sum + Valid Parentheses
- Day 2: Reverse Linked List
- Day 3: Maximum Subarray
- Day 4: Merge Two Sorted Lists
- Day 5-7: Review and practice variations

### Week 4: Integration
- Solve 10 new problems using learned patterns
- Implement variations (e.g., 3Sum, Max Stack)
- Practice explaining solutions verbally

---

## 🚀 Next Steps After Mastery

1. **More Data Structures:**
   - Heap / Priority Queue
   - Trie (Prefix Tree)
   - Segment Tree
   - Disjoint Set (Union-Find)

2. **More Algorithms:**
   - Dijkstra's Algorithm
   - Dynamic Programming patterns
   - Backtracking
   - Greedy algorithms

3. **LeetCode Grind:**
   - Solve 100+ problems
   - Focus on medium difficulty
   - Learn to recognize patterns quickly

4. **System Design:**
   - When to use which structure at scale
   - Distributed systems considerations
   - Caching strategies (HashMap in production)

---

## 💡 Pro Tips

1. **Don't Memorize - Understand:**
   - Understand WHY algorithms work
   - Know the invariants and assumptions
   - Practice explaining to others

2. **Draw Everything:**
   - Draw linked list pointer changes
   - Draw tree structures
   - Draw graph traversals

3. **Code Multiple Times:**
   - First time: understand
   - Second time: implement
   - Third time: optimize
   - Fourth time: teach someone

4. **Compare Implementations:**
   - Notice PHP vs JS differences
   - Understand language strengths
   - Learn transferable patterns

5. **Test Edge Cases:**
   - Empty inputs
   - Single element
   - All same elements
   - Negative numbers
   - Large inputs

---

## 📞 Quick Help

**Stuck on complexity analysis?**
→ Count loops and recursive calls

**Can't understand recursion?**
→ Start with base case, trust the recursive call

**HashMap collisions confusing?**
→ Think of it as array of linked lists

**Graph algorithms unclear?**
→ Draw small examples and trace execution

**Don't know which approach to use?**
→ Start with brute force, then optimize

---

## 🏆 Achievement Unlocked!

By completing this study pack, you've gained:

✨ **Solid foundation** in core data structures  
✨ **Interview-ready** algorithm knowledge  
✨ **Pattern recognition** for common problems  
✨ **Optimization skills** for time/space complexity  
✨ **Two-language proficiency** (PHP & JS)  

**You're now ready to tackle real coding interviews!**

---

*"The best way to learn is by doing. Code every example, break it, fix it, optimize it."*

**Happy Coding! 🎉**
