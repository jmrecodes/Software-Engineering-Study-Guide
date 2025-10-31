/**
 * FILE PURPOSE: Aggregate individual route modules under a single router for /api.
 * LEARNING NOTE: This pattern keeps new feature routes easy to register without editing server.ts repeatedly.
 * TRY THIS: Add a /api/profile route and register it here.
 */

import { Router } from 'express';

import { authRouter } from './authRoutes';
import { taskRouter } from './taskRoutes';

/**
 * FUNCTION: registerRoutes
 * WHAT: Builds a router instance with all feature-specific routers mounted.
 * WHY: Supports modular design and easier unit testing for route groups.
 * RETURNS: Router instance ready to be mounted on the Express app.
 */
export function registerRoutes(): Router {
  const router = Router();

  router.use('/auth', authRouter);
  router.use('/tasks', taskRouter);

  return router;
}

// TODO: Challenge – Add versioning (e.g., /v1) if you plan to evolve the API.
