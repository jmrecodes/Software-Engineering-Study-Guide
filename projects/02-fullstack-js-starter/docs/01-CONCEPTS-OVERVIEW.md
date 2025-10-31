# 01 · Concepts Overview

This overview builds the mental scaffolding you need before diving deep into code.

## What Is Full-Stack Development?
Full-stack developers craft both the **user interface** and the **server logic**. They understand how buttons trigger database updates and how data travels back to the screen.

```
+-----------------------+        HTTP Request        +----------------------+
|      Frontend         |  ----------------------->  |       Backend        |
|  (React, Vite, TS)    |        HTTP Response       | (Express, Prisma, DB) |
+-----------------------+  <-----------------------  +----------------------+
```

## Frontend vs Backend Responsibilities
- **Frontend:** Build accessible UI, manage browser state, call APIs, display feedback.
- **Backend:** Validate requests, apply business rules, interact with the database, return data or errors.
- They communicate via HTTP over clearly defined REST endpoints.

## The Request-Response Cycle (Step-by-Step)
1. A user action triggers an HTTP request (e.g., clicking “Create Task”).
2. React (frontend) sends a request to the Express server using Axios.
3. Express pipelines the request through middleware (logging, validation, authentication).
4. The controller calls services that interact with Prisma to read/write the database.
5. A response is sent back (success or error) and the frontend updates the UI accordingly.

## What Is an API?
An **Application Programming Interface** exposes functionality for other programs. REST APIs use URLs and HTTP methods. Example:
- `POST /api/tasks` – create a task.
- `GET /api/tasks` – list tasks.
- `PUT /api/tasks/:id` – update task.
- `DELETE /api/tasks/:id` – delete task.

## Authentication vs Authorization
- **Authentication:** Proving who you are (login with email/password, receive JWT).
- **Authorization:** Deciding what you’re allowed to do (only edit your own tasks).
- JWT tokens hold identity claims that Express middleware uses to authorize routes.

## Databases and ORMs
- PostgreSQL stores structured data in tables (`User`, `Task`).
- Prisma translates TypeScript into SQL. Instead of writing raw SQL, you call functions like `prisma.task.create`.
- Benefits: Type safety, migration tracking, cleaner code.

## Modern JavaScript/TypeScript Concepts Used
- **ES Modules:** `import`/`export` syntax for modular code.
- **Async/Await:** Simplified asynchronous flow for API calls and database queries.
- **Generics & Interfaces:** TypeScript features for strong typing.
- **Optional Chaining & Nullish Coalescing:** Safely access nested properties.

## Package Managers
- **npm:** Default with Node.js. Installs dependencies defined in `package.json`.
- **Scripts:** Shortcuts, e.g., `npm run dev` starts development servers.
- You’ll use npm in both backend and frontend directories.

## Version Control with Git
- Track changes, experiment safely, and collaborate.
- Basic flow:
  1. `git status` – see changes.
  2. `git add .` – stage updates.
  3. `git commit -m "Describe change"`
- Consider creating branches for exercises (e.g., `git checkout -b feature/task-priorities`).

> **Learning Reminder:** Refer back to this overview whenever you feel lost in the details. Understanding the “why” makes the “how” much easier.
