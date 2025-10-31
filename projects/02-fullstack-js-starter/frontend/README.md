# Frontend Learning Guide

This directory hosts the React + Vite + TypeScript client. Each file contains annotated comments to explain React patterns and design choices.

## Scripts
```bash
npm install     # Install dependencies
npm run dev     # Start Vite dev server with hot module replacement
npm run build   # Produce optimized production bundle
npm run preview # Preview the built site locally
npm run lint    # Lint the codebase with ESLint
```

## Quick Start
1. Copy `.env.example` to `.env` and point `VITE_API_URL` at your running backend (`http://localhost:4000/api` by default).
2. Install dependencies with `npm install`.
3. Launch the dev server via `npm run dev` and open the printed URL (typically `http://localhost:5173`).
4. Register or log in, then explore the task flow. The guided comments in each component highlight what to pay attention to.

## Testing Your Understanding
- Toggle the network tab in DevTools and confirm the Axios interceptor attaches the `Authorization` header after login.
- Add a new route in `App.tsx` (for example, an `/about` page) and wire it up with a link in `Layout.tsx` to practice React Router basics.
- Experiment in `contexts/AuthContext.tsx` by changing the persistence strategy (for example, swap `localStorage` for `sessionStorage`) and observe the impact.

## Architecture Overview
```
src/
  main.tsx        # Entry point, renders React tree
  App.tsx         # Top-level routes and layout
  components/     # Reusable UI components
  contexts/       # React Context providers (AuthContext)
  hooks/          # Custom hooks for reuse
  pages/          # Feature pages (Login, Register, Tasks)
  services/       # API clients (Axios instance and wrappers)
  styles/         # CSS Modules for scoped styling
```

## Learning Tips
- Start with `src/main.tsx` to understand how React renders into the DOM.
- Trace routing flow in `App.tsx` and the `pages/` directory.
- Inspect `contexts/AuthContext.tsx` to see how global state is managed.
- Explore `services/apiClient.ts` for Axios interceptors that attach JWT tokens automatically.

> **TRY THIS:** Duplicate a page component into `pages/Playground.tsx` and experiment freely—break things, then fix them to solidify your knowledge.
