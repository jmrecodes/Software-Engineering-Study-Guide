/**
 * FILE PURPOSE: Define Express handlers for the Task resource.
 * LEARNING NOTE: Controllers focus on translating between HTTP and service responses.
 * TRY THIS: Return pagination metadata when implementing the advanced challenge.
 */

import { Request, Response } from 'express';
import { StatusCodes } from 'http-status-codes';

import { TaskService } from '../services/taskService';
import { logger } from '../config/logger';

const taskService = new TaskService();

/**
 * FUNCTION: listTasksController
 * WHAT: Fetch tasks for the authenticated user.
 * WHY: Keeps HTTP status codes and JSON serialization consistent.
 * RETURNS: Promise<void>
 */
export async function listTasksController(req: Request, res: Response): Promise<void> {
  const userId = req.user?.id;

  if (!userId) {
    res.status(StatusCodes.UNAUTHORIZED).json({ message: 'Missing user context.' });
    return;
  }

  const tasks = await taskService.getTasksForUser(userId);
  res.status(StatusCodes.OK).json({ tasks });
}

/**
 * FUNCTION: createTaskController
 * WHAT: Creates a new task owned by the authenticated user.
 */
export async function createTaskController(req: Request, res: Response): Promise<void> {
  const userId = req.user?.id;

  if (!userId) {
    res.status(StatusCodes.UNAUTHORIZED).json({ message: 'Missing user context.' });
    return;
  }

  const task = await taskService.createTask(userId, req.body);
  res.status(StatusCodes.CREATED).json({ task });
}

/**
 * FUNCTION: updateTaskController
 * WHAT: Updates task fields if the task belongs to the user.
 */
export async function updateTaskController(req: Request, res: Response): Promise<void> {
  const userId = req.user?.id;

  if (!userId) {
    res.status(StatusCodes.UNAUTHORIZED).json({ message: 'Missing user context.' });
    return;
  }

  const { id } = req.params;

  try {
    const task = await taskService.updateTask(userId, id, req.body);
    res.status(StatusCodes.OK).json({ task });
  } catch (error) {
    logger.warn('updateTaskController error', { error, userId, taskId: id });
    res.status(StatusCodes.NOT_FOUND).json({ message: 'Task not found or access denied' });
  }
}

/**
 * FUNCTION: completeTaskController
 * WHAT: Marks a task as completed.
 */
export async function completeTaskController(req: Request, res: Response): Promise<void> {
  const userId = req.user?.id;

  if (!userId) {
    res.status(StatusCodes.UNAUTHORIZED).json({ message: 'Missing user context.' });
    return;
  }

  const { id } = req.params;

  try {
    const task = await taskService.completeTask(userId, id);
    res.status(StatusCodes.OK).json({ task });
  } catch (error) {
    logger.warn('completeTaskController error', { error, userId, taskId: id });
    res.status(StatusCodes.CONFLICT).json({ message: 'Task already completed or not found' });
  }
}

/**
 * FUNCTION: deleteTaskController
 * WHAT: Removes a task owned by the user.
 */
export async function deleteTaskController(req: Request, res: Response): Promise<void> {
  const userId = req.user?.id;

  if (!userId) {
    res.status(StatusCodes.UNAUTHORIZED).json({ message: 'Missing user context.' });
    return;
  }

  const { id } = req.params;

  try {
    await taskService.deleteTask(userId, id);
    res.status(StatusCodes.NO_CONTENT).send();
  } catch (error) {
    logger.warn('deleteTaskController error', { error, userId, taskId: id });
    res.status(StatusCodes.NOT_FOUND).json({ message: 'Task not found or access denied' });
  }
}

// TODO: Challenge – implement bulk deletion by passing an array of IDs in the request body.
