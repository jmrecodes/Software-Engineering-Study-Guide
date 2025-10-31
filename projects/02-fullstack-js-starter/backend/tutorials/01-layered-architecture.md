# Tutorial · Layered Architecture Walkthrough

Follow this mini-lab to see how a single request flows through the backend layers.

1. Open `src/routes/taskRoutes.ts` and locate the `router.post('/')` handler.
2. Jump to `taskController.createTask` to examine how the request payload is shaped.
3. Explore `taskService.createTask` to understand Prisma interactions.
4. Review `validators/taskValidators.ts` to see how express-validator guards the input.
5. Check `middleware/authMiddleware.ts` to learn how JWT verification works.

**Experiment:** Comment out the validation chain temporarily and send invalid data. Observe the Prisma error and compare it to the cleaner validation error.

**Challenge:** Add an `updateStatus` controller method that uses a service function to toggle between `pending` and `completed` states.
