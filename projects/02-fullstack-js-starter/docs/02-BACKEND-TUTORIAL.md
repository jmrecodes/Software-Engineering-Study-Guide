# 02 · Backend Tutorial

Welcome to the server side! This guided tour explains what happens from `npm run dev` to database updates. Keep your editor open and follow along with the code comments.

---

## Chapter 1: Understanding the Server

### What Happens When You Run `npm run dev`
1. The script in `backend/package.json` runs `ts-node-dev --respawn src/index.ts`.
2. TypeScript files compile in-memory and reload on save.
3. `src/index.ts` loads environment variables, validates them, and starts the HTTP server.
4. Express listens for requests on the port defined in `.env`.

**TRY THIS:** Add a `console.log("Server boot sequence step X")` inside `src/index.ts` to visualize the startup order.

### Express.js Basics with Analogies
- Think of Express as a train station.
- **Routes** are platforms (e.g., `/api/tasks`) where trains (requests) arrive.
- **Middleware** are checkpoints that inspect passengers (data) before letting them board.
- **Controllers** are the conductors who decide where trains go next (database operations).

### Middleware Pipeline Visualization
```
Request -> Logging -> CORS -> JSON Parser -> Validation -> Auth -> Route Handler -> Response
```
- Order matters. Logging occurs before validation, so you can debug failed inputs.
- Authentication happens before protected routes to ensure only valid users proceed.

---

## Chapter 2: TypeScript in the Backend

### Why TypeScript?
- Catches typos before runtime errors.
- Offers IntelliSense for faster development.
- Documents data shapes explicitly.

**Before (JavaScript):**
```js
function createTask(data) {
  return prisma.task.create({ data });
}
```
**After (TypeScript):**
```ts
interface CreateTaskInput {
  title: string;
  description?: string;
}

function createTask(data: CreateTaskInput) {
  return prisma.task.create({ data });
}
```

### Types vs Interfaces
- **Types:** Great for unions (`type AuthMode = 'jwt' | 'session'`).
- **Interfaces:** Ideal for object shapes and extending (`interface Task extends BaseModel { ... }`).

### Common Patterns in This Project
- `type` aliases for small snippets like controller responses.
- `interface` definitions for request payloads and service outputs.
- `enum` for constant sets (e.g., task status).
- `as const` to build literal type unions from arrays.

**CHALLENGE:** Introduce an enum for task priorities and use it across controllers, services, and Prisma schema.

---

## Chapter 3: Database & Prisma

### Database Design Basics
- Tables represent entities: `User`, `Task`.
- Relationships: Each `Task` belongs to one `User` (foreign key `userId`).
- Use migrations to keep the schema consistent across environments.

### The `schema.prisma` File (Line-By-Line)
Open `backend/prisma/schema.prisma`. You’ll see:
1. **Datasource:** Points to PostgreSQL using `DATABASE_URL`.
2. **Generator:** Configures the Prisma client.
3. **Model User:** Fields for id, email, passwordHash, tasks relation.
4. **Model Task:** Fields for id, title, description, status, timestamps, relations.

**LEARNING NOTE:** Prisma auto-generates types for these models, used throughout the codebase.

### Migrations Explained
- `npx prisma migrate dev --name init` creates migration SQL and applies it.
- Prisma keeps a history in `prisma/migrations/`.
- For production, use `prisma migrate deploy` to apply existing migrations without generating new ones.

### CRUD Operations Walkthrough
- **Create:** `prisma.task.create` inside `taskService.createTask`.
- **Read:** `prisma.task.findMany` inside `taskService.getTasksForUser`.
- **Update:** `prisma.task.update` inside `taskService.updateTask`.
- **Delete:** `prisma.task.delete` inside `taskService.deleteTask`.

**TRY THIS:** Open `taskController.ts` and trace a request from the router to the service.

---

## Chapter 4: Authentication Deep Dive

### Password Hashing Explained
- We use `bcrypt` to hash passwords before storing.
- Hashing is one-way: you can’t derive the original password from the hash.
- Stored hash + salt protects users even if the database leaks.

### JWT Tokens Demystified
- JSON Web Tokens contain user data (like `userId`) signed with a secret.
- Clients send JWTs in the `Authorization: Bearer <token>` header.
- The server verifies signatures to ensure authenticity.

**Diagram:**
```
Login Request -> Validate Credentials -> Sign JWT -> Return Token -> Store in Local Storage
Protected Route -> Read Header -> Verify JWT -> Attach user to request -> Continue
```

### Session vs Token Authentication
- **Session:** Server stores session info (stateful).
- **JWT Token:** Client stores token, server is stateless (simpler scaling).
- We use JWT for quick learning and easy mobile compatibility.

### Protecting Routes Step-by-Step
1. Frontend attaches `Authorization` header.
2. `authMiddleware.ts` reads and verifies the JWT.
3. If valid, user info is added to `req.user`.
4. Route handlers use `req.user` to authorize operations (e.g., only fetch your tasks).
5. If missing or invalid, respond with `401 Unauthorized`.

**EXPERIMENT:** Temporarily remove the `Authorization` header in a request and observe the error response.

---

## Practice Exercises

1. **Exercise:** Add a `GET /api/tasks/:id` endpoint.
   - *Goal:* Learn route params and Prisma queries.
   - *Solution Idea:* Create a controller function that calls `prisma.task.findFirst` with `id` and `userId`.

2. **Exercise:** Log request processing time.
   - *Goal:* Understand middleware ordering.
   - *Solution Idea:* Write middleware that records `Date.now()` and logs the delta after `next()`.

3. **Exercise:** Implement soft deletes for tasks.
   - *Goal:* Introduce boolean `deleted` column and adjust queries to filter it out.

4. **Stretch:** Add refresh tokens for longer sessions.
   - *Goal:* Explore token rotation and revocation lists.

Check your work against the hints in `docs/06-EXERCISES-AND-CHALLENGES.md`.

> **Keep Going:** Ready for the UI layer? Jump to `docs/03-FRONTEND-TUTORIAL.md` when you feel comfortable here.
