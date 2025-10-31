<?php

declare(strict_types=1);

/**
 * PROBLEM 2: Valid Parentheses
 * 
 * Difficulty: Easy
 * 
 * Problem Statement:
 * Given a string s containing just the characters '(', ')', '{', '}', '[' and ']',
 * determine if the input string is valid.
 * 
 * An input string is valid if:
 * 1. Open brackets must be closed by the same type of brackets
 * 2. Open brackets must be closed in the correct order
 * 3. Every close bracket has a corresponding open bracket of the same type
 * 
 * Example 1:
 * Input: s = "()"
 * Output: true
 * 
 * Example 2:
 * Input: s = "()[]{}"
 * Output: true
 * 
 * Example 3:
 * Input: s = "(]"
 * Output: false
 * 
 * Example 4:
 * Input: s = "([)]"
 * Output: false
 * 
 * Example 5:
 * Input: s = "{[]}"
 * Output: true
 */

class ValidParentheses
{
    /**
     * OPTIMAL APPROACH: Stack
     * 
     * Time Complexity: O(n) - single pass through string
     * Space Complexity: O(n) - worst case all opening brackets
     * 
     * Strategy:
     * - Use stack (LIFO) to track opening brackets
     * - When we see opening bracket, push to stack
     * - When we see closing bracket, check if it matches top of stack
     * - String is valid if stack is empty at the end
     */
    public static function isValid(string $s): bool
    {
        // Line 1: Initialize empty stack to track opening brackets
        // We use PHP array as stack (push/pop from end)
        $stack = [];
        
        // Line 2: Create mapping of closing brackets to opening brackets
        // This allows O(1) lookup to check if brackets match
        $pairs = [
            ')' => '(',
            '}' => '{',
            ']' => '['
        ];
        
        // Line 3: Iterate through each character in string
        for ($i = 0; $i < strlen($s); $i++) {
            $char = $s[$i];
            
            // Line 4: Check if current character is a closing bracket
            // If it's in our pairs array as key, it's a closing bracket
            if (isset($pairs[$char])) {
                // Line 5: Pop the top element from stack
                // If stack is empty, use dummy value that won't match
                $top = empty($stack) ? '#' : array_pop($stack);
                
                // Line 6: Check if popped element matches expected opening bracket
                // $pairs[$char] gives us the opening bracket that should match
                if ($top !== $pairs[$char]) {
                    // Line 7: Mismatch found - invalid
                    return false;
                }
            } else {
                // Line 8: Current character is opening bracket
                // Push it onto stack to match with future closing bracket
                $stack[] = $char;
            }
        }
        
        // Line 9: String is valid only if stack is empty
        // Empty stack means all opening brackets were matched and closed
        // Non-empty means some opening brackets were never closed
        return empty($stack);
    }
}

/**
 * WALKTHROUGH EXAMPLE:
 * 
 * Input: s = "([{}])"
 * 
 * Step 1: char='(' (opening) -> push to stack
 *   stack = ['(']
 * 
 * Step 2: char='[' (opening) -> push to stack
 *   stack = ['(', '[']
 * 
 * Step 3: char='{' (opening) -> push to stack
 *   stack = ['(', '[', '{']
 * 
 * Step 4: char='}' (closing) -> pop and check
 *   popped = '{', expected = '{' ✓ match
 *   stack = ['(', '[']
 * 
 * Step 5: char=']' (closing) -> pop and check
 *   popped = '[', expected = '[' ✓ match
 *   stack = ['(']
 * 
 * Step 6: char=')' (closing) -> pop and check
 *   popped = '(', expected = '(' ✓ match
 *   stack = []
 * 
 * Result: stack is empty -> VALID ✓
 */

/**
 * WHY STACK?
 * 
 * Stack is perfect because brackets must be closed in reverse order of opening.
 * Last opened bracket must be first closed (LIFO - Last In, First Out).
 * 
 * Example: "([{" must close as "}])"
 * - '{' opened last, so it must close first with '}'
 * - '[' opened second, so it must close second with ']'
 * - '(' opened first, so it must close last with ')'
 */

// Test cases
if (basename(__FILE__) === basename($_SERVER['PHP_SELF'] ?? '')) {
    echo "=== Problem 2: Valid Parentheses ===\n\n";
    
    $testCases = [
        "()",          // true
        "()[]{}",      // true
        "(]",          // false
        "([)]",        // false
        "{[]}",        // true
        "(((",         // false
        ")))",         // false
        "([{}])",      // true
        "",            // true (empty string is valid)
    ];
    
    foreach ($testCases as $i => $test) {
        $result = ValidParentheses::isValid($test);
        echo "Test Case " . ($i + 1) . ":\n";
        echo "Input: \"$test\"\n";
        echo "Output: " . ($result ? "true" : "false") . "\n";
        echo "\n";
    }
}
