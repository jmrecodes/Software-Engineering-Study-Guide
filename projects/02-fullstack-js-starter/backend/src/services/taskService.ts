/**
 * FILE PURPOSE: Implement business logic for task CRUD operations.
 * LEARNING NOTE: Services translate high-level intentions into database calls.
 * TRY THIS: Implement caching (e.g., in-memory map) and compare performance.
 */

import { Prisma, Task } from '@prisma/client';

import { prisma } from '../prismaClient';

export interface CreateTaskInput {
  title: string;
  description?: string;
}

export interface UpdateTaskInput {
  title?: string;
  description?: string;
  status?: Prisma.TaskUpdateInput['status'];
  isCompleted?: boolean;
}

export class TaskService {
  /**
   * FUNCTION: getTasksForUser
   * WHAT: Fetches all tasks belonging to a specific user.
   * RETURNS: Promise<Task[]>
   */
  async getTasksForUser(userId: string): Promise<Task[]> {
    return prisma.task.findMany({
      where: { userId },
      orderBy: { createdAt: 'desc' },
    });
  }

  /**
   * FUNCTION: createTask
   * WHAT: Persists a new task associated with the user.
   * RETURNS: Promise<Task>
   */
  async createTask(userId: string, input: CreateTaskInput): Promise<Task> {
    return prisma.task.create({
      data: {
        title: input.title,
        description: input.description,
        userId,
      },
    });
  }

  /**
   * FUNCTION: updateTask
   * WHAT: Updates a task if it belongs to the user.
   * RETURNS: Promise<Task>
   */
  async updateTask(userId: string, taskId: string, input: UpdateTaskInput): Promise<Task> {
    const task = await prisma.task.findUnique({ where: { id: taskId } });

    if (!task || task.userId !== userId) {
      throw new Error('Task not found or access denied');
    }

    return prisma.task.update({
      where: { id: taskId },
      data: input,
    });
  }

  /**
   * FUNCTION: completeTask
   * WHAT: Marks the task completed, with conflict handling.
   */
  async completeTask(userId: string, taskId: string): Promise<Task> {
    const task = await prisma.task.findUnique({ where: { id: taskId } });

    if (!task || task.userId !== userId) {
      throw new Error('Task not found');
    }

    if (task.isCompleted) {
      throw new Error('Task already completed');
    }

    return prisma.task.update({
      where: { id: taskId },
      data: { isCompleted: true, status: 'COMPLETED' },
    });
  }

  /**
   * FUNCTION: deleteTask
   * WHAT: Deletes a task owned by the user.
   */
  async deleteTask(userId: string, taskId: string): Promise<void> {
    const task = await prisma.task.findUnique({ where: { id: taskId } });

    if (!task || task.userId !== userId) {
      throw new Error('Task not found or access denied');
    }

    await prisma.task.delete({
      where: { id: taskId },
    });
  }
}

// TODO: Challenge – support pagination by adding skip/take parameters.
