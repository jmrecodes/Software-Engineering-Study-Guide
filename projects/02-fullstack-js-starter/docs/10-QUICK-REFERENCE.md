# 10 · Quick Reference

Keep this cheat sheet open while you build.

---

## Essential npm Commands
- `npm install` – Install dependencies.
- `npm run dev` – Start development server.
- `npm run build` – Create production build.
- `npm test` – Run tests (extend with your own).

## Prisma Commands
- `npx prisma migrate dev --name <description>` – Create & apply migration.
- `npx prisma generate` – Regenerate Prisma client.
- `npx prisma studio` – Launch visual data explorer.

## Git Commands
- `git status`
- `git add <file>`
- `git commit -m "message"`
- `git checkout -b new-branch`
- `git merge main`

## TypeScript Syntax Nuggets
- `interface User { id: string; email: string; }`
- `type Status = 'pending' | 'completed';`
- `const value = maybe ?? 'default';`
- `const safe = maybe?.nested?.value;`

## React Patterns
- `useState` for local state.
- `useEffect` for side effects.
- `useContext` for shared state.
- `useMemo` / `useCallback` for expensive calculations.
- `Suspense` and `ErrorBoundary` for async UI.

## File Structure Map
```
starter-kit/
  backend/
    src/
      routes/ controllers/ services/ middleware/ utils/
  frontend/
    src/
      components/ contexts/ hooks/ pages/ styles/
  docs/
    00-... to 10-... plus diagrams/
  exercises/
```

## Ports & URLs
- Frontend (Vite): `http://localhost:5173`
- Backend (Express): `http://localhost:4000`
- Prisma Studio: `http://localhost:5555`

## Environment Variables Checklist
- `DATABASE_URL`
- `JWT_SECRET`
- `PORT`
- `CLIENT_URL`
- `VITE_API_URL` (frontend)

## API Endpoints Summary
- `POST /api/auth/register`
- `POST /api/auth/login`
- `GET /api/tasks`
- `POST /api/tasks`
- `PUT /api/tasks/:id`
- `PATCH /api/tasks/:id/complete`
- `DELETE /api/tasks/:id`

> Bookmark this file. Add your own snippets as you learn.
