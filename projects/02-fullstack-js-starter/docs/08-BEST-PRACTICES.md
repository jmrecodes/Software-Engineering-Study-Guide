# 08 · Best Practices

Adopt these habits early to keep your projects clean, secure, and maintainable.

---

## Code Organization
- Group files by responsibility (`controllers`, `services`, `routes`).
- Keep functions short; one reason to change.
- Co-locate tests with implementation for easy discovery.

## Naming Conventions
- Use descriptive names (`createTaskController`, not `doTask`).
- Stick to camelCase for functions/variables, PascalCase for components/classes.
- Prefix boolean helpers with `is`, `has`, or `should`.

**Bad:** `const data = fetch();`
**Good:** `const userTasks = await taskService.getTasksForUser(userId);`

## Security Basics
- Hash passwords with a strong algorithm (`bcrypt` with salt).
- Validate every incoming request (Joi/express-validator).
- Sanitize user input to prevent injection attacks.
- Never commit `.env` files; use `.env.example` templates instead.

## Performance Tips
- Use pagination for large collections.
- Memoize expensive React calculations (`useMemo`, `useCallback`).
- Enable Prisma query logging only when debugging to avoid noise.
- Cache static assets via Vite build in production.

## Clean Code Principles
- **DRY:** Extract reusable logic into utilities or hooks.
- **KISS:** Favor simple approaches before complex patterns.
- **YAGNI:** Only build features you need; avoid premature abstraction.
- Document “why” for non-obvious decisions (code comments or README snippets).

## Git Workflow (Solo Projects)
1. Create branches per feature (`git checkout -b feat/task-filters`).
2. Commit frequently with meaningful messages.
3. Rebase or merge main when ready.
4. Tag milestones (e.g., `v0.1-learning-complete`).

> Consistency beats cleverness. Pick conventions and follow them relentlessly.
