/**
 * FILE PURPOSE: Share Task-related TypeScript types between components and services.
 * LEARNING NOTE: Keeping types in one place avoids mismatch bugs between frontend and backend.
 * TRY THIS: Extend Task with a `dueDate` or `priority` when completing exercises.
 */

export interface Task {
  id: string;
  title: string;
  description?: string;
  status: 'PENDING' | 'IN_PROGRESS' | 'COMPLETED';
  isCompleted: boolean;
  createdAt: string;
  updatedAt: string;
}
