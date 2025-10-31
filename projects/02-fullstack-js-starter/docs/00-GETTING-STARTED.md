# 00 · Getting Started Guide

Welcome! This document walks you from zero-to-running with patient explanations at every step. Keep it open as you set up.

## 🧠 Mindset Check
- You do **not** need to know everything up front. Follow the steps, learn as you go.
- If a command looks scary, read the commentary before executing it.
- Mistakes are signals, not failures. Each error message teaches you something new.

## 1. Installing the Essentials

### 1.1 Node.js
- **What it is:** Node.js lets you run JavaScript on your computer outside the browser. We use it for the backend and tooling.
- **Why you need it:** npm (Node Package Manager) comes bundled with Node.js and installs all project dependencies.
- **How to install:**
  1. Visit [https://nodejs.org](https://nodejs.org) and download the LTS (Long-Term Support) version.
  2. Follow the installer prompts (keep defaults).
  3. Verify installation:
     ```bash
     node --version
     npm --version
     ```

### 1.2 PostgreSQL
- **What it is:** A powerful relational database that stores our app's data (users, tasks, etc.).
- **Why you need it:** The backend uses Prisma to talk to PostgreSQL.
- **How to install:**
  - **macOS:** Use [Postgres.app](https://postgresapp.com/) or Homebrew (`brew install postgresql`).
  - **Windows:** Use [EnterpriseDB installer](https://www.postgresql.org/download/windows/).
  - **Linux:** Use your package manager (e.g., `sudo apt install postgresql`).
- **Verify installation:**
  ```bash
  psql --version
  ```
- **Start the server:**
  - Postgres.app: open it and click “Initialize”.
  - Homebrew: `brew services start postgresql`.
  - Windows/Linux: use the service manager or run `pg_ctl start`.

### 1.3 VS Code & Extensions
- Install [Visual Studio Code](https://code.visualstudio.com/).
- Recommended extensions (search within VS Code Extensions view):
  - **ESLint** – catches code issues early.
  - **Prisma** – syntax highlighting for schema files.
  - **Prettier** – opinionated code formatter.
  - **REST Client** – send HTTP requests from VS Code.

### 1.4 Terminal Basics
- The terminal accepts commands to run tools. You’ll use it for npm scripts, Git, and database commands.
- Navigate with `cd <folder>` and list files with `ls` (macOS/Linux) or `dir` (Windows).
- Press `↑` to reuse previous commands. Press `Ctrl + C` to stop a running process.

## 2. Your First Run
1. **Clone or unzip the project** into a folder like `~/projects/starter-kit`.
2. **Open VS Code** and choose “File → Open Folder…” to load the project root (`starter-kit/`).
3. **Set up environment files:**
   - Copy `backend/.env.example` to `backend/.env`.
   - Copy `frontend/.env.example` to `frontend/.env`.
   - Update the variables (secrets stay local).
4. **Install backend dependencies:**
   ```bash
   cd backend
   npm install
   npm run prisma:migrate
   npm run dev
   ```
   Keep this terminal running; it hosts the API.
5. **Install frontend dependencies:**
   ```bash
   cd ../frontend
   npm install
   npm run dev
   ```
   Vite will print a local URL (often `http://localhost:5173`). Click it to open the app.

## 3. Understanding the Running App
- **Frontend (React):** You’ll see a login/register interface. Try registering a user, then add tasks.
- **Backend (Express API):** REST endpoints run on `http://localhost:4000` (configurable). Explore them with Postman or `docs/04-API-TUTORIAL.md` guidance.
- **Database:** Prisma seeds an admin user. Inspect the data via `npx prisma studio` (browser UI).

## 4. Common Beginner Mistakes
| Situation | What it means | How to fix |
|-----------|---------------|------------|
| `command not found: npm` | Node.js isn’t installed or PATH isn’t set | Reinstall Node.js and restart terminal |
| `psql: command not found` | PostgreSQL CLI isn't available | Reinstall or add to PATH |
| `ECONNREFUSED 127.0.0.1:5432` | PostgreSQL server not running | Start PostgreSQL service |
| `Error: P1001` during Prisma connect | Database credentials wrong | Double-check `.env` values |
| Browser shows CORS error | Frontend can’t reach backend | Ensure backend is running and CORS configured |

## 5. Glossary
- **API:** Interface that lets one program talk to another using HTTP requests.
- **Backend:** Server-side code (Express + Prisma here).
- **Frontend:** Client-side code running in the browser (React).
- **CRUD:** Create, Read, Update, Delete – operations against data.
- **Middleware:** Functions in Express that process requests in sequence.
- **ORM (Object-Relational Mapper):** Tool that maps database rows to objects (Prisma).
- **Token:** A signed string (JWT) representing user identity.
- **Environment Variables:** Configuration values set outside code, often secrets.

## ✅ You’re Ready
If everything is running, you’ve cleared the hardest hurdle. Head to `docs/01-CONCEPTS-OVERVIEW.md` to contextualize what you just launched. Celebrate—you’ve got a full stack humming on your machine! 🎉
