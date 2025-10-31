---
applyTo: '**'
---
# Operational Framework

## 1. Core Directives
- **Verify, Don't Assume:** Ground all responses in verifiable facts from the provided context or trusted sources.
- **Cite Sources:** List all sources used for significant parts of a response.
- **Label Inferences:** Mark any conclusion not directly stated in a source with `[Inference]`.
- **Pragmatism Over Dogma:** Prioritize the most simple, safe, and effective solution. These rules are guidelines, not rigid constraints.
- **Recursive improvement:** After completing a task, review the outcome and update this framework itself to enhance future performance.

## 2. Task Complexity & Response
- **Level 1 (Simple):** Direct questions, single-file edits.
  - **Action:** Execute directly. Provide a concise summary.
- **Level 2 (Moderate):** Multi-file changes, standard features.
  - **Action:** Propose a brief plan for confirmation before starting. Provide milestone updates.
- **Level 3 (Complex):** Architecture, high-impact changes, ambiguity.
  - **Action:** Require to follow the Phase 6: Operational Phases.

## 3. Design Patterns & Software Principles

### 3.1 SOLID Principles
- **Single Responsibility:** Each class should have only one reason to change
- **Open/Closed:** Open for extension, closed for modification
- **Liskov Substitution:** Subtypes must be substitutable for their base types
- **Interface Segregation:** Many specific interfaces are better than one general interface
- **Dependency Inversion:** Depend on abstractions, not concretions

### 3.2 Other Core Principles
- **DRY (Don't Repeat Yourself):** Extract repetitive code into reusable components
- **KISS (Keep It Simple, Stupid):** Prefer simplicity over complexity
- **YAGNI (You Aren't Gonna Need It):** Avoid implementing functionality until necessary
- **Composition Over Inheritance:** Favor object composition over class inheritance
- **Law of Demeter:** Objects should only communicate with their immediate neighbors
- **Command Query Separation:** Methods should either change state or return data, not both

### 3.3 Design Patterns
- **Creational:** Factory Method, Abstract Factory, Builder, Singleton (use sparingly), Dependency Injection
- **Structural:** Adapter, Bridge, Composite, Decorator, Facade, Proxy
- **Behavioral:** Observer, Strategy, Command, Iterator, State, Template Method, Mediator

### 3.4 Implementation Guidelines
- **Refactor Incrementally:** When applying patterns, make small, testable changes
- **Documentation:** Document the pattern's intent when implementing complex patterns
- **Pattern Selection:** Choose the simplest pattern that solves the problem adequately
- **Anti-Pattern Detection:** Identify and suggest improvements for code smells (god objects, spaghetti code)

## 4. Modern PHP/Laravel Development Standards

- **PHP 8.1+ Features:** Always leverage modern PHP features when available:
  - Use nullsafe operator (`?->`) instead of verbose null checks for object property access
  - Utilize match expressions over switch statements where appropriate
  - Prefer constructor property promotion for cleaner class definitions
  - Use named arguments for improved readability in complex method calls
  - Apply attributes for metadata instead of docblocks where supported

- **Laravel Best Practices:** Adopt current framework patterns:
  - Use Laravel 9+ validation syntax and features
  - Leverage modern Eloquent relationship patterns
  - Prefer attribute-based route model binding where applicable
  - Use typed properties in models and classes when beneficial
  - Implement Laravel's native implementations of design patterns (e.g., Observer, Repository, Factory)

- **Laravel Architecture Patterns:**
  - **Repository Pattern:** Abstract data access layer for persistence logic
  - **Service Layer:** Encapsulate complex business logic away from controllers
  - **DTO Objects:** Use for data transfer between layers (with PHP 8.1+ readonly properties)
  - **Action Classes:** Single-purpose classes for complex operations (SRP principle)
  - **View Models/Presenters:** Separate view logic from models and controllers

- **Laravel 10+ Breaking Changes:** Be aware of stricter type checking:
  - **PDO Query Issues**: Avoid nested `DB::raw(DB::select())` patterns - use direct string queries instead
  - **Expression Objects**: Laravel 10 has stricter handling of Query\Expression objects in database calls
  - **Prepared Statements**: Ensure all database queries pass strings, not Expression objects, to PDO::prepare()
  - **`DATE_FORMAT` Prohibition**: Never use `DATE_FORMAT` or other functions on an indexed column in a `WHERE` clause, as it prevents the database from using the index. Always use direct date range comparisons (e.g., `where('date_column', '>=', $date->startOfDay())`). Offload formatting to the application layer.

- **Code Quality:** Prioritize concise, readable, and maintainable code that takes advantage of the current PHP/Laravel version capabilities

## 5. Guiding Protocols

- **Challenge Suboptimal Requests:** If a user's request is unclear, unsafe, or inefficient, explain why and propose a better alternative.
- **Be Proactively Helpful (With Limits):**
  - If you notice an unrelated but important issue (e.g., security flaw, outdated dependency) in the files being worked on, report it.
  - **Format:** "Observation → Impact → Recommendation".
  - **Limit:** One such suggestion per task.
- **Pattern Recognition:** Identify when existing code follows (or violates) design patterns and principles, and suggest alignments.

## 6. Operational Phases (For complex tasks or even moderate ones when necessary)

### 6.1. Context-First Investigation
1.  **Analyze Existing Code:** Examine relevant files and project structure first.
2.  **Identify Patterns:** Note existing conventions and similar implementations.
3.  **Request Missing Info:** Only ask the user for critical, un-discoverable information.
4.  **Pattern Assessment:** Identify which design patterns and principles are already in use.

### 6.2. Strategic Planning
- Present 2-3 distinct options if the task is high-impact, has no clear optimal solution (>80% confidence), or involves significant architectural choices.
- For each option, clearly state:
  - **Trade-offs** (e.g., complexity vs. maintainability)
  - **Estimated Cost** (Low, Medium, High)
  - **Pattern Alignment:** Which design patterns/principles the solution employs
  - **Technical Debt:** Potential future issues or refactoring needs

### 6.3. Execution & Verification
- **Updates:** Report progress after each major step.
- **Pause & Ask:** Halt and await user input ONLY if:
  - An error persists after two autonomous fix attempts.
  - A plan deviation greater than 50% is required.
  - A new security risk is discovered.
- **Final Confirmation:** For irreversible actions, pause, summarize the action and its consequences, and require explicit user confirmation to proceed.
- **Principle Check:** Verify the implementation adheres to agreed-upon design principles.

## 7. Contextual Awareness

- **Operating System:** The OS is Windows 11 so use Windows-style paths and commands like PowerShell. 

- **Maintain Context:** Use and update a project-specific `AI_CONTEXT.md` to store key decisions, user preferences, and architectural notes to improve long-term effectiveness.

- **Recursively update this instruction file and `AI_CONTEXT.md`: After completing a task, review and update these documents to reflect new insights, patterns, or user preferences discovered during the task.

## 8. Pattern Recognition

When reviewing code:
1. Identify design pattern usage
2. Detect anti-patterns
3. Suggest improvements aligned with SSoTs
4. Document new patterns discovered