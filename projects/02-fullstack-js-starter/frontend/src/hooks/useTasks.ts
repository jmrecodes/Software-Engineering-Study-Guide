/**
 * FILE PURPOSE: Encapsulate task fetching and mutations with reusable logic.
 * LEARNING NOTE: Custom hooks can combine state, effects, and services for clean components.
 * TRY THIS: Add optimistic updates for deleting tasks, then reconcile if the API fails.
 */

import { useCallback, useEffect, useMemo, useState } from 'react';

import { useAuth } from './useAuth';
import { taskService } from '../services/taskService';
import type { Task } from '../types/task';

type TaskHookState = {
  tasks: Task[];
  isLoading: boolean;
  error: string | null;
};

export function useTasks(): {
  tasks: Task[];
  isLoading: boolean;
  error: string | null;
  createTask: (payload: { title: string; description?: string }) => Promise<void>;
  updateTask: (id: string, payload: Partial<Task>) => Promise<void>;
  completeTask: (id: string) => Promise<void>;
  deleteTask: (id: string) => Promise<void>;
  refresh: () => Promise<void>;
} {
  const { token } = useAuth();
  const [state, setState] = useState<TaskHookState>({ tasks: [], isLoading: false, error: null });

  const fetchTasks = useCallback(async () => {
    if (!token) {
      return;
    }

    setState((prev: TaskHookState) => ({ ...prev, isLoading: true, error: null }));

    try {
      const tasks = await taskService.listTasks();
      setState({ tasks, isLoading: false, error: null });
    } catch (error) {
      setState((prev: TaskHookState) => ({ ...prev, isLoading: false, error: 'Failed to fetch tasks' }));
      console.error('Failed to fetch tasks', error);
    }
  }, [token]);

  useEffect(() => {
    void fetchTasks();
  }, [fetchTasks]);

  const createTask = useCallback(
    async (payload: { title: string; description?: string }) => {
      if (!token) return;
      await taskService.createTask(payload);
      await fetchTasks();
    },
    [token, fetchTasks],
  );

  const updateTask = useCallback(
    async (id: string, payload: Partial<Task>) => {
      if (!token) return;
      await taskService.updateTask(id, payload);
      await fetchTasks();
    },
    [token, fetchTasks],
  );

  const completeTask = useCallback(
    async (id: string) => {
      if (!token) return;
      await taskService.completeTask(id);
      await fetchTasks();
    },
    [token, fetchTasks],
  );

  const deleteTask = useCallback(
    async (id: string) => {
      if (!token) return;
      await taskService.deleteTask(id);
      await fetchTasks();
    },
    [token, fetchTasks],
  );

  return useMemo(
    () => ({
      tasks: state.tasks,
      isLoading: state.isLoading,
      error: state.error,
      createTask,
      updateTask,
      completeTask,
      deleteTask,
      refresh: fetchTasks,
    }),
    [state, createTask, updateTask, completeTask, deleteTask, fetchTasks],
  );
}

// TODO: Challenge – expose derived counts (e.g., completed vs pending) via useMemo.
