/**
 * FILE PURPOSE: Define RESTful routes for the Task resource (CRUD + complete).
 * LEARNING NOTE: Protect routes with authentication and validation middleware before controller logic.
 * TRY THIS: Add query parameters for filtering (e.g., /tasks?status=completed).
 */

import { Router } from 'express';

import {
  createTaskController,
  deleteTaskController,
  listTasksController,
  updateTaskController,
  completeTaskController,
} from '../controllers/taskController';
import { authenticate } from '../middleware/authMiddleware';
import { runValidation } from '../middleware/validationMiddleware';
import {
  createTaskValidator,
  updateTaskValidator,
  taskIdParamValidator,
} from '../validators/taskValidators';

export const taskRouter = Router();

// All task routes require a valid JWT token.
taskRouter.use(authenticate);

// GET /api/tasks
// LEARNING NOTE: Query params can be validated before reaching this handler (see TODOs in validator file).
taskRouter.get('/', listTasksController);

// POST /api/tasks
taskRouter.post('/', createTaskValidator, runValidation, createTaskController);

// PUT /api/tasks/:id
taskRouter.put('/:id', taskIdParamValidator, updateTaskValidator, runValidation, updateTaskController);

// PATCH /api/tasks/:id/complete
taskRouter.patch('/:id/complete', taskIdParamValidator, runValidation, completeTaskController);

// DELETE /api/tasks/:id
taskRouter.delete('/:id', taskIdParamValidator, runValidation, deleteTaskController);

// TODO: Experiment – add router.param('id', ...) to centralize task lookup logic.
