# 🚀 Welcome to the Modern Web Dev Starter Kit

> **Celebrate every small win. Break things. Fix them. Learn loudly.**

## Learning Objectives
- Build confidence with a full-stack TypeScript project by exploring both backend and frontend codebases.
- Understand the request-response cycle from the browser to the database and back.
- Practice modern tooling: Vite, React 18+, Express.js, Prisma, and PostgreSQL.
- Strengthen your debugging muscles with intentional bugs and guided investigations.
- Develop a personal learning workflow with checklists, exercises, and progressive disclosure of complexity.

## Prerequisites
- Comfort with basic computer usage, file systems, and installing software.
- Beginner-friendly exposure to JavaScript (variables, functions, arrays) recommended but not required.
- Willingness to experiment, read comments, and try the **TRY THIS** suggestions sprinkled throughout the repo.

## Learning Path
1. Read `docs/00-GETTING-STARTED.md` to set up your environment and run the project.
2. Skim `docs/01-CONCEPTS-OVERVIEW.md` for a big-picture mental model.
3. Dive into the backend via `docs/02-BACKEND-TUTORIAL.md` and the files inside `backend/src`.
4. Switch to the frontend guided by `docs/03-FRONTEND-TUTORIAL.md` and `frontend/src`.
5. Connect both halves using `docs/05-CONNECTING-FRONTEND-BACKEND.md`.
6. Level up through challenges in `docs/06-EXERCISES-AND-CHALLENGES.md` and the `exercises/` folder.
7. Keep the quick references in `docs/10-QUICK-REFERENCE.md` handy as you iterate.

## Quick Start Tutorial
1. Clone or download this repository into a comfortable workspace.
2. Open the root folder in VS Code and install the recommended extensions.
3. Follow `docs/00-GETTING-STARTED.md` to install Node.js, PostgreSQL, and other prerequisites.
4. Copy `.env.example` in both `backend/` and `frontend/` to create `.env` files, then fill in the secrets.
5. Install dependencies and prep the database:
   ```bash
   cd backend
   npm install
   npm run prisma:migrate
   npm run prisma:seed       # optional: loads demo learner + tasks
   npm run dev
   # open a separate terminal
   cd ../frontend
   npm install
   npm run dev
   ```
6. Visit the logged URL (usually `http://localhost:5173`) and start exploring the UI.
7. Make your first API call using Postman or the VS Code REST client following `docs/04-API-TUTORIAL.md`.

## Success Checklist
- [ ] Successfully run the application locally
- [ ] Understand the file structure
- [ ] Make the first API call with Postman or curl
- [ ] Modify a React component and see the change live
- [ ] Add a new field to the Prisma `Task` model and migrate
- [ ] Create a new API endpoint in Express and call it from the frontend
- [ ] Implement a new feature end-to-end (UI + API + database)
- [ ] Debug and fix an intentional bug from `docs/07-DEBUGGING-GUIDE.md`
- [ ] Deploy the application (Heroku, Render, Vercel, or your choice)
- [ ] Run `npm run lint` in both `backend/` and `frontend/` without errors

## Additional Learning Resources
- [MDN Web Docs](https://developer.mozilla.org/) – Essential reference for web technologies.
- [Node.js Docs](https://nodejs.org/en/docs) – Official documentation for backend runtime.
- [React Docs](https://react.dev/) – Interactive tutorials and modern explanations.
- [Prisma Docs](https://www.prisma.io/docs/) – ORM guides and best practices.
- [TypeScript Handbook](https://www.typescriptlang.org/docs/handbook/intro.html) – Language fundamentals.
- [PostgreSQL Tutorial](https://www.postgresqltutorial.com/) – SQL fundamentals.

## Community & Support
- Ask questions in the [Prisma Discord](https://discord.gg/prisma), [Reactiflux](https://www.reactiflux.com/), or [Node Slackers](https://www.nodeslackers.com/) communities.
- Stack Overflow is great for targeted troubleshooting—search before posting, but don’t hesitate to ask.
- Pair up with a friend or join a study group; teaching others is a powerful way to reinforce your own learning.

> **Learning Philosophy:** This starter kit balances guidance with space for experimentation. Each file contains **LEARNING NOTES**, **TRY THIS** prompts, and **CHALLENGE** sections designed to keep you curious. Dive in, make changes, and celebrate every bug you squash!
