# 03 · Frontend Tutorial

React time! This guide walks you through the client experience, from rendering components to handling authenticated API calls.

---

## Chapter 1: React Fundamentals

### Component Lifecycle (Functional Components)
1. Component mounts → React renders UI based on props/state.
2. Effects run after paint (think: data fetching).
3. Updates trigger re-renders; React diffing efficiently updates the DOM.
4. Component unmounts → cleanup functions run.

### Hooks with Use Cases
- **`useState`** – local state, like form inputs.
  ```tsx
  const [count, setCount] = useState(0);
  ```
  *LEARNING NOTE:* State updates schedule re-renders; React batches them.

- **`useEffect`** – side effects (API calls, subscriptions).
  ```tsx
  useEffect(() => {
    fetchTasks();
  }, []);
  ```
  *Common mistake:* Forgetting dependency arrays, causing infinite loops.

- **`useContext`** – access global state via Context API (auth in this project).
  ```tsx
  const { user } = useAuthContext();
  ```

- **Custom Hooks** – reuse logic.
  ```tsx
  function useTasks() { /* wrap fetching logic */ }
  ```
  *TRY THIS:* Create `useDocumentTitle` to set the page title from components.

---

## Chapter 2: TypeScript in React
- Type props with interfaces: `interface TaskCardProps { task: Task; }`.
- Event typing: `React.FormEvent<HTMLFormElement>` for forms.
- Use generics with hooks (`useState<string | null>(null)`).
- Benefit: IDE shows valid properties, preventing undefined access.

---

## Chapter 3: State Management
- **Local State:** Component-specific (e.g., input value).
- **Global State:** Auth info shared across app via Context.
- **State Lifting:** Move state up when siblings need the same data.

**Context Pattern in Project:**
1. `AuthProvider` holds `user`, `token`, and actions.
2. `useAuthContext` exposes them to components.
3. `ProtectedRoute` reads auth state to guard pages.

---

## Chapter 4: Routing and Navigation
- Single-page apps change the view without reloading the page.
- `react-router-dom v6` uses `<BrowserRouter>`, `<Routes>`, `<Route>`.
- Protected routes check auth before rendering child routes.
- Navigation uses `useNavigate()` hook or `<Link>` components.

**TRY THIS:** Add a `/settings` route that renders “Coming Soon” and links back home.

---

## Chapter 5: API Integration
- **Axios vs Fetch:** Axios provides interceptors, automatic JSON parsing, unified error handling.
- **Async Handling:** Use try/catch inside `useEffect` or event handlers.
- **Loading States:** Show spinners to keep UX responsive.
- **Error Boundaries:** Wrap major layout areas to catch rendering errors gracefully.

**Pattern Highlight:**
1. Axios instance attaches JWT automatically via interceptor.
2. Requests hit Express routes; errors display via toast messages or inline hints.
3. `useMutation` pattern (even without React Query) keeps API calls isolated.

---

## Practice Exercises
1. **Exercise:** Add a toast notification when a task is completed.
2. **Exercise:** Create a custom hook `useApi` for reusable GET/POST logic.
3. **Exercise:** Implement optimistic UI updates for task deletion.
4. **Stretch:** Integrate React Query or SWR and compare developer experience.

> Next stop: Dive deeper into the API specifics in `docs/04-API-TUTORIAL.md`.
