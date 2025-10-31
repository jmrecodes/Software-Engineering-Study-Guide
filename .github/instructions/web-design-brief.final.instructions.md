---
applyTo: '**'
---

# Web Design System Brief
* **CSS File Structure:** MANDATORY external CSS files for component-specific styles. The goal is a clear separation of concerns:
  - `design-system.css`: Core file for global design tokens (CSS variables for colors, spacing, fonts), and base element styles. This file should be lean and foundational.
  - **Shared Feature-Specific Stylesheets (e.g., `forms.css`, `tables.css`, `modals.css`):** Create separate CSS files for common, reusable patterns that are shared across multiple components but are not universal design tokens. This keeps the `design-system.css` clean while promoting reuse for specific features.
  - `component-name.css`: Component-specific styles that are unique to one component and not reusable elsewhere (e.g., `projects-management.css`, `user-profile.css`).
  - **PROHIBITED**: Inline `@push('styles')` with `<style>` tags in component views.
  - **PATTERN**: Use `<link rel="stylesheet" href="{{ asset('css/filename.css') }}">` in `@push('styles')` sections.

## Your Mission

As GitHub Copilot, your mission is to generate front-end code that exemplifies software engineering excellence through both visual design best practices and code architecture principles. When generating UI components, markup, styles, or interaction code, adhere to the principles of DRY (Don't Repeat Yourself), SOLID, component-based architecture, and accessibility to create maintainable, reusable, and user-friendly interfaces.

## Core Design Principles

### 1. Visual Design & Accessibility
* **Simplicity:** Create minimalist, purpose-driven designs that prioritize function over decoration
* **Consistency:** Maintain a cohesive visual language through systematic design tokens and patterns
* **Accessibility:** Generate WCAG 2.1 AA compliant code by default (proper contrast, keyboard navigation, semantic structure)
* **Responsiveness:** Design adaptive interfaces that function optimally across all screen sizes

### 2. Code Architecture & Software Engineering Principles

#### 2.1 DRY (Don't Repeat Yourself)
* **Design Token System:** Implement CSS variables/design tokens for all repeating visual attributes:
  ```css
  :root {
    --color-primary: #3b82f6;
    --spacing-unit: 0.25rem;
    --border-radius-sm: 4px;
    /* Use mathematical relationships for scale */
    --spacing-xs: calc(var(--spacing-unit) * 1); /* 0.25rem */
    --spacing-sm: calc(var(--spacing-unit) * 2); /* 0.5rem */
    --spacing-md: calc(var(--spacing-unit) * 4); /* 1rem */
  }
  ```
* **Component Extraction:** When similar UI patterns appear more than once, extract to reusable components
* **Utility Class Strategy:** For frameworks using utility classes (Tailwind, Bootstrap), create component classes for repeating patterns:
  ```css
  .card-standard {
    @apply rounded-lg shadow-md p-4 bg-white dark:bg-gray-800;
  }
  ```

#### 2.2 Component-Based Architecture
* **Single Responsibility:** Each component should do one thing well
* **Composition Over Inheritance:** Build complex UIs through component composition rather than extending existing ones
* **Stateful/Stateless Separation:** Keep visual components stateless when possible, manage state in container components
* **Interface Consistency:** Maintain consistent prop/parameter patterns across similar component types

#### 2.3 SOLID Principles for UI
* **Open/Closed:** Components should be open for extension but closed for modification
* **Interface Segregation:** Split complex components into smaller, focused ones
* **Dependency Inversion:** High-level components should not depend on low-level implementation details

## 3. Implementation Guidelines

### 3.1 Styling Architecture & CSS Organization
* **CSS File Structure:** MANDATORY external CSS files for component-specific styles
  - `design-system.css`: Global design tokens, CSS variables, utility classes
  - `component-name.css`: Component-specific styles (e.g., `login.css`, `dashboard.css`)
  - **PROHIBITED**: Inline `@push('styles')` with `<style>` tags in component views
  - **PATTERN**: Use `<link rel="stylesheet" href="{{ asset('css/component-name.css') }}">` in `@push('styles')` sections
* **CSS Organization:** Follow BEM (Block Element Modifier) methodology for component styling
* **Style Hierarchy**: 
  1. Global design tokens (design-system.css) 
  2. Component-specific external files
  3. Framework utilities (Bootstrap, Tailwind) 
  4. NO inline styles except for dynamic values
* **Scoped Styles:** Implement proper style encapsulation through naming conventions
* **Responsive Design:** Use mobile-first approach with strategic breakpoints
* **Dark Mode Support: (if specifically requested)** Design for both light and dark themes using CSS variables (NOT YET IMPLEMENTED GLOBALLY - do not add @media (prefers-color-scheme: dark) queries)
* **CRITICAL - CSS Variable Reuse (ENFORCEMENT REQUIRED):**
  - **ALWAYS reference existing CSS variables** from design-system.css
  - **DO NOT redefine** variables that already exist (e.g., --spacing-*, --color-*, --border-radius-*, --shadow-*)
  - **Before creating ANY new CSS file, MANDATORY checks:**
    1. Open design-system.css and review ALL existing variables
    2. Check if similar component CSS already exists (e.g., UsersManagement for ProjectsManagement)
    3. Can I use existing utility classes instead of custom styles?
  - **PROHIBITED Actions:**
    - Redefining spacing scale (--spacing-xs, --spacing-sm, etc.)
    - Redefining color tokens (--color-primary, --color-text, etc.)
    - Redefining border radius values (--border-radius-sm, --border-radius-md, etc.)
    - Redefining shadow values (--shadow-sm, --shadow-md, etc.)
    - Adding dark mode @media queries (not implemented globally)
  - **Only define new variables** when truly unique to the component and not reusable
  - **Prefer composition** of existing variables over creating new ones
  - **Example of CORRECT reuse:**
    ```css
    /* ✅ CORRECT - Reuses existing variables */
    .my-component {
        padding: var(--spacing-md);
        border-radius: var(--border-radius-lg);
        box-shadow: var(--shadow-sm);
        color: var(--color-text);
    }
    ```
  - **Example of WRONG approach:**
    ```css
    /* ❌ WRONG - Redefines existing variables */
    .my-component {
        --spacing-md: 1rem; /* Already defined in design-system.css! */
        padding: var(--spacing-md);
    }
    ```

* **CRITICAL - Generic Component Styles in `design-system.css`:**
  - **Rule:** Common UI patterns found in multiple components (e.g., management pages, modals) MUST be defined as generic, reusable classes in `design-system.css`.
  - **Component-specific CSS files (`component-name.css`) should ONLY contain styles that are unique and not applicable to any other component.**
  - **Examples of Generic Styles for `design-system.css`:**
    - `.management-content`: The main content block for a management page.
    - `.search-container`, `.search-input-wrapper`: Standardized search bar styling.
    - `.empty-state`: Styling for when a table or list has no data.
    - `.modal-header`, `.modal-body`, `.modal-footer`: Base modal styling, not tied to a specific modal ID. Use generic classes instead of `#myModal .modal-header`.
    - `.action-buttons`: Consistent styling for button groups in table rows or headers.
  - **Workflow:**
    1. Before creating a new style, ALWAYS check `design-system.css` for an existing utility or component class.
    2. If a style is needed in more than one place, add it to `design-system.css` with a generic name.
    3. Avoid using IDs (`#my-component`) for styling. Use classes instead.

* **CRITICAL - Select2 Modal Isolation (Implemented in `design-system.css`):**
  - **Problem:** When opening a modal that contains Select2 dropdowns while the page has other Select2 dropdowns, Select2's internal focus management can inadvertently highlight/select page-level dropdowns behind the modal.
  - **Solution:** CSS-based visual suppression pattern (already implemented in design-system.css):
    ```css
    /* Suppress Select2 containers outside modal */
    .modal-open .select2-container:not(.select2-container--open) {
        opacity: 0.5 !important;
        pointer-events: none !important;
        filter: grayscale(50%);
    }
    
    /* Ensure Select2 dropdowns INSIDE modals remain functional */
    .modal-open .modal .select2-container {
        opacity: 1 !important;
        pointer-events: auto !important;
        filter: none !important;
    }
    ```
  - **Why CSS over JavaScript:**
    - ✅ Browser-agnostic (works consistently across all browsers)
    - ✅ Timing-independent (no race conditions with modal animations)
    - ✅ Automatic (no manual event handlers needed per component)
    - ✅ Visual guarantee (forcefully overrides Select2's internal styling)
    - ✅ Prevents interaction (`pointer-events: none`)
  - **DO NOT implement JavaScript blur handlers** for this issue - the CSS pattern handles it globally
  - **This pattern is already active** - no per-component implementation required

### 3.2 Markup Structure
* **Semantic HTML:** Always use the most semantically appropriate HTML elements
* **Progressive Enhancement:** Ensure core functionality works without JavaScript when possible
* **Accessibility:** Include proper ARIA attributes, keyboard navigation, and focus management
* **Performance:** Minimize DOM depth and prioritize structural simplicity

### 3.3 JavaScript Patterns
* **Event Delegation:** Use event delegation for multiple similar interactive elements
* **Pure Functions:** Implement pure functions for predictable behavior and testability
* **State Management:** Choose appropriate state management based on component complexity
* **Unidirectional Data Flow:** Maintain clear parent-to-child data flow with events for upward communication

## 4. Framework-Specific Best Practices

### 4.1 React
* **Hooks for State:** Prefer functional components with hooks over class components
* **Memoization:** Use React.memo, useMemo, and useCallback for performance optimization
* **Context API:** Implement Context API for theming and global state that doesn't require Redux
* **Prop Drilling Solution:** Use composition or context to avoid excessive prop drilling

### 4.2 Vue
* **Composition API:** Prefer Composition API for complex components
* **Single File Components:** Keep template, script, and style in a single .vue file
* **Computed Properties:** Use computed properties instead of complex template expressions
* **Provide/Inject:** Use provide/inject for deeply nested component communication

### 4.3 Angular
* **OnPush Change Detection:** Implement OnPush change detection strategy for performance
* **Reactive Forms:** Prefer reactive forms over template-driven forms for complex validation
* **Smart/Dumb Components:** Separate container (smart) components from presentational (dumb) components
* **NgRx Pattern:** Follow NgRx patterns for state management in complex applications

### 4.4 Laravel + Livewire (Design-only scope)
This brief focuses on CSS and visual consistency. For Livewire architecture, JS lifecycle, and cascading Select2 patterns, see the Livewire Best Practices document. Highlights relevant to design:
* **Layout & CSS:** Use Livewire's Layout attribute for full-page components; keep modal markup stable with `wire:ignore.self`. Link external CSS via `@push('styles')`; avoid inline styles.
* **Loading/Feedback:** Ensure visible states for interactions (`.loading-overlay`, button spinners). Implementation details live in Bootstrap Integration and Livewire Best Practices.
* **Dropdowns:** All selects use Select2 for consistent visuals. Lifecycle and cascade logic are defined in Livewire Best Practices §2.7–2.8. This brief owns only the styling (including modal isolation below).
* **State Management:**
    *   **CRITICAL - Eloquent Object Serialization**: When passing Eloquent models with joined columns to child components:
        - Convert to arrays immediately in `mount()`: `$this->data = is_object($data) ? $data->toArray() : $data;`
        - Access properties via array syntax throughout component: `$data['column']` not `$data->column`
        - Livewire serialization converts objects to arrays during hydration, causing type mismatches if using object access
        - This prevents "object instead of array" hydration errors
    *   Store only simple, serializable data (strings, integers, arrays of scalars) in `public` properties.
    *   NEVER store complex objects (DB::raw, Paginator, Collection with relations) in public properties
    *   Resolve complex objects (e.g., `DB::raw`) "just-in-time" within the `render()` method.
    *   **Event-Driven Refresh**: After mutations, dispatch events to trigger row-level refreshes with identical query structure as parent
* **CRITICAL - Livewire 3 Event Handling (MANDATORY):**
    *   **Rule**: When dispatching events from PHP with named parameters, the JavaScript listener receives a single `event` object containing those parameters. Access them directly as properties of the object or use optional destructuring.
    *   **Correct Pattern 1: Accessing via Event Object (Recommended)**
        ```javascript
        // Listening to events and accessing properties from the event object
        $wire.on('event-name', (event) => {
            console.log(event.param1);
            console.log(event.param2);
        });
        ```
    *   **Correct Pattern 2: Using Object Destructuring (Optional)**
        ```javascript
        // Using destructuring for more concise code
        $wire.on('event-name', ({ param1, param2 }) => {
            // Use param1, param2 directly
        });
        ```
    *   **INCORRECT Pattern** (Legacy Livewire 2 - DO NOT USE):
        ```javascript
        // ❌ WRONG - Array indexing (Livewire 2 pattern)
        Livewire.on('event-name', (event) => {
            const param1 = event[0].param1; // This will fail in Livewire 3
        });
        ```
    *   **Why This Matters**:
        - Livewire 3 unified its event API. Named parameters from PHP are always wrapped in a single object on the frontend.
        - The legacy array-based access (`event[0]`) is deprecated and will cause JavaScript errors.
        - Understanding both correct patterns allows for flexibility while ensuring code works reliably.
    *   **Implementation Examples**:
        - ✅ `$wire.on('stages-updated', (event) => { /* use event.stages */ })`
        - ✅ `$wire.on('children-updated', ({ children, selectedChildId }) => { /* use both destructured variables */ })`
        - ❌ `Livewire.on('stages-updated', (event) => { const stages = event[0].stages; })`
    *   **When to Use**: This applies to ALL Livewire event listeners in `@script` blocks, modal initializations, and component interactions.
* **CRITICAL - Design Consistency Requirements:**
    *   **Always reference existing implementations** before creating new components
    *   **Maintain consistent structure patterns** across similar component types (e.g., all management pages use the same layout structure)
    *   **Required elements for management pages:**
        - `wire:loading` overlay with loading-overlay/loading-spinner classes
        - Consistent page-header with page-title structure
        - Search container with search-input-wrapper and a dedicated clear button (`.search-clear`)
        - Sort buttons (not direct wire:click on th elements)
        - Empty state with consistent styling (icon, title, message). The empty state message should NOT contain its own "Clear search" button; this functionality is handled by the main search input's clear button.
        - Pagination container placement
        - Table structure: parent component wraps tbody, not individual rows
    *   **Before implementing, check:**
        1. Does a similar component already exist? (e.g., UsersManagement for ProjectsManagement)
        2. What patterns does it use for search, sorting, loading, pagination?
        3. What CSS class names and structure does it follow?
        4. Does it use wire:loading properly?
    *   **Consistency over innovation:** When adding new features to existing component types, follow established patterns exactly unless there's a compelling technical reason to diverge
* **CRITICAL - Modal-Based CRUD Pattern (MANDATORY):**
    *   **Rule**: ALL management pages MUST use modal-based creating, editing, and deletion with dedicated modals for each operation
    *   **When to Use Inline Editing**: ONLY for simple single-field updates:
        - Status toggles (active/inactive)
        - Simple boolean flags
        - Single text field updates with no validation
        - Actions that can be completed in one click with no user input
    *   **When to Use Modal-Based CRUD**: For ALL other scenarios:
        - Forms with multiple fields (2+ inputs)
        - Forms with cascading/dependent dropdowns
        - Forms requiring validation feedback
        - Forms with relationships (foreign keys, associations)
        - Any edit operation requiring user confirmation
        - Delete operations (ALWAYS use confirmation modal)
        - Create operations (ALWAYS use dedicated "Add" modal, never reuse "Edit" modal)
    *   **Add Modal Requirements (New Records)**:
        - Dedicated "Add {Entity}" button in management page action bar (top-right, `.btn-success`)
        - Separate `Add{Entity}Modal` component (never dual-purpose "Edit" modals)
  - Validation via attribute-based rules with centralized `$this->validate()` call
        - Duplicate detection before insertion with inline `$errorMessage` property
        - 1:1 replication of legacy AdminController logic for backward compatibility
        - Event-driven communication: `open-add-{entity}-modal` → `{entity}-created` → parent refresh
        - Loading states on submit buttons: `wire:loading` with spinner and disabled state
        - Select2 integration (if dropdowns): `wire:ignore`, `dropdownParent`, `$wire.set()` sync
    *   **Benefits of Modal-Based Pattern**:
        1. **Separation of Concerns**: Row components display only, modals handle all mutations
        2. **Consistent UX**: Same pattern across all management pages for create/edit/delete
        3. **Better Validation**: Inline error messages in modal context
        4. **Cascading Support**: Proper space for dependent dropdown logic
        5. **Loading States**: Centralized loading feedback during save/delete
        6. **Maintainability**: Easier to test and extend modal components
        7. **Clear Intent**: Dedicated "Add" modals are more intuitive than dual-mode "Edit" modals
    *   **Anti-Pattern Warning**: Inline editing with dropdowns/complex forms creates:
        - Mixed responsibilities in row components
        - Hydration complexity with protected collections
        - Inconsistent UX across application
        - Difficult validation feedback
        - Poor loading state management
  *   **Anti-Pattern Warning (second)**: Dual-purpose modals (using "Edit" for both create and update) create:
        - Complex conditional logic in single component
        - Confusing UX (unclear whether creating or updating)
        - Difficult to maintain default values and validation rules
        - Increased cognitive load for developers and users
    *   **Reference Implementation**: 
        - Edit/Delete: ProjectsManagement, StagesManagement, ProcessesManagement, TargetsManagement, ProcessUsageManagement
        - Add: AddProcessGroupModal, AddProcessModal, AddProjectModal, AddRoleModal, AddStageModal, AddTargetModal, AddUserModal
    *   **CRITICAL**: When creating new management pages, implement THREE separate modals: Add{Entity}Modal, Edit{Entity}Modal, and Delete{Entity}Modal. Never create dual-purpose modals.

## 5. Performance & Optimization

* **Critical Rendering Path:** Optimize the critical rendering path with minimal blocking resources
* **Bundle Size:** Implement code splitting and lazy loading for improved initial load time
* **Image Optimization:** Use responsive images, WebP format, and appropriate compression
* **Animation Performance:** Prefer CSS transitions and animations that utilize GPU acceleration

## 6. Testing Approach

* **Component Testing:** Design components with testability in mind
* **Accessibility Testing:** Consider programmatic accessibility testing in component design
* **Visual Regression:** Structure components to support visual regression testing
* **State Testing:** Ensure components handle all possible states (loading, error, empty, filled)

## Code Examples

### Component Design Pattern (React)
```jsx
// Button.jsx - Reusable component with variants
import React from 'react';
import './Button.css'; // Or styled-components, CSS Modules, etc.

// Single responsibility component with clear API
export const Button = ({ 
  children, 
  variant = 'primary', 
  size = 'medium',
  disabled = false, 
  onClick,
  type = 'button',
  ...props // Open for extension
}) => {
  const baseClass = 'btn';
  const classes = [
    baseClass,
    `${baseClass}--${variant}`,
    `${baseClass}--${size}`,
    disabled ? `${baseClass}--disabled` : ''
  ].filter(Boolean).join(' ');

  return (
    <button
      className={classes}
      disabled={disabled}
      type={type}
      onClick={disabled ? undefined : onClick}
      aria-disabled={disabled}
      {...props}
    >
      {children}
    </button>
  );
};

// Usage example
// <Button variant="secondary" size="large" onClick={handleClick}>Submit</Button>
```

### CSS Architecture Pattern
```scss
// _button.scss - BEM methodology with design tokens

// Import design tokens
@import 'tokens';

// Block
.btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  border-radius: var(--border-radius-md);
  font-weight: 500;
  transition: all 0.2s ease;
  
  // Element
  &__icon {
    margin-right: var(--spacing-xs);
  }
  
  // Modifiers - Variants
  &--primary {
    background-color: var(--color-primary);
    color: var(--color-white);
    
    &:hover:not(:disabled) {
      background-color: var(--color-primary-dark);
    }
    
    &:focus {
      outline: none;
      box-shadow: 0 0 0 3px var(--color-primary-light);
    }
  }
  
  &--secondary {
    background-color: transparent;
    color: var(--color-primary);
    border: 1px solid var(--color-primary);
    
    &:hover:not(:disabled) {
      background-color: var(--color-primary-lightest);
    }
  }
  
  // Modifiers - Sizes
  &--small {
    padding: var(--spacing-xs) var(--spacing-sm);
    font-size: var(--font-size-sm);
  }
  
  &--medium {
    padding: var(--spacing-sm) var(--spacing-md);
    font-size: var(--font-size-base);
  }
  
  &--large {
    padding: var(--spacing-md) var(--spacing-lg);
    font-size: var(--font-size-lg);
  }
  
  // State modifiers
  &--disabled {
    opacity: 0.6;
    cursor: not-allowed;
  }
}
```

### Form Component Design Pattern
```html
<!-- Accessible form field component structure -->
<div class="form-field">
  <label for="username" class="form-field__label">
    Username
    <span class="form-field__required" aria-hidden="true">*</span>
    <span class="sr-only">(Required)</span>
  </label>
  
  <div class="form-field__input-wrapper">
    <input 
      type="text" 
      id="username" 
      class="form-field__input" 
      aria-describedby="username-hint username-error" 
      aria-invalid="false"
      required
    />
    
    <!-- Icon placement with proper accessibility -->
    <span class="form-field__icon" aria-hidden="true">
      <!-- SVG icon here -->
    </span>
  </div>
  
  <!-- Help text with proper ID for aria-describedby -->
  <p id="username-hint" class="form-field__hint">
    Choose a username between 3-15 characters
  </p>
  
  <!-- Error message structure -->
  <p id="username-error" class="form-field__error" role="alert">
    <!-- Dynamic error message here -->
  </p>
</div>
```

## 7. Additional Guidance for Production Code

### 7.1 Code Quality Metrics
* **Complexity:** Keep component logic below cyclomatic complexity of 10
* **Reusability:** Design components for 80% use cases, add props for edge cases
* **Component Size:** Limit components to ~300 lines, extract when exceeding
* **Dependency Management:** Minimize external dependencies, prefer standard browser APIs

### 7.2 Documentation
* Include JSDoc or similar documentation for component props/params
* Document state management approaches for complex components
* Provide usage examples for reusable components
* Document accessibility considerations and keyboard shortcuts

### 7.3 Naming Conventions
* Use descriptive, intention-revealing names for components, functions, and variables
* Follow consistent naming patterns across similar component types
* Use meaningful prefixes for component variants (e.g., ButtonPrimary, ButtonSecondary vs. PrimaryButton, SecondaryButton)

---

When generating code, always remember:
1. **Maintainability** over cleverness
2. **Consistency** over personal preference
3. **Accessibility** as a fundamental requirement, not an add-on
4. **Performance** as a core feature, not an optimization

Apply these principles to create UI code that's not just visually appealing, but also technically excellent.
