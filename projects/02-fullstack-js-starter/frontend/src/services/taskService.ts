/**
 * FILE PURPOSE: Provide task-related HTTP operations using the configured Axios client.
 * LEARNING NOTE: Keeping HTTP logic out of components improves testability.
 * TRY THIS: Add error translations (HTTP codes → user-friendly messages).
 */

import apiClient from './apiClient';
import type { Task } from '../types/task';

class TaskService {
  async listTasks(): Promise<Task[]> {
    const response = await apiClient.get<{ tasks: Task[] }>('/tasks');
    return response.data.tasks;
  }

  async createTask(payload: { title: string; description?: string }): Promise<Task> {
    const response = await apiClient.post<{ task: Task }>('/tasks', payload);
    return response.data.task;
  }

  async updateTask(id: string, payload: Partial<Task>): Promise<Task> {
    const response = await apiClient.put<{ task: Task }>(`/tasks/${id}`, payload);
    return response.data.task;
  }

  async completeTask(id: string): Promise<Task> {
    const response = await apiClient.patch<{ task: Task }>(`/tasks/${id}/complete`);
    return response.data.task;
  }

  async deleteTask(id: string): Promise<void> {
    await apiClient.delete(`/tasks/${id}`);
  }
}

export const taskService = new TaskService();

// TODO: Challenge – cache responses and expose `invalidateCache` for manual refreshing.
