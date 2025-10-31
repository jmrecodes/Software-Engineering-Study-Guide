# 06 · Exercises & Challenges

Progress by building! Choose challenges that match your current comfort level. Solutions (and hints) live on the `solutions` branch referenced below.

---

## Beginner Challenges
1. **Task Model Upgrade**
   - *Goal:* Add a `dueDate` field to tasks.
   - *Steps:* Update `schema.prisma`, run migration, adjust form/UI.
   - *TRY THIS:* Display overdue tasks in a different color.

2. **About Page**
   - *Goal:* Create a simple `/about` route in React.
   - *Steps:* Add component, update router, link from nav.
   - *Learning Note:* Practice routing basics.

3. **Task Priority Toggle**
   - *Goal:* Add a priority dropdown with options (Low/Medium/High).
   - *Steps:* Update schema, controller, frontend form.

## Intermediate Challenges
1. **Task Categories**
   - *Goal:* Associate tasks with categories (Work, Personal, etc.).
   - *Ideas:* Introduce new Prisma model; create join table or simple enum.

2. **User Profile Page**
   - *Goal:* Display authenticated user info and let them update their name.
   - *Focus:* Build new endpoint, update auth context after changes.

3. **Task Search**
   - *Goal:* Filter tasks by keyword.
   - *Implementation:* Add query param `?q=`; use Prisma `contains` filter; update frontend list.

## Advanced Challenges
1. **Real-Time Updates**
   - *Goal:* Use WebSockets (Socket.IO) to broadcast task updates to all open clients.
   - *Hint:* Separate transport layer, maintain server-side list of connections.

2. **Pagination**
   - *Goal:* Efficiently load tasks with `page` and `pageSize`.
   - *Focus:* Update controller to return metadata (`total`, `pageCount`).

3. **File Uploads**
   - *Goal:* Attach files to tasks (e.g., reference documents).
   - *Idea:* Use `multer` for Express, store files locally or in cloud storage.

---

## Solutions Branch
Checkout the `solutions` branch to compare approaches:
```bash
git fetch origin
git checkout solutions
```
> **Reminder:** Treat solutions as inspiration, not the only correct answer. Try first, then peek.
