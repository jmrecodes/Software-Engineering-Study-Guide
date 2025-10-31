# **Laravel & Software Engineering Study Guide**

This repository is a personal collection of code, guides, and case studies designed to bridge the gap between being a developer and becoming a software engineer. It focuses on modern PHP, Laravel, design patterns, software principles, and full-stack concepts.

## **Credits & Stack**

This project was planned and structured with the help of AI.

* **Author:** [jmrecodes](https://github.com/jmrecodes)  
* **Planning & Structuring:** Gemini 2.5 Pro  
* **Implementation & Verification (AI):** GitHub Copilot (via Codex / Opus 4.1 models)  
* **IDE:** VSCode

## **Table of Contents**

1. **Projects** 
   * [/01-ecommerce-refactor](/projects/01-ecommerce-refactor): A "Before & After" case study of a complete Laravel app.  
   * [/02-fullstack-js-starter](projects/02-fullstack-js-starter): A full-stack Node.js, React, and TypeScript starter kit.  
2. **Guides**
   * [/01-software-principles](/guides/01-software-principles): A practical "E-book" on DRY, KISS, YAGNI, and SOLID principles with PHP examples.  
   * [/02-design-patterns](/guides/02-design-patterns): An "E-book" on key design patterns (Strategy, Factory, etc.) for PHP & Laravel.  
3. **Snippets**
   * [/01-algorithms-data-structures](/snippets/01-algorithms-data-structures): A study pack of core algorithms and data structures implemented in PHP.

## **1\. Projects**

### [**/projects/01-ecommerce-refactor**](/projects/01-ecommerce-refactor)

This is the centerpiece of the repository: a complete "Before & After" case study of a real-world Laravel E-commerce "Shopping Cart & Order Checkout" application.

#### **/v1-bad-practice (The "Before" Version)**

This directory contains a monolithic, "bad practice" implementation. It is functional but intentionally difficult to maintain, featuring:

* **Fat Controllers:** All logic (validation, cart management, calculations, sending emails) is packed into a single OrderController.  
* **No Form Requests:** Validation is done manually with $request-\>validate().  
* **Facade/Session Abuse:** Cart logic is handled directly in the controller using session().  
* **No Service Layer:** All business logic is inline or in private controller methods.  
* **Tightly Coupled Code:** Mail::to(...) is called directly from the controller, and auth checks are done with if (auth()-\>id() ...)

#### **/v2-best-practice (The "After" Version)**

This directory contains the fully refactored, modern, and scalable "After" version of the *exact same application*. It demonstrates modern Laravel best practices:

* **Skinny Controllers:** The controller is thin and only handles the HTTP request/response.  
* **Service Classes:** Logic is abstracted into a CartService and OrderService.  
* **Repository Pattern:** Database queries are abstracted into an OrderRepository.  
* **Form Requests:** All checkout validation is in a StoreOrderRequest.  
* **Events & Listeners:** The OrderPlaced event is dispatched, triggering SendOrderConfirmationEmail and UpdateProductInventory listeners.  
* **Authorization:** All logic is handled in an OrderPolicy.  
* **Dependency Injection:** All dependencies are injected via the constructor.

A detailed CODE\_REVIEW.md file in this directory explains the "why" behind every change.

### [**/projects/02-fullstack-js-starter**](/projects/02-fullstack-js-starter)

This directory contains a complete, full-stack "starter-kit" project for building modern web applications with a JavaScript/TypeScript stack.

* **Backend (/backend):**  
  * **Stack:** Node.js, Express.js, and TypeScript.  
  * **API:** A RESTful API for a "Task" resource (CRUD).  
  * **ORM:** Prisma with a PostgreSQL schema for User and Task.  
  * **Auth:** JWT-based authentication (/register, /login) with protected route middleware.  
* **Frontend (/frontend):**  
  * **Stack:** React (with Vite) and TypeScript.  
  * **Patterns:** Functional components with React Hooks.  
  * **Routing:** react-router-dom for client-side routing.  
  * **State:** A simple login form and task management page. Global auth state is managed with a React Context.

## **2\. Guides**

### [**/guides/01-software-principles**](/guides/01-software-principles)

This is a comprehensive "E-book" titled **'The Software Engineer's Guide to Practical Principles (in PHP & Laravel).'**  
It is broken into two parts:  
Part 1: The Foundational Mindset  
Covers DRY, KISS, and YAGNI. Each principle includes:

* A simple, real-world analogy.  
* A clear explanation of the problem it solves.  
* A "Before" (problem) and "After" (solution) code example in PHP.  
* A specific Laravel scenario where the principle applies.

Part 2: The SOLID Principles (A Deep Dive)  
A detailed breakdown of all five SOLID principles (Single Responsibility, Open/Closed, Liskov Substitution, Interface Segregation, Dependency Inversion). Each principle includes:

* A simple, real-world analogy.  
* A full "Before" (violation) and "After" (solution) code implementation in PHP 8.x.  
* A classic example of how the principle is used in a typical Laravel application.

### [**/guides/02-design-patterns**](/guides/02-design-patterns)

This is a comprehensive "E-book" titled **'Design Patterns for the Modern PHP & Laravel Developer.'**  
It provides a full guide for each of the following essential patterns:

1. **Strategy Pattern**  
2. **Factory Method Pattern**  
3. **Observer Pattern**  
4. **Adapter Pattern**  
5. **Decorator Pattern**

Each pattern includes:

* A simple, real-world analogy.  
* A clear explanation of the problem it solves.  
* A full "Before" (problem) and "After" (solution) code implementation in PHP 8.x.  
* A specific example of how and where the pattern is used in Laravel (e.g., "The Strategy pattern is great for creating different payment gateways").

## **3\. Snippets**

### [**/snippets/01-algorithms-data-structures**](/snippets/01-algorithms-data-structures)

This directory contains a **'PHP Algorithm & Data Structure Study Pack'** with optimized, well-commented PHP 8.x code.  
**Core Data Structures:**

* Linked List (Singly and Doubly)  
* Stack  
* Queue  
* Hash Map (from scratch, explaining collision handling)  
* Binary Search Tree (with insert, delete, and find)

**Core Algorithms:**

* Merge Sort  
* Quicksort  
* Breadth-First Search (BFS) & Depth-First Search (DFS)

**Problem-Solving:**

* A collection of common "LeetCode-style" problems (like "Two Sum," "Valid Parentheses," etc.) with optimal PHP solutions and line-by-line explanations.

*This document was last updated on Sunday, November 2, 2025.*