# 05 · Connecting Frontend & Backend

This guide shows how React and Express collaborate to deliver features from click to database.

---

## The Full Picture
```
User Action -> React Component -> Axios Request -> Express Router -> Controller -> Service -> Prisma -> PostgreSQL
                                                  ^                                        |
                                                  |----------------------------------------|
```
- The frontend sends requests via Axios using the base URL from `.env`.
- The backend processes, touches the database, and returns JSON.
- React updates UI state based on the response.

## Step-by-Step Data Flow Example
**Scenario:** User creates a task.
1. User fills the “Add Task” form and clicks submit.
2. Component triggers `handleCreateTask`.
3. Axios POST `/api/tasks` sends `{ title, description }` with JWT header.
4. Express route `/api/tasks` receives the request.
5. Validation middleware checks body fields.
6. Auth middleware verifies JWT, attaches `req.user`.
7. Controller calls `taskService.createTask(userId, payload)`.
8. Service invokes `prisma.task.create`.
9. Prisma writes to PostgreSQL and returns the new task.
10. Controller responds with `201 Created` and task data.
11. Frontend updates state, re-renders task list.

## CORS Explained
- Browsers enforce the Same-Origin Policy; cross-domain requests need **CORS** (Cross-Origin Resource Sharing) headers.
- Express uses `cors` middleware to whitelist the frontend origin (e.g., `http://localhost:5173`).
- Headers added:
  - `Access-Control-Allow-Origin`
  - `Access-Control-Allow-Methods`
  - `Access-Control-Allow-Headers`
- Without CORS, browser blocks API calls despite the backend responding.

## Authentication Flow Diagram
```
[Frontend] Login Form -> POST /auth/login -> [Backend] Validate & Sign JWT
[Backend] -> Respond { token } -> [Frontend] Store token (localStorage)
[Frontend] Axios Interceptor -> Attach token for protected routes
[Backend] Auth Middleware -> Verify token -> Allow access or 401
```

## Debugging Connection Issues
- **Symptom:** Network tab shows `CORS error` → Check backend CORS config and ensure server restarts after changes.
- **Symptom:** `401 Unauthorized` → Token missing or expired; inspect Axios interceptor and local storage.
- **Symptom:** `404 Not Found` → Verify route path and base URL.
- **Symptom:** `500 Internal Server Error` → Check backend logs; look at error middleware output.
- **Tip:** Use the browser Network tab + backend console logs together for full visibility.

> Keep both servers running during development for instant feedback. Try editing the frontend form and seeing the request hit Express in real time.
