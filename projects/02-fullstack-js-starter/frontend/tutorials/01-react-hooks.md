# Tutorial · React Hooks in Context

Follow along to see how hooks power the application state.

1. Open `src/contexts/AuthContext.tsx` to observe `useReducer` managing auth state transitions.
2. Inspect `src/hooks/useTasks.ts` (created for you) to see custom hooks combining state and effects.
3. Modify the hook to introduce an artificial delay (`setTimeout`) and observe loading indicators.
4. Experiment with `useMemo` in `TaskList` to compute derived values (e.g., completed count).

**Challenge:** Create a `useOnlineStatus` hook that listens for browser `online/offline` events and display a banner when offline.
